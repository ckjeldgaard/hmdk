<?php
// $Id: xpoll.admin.inc Exp $

/**
 * Implements hook_menu().
 */
function xpoll_menu() {
  $items['admin/config/system/poll_results'] = array(
    'title' => 'Poll results',
    'description' => 'View poll results.',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('_poll_admin_page'),
    'access arguments' => array('view poll results'),
    'type' => MENU_NORMAL_ITEM,
  );
  return $items;
}

/**
 * Implements hook_permission().
 */
function xpoll_permission() {
  return array(
    'view poll results' => array(
      'title' => t('View poll results'),
    ),
  );
}

/**
 * Gets the poll admin page contents.
 */
function _poll_admin_page() {
  $form = array();
  $header = array(t('Answer'), t('Votes'));
  
  // Get polls:
  $query = db_select('node', 'n');
  $query->fields('n', array('nid', 'title'));
  $query->condition('n.type', 'poll');
  $query->condition('n.status', 1);
  $query->orderBy('n.nid', 'DESC');
  $rs = $query->execute();
  foreach ($rs as $row) {
    $table = '';
    
    // Get answers:
    $count_matrix = array();
    $sql = "SELECT id, answers FROM xpoll_answers WHERE poll_nid = :poll_nid";
    $rs = db_query($sql, array(':poll_nid' => $row->nid));
    $total_votes = 0;
    foreach ($rs as $r) {
      $answers = unserialize($r->answers);
      foreach ($answers as $question => $answer) {
	$count_matrix[$question][$answer]++;
      }
      $total_votes++;
    }
    
    // Build table:
    foreach ($count_matrix as $question_id => $answers) {
      arsort($count_matrix[$question_id]);
      
      $rows = array();
      $v = 0;
      foreach ($count_matrix[$question_id] as $ans => $votes) {
	$rows[] = array(
	  $ans,
	  $votes,
	);
	$v+= $votes;
      }
      
      $question = field_collection_item_load($question_id);
      $table.= '<h2>' . check_plain($question->field_question[LANGUAGE_NONE][0]['value']) . '</h2>';
      $table.= theme('table', array('header' => $header, 'rows' => $rows));
      $table.= '<p><em>' . $v . ' stemmer i alt til dette spørgsmål.</em></p>';
    }
    
    $form['poll_group_' . $row->nid] = array(
      '#type' => 'fieldset',
      '#title' => $row->title,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#description' => $total_votes . ' personer har afgivet en stemme i denne afstemning.'
    );
    $form['poll_group_' . $row->nid]['questions'] = array(
      '#markup' => $table,
    );
  }
  return $form;
}