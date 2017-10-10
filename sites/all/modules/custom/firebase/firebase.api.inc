<?php
/**
 * @file
 * Defining API endpoints handling subscripts for push notifications for the firebase module.
 */

function _firebase_api_add_subscription() {
  header("content-type:application/json;charset=utf-8");
  header("Access-Control-Expose-Headers:Access-Control-*");
  header("Access-Control-Allow-Headers:Access-Control-*, Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS, HEAD');
  header('Access-Control-Allow-Origin:*');
  header('Allow:GET, POST, PUT, DELETE, OPTIONS, HEAD');

  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    drupal_exit();
  }

  $subscription = _valid_subscription_id($_POST['subscription_id']);
  if ($subscription !== TRUE) {
    _throw_api_failure($subscription);
  }
  $artist_valid = _valid_artist($_POST['artist_id']);
  if ($artist_valid !== TRUE) {
    _throw_api_failure($artist_valid);
  }

  try {
    db_merge('firebase_subscriptions')
      ->key([
        'subscription_id' => $_POST['subscription_id'],
        'artist_nid' => $_POST['artist_id']
      ])
      ->fields([
        'created' => REQUEST_TIME,
        'ip' => $_SERVER['REMOTE_ADDR'],
      ])
      ->execute();

    http_response_code(200);
    print drupal_json_encode(array(
      'status' => 200,
      'message' => t('Successfully saved subscription.'),
    ));

  } catch (Exception $e) {
    _throw_api_failure(4001, 500);
  }
}

function _firebase_api_delete_subscription() {
  header("content-type:application/json;charset=utf-8");
  header("Access-Control-Expose-Headers:Access-Control-*");
  header("Access-Control-Allow-Headers:Access-Control-*, Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS, HEAD');
  header('Access-Control-Allow-Origin:*');
  header('Allow:GET, POST, PUT, DELETE, OPTIONS, HEAD');

  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    drupal_exit();
  }

  $subscription_id = arg(3);
  $artist_id = arg(4);

  if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
    _throw_api_failure(3001);
  }
  $s = _valid_subscription_id($subscription_id);
  if ($s !== TRUE) {
    _throw_api_failure($s);
  }
  $a = _valid_artist($artist_id);
  if ($a !== TRUE) {
    _throw_api_failure($a);
  }
  if (!_subscription_exists($subscription_id, $artist_id)) {
    _throw_api_failure(3002);
  }

  try {
    $num_deleted = db_delete('firebase_subscriptions')
      ->condition('subscription_id', $subscription_id)
      ->condition('artist_nid', $artist_id)
      ->execute();
    if ($num_deleted == 0) {
      _throw_api_failure(3003);
    }

    http_response_code(200);
    print drupal_json_encode(array(
      'status' => 200,
      'message' => t('Successfully deleted subscription.'),
    ));

  } catch (Exception $e) {
    _throw_api_failure(4002, 500);
  }
}

function _firebase_api_get_subscriptions() {
  header("content-type:application/json;charset=utf-8");
  header("Access-Control-Expose-Headers:Access-Control-*");
  header("Access-Control-Allow-Headers:Access-Control-*, Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS, HEAD');
  header('Access-Control-Allow-Origin:*');
  header('Allow:GET, POST, PUT, DELETE, OPTIONS, HEAD');

  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    drupal_exit();
  }

  $subscription_id = arg(3);

  if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    _throw_api_failure(3004);
  }

  $s = _valid_subscription_id($subscription_id);
  if ($s !== TRUE) {
    _throw_api_failure($s);
  }

  try {
    $query = db_select('firebase_subscriptions', 's');
    $query->fields('s', ['artist_nid']);
    $query->fields('n', ['title']);
    $query->join('node', 'n', 'n.nid = s.artist_nid');
    $query->condition('s.subscription_id', $subscription_id);
    $query->orderBy('n.title');
    $rs = $query->execute();

    $artists = [];
    foreach ($rs as $row) {
      $artists[] = [
        'id' => $row->artist_nid,
        'name' => $row->title,
        'lowercase' => strtolower($row->title)
      ];
    }

    http_response_code(200);
    print drupal_json_encode([
      'status' => 200,
      'message' => t('Successfully fetched subscriptions.'),
      'subscriptions' => array_values($artists),
      'num_subscriptions' => count($artists)
    ]);
  } catch (Exception $e) {
    _throw_api_failure(4003, 500);
  }
}

/**
 * Get the latest unread (i.e. with accessed_time = NULL) notification in the log.
 * Accessible through the API endpoint url firebase/api/notification/%, where % is the desired subscription ID.
 */
