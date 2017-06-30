<?php
/**
 * @file
 * Administration pages and settings for the firebase module.
 */

function _firebase_settings_form($form, &$form_state) {
  $firebase_url = variable_get('firebase_url');
  $firebase_application_name = variable_get('firebase_application_name');
  $firebase_service_account_file = variable_get('firebase_service_account_file');

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
    '#submit' => array('_firebase_sync'),
  );

  return $form;
}

function _firebase_settings_form_validate($form_id, &$form_state) {
  $file = file_save_upload('firebase_service_account_file', array(
    'file_validate_extensions' => array('json'),
  ));

  if ($file) {
    if ($file = file_move($file, 'private://')) {
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
