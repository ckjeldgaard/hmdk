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

function _add_interview_request_form() {
  global $user;
  $form = array();

  $form['city'] = array(
    '#type' => 'textfield',
    '#title' => 'City',
    '#maxlength' => 128,
    '#autocomplete_path' => 'bands/autocomplete',
  );

  $form['interview_type'] = array(
    '#type' => 'select',
    '#title' => t('Type'),
    '#options' => _interview_type_options(),
    '#description' => t('Select an interview type.'),
  );
  $form['interview_interviewer'] = array(
    '#type' => 'select',
    '#title' => t('Interviewer'),
    '#options' => _get_interviewers(),
    '#default_value' => $user->uid,
    '#description' => t('Select the interviewer'),
  );
  $form['interview_request_date'] = array(
      '#title' => t('Interview request date'),
      '#type' => 'date_popup',
      '#date_format' => 'm/d/Y',
      '#default_value' => 'YYYY-MM-DD 00:00:00',
      '#date_year_range' => '0:+3',
      '#size' => 12,
      '#required' => true,
  );

  return $form;
}

/**
 * @return array
 */
function _interview_type_options() {
  return array(
    0 => t('Email'),
    1 => t('Telephone'),
    2 => t('Skype'),
    3 => t('Live (text)'),
    4 => t('Live (video)'),
  );
}

/**
 * @return array Returns an array of interviewers (i.e. editors and reviewers)
 */
function _get_interviewers() {
  $sql = "SELECT u.uid, u.name ";
  $sql.= "FROM users u ";
  $sql.= "INNER JOIN users_roles ur ON ur.uid = u.uid ";
  $sql.= "WHERE ur.rid IN (3, 4, 5) ";
  $sql.= "ORDER BY u.name";

  $rs = db_query($sql);
  $users = array();
  foreach ($rs as $row) {
    $users[$row->uid] = $row->name;
  }
  return $users;
}