function _firebase_api_get_notification() {
  header("content-type:application/json;charset=utf-8");
  header("Access-Control-Expose-Headers:Access-Control-*");
  header("Access-Control-Allow-Headers:Access-Control-*, Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS, HEAD');
  header('Access-Control-Allow-Origin:*');
  header('Allow:GET, POST, PUT, DELETE, OPTIONS, HEAD');

  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    drupal_exit();
  }

  $subscription_id = arg(3);

  if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    _throw_api_failure(3005);
  }

  $s = _valid_subscription_id($subscription_id);
  if ($s !== TRUE) {
    _throw_api_failure($s);
  }

  try {
    $query = db_select('firebase_push_log', 'l');
    $query->fields('l', ['concert_nid', 'artist_nid', 'message_title', 'message_body']);
    $query->condition('l.subscription_id', $subscription_id);
    $query->condition('l.accessed_time', NULL);
    $query->orderBy('l.created', 'ASC');
    $query->range(0, 1);
    $notification = $query->execute()->fetchAssoc();

    if (!is_array($notification)) {
      _throw_api_failure(5001);
    }

    _set_accessed_time($notification['concert_nid'], $notification['artist_nid'], $subscription_id);
    http_response_code(200);
    print drupal_json_encode([
      'status' => 200,
      'message' => t('Successfully fetched notification.'),
      'notification' => array(
        'title' => $notification['message_title'],
        'body' => $notification['message_body']
      )
    ]);

  } catch (Exception $e) {
    _throw_api_failure(4004, 500);
  }
}

/**
 * Updates the push notification access time to NOW in the database log.
 *
 * @param integer $concert_nid The concert node ID.
 * @param integer $artist_nid The artist node ID.
 * @param string $subscription_id The subscription ID.
 */
function _set_accessed_time($concert_nid, $artist_nid, $subscription_id) {
  db_update('firebase_push_log')
    ->fields(array(
      'accessed_time' => REQUEST_TIME
    ))
    ->condition('concert_nid', $concert_nid)
    ->condition('artist_nid', $artist_nid)
    ->condition('subscription_id', $subscription_id)
    ->condition('accessed_time', NULL)
    ->execute();
}

/**
 * Checks if a subscription ID is valid.
 *
 * @param string $subscription_id The subscription ID to check.
 *
 * @return bool|int Returns an integer error code if the subscription ID is not valid, TRUE otherwise.
 */
function _valid_subscription_id($subscription_id) {
  if (!isset($subscription_id)) {
    return 1001;
  }
  if (strlen($subscription_id) > 200) {
    return 1002;
  }
  return TRUE;
}

/**
 * Checks if an artist ID is valid.
 *
 * @param integer $artist_id The artist ID to check.
 *
 * @return bool|int Returns an integer error code if the artist ID is not valid, TRUE otherwise.
 */
function _valid_artist($artist_id) {
  if (!isset($artist_id)) {
    return 2001;
  }
  if (!is_numeric($artist_id)) {
    return 2002;
  }
  if (!_artist_exists($artist_id)) {
    return 2003;
  }
  return TRUE;
}

function _subscription_exists($subscription_id, $artist_id) {
  $sql = "SELECT COUNT(*) AS cnt FROM firebase_subscriptions WHERE subscription_id = :subscription_id AND artist_nid = :artist_id";
  $rs = db_query(
    $sql,
    array(
      ':subscription_id' => $subscription_id,
      ':artist_id' => $artist_id
    )
  )->fetchAssoc();
  return ($rs['cnt'] == 1);
}

/**
 * Checks if an artist exists in the database.
 *
 * @param integer $artist_id The artist node ID.
 *
 * @return bool Returns TRUE if the artist exists, FALSE otherwise.
 */
function _artist_exists($artist_id) {
  $sql = "SELECT COUNT(nid) AS cnt FROM node WHERE nid = :artist_id AND type = :type";
  $rs = db_query($sql, array(':artist_id' => $artist_id, ':type' => 'artist'))->fetchAssoc();
  return ($rs['cnt'] == 1);
}

/**
 * @return array Returns an array of error codes and error messages used in the API.
 */
function _api_errors() {
  return array(
    1001 => 'No subscription_id provided.',
    1002 => 'The provided subscription_id is too long.',
    2001 => 'No artist_id provided.',
    2002 => 'The provided artist_id must be numeric.',
    2003 => 'The provided artist_id doesn\'t match an existing artist.',
    3001 => 'Deletion of subscriptions must be performed with a DELETE command.',
    3002 => 'The provided subscription_id and artist_id doesn\'t match an existing subscription.',
    3003 => 'No subscription were deleted.',
    3004 => 'Fetching subscriptions must be performed with a GET command.',
    3005 => 'Fetching notifications must be performed with a GET command.',
    4001 => 'An internal server error occurred when trying to save the subscription. Please try again later.',
    4002 => 'An internal server error occurred when trying to delete the subscription. Please try again later.',
    4003 => 'An internal server error occurred when trying to get the subscriptions. Please try again later.',
    4004 => 'An internal server error occurred when trying to get the notification. Please try again later.',
    5001 => 'No notifications found for the provided subscription_id.'
  );
}

/**
 * Throws an API error and exists execution.
 *
 * @param string $error_code The API error code to output
 * @param int $http_status_code The HTTP status code to set. Defaults to 400
 */
function _throw_api_failure($error_code, $http_status_code = 400) {
  http_response_code($http_status_code);

  $output = array(
    'status' => $http_status_code,
    'message' => _api_errors()[$error_code],
    'errorCode' => $error_code
  );
  print drupal_json_encode($output);
  drupal_exit();
}
