<?php

/**
 * Implements hook_menu().
 */
function concert_add_menu() {
  $items['admin/config/system/concert_add'] = array(
    'title' => 'Concert add settings',
    'description' => 'Manage the e-mail sent to editors when users submit a new concert',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('_concert_add_settings_form'),
    'access arguments' => array('administer content'),
    'type' => MENU_NORMAL_ITEM,
  );
  return $items;
}

function _concert_add_settings_form() {
  $form = array();
  
  // Get users:
  $query = db_select('users', 'u');
  $query->fields('u', array('uid', 'name'));
  $query->join('users_roles', 'r', 'r.uid = u.uid');
  $query->orderBy('u.name', 'ASC');
  $result = $query->execute();
  
  $users = array();
  foreach ($result as $u) {
    $users[$u->uid] = $u->name;
  }
  
  $form['recipients'] = array(
    '#type' => 'fieldset',
    '#title' => t('Recipients'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['recipients']['concert_add_email_recipients'] = array(
    '#type' => 'select',
    '#title' => t('E-mail recipients'),
    '#options' => $users,
    '#default_value' => variable_get('concert_add_email_recipients'),
    '#description' => t('Select users who should receive an e-mail when a new concert is submitted by an authenticated user. To select multiple users, hold down the <em>Ctrl</em> key, and click each of the other users you want.'),
    '#multiple' => TRUE,
    '#required' => FALSE,
  );
  
  
  $form['email'] = array(
    '#type' => 'fieldset',
    '#title' => t('E-mail contents'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['email']['concert_add_email_subject'] = array(
    '#type' => 'textfield',
    '#title' => t('Subject'),
    '#default_value' => variable_get('concert_add_email_subject'),
    '#maxlength' => 128,
    '#required' => TRUE,
    '#description' => t('Subject of the concert add notification e-mail sent to editors.'),
  );
  $form['email']['concert_add_email_body'] = array(
    '#type' => 'textarea',
    '#title' => t('E-mail body'),
    '#default_value' => variable_get('concert_add_email_body'),
    '#required' => TRUE,
    '#description' => t('Body of the concert add notification e-mail sent to editors.<br />Possible placeholders:<br><ul><li>!name = Name of authenticated user who submitted the concert.</li><li>!userlink = Link to the user profile who submitted the concert.</li><li>!concert = Name of the submitted concert.</li><li>!link = Link to the submitted concert.</li></ul>'),
  );
  return system_settings_form($form);
}

/**
 * Sends e-mails to editors selected on the settings page.
 *
 * @param object $node The concert node just submitted by an authenticated user.
 */
function _concert_add_send_notification(&$node) {
  global $user;

  $site_email = variable_get('site_mail', '');
  $languages = language_list();
  $language = $languages['da'];
  
  $args = array(
    '!name' => $user->name,
    '!userlink' => url('user/' . $node->uid, array('absolute' => TRUE)),
    '!concert' => $node->title,
    '!link' => url('node/' . $node->nid, array('absolute' => TRUE)),
  );
  $body = t(variable_get('concert_add_email_body'), $args);
  $params = array(
    'subject' => variable_get('concert_add_email_subject'),
    'body' => $body,
  );
  
  $recipients = variable_get('concert_add_email_recipients');
  foreach ($recipients as $uid) {
    $editor = user_load($uid);
    drupal_mail('concert_add', 'notice', $editor->mail, $language, $params, $site_email);
  }
}

function concert_add_mail($key, &$message, $params) {
  switch($key) {
    case 'notice':
      $langcode = $message['language']->language;
      $message['subject'] = $params['subject'];
      $message['body'][] = $params['body'];
      break;
  }
}