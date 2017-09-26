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

function _valid_subscription_id($subscription_id) {
  if (!isset($subscription_id)) {
    return 1001;
  }
  if (strlen($subscription_id) > 200) {
    return 1002;
  }
  return TRUE;
}

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

function _artist_exists($artist_id) {
  $sql = "SELECT COUNT(nid) AS cnt FROM node WHERE nid = :artist_id AND type = :type";
  $rs = db_query($sql, array(':artist_id' => $artist_id, ':type' => 'artist'))->fetchAssoc();
  return ($rs['cnt'] == 1);
}

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
    4001 => 'An internal server error occurred when trying to save the subscription. Please try again later.',
    4002 => 'An internal server error occurred when trying to delete the subscription. Please try again later.'
  );
}

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
