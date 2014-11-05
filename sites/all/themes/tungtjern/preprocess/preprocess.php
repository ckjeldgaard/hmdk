<?php
/**
 * @File
 * Preprocess functions for preprocessing nodes and comments
 */

function tungtjern_preprocess_user_profile(&$variables) {
  global $user;
  
  $uid = $variables['user_profile']['field_address']['#object']->uid;

  $variables['user_reviews'] = _get_reviews_by_author($uid);
  $variables['user_news'] = _get_news_by_author($uid);
  $variables['user_interviews'] = _get_interviews_by_author($uid);
  $variables['user_concerts'] = _get_live_reviews_by_author($uid);
  $variables['user_comments'] = _get_user_comments($uid);
  
  // Compute age:
  if (isset($variables['user_profile']['field_address']['#object']->field_birthday[LANGUAGE_NONE][0]['value']) && $variables['user_profile']['field_address']['#object']->field_birthday[LANGUAGE_NONE][0]['value'] > 0) {
    $birth = $variables['user_profile']['field_address']['#object']->field_birthday[LANGUAGE_NONE][0]['value'];
    $t = time();
    $age = ($birth < 0) ? ( $t + ($birth * -1) ) : $t - $birth;
    $age = floor($age/31536000);
    $variables['age'] = $age;
  }
  
  if (isset($variables['user_profile']['field_address']['#object']->field_gender[LANGUAGE_NONE][0]['value'])) {
    if ($variables['user_profile']['field_address']['#object']->field_gender[LANGUAGE_NONE][0]['value'] == 'M') {
      $variables['gender'] = t('Man');
    }
    else if ($variables['user_profile']['field_address']['#object']->field_gender[LANGUAGE_NONE][0]['value'] == 'F') {
      $variables['gender'] = t('Woman');
    }
  }
  
  // Only administrators can view addresses:
  if (in_array('administrator', $user->roles)) {
    $address = '';
    if (strlen($variables['user_profile']['field_address']['#object']->field_address[LANGUAGE_NONE][0]['thoroughfare']) > 0) {
      $address .= $variables['user_profile']['field_address']['#object']->field_address[LANGUAGE_NONE][0]['thoroughfare'] . "<br />\n";
    }
    if (
      strlen($variables['user_profile']['field_address']['#object']->field_address[LANGUAGE_NONE][0]['postal_code']) > 0 ||
      strlen($variables['user_profile']['field_address']['#object']->field_address[LANGUAGE_NONE][0]['locality']) > 0
    ) {
      if (strlen($variables['user_profile']['field_address']['#object']->field_address[LANGUAGE_NONE][0]['postal_code']) > 0) {
        $address .= $variables['user_profile']['field_address']['#object']->field_address[LANGUAGE_NONE][0]['postal_code'] . " ";
      }
      if (strlen($variables['user_profile']['field_address']['#object']->field_address[LANGUAGE_NONE][0]['locality']) > 0) {
        $address .= $variables['user_profile']['field_address']['#object']->field_address[LANGUAGE_NONE][0]['locality'];
      }
      $address .= "<br />\n";
    }
    if (strlen($variables['user_profile']['field_address']['#object']->field_address[LANGUAGE_NONE][0]['country']) > 0) {
      $country_names = _country_get_predefined_list();
      $address .= $country_names[$variables['user_profile']['field_address']['#object']->field_address[LANGUAGE_NONE][0]['country']];
    }
    $variables['address'] = $address;
  }
  
  // Protect personal info (like email addresses etc.)
  $variables['display'] = (in_array('administrator', $user->roles)) ? TRUE : FALSE;
}

/**
 * Implements theme_preprocess_node().
 */
function tungtjern_preprocess_node(&$variables) {
  global $user;
  
  $node = $variables['node'];
  
  $comments_display = TRUE;
  if ($user->uid == 0 && $node->comment != 0 && $node->comment_count == 0) {
    $comments_display = FALSE;
  }
  $node->comments_display = $comments_display;
  
  $view_mode = $variables['view_mode'];

  // Add templates hook suggestions.
  if ($view_mode !== 'full') {
    array_push($variables['theme_hook_suggestions'], 'node__' . $node->type . '_' . $view_mode);
  }
  
  //Preprocess function
  $preprocess_function = "_preprocess_" . $node->type;

  //If preprocess function exists, call it
  if (function_exists($preprocess_function)) {
    $preprocess_function($node, $view_mode);
  }
}

/**
 * Preprocess review node.
 */
