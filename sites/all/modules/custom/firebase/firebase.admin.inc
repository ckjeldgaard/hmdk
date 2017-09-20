<?php
/**
 * @file
 * Administration pages and settings for the firebase module.
 */

function _firebase_settings_form($form, &$form_state) {
  $firebase_url = variable_get('firebase_url');
  $firebase_application_name = variable_get('firebase_application_name');
  $firebase_service_account_file = variable_get('firebase_service_account_file');
  $last_cron = variable_get('firebase_last_cron_run', 0);

  $service_account_help_link = l(
    'Firebase Database REST API User Authentication',
    'https://firebase.google.com/docs/reference/rest/database/user-auth'
  );

  $form = array();
  $form['#attributes']['enctype'] = 'multipart/form-data';
  $form['firebase_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Firebase URL'),
    '#default_value' => $firebase_url,
    '#required' => TRUE,
    '#description' => t('The Firebase base URL (e.g. https://[PROJECT_ID].firebaseio-demo.com)'),
  );
  $form['firebase_application_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Firebase application name'),
    '#default_value' => $firebase_application_name,
    '#required' => TRUE,
    '#maxlength' => 256,
    '#description' => t('Firebase application name'),
  );
  $form['firebase_service_account_file'] = array(
    '#type' => 'managed_file',
    '#title' => t('Service account file'),
    '#description' => t('Upload a service account JSON file. See !link', array('!link' => $service_account_help_link)),
    '#default_value' => (isset($firebase_service_account_file->fid) ? $firebase_service_account_file->fid : ''),
    '#upload_validators' => array(
      'file_validate_extensions' => array('json'),
    ),
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save settings'),
    '#name' => 'settings'
  );
  $form['sync'] = array(
    '#type' => 'submit',
    '#value' => t('Sync Firebase'),
    '#submit' => array('_firebase_admin_sync'),
    '#suffix' => '<br />' . t('Last synchronized on !time', array('!time' => date('d-m-Y H:i:s', $last_cron))),
  );

  return $form;
}

function _firebase_settings_form_validate($form_id, &$form_state) {
  $file = file_save_upload('firebase_service_account_file', array(
    'file_validate_extensions' => array('json'),
  ));

  if ($file) {
    if ($file = file_move($file, 'private://')) {
      $file->status = 1;
      file_save($file);

      file_usage_add($file, 'firebase', 'service_account_file', 1);
      $form_state['values']['firebase_service_account_file'] = $file;
    }
    else {
      form_set_error('firebase_service_account_file', t('Failed to upload the file.'));
    }
  }

}

function _firebase_settings_form_submit($form_id, &$form_state) {
  variable_set('firebase_url', $form_state['values']['firebase_url']);
  variable_set('firebase_application_name', $form_state['values']['firebase_application_name']);
  variable_set('firebase_service_account_file', $form_state['values']['firebase_service_account_file']);
  drupal_set_message(t('Firebase settings saved'));
}

function _firebase_subscriptions_form() {

  $form = array();
  $header = [
    array('data' => t('Subscription ID'), 'field' => 'subscription_id'),
    array('data' => t('Artist'), 'field' => 'artist_nid'),
    array('data' => t('Time added'), 'field' => 'created', 'sort' => 'desc'),
    array('data' => t('IP address'), 'field' => 'ip')
  ];
  $rows = [];

  $element = 1; // Integer to distinguish between multiple pagers on same page.

  // echo "<pre>";
  $query = db_select('firebase_subscriptions', 's')
    ->extend('PagerDefault')->element($element)
    ->limit(75)
    ->extend('TableSort')
    ->orderByHeader($header);
  $query->fields('s', ['subscription_id', 'artist_nid', 'created', 'ip']);
  $query->fields('n', ['title']);
  $query->join('node', 'n', 's.artist_nid = n.nid');
  $query->orderBy('s.created', 'DESC');
  $rs = $query->execute();

  foreach ($rs as $row) {
    $rows[] = [
      $row->subscription_id,
      l($row->title, 'node/' . $row->artist_nid) . ' (' . $row->artist_nid . ')',
      format_date($row->created, 'fulldate'),
      $row->ip
    ];
  }

  // print_r($rows);
  $table = theme('table', ['header' => $header, 'rows' => $rows]);
  $pager = array('#theme' => 'pager', '#element' => $element);
  $table .= render($pager);

  $form['subscriptions_table'] = [
    '#markup' => $table,
  ];
  return $form;
}
