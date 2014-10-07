<?php
/**
 * @file
 * Administration pages and settings for the google site search module.
 */

/**
 * Implements hook_permission().
 */
function google_search_permission() {
  return array(
    'administer google search module' => array(
      'title' => t('Administer google search module'),
    ),
  );
}

/**
 * The google search module settings form in the backend.
 */
function _google_search_settings_form() {
  $form = array();
  
  $form['general'] = array(
    '#type' => 'fieldset', 
    '#title' => t('General'),
    '#collapsible' => TRUE,
  );
  $form['general']['google_search_application_name'] = array(
    '#type' => 'textfield', 
    '#title' => t('Google Search Application name'), 
    '#maxlength' => 128, 
    '#required' => FALSE,
    '#default_value' => variable_get('google_search_application_name'),
    '#description' => t('Optional name of the search application.'),
  );
  $form['general']['google_developer_key'] = array(
    '#type' => 'textfield', 
    '#title' => t('Google Developer key'), 
    '#maxlength' => 128, 
    '#required' => TRUE,
    '#default_value' => variable_get('google_developer_key'),
    '#description' => t('The Google Developer key. Named Simple API Access in !link.', array('!link' => l('Google Cloud Console', 'https://cloud.google.com/console', array('attributes' => array('target' => '_blank'))))),
  );
  $cse_link = l('Google Custom Search Engine', 'https://www.google.com/cse/', array('attributes' => array('target' => '_blank')));
  $form['general']['google_custom_search_engine_id'] = array(
    '#type' => 'textfield', 
    '#title' => t('Google custom search engine ID'), 
    '#maxlength' => 256, 
    '#required' => TRUE,
    '#default_value' => variable_get('google_custom_search_engine_id'),
    '#description' => t('The Google custom search engine ID in the form %id_format. Can be found or set up on !link.', array('%id_format' => 'xxxxxxxxxxxxxxxxxxxxx:yyyyyyyyyyy', '!link' => $cse_link)),
  );
  $form['general']['search_results_per_page'] = array(
    '#type' => 'textfield', 
    '#title' => t('Results per page'), 
    '#maxlength' => 128, 
    '#required' => TRUE,
    '#default_value' => variable_get('search_results_per_page', '10'),
    '#description' => t('Number of search results per page.'),
  );
  
  // Messages:
  $form['messages'] = array(
    '#type' => 'fieldset', 
    '#title' => t('Messages'),
    '#collapsible' => TRUE,
  );
  $form['messages']['google_search_error_message'] = array(
    '#type' => 'textfield', 
    '#title' => t('Google search error message'), 
    '#maxlength' => 256, 
    '#required' => FALSE,
    '#default_value' => variable_get('google_search_error_message', 'Der skete en fejl.'),
    '#description' => t("Error message to display to the user, if the custom search engine doesn't work"),
  );
  
  $form['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );
  
  return $form;
}

/**
 * Submits the google search module settings form.
 */
function _google_search_settings_form_submit($form, &$form_state) {
  
  variable_set('google_search_application_name', $form_state['values']['google_search_application_name']);
  variable_set('google_developer_key', $form_state['values']['google_developer_key']);
  variable_set('google_custom_search_engine_id', $form_state['values']['google_custom_search_engine_id']);
  variable_set('search_results_per_page', $form_state['values']['search_results_per_page']);
  variable_set('google_search_error_message', $form_state['values']['google_search_error_message']);
  
  drupal_set_message(t('Google search settings updated.'));
}