function _preprocess_review(&$node) {

  if (isset($node->field_release[LANGUAGE_NONE][0]['entity'])) {
    $release = $node->field_release[LANGUAGE_NONE][0]['entity'];
  } else {
    $release = node_load($node->field_release[LANGUAGE_NONE][0]['target_id']);
  }
  $node->release = $release;
  $node->artist = node_load($release->field_artist[LANGUAGE_NONE][0]['target_id']);
  
  if (isset($release->field_label[LANGUAGE_NONE][0]['tid'])) {
    $label_term = taxonomy_term_load($release->field_label[LANGUAGE_NONE][0]['tid']);
    $node->label = $label_term;
  } else {
    $node->label = FALSE;
  }
  
  $distributor_term = (isset($release->field_distributor[LANGUAGE_NONE][0]['tid'])) ? taxonomy_term_load($release->field_distributor[LANGUAGE_NONE][0]['tid']) : FALSE;
  $node->distributor = $distributor_term;
  
  $node->user_ratings = _get_item_user_rating($node->nid);
 
  // Load tracklist as array (for unordered list):
  $tracks = array();
  if (isset($node->field_tracklist[LANGUAGE_NONE])) {
    $separator = "\r\n";
    $line = strtok($node->field_tracklist[LANGUAGE_NONE][0]['value'], $separator);
    $tracks[] = $line;
    while ($line !== false) {
      $line = strtok($separator);
      if (strlen($line) > 0) {
        $tracks[] = $line;
      }
    }
  }
  else if (isset($node->release->field_tracklist[LANGUAGE_NONE])) {
    // Get tracklist from release node instead. TODO: This should be deleted.
    $separator = "\r\n";
    $line = strtok($node->release->field_tracklist[LANGUAGE_NONE][0]['value'], $separator);
    $tracks[] = $line;
    while ($line !== false) {
      $line = strtok($separator);
      if (strlen($line) > 0) {
        $tracks[] = $line;
      }
    }
  }
  $node->tracklist = $tracks;
  
  // Load genres (if any):
  $genres = FALSE;
  $genre_label = 'Genre';
  if (isset($node->field_genre[LANGUAGE_NONE])) {
    $g = array();
    foreach ($node->field_genre[LANGUAGE_NONE] as $genre) {
      if (isset($genre['entity'])) {
        $g[] = l($genre['entity']->name, 'taxonomy/term/' . $genre['entity']->tid, array('attributes' => array('itemprop' => 'genre')));
      } else {
        $term = taxonomy_term_load($genre['target_id']);
        $g[] = l($term->name, 'taxonomy/term/' . $term->tid, array('attributes' => array('itemprop' => 'genre')));
      }
    }
    if (count($g) > 1) {
      $genre_label = 'Genrer';
    }
    $genres .= implode(", ", $g);
  }
  $node->genre_label = $genre_label;
  $node->genre = $genres;
  
  // Load first genre for teaser and topfront view:
  if (isset($node->field_genre[LANGUAGE_NONE][0]['entity'])) {
    $node->first_genre = $node->field_genre[LANGUAGE_NONE][0]['entity'];
  } else if (isset($node->field_genre[LANGUAGE_NONE][0]['target_id'])) {
    $node->first_genre = taxonomy_term_load($node->field_genre[LANGUAGE_NONE][0]['target_id']);
  }
  
  if ($node->status == 0) {
    drupal_set_message(t('The review %review is not published.', array('%review' => $node->title)), 'warning');
  }
}

/**
 * Preprocess concert review node.
 */
