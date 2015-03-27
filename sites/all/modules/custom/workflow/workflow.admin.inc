<?php
/**
 * @file
 * Administration pages and settings for the workflow module.
 */

function _editor_messages_settings_form() {
    
  $message = variable_get('workflow_editor_messages'); 
  $form = array();
    $form['workflow_editor_messages'] = array(
    '#type' => 'text_format',
    '#title' => t('Editor messages'),
    '#default_value' => $message['value'],
    '#required' => TRUE,
    '#description' => t('Messages from the edtior to reviewers displayed on the dashboard.'),
  );
    
  return system_settings_form($form);
}