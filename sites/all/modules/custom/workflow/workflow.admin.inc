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

function _edit_interview_request_form() {
  $sql = "SELECT * FROM workflow_interview_requests WHERE id = :id LIMIT 1";
  $rs = db_query($sql, array(':id' => arg(4)))->fetchAssoc();

  $form = _add_interview_request_form();
  $form['#id'] = 'edit-interview-request-form';
  $form['band']['#default_value'] = $rs['artist'];
  if (strlen($rs['request_date'] > 0)) {
    $form['interview_request_date']['#default_value'] = date('Y-m-d', $rs['request_date']);
  }
  if (strlen($rs['execution_date'] > 0)) {
    $form['interview_execution_date']['#default_value'] = date('Y-m-d', $rs['execution_date']);
  }
  $form['interview_type']['#default_value'] = '' . array_search($rs['type'], _interview_type_options());
  $form['interview_interviewer']['#default_value'] = '' . $rs['interviewer_uid'];
  $form['completed']['#default_value'] = array_search($rs['completed'], _interview_completed_options());
  $form['approved']['#default_value'] = array_search($rs['approved'], _interview_completed_options());

  return $form;
}

function _add_interview_request_form() {
  global $user;
  $form = array();
  $form['#id'] = 'add-interview-request-form';

  $form['band'] = array(
    '#type' => 'textfield',
    '#title' => t('Artist'),
    '#maxlength' => 128,
    '#autocomplete_path' => 'bands/autocomplete',
    '#required' => TRUE,
  );
  $form['interview_request_date'] = array(
    '#title' => t('Interview request date'),
    '#type' => 'date_popup',
    '#date_format' => 'Y-m-d',
    '#default_value' => 'YYYY-MM-DD 00:00:00',
    '#date_year_range' => '0:+3',
    '#size' => 12,
  );
  $form['interview_execution_date'] = array(
    '#title' => t('Interview execution date'),
    '#type' => 'date_popup',
    '#date_format' => 'Y-m-d',
    '#default_value' => 'YYYY-MM-DD 00:00:00',
    '#date_year_range' => '0:+3',
    '#size' => 12,
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
    '#required' => TRUE,
  );
  $form['completed'] = array(
    '#type' => 'select',
    '#title' => t('Completed'),
    '#options' => _interview_completed_options(),
    '#description' => t('Select completion status of the interview.'),
  );
  $form['approved'] = array(
    '#type' => 'checkbox',
    '#title' => t('Approved'),
  );
  $form['submit'] = [
    '#type' => 'submit',
    '#value' => t('Save'),
  ];

  return $form;
}

function _add_interview_request_form_submit($form, &$form_state) {
  global $user;

  $id = db_insert('workflow_interview_requests')
  ->fields(array(
    'uid' => $user->uid,
    'created' => REQUEST_TIME,
    'artist' => $form_state['values']['band'],
    'request_date' => strlen($form_state['values']['interview_request_date']) > 0 ? strtotime($form_state['values']['interview_request_date']) : NULL,
    'execution_date' => strlen($form_state['values']['interview_execution_date']) > 0 ? strtotime($form_state['values']['interview_execution_date']) : NULL,
    'type' => _interview_type_options()[$form_state['values']['interview_type']],
    'interviewer_uid' => $form_state['values']['interview_interviewer'],
    'completed' => _interview_completed_options()[$form_state['values']['completed']],
    'approved' => $form_state['values']['approved']
  ))->execute();
  drupal_set_message(t('Successfully added new interview request.'));
  drupal_goto('admin/dashboard');
}

function _edit_interview_request_form_submit($form, &$form_state) {
  $num_updated = db_update('workflow_interview_requests')
    ->fields(array(
      'artist' => $form_state['values']['band'],
      'request_date' => strlen($form_state['values']['interview_request_date']) > 0 ? strtotime($form_state['values']['interview_request_date']) : NULL,
      'execution_date' => strlen($form_state['values']['interview_execution_date']) > 0 ? strtotime($form_state['values']['interview_execution_date']) : NULL,
      'type' => _interview_type_options()[$form_state['values']['interview_type']],
      'interviewer_uid' => $form_state['values']['interview_interviewer'],
      'completed' => _interview_completed_options()[$form_state['values']['completed']],
      'approved' => $form_state['values']['approved']
    ))
    ->condition('id', arg(4))
    ->execute();
  drupal_set_message(t('Successfully updated interview request.'));
  drupal_goto('admin/dashboard');
}

function _delete_interview_request() {
  $num_deleted = db_delete('workflow_interview_requests')
    ->condition('id', arg(4))
    ->execute();
  drupal_set_message(t('Successfully deleted interview request.'));
  drupal_goto('admin/dashboard');
}

function _interview_completed_options() {
  return array(
    0 => t('No'),
    1 => t('Yes (being edited)'),
  );
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