function _preprocess_concert_review(&$node) {
  
  $concert_reviews = array();
  if (isset($node->field_concert_reviews[LANGUAGE_NONE])) {
    foreach ($node->field_concert_reviews[LANGUAGE_NONE] as $concert_review) {
      $concert_reviews[] = field_collection_item_load($concert_review['value']);
    }
  }
  $node->concert_reviews = $concert_reviews;
  
  $concert_node = node_load($node->field_concert[LANGUAGE_NONE][0]['target_id']);
  
  $is_festival = (isset($concert_node->field_is_festival[LANGUAGE_NONE]) && $concert_node->field_is_festival[LANGUAGE_NONE][0]['value'] == 1) ? TRUE : FALSE;
  
  $node->headline = (!$is_festival) ? _generate_artists_string($concert_node->field_artists[LANGUAGE_NONE]) : $concert_node->title;
  $node->venue = (isset($concert_node->field_venue[LANGUAGE_NONE])) ? _get_venue_name($concert_node->field_venue[LANGUAGE_NONE][0]['tid']) : '';
  $node->venue_tid = (isset($concert_node->field_venue[LANGUAGE_NONE])) ? $concert_node->field_venue[LANGUAGE_NONE][0]['tid'] : NULL;
  
  $node->concertdate = $concert_node->field_event_date[LANGUAGE_NONE][0]['value'];
  $node->enddate = ($concert_node->field_event_date[LANGUAGE_NONE][0]['value'] != $concert_node->field_event_date[LANGUAGE_NONE][0]['value2']) ? $concert_node->field_event_date[LANGUAGE_NONE][0]['value2'] : FALSE;
  
  $headliners = array();
  $support = array();
  $full_lineup = FALSE;
  if (isset($concert_node->field_artists[LANGUAGE_NONE])) {
    if (!$is_festival) {
      foreach ($concert_node->field_artists[LANGUAGE_NONE] as $a) {
        $headliners[] = l(_get_artist_name($a['target_id']), 'node/' . $a['target_id']);
      }
    }
    else {
      $limit = 5; // Maximal number of festival artists to display before a 'More' link
      for ($i = 0; $i < $limit; $i++) {
        if (isset($concert_node->field_artists[LANGUAGE_NONE][$i])) {
          $headliners[] = l(_get_artist_name($concert_node->field_artists[LANGUAGE_NONE][$i]['target_id']), 'node/' . $concert_node->field_artists[LANGUAGE_NONE][$i]['target_id']);
        }
      }
      if (count($concert_node->field_artists[LANGUAGE_NONE]) > $limit) {
        $full_lineup = l(t('Full line-up'), 'node/' . $concert_node->nid);
      }
    }
  }
  if (isset($concert_node->field_support_artists[LANGUAGE_NONE])) {
    foreach ($concert_node->field_support_artists[LANGUAGE_NONE] as $b) {
      $support[] = l(_get_artist_name($b['target_id']), 'node/' . $b['target_id']);
    }
  }
  
  $node->num_headliners = (isset($node->published_at)) ? count($concert_node->field_artists[LANGUAGE_NONE]) : 0;
  $node->headliners = implode(", ", $headliners);
  $node->support = implode(", ", $support);
  $node->full_lineup = $full_lineup;
  
  $node->primary_img = (isset($node->field_image[LANGUAGE_NONE][0])) ? TRUE : FALSE;
  $node->has_galley = (isset($node->field_photos[LANGUAGE_NONE]) && count($node->field_photos[LANGUAGE_NONE]) > 0) ? TRUE : FALSE;
  $node->concert_node = $concert_node;
}

/**
 * Preprocess reportage node.
 */
function _preprocess_reportage(&$node) {

  $has_gallery = (isset($node->field_photos[LANGUAGE_NONE]) && count($node->field_photos[LANGUAGE_NONE]) > 0) ? TRUE : FALSE;
  
  $rating_value = 0;
  $rating_count = 0;
  
  $number_reviews = 0;
  
  $tabs = array();
  $tab_names = array();
  
  if (isset($node->field_tabs[LANGUAGE_NONE])) {

    for ($i = 0; $i < count($node->field_tabs[LANGUAGE_NONE]); $i++) {
      $tab_item = field_collection_item_load($node->field_tabs[LANGUAGE_NONE][$i]['value']);
      
      //$next = FALSE;
      $tab_item->next_link = FALSE;
      $prev_index = $i-1;
      $next_index = $i+1;
      if (isset($node->field_tabs[LANGUAGE_NONE][$prev_index])) {
        //print_r($tab_item->field_tab_headline);
        $tabs[$prev_index]->next_link = $tab_item->field_tab_headline[LANGUAGE_NONE][0];
      }
      if ($has_gallery && !isset($node->field_tabs[LANGUAGE_NONE][$next_index])) {
        $tab_item->next_link = array(
          'human' => 'Galleri',
          'machine' => 'galleri',
        );
      }
      
      $tab_item->reviews = array();
      foreach ($tab_item->field_reportage_review[LANGUAGE_NONE] as $review) {
        $review = field_collection_item_load($review['value']);
        
        // Create machine key of artist name:
        if (isset($review->field_artist[LANGUAGE_NONE][0]['entity'])) {
          $machine_suggestion = transliteration_get($review->field_artist[LANGUAGE_NONE][0]['entity']->title, '-', 'en');
          $machine_suggestion = strtolower($machine_suggestion);
          $machine_suggestion = str_replace(' ', '-', $machine_suggestion);
        } else {
          $machine_suggestion = '';
        }
        $review->artist_key = $machine_suggestion;
        
        if (isset($review->field_rating[LANGUAGE_NONE][0]['value'])) {
          $rating_value += $review->field_rating[LANGUAGE_NONE][0]['value'];
          $rating_count++;
        }

        $tab_item->reviews[] = $review;
        $number_reviews++;
      }
      $tabs[] = $tab_item;
      $tab_names[$tab_item->field_tab_headline[LANGUAGE_NONE][0]['machine']] = $tab_item->field_tab_headline[LANGUAGE_NONE][0]['human'];
    }
  }

  $node->rating_value = ($rating_count > 0) ? number_format(($rating_value / $rating_count)/2, 1) : $rating_value;
  $node->rating_count = $rating_count;
  $node->has_gallery = $has_gallery;
  $node->number_reviews = $number_reviews;
  
  $node->tab_names = $tab_names;
  $node->tabs = $tabs;
  
  $node->primary_img = (isset($node->field_image[LANGUAGE_NONE][0])) ? TRUE : FALSE;
  
  if ($node->status == 0) {
    drupal_set_message(t('The reportage %reportage is not published.', array('%reportage' => $node->title)), 'warning');
  }
}

/**
 * Preprocess concert node.
 */
function _preprocess_concert(&$node) {
  $artists = array();
  if (isset($node->field_artists[LANGUAGE_NONE])) {
    foreach ($node->field_artists[LANGUAGE_NONE] as $a) {
      $name = _get_artist_name($a['target_id']);
      $artists[] = l($name, 'node/' . $a['target_id'], array('attributes' => array('title' => $name . " bandprofil")));
    }
  }
  if (isset($node->field_support_artists[LANGUAGE_NONE])) {
    foreach ($node->field_support_artists[LANGUAGE_NONE] as $b) {
      $name = _get_artist_name($b['target_id']);
      $artists[] = l($name, 'node/' . $b['target_id'], array('attributes' => array('title' => $name . " bandprofil")));
    }
  }
  $node->artists = implode(", ", $artists);

  // Get concert review (if any):
  $q = db_select('field_data_field_concert', 'c');
  $q->fields('c', array('entity_id'));
  $q->condition('c.field_concert_target_id', $node->nid);
  $q->range(0, 1);
  $rs = $q->execute()->fetchAssoc();
  
  $node->review = (is_array($rs)) ? $rs['entity_id'] : FALSE;
  
  if ($node->status == 0) {
    drupal_set_message(t('The concert %concert is not published.', array('%concert' => $node->title)), 'warning');
  }
}

/**
 * Preprocess artist node.
 */
function _preprocess_artist(&$node) {

  // Get releases:
  $sql = "SELECT a.`entity_id` AS release_id, r.entity_id AS review_id ";
  $sql.= "FROM `field_data_field_artist` a ";
  $sql.= "LEFT JOIN field_data_field_release r ON r.field_release_target_id = a.entity_id ";
  $sql.= "INNER JOIN field_data_field_release_date d ON d.entity_id = a.entity_id ";
  $sql.= "WHERE a.`field_artist_target_id` = :artist_id ";
  $sql.= "AND a.bundle = :bundle ";
  $sql.= "ORDER BY d.field_release_date_value DESC";
  $rs = db_query($sql, array(':artist_id' => $node->nid, ':bundle' => 'release'));
  
  $releases = array();
  foreach ($rs as $row) {
    $release_node = node_load($row->release_id);
    $review_node = (!is_null($row->review_id)) ? node_load($row->review_id) : FALSE;
    
    $releases[] = array(
      'release' => $release_node,
      'review' => $review_node,
    );
  }
  $node->releases = $releases;
  
  $node->news = _get_artist_news($node->nid);
  $node->concerts = _get_artist_concerts($node->nid);
  $node->interviews = _get_artist_interview($node->nid);
  $node->reports = _get_artist_reports($node->nid);
  
  $genres = FALSE;
  if (isset($node->field_artist_genres[LANGUAGE_NONE])) {
    $g = array();
    foreach ($node->field_artist_genres[LANGUAGE_NONE] as $genre) {
      if (isset($genre['taxonomy_term'])) {
        $g[] = l($genre['taxonomy_term']->name, 'taxonomy/term/' . $genre['taxonomy_term']->tid);
      } else {
        $term = taxonomy_term_load($genre['tid']);
        $g[] = l($term->name, 'taxonomy/term/' . $term->tid);
      }
    }
    $genres = (count($g) == 1) ? 'Genre: ' : 'Genrer: ';
    $genres .= implode(", ", $g);
  }
  $node->genres = $genres;
  
  // Load related bands:
  $related = FALSE;
  if (isset($node->field_related_bands[LANGUAGE_NONE])) {
    $r = array();
    foreach ($node->field_related_bands[LANGUAGE_NONE] as $rel) {
      if (isset($rel['entity'])) {
        $r[] = l($rel['entity']->title, 'node/' . $rel['entity']->nid);
      } else {
        $n = node_load($rel['target_id']);
        $r[] = l($n->title, 'node/' . $rel['target_id']);
      }
    }
    $related = (count($r) == 1) ? 'Relateret band: ' : 'Relaterede bands: ';
    $related .= implode(", ", $r);
  }
  $node->related = $related;
}

/**
 * Preprocess news node.
 */
function _preprocess_news(&$node) {
  $artists = array();
  if (isset($node->field_artists[LANGUAGE_NONE])) {
    foreach ($node->field_artists[LANGUAGE_NONE] as $a) {
      if (isset($a['entity'])) {
        $artists[] = l($a['entity']->title, 'node/' . $a['entity']->nid);
      }
    }
  }
  $node->artists = implode(", ", $artists);
}
