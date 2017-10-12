<?php

define('FIREBASE_PUSH_PROCESS_QUEUE_NAME', 'firebase_push_process_queue');

/**
 * Implements hook_node_submit().
 */
function firebase_node_submit($node, $form, &$form_state) {

  $production_mode = variable_get('firebase_production_mode', 1);

  if ($node->type == 'concert' && $node->status == 1 && strlen(variable_get('firebase_server_key', '')) > 0) {

    $artists = array();
    foreach ($node->field_artists[LANGUAGE_NONE] as $artist) {
      $artists[] = $artist['target_id'];
    }
    foreach ($node->field_support_artists[LANGUAGE_NONE] as $artist) {
      $artists[] = $artist['target_id'];
    }
    $queue_item = array(
      'node_id' => $node->nid,
      'artists' => $artists
    );

    if ($production_mode == 1) {
      $queue = DrupalQueue::get(FIREBASE_PUSH_PROCESS_QUEUE_NAME);
      $queue->createQueue();
      $queue->createItem($queue_item);

      $message = t('Item queued for being processed and sent through FCM: Node ID: @node_nid. Artists: @artists',
        array(
          '@node_nid' => $node->nid,
          '@artists' => implode(", ", $artists)
        )
      );
      watchdog('firebase', $message, [], WATCHDOG_INFO);
    } else {
      // Send notification immediately in Dev mode:
      _firebase_push_process_concert($queue_item);
    }
  }
}

/**
 * Implements hook_cron_queue_info().
 */
function firebase_cron_queue_info() {
  $queues [FIREBASE_PUSH_PROCESS_QUEUE_NAME] = array(
    'worker callback' => '_firebase_push_process_concert',
    'time' => 60,
    'skip on cron' => FALSE,
  );
  return $queues;
}

/**
 * Processes a single item from the queue item.
 *
 * @param array $concert_node
 *   Contains the artist node ID's and concert node ID
 */
function _firebase_push_process_concert(array $concert_node) {

  foreach($concert_node['artists'] as $artist_nid) {
    _send_push_message($artist_nid, $concert_node['node_id']);
  }
}

function _send_push_message($artist_nid, $concert_nid) {
  $query = db_select('firebase_subscriptions', 's');
  $query->fields('s', ['subscription_id']);
  $query->fields('n', ['title']);
  $query->join('node', 'n', 'n.nid = s.artist_nid');
  $query->condition('s.artist_nid', $artist_nid);
  $rs = $query->execute();

  foreach($rs as $a) {
    if (!_in_log($concert_nid, $artist_nid, $a->subscription_id)) {
      $notification = _save_to_log($concert_nid, $artist_nid, $a->subscription_id, $a->title);
      $message_id = _fcm_send($a->subscription_id, $notification);

      if ($message_id != FALSE) {
        _set_message_id($concert_nid, $artist_nid, $a->subscription_id, $message_id);
      }
    }
  }
}

/**
 * Checks if a push notification exists in the database log.
 *
 * @param integer $concert_nid Concert node ID.
 * @param integer $artist_nid Artist node ID.
 * @param string $subscription_id The user's subscription ID.
 *
 * @return bool Returns TRUE if the record exists, FALSE otherwise.
 */
function _in_log($concert_nid, $artist_nid, $subscription_id) {
  $result = db_select('firebase_push_log', 'l')
    ->fields('l')
    ->condition('l.concert_nid', $concert_nid)
    ->condition('l.artist_nid', $artist_nid)
    ->condition('l.subscription_id', $subscription_id)
    ->execute();

  return ($result->rowCount() > 0);
}

/**
 * Saves a new notification log entry to the database with default values for message_id and accessed_time (i.e. not read yet by user).
 *
 * @param $concert_nid
 * @param $artist_nid
 * @param $subscription_id
 * @param $artist_name
 *
 * @return array Returns an array containing the saved notification's title and body texts.
 */
function _save_to_log($concert_nid, $artist_nid, $subscription_id, $artist_name) {

  $sql = 'SELECT title FROM node WHERE nid = :concert_nid';
  $rs = db_query($sql, array(':concert_nid' => $concert_nid));
  $concert = $rs->fetchAssoc();

  $title = t('New concert for !artist', array('!artist' => $artist_name));
  $body = $concert['title'];

  db_insert('firebase_push_log')
    ->fields(array(
      'concert_nid' => $concert_nid,
      'artist_nid' => $artist_nid,
      'subscription_id' => $subscription_id,
      'message_title' => $title,
      'message_body' => $body,
      'created' => REQUEST_TIME,
    ))
    ->execute();

  return array(
    'body' 	=> $body,
    'title'	=> $title,
    'icon'	=> 'myicon',/*Default Icon*/
    'sound' => 'mySound'/*Default sound*/
  );
}

/**
 * Sets the message_id field on a notification in the database log.
 */
function _set_message_id($concert_nid, $artist_nid, $subscription_id, $message_id) {
  db_update('firebase_push_log')
    ->fields(array(
      'message_id' => $message_id,
    ))
    ->condition('concert_nid', $concert_nid)
    ->condition('artist_nid', $artist_nid)
    ->condition('subscription_id', $subscription_id)
    ->execute();
}

/**
 * Sends a push notification to a subscriber through Firebase Cloud Messaging.
 *
 * @param string $subscription_id The subscriber's ID.
 * @param array $notification The push notification to send.
 *
 * @return string|bool Returns the message_id from FCM if successful, FALSE otherwise.
 */
function _fcm_send($subscription_id, $notification) {
  $fields = array(
    'to' => $subscription_id,
    'notification' => $notification
  );
  $headers = array(
    'Authorization: key=' . variable_get('firebase_server_key'),
    'Content-Type: application/json'
  );

  // Send request to Firebase
  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch,CURLOPT_POSTFIELDS, drupal_json_encode($fields));
  $result = curl_exec($ch);
  curl_close($ch);
  $fcm_result = drupal_json_decode($result);

  if (!is_array($fcm_result) || $fcm_result['success'] != 1) {

    $error_msg = (isset($fcm_result['results'][0]['error'])) ? $fcm_result['results'][0]['error'] : $result;
    $message = t('Error sending FCM push notification to subscription ID @subscription. Error message: @error', array(
      '@subscription' => $subscription_id,
      '@error' => $error_msg,
    ));
    watchdog('firebase', $message, array(), WATCHDOG_ERROR);
    return false;
  } else {
    $params = array(
      '@message_id' => $fcm_result['results'][0]['message_id'],
      '@subscription' => $subscription_id,
      '@notification' => 'Title: ' . $notification['title'] . ' - Body: ' . $notification['body']
    );
    watchdog('firebase', t('Successfully sent FCM push notification with message ID @message_id to subscription ID: @subscription. Notification: @notification', $params), array(), WATCHDOG_INFO);
    return $fcm_result['results'][0]['message_id'];
  }
}
