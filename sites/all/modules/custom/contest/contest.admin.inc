<?php
// $Id: contest.admin.inc Exp $

/**
 * Implements hook_menu().
 */
function contest_menu() {
  $items['admin/config/system/manage_contests'] = [
    'title' => 'Manage contests',
    'description' => 'View contests and contest answers from authenticated users.',
    'page callback' => 'drupal_get_form',
    'page arguments' => ['_contest_admin_page'],
    'access arguments' => ['manage contests'],
    'type' => MENU_NORMAL_ITEM,
  ];
  return $items;
}

/**
 * Implements hook_permission().
 */
function contest_permission() {
  return [
    'manage contests' => [
      'title' => t('Manage contests'),
    ],
  ];
}

/**
 * Gets the contest admin page contents.
 */
function _contest_admin_page() {
  drupal_add_js(drupal_get_path('module', 'contest') . '/js/contest_winner.js');
  
  $form = [];

  // Get contests:
  $query = db_select('node', 'n');
  $query->fields('n', ['nid']);
  $query->condition('n.type', 'contest');
  $query->condition('n.status', 1);
  $query->orderBy('n.nid', 'DESC');
  $rs = $query->execute();
  foreach ($rs as $row) {
    $node = node_load($row->nid);

    // Get answers:
    $header = [t('User'), t('Answered correct'), t('Time participated')];
    $rows = [];

    $query = db_select('contest_answers', 'a');
    $query->fields('a', ['uid', 'correct', 'created']);
    $query->fields('u', ['name']);
    $query->join('users', 'u', 'a.uid = u.uid');
    $query->condition('a.contest_nid', $node->nid);
    $query->orderBy('a.correct', 'DESC');
    $query->orderBy('a.uid', 'ASC');
    $result = $query->execute();
    $num_answers = $result->rowCount();
    $winner_pool = [];

    if ($num_answers > 0) {
      foreach ($result as $a) {
        $rows[] = [
          l($a->name, 'user/' . $a->uid),
          ($a->correct == 1) ? t('Yes') : t('No'),
          format_date($a->created, 'fulldate'),
        ];
        if ($a->correct == 1) {
          $winner_pool[] = '"' . $a->name . '"';
        }
      }
      $table = theme('table', ['header' => $header, 'rows' => $rows]);
      $table .= '<p>Et tryk på <em>Vælg vinder</em>-knappen vælger en tilfældig vinder i denne konkurrence blandt alle dem der har svaret rigtigt. Der sendes ikke besked til vinderen og den udtrukne vinder gemmes ingen steder i systemet.</p>';
    }
    else {
      $table = '<p>' . t("No one has participated in the contest yet.") . '</p>';
    }

    $form['contest_group_' . $node->nid] = [
      '#type' => 'fieldset',
      '#title' => $node->title,
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#description' => l('Redigér konkurrence', 'node/' . $node->nid . '/edit', ['absolute' => TRUE]) . '. Slutdato: ' . format_date($node->field_enddate[LANGUAGE_NONE][0]['value'], 'fulldate') . '. Detagere: ' . $num_answers,
    ];
    $form['contest_group_' . $node->nid]['contests'] = [
      '#markup' => $table,
    ];
    if ($num_answers > 0) {
      $form['contest_group_' . $node->nid]['winner_button'] = [
        '#type' => 'button',
        '#value' => t("Vælg vinder"),
        '#attributes' => [
          'onclick' => 'getWinner(new Array(' . implode(", ", $winner_pool) . '));',
        ],
      ];
    }
  }

  return $form;
}
