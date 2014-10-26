<?php
/**
 * @file
 * Administration pages and settings for the newsletter signup module.
 */

/**
 * Implements hook_permission().
 */
function newsletter_signup_permission() {
  return array(
    'administer newsletter signup module' => array(
      'title' => t('Administer newsletter signup module'),
    ),
  );
}

function _newsletter_signup_settings_form() {
  $form = array();
    $form['newsletter_signup_destination_nid'] = array(
    '#type' => 'textfield',
    '#title' => t('Newsletter signup destination Node ID'),
    '#default_value' => variable_get('newsletter_signup_destination_nid'),
    '#maxlength' => 8,
    '#required' => TRUE,
    '#description' => t('ID of the node the user will be redirected to after signing up for the newsletter.'),
  );
    
  return system_settings_form($form);
}