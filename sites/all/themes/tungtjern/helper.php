<?php
/**
 * @file
 * File containing common helper functions to retreive, update or delete various data or content types.
 * 
 */

/**
 * Get reviews written by a particular author.
 *
 * @param int $uid The author UID.
 * @return array Returns an array of reviews.
 */
function _get_reviews_by_author($uid) {
  $sql = "SELECT n.nid, d.published_at AS review_date, na.title AS artist, t.field_release_title_value AS title ";
  $sql .= "FROM node n ";
  $sql .= "INNER JOIN publication_date d ON n.nid = d.nid ";
  $sql .= "INNER JOIN field_data_field_release r ON n.nid = r.entity_id ";
  $sql .= "INNER JOIN field_data_field_release_title t ON r.field_release_target_id = t.entity_id ";
  $sql .= "INNER JOIN field_data_field_artist a ON r.field_release_target_id = a.entity_id ";
  $sql .= "INNER JOIN node na ON a.field_artist_target_id = na.nid ";
  $sql .= "WHERE  (n.type = :type) AND (n.status = :status) AND (n.uid = :uid) ";
  $sql .= "ORDER BY d.published_at DESC, n.created DESC";
  
  $rs = db_query($sql, array(':type' => 'review', ':status' => 1, ':uid' => $uid));
  $reviews = array();
  foreach ($rs as $row) {
    $reviews[] = $row;
  }
  return $reviews;
}

/**
 * Get recent comments written by a particular author.
 *
 * @param int $uid The user UID.
 * @param int $limit Maximum number of comments to return. Defaults to 10.
 * @return array Returns an array of comment objects.
 */
function _get_user_comments($uid, $limit = 10) {
  $query = db_select('comment', 'c');
  $query->fields('c', array('cid', 'nid', 'subject', 'created'));
  $query->fields('n', array('title'));
  $query->join('node', 'n', 'c.nid = n.nid');
  $query->condition('c.uid', $uid);
  $query->condition('c.status', 1);
  $query->orderBy('c.created', 'DESC');
  $query->range(0, $limit);
  $rs = $query->execute();
  $comments = array();
  foreach ($rs as $row) {
    $comments[] = $row;
  }
  return $comments;
}

/**
 * Get news articles written by a particular author.
 *
 * @param int $uid The author UID.
 * @return array Returns an array of news.
 */
function _get_news_by_author($uid) {
  $query = db_select('node', 'n');
  $query->fields('n', array('nid', 'title', 'created'));
  $query->condition('n.type', 'news');
  $query->condition('n.status', 1);
  $query->condition('n.uid', $uid);
  $query->orderBy('n.created', 'DESC');
  
  $rs = $query->execute();
  $news = array();
  foreach ($rs as $row) {
    $news[] = $row;
  }
  return $news;
}

/**
 * Get interviews written by a particular author.
 *
 * @param int $uid The author UID.
 * @return array Returns an array of interviews.
 */
function _get_interviews_by_author($uid) {
  $query = db_select('node', 'n');
  $query->fields('n', array('nid', 'title', 'created'));
  $query->condition('n.type', 'interview');
  $query->condition('n.status', 1);
  $query->condition('n.uid', $uid);
  $query->orderBy('n.created', 'DESC');
  
  $rs = $query->execute();
  $interviews = array();
  foreach ($rs as $row) {
    $interviews[] = $row;
  }
  return $interviews;
}

/**
 * Get concert review nodes and reportage nodes written by an author.
 * Gets any reportage node where the author has written at least one review.
 *
 * @param int $uid The author UID.
 * @return array Returns an array of node ID's.
 */
function _get_live_reviews_by_author($uid) {
  // Get concert reviews:
  $query = db_select('node', 'n');
  $query->fields('n', array('nid', 'title', 'created'));
  $query->condition('n.type', 'concert_review');
  $query->condition('n.status', 1);
  $query->condition('n.uid', $uid);
  $query->orderBy('n.created', 'DESC');
  
  $rs = $query->execute();
  $concerts = array();
  foreach ($rs as $row) {
    $concerts[] = $row;
  }
  
  // Get reportages:
  $query = db_select('node', 'n');
  $query->fields('n', array('nid', 'title', 'created'));
  $query->join('field_data_field_tabs', 't', 't.entity_id = n.nid');
  $query->join('field_data_field_reportage_review', 'r', 'r.entity_id = t.field_tabs_value');
  $query->join('field_data_field_author', 'a', 'a.entity_id = r.field_reportage_review_value');
  $query->condition('n.status', 1);
  $query->condition('n.type', 'reportage');
  $query->condition('a.field_author_target_id', $uid);
  $query->orderBy('n.created', 'DESC');
  $query->groupBy('n.nid');
  
  $rs = $query->execute();
  foreach ($rs as $row) {
    $concerts[] = $row;
  }
  
  // Sort concert reviews and reports:
  usort($concerts, "cmp_concerts");
  
  return $concerts;
}

/**
 * usort function to compare two concerts and order them by date.
 */
function cmp_concerts($a, $b) {
    if ($a->created == $b->created) {
        return 0;
    }
    return ($a->created < $b->created) ? 1 : -1;
}

/**
 * Get news nodes tagged with a particular artist.
 *
 * @param int $artist_nid The artist node ID.
 * @return array Returns an array of news.
 */
function _get_artist_news($artist_nid) {
  $query = db_select('node', 'n');
  $query->fields('n', array('nid', 'title', 'created'));
  $query->join('field_data_field_artists', 'a', 'a.entity_id = n.nid');
  $query->condition('n.type', 'news');
  $query->condition('n.status', 1);
  $query->condition('a.field_artists_target_id', $artist_nid);
  $query->orderBy('n.created', 'DESC');
  
  $rs = $query->execute();
  $news = array();
  foreach ($rs as $row) {
    $news[] = $row;
  }
  return $news;
}

/**
 * Get interview nodes tagged with a particular artist.
 *
 * @param int $artist_nid The artist node ID.
 * @return array Returns an array of interview nodes.
 */
function _get_artist_interview($artist_nid) {
  $query = db_select('node', 'n');
  $query->fields('n', array('nid'));
  $query->join('field_data_field_artist', 'a', 'a.entity_id = n.nid');
  $query->condition('n.type', 'interview');
  $query->condition('n.status', 1);
  $query->condition('a.field_artist_target_id', $artist_nid);
  $query->orderBy('n.created', 'DESC');
  
  $rs = $query->execute();
  $interviews = array();
  foreach ($rs as $row) {
    $interviews[] = node_load($row->nid);
  }
  return $interviews;
}

/**
 * Get reportage nodes tagged with a particular artist.
 *
 * @param int $artist_nid The artist node ID.
 * @return array Returns an array of reportage nodes.
 */
function _get_artist_reports($artist_nid) {
  $query = db_select('node', 'n');
  $query->fields('n', array('nid', 'title', 'created'));
  $query->join('field_data_field_tabs', 't', 't.entity_id = n.nid');
  $query->join('field_data_field_reportage_review', 'r', 'r.entity_id = t.field_tabs_value');
  $query->join('field_data_field_artist', 'a', 'a.entity_id = r.field_reportage_review_value');
  $query->condition('n.status', 1);
  $query->condition('n.type', 'reportage');
  $query->condition('a.field_artist_target_id', $artist_nid);
  $query->orderBy('n.created', 'DESC');
  $query->groupBy('n.nid');
  $obj_reportage = $query->execute();

  $reports = array();
  foreach ($obj_reportage as $row) {
    $reports[] = $row;
  }
  return $reports;
}

/**
 * Get concert nodes tagged with a particular artist.
 *
 * @param int $artist_nid The artist node ID.
 * @return array Returns an array of concerts and festivals.
 */
function _get_artist_concerts($artist_nid) {
  
  // Get concerts:
  $query = db_select('node', 'n');
  $query->distinct();
  $query->fields('n', array('nid'));
  $query->leftJoin('field_data_field_artists', 'a', 'a.entity_id = n.nid');
  $query->leftJoin('field_data_field_support_artists', 's', 's.entity_id = n.nid');
  $query->join('field_data_field_event_date', 'd', 'd.entity_id = n.nid');
  $query->condition('n.type', 'concert');
  $query->condition('n.status', 1);
  $or = db_or();
  $or->condition('a.field_artists_target_id', $artist_nid);
  $or->condition('s.field_support_artists_target_id', $artist_nid);
  $query->condition($or);
  $query->orderBy('d.field_event_date_value', 'DESC');

  $rs = $query->execute();
  $concerts = array();
  foreach ($rs as $row) {
    
    $node = node_load($row->nid);
    // If festival:
    if (isset($node->field_is_festival[LANGUAGE_NONE]) && $node->field_is_festival[LANGUAGE_NONE][0]['value'] == 1) {
      $artist_string = "<strong>" . $node->title . "</strong>: ";
      $band_limit = 5;
      $artists = array();
      for ($i = 0; $i < $band_limit; $i++) {
        if (isset($node->field_artists[LANGUAGE_NONE][$i])) {
          $artists[] = _get_artist_name($node->field_artists[LANGUAGE_NONE][$i]);
        }
      }
      $artist_string.= implode(", ", $artists);
      if (count($node->field_artists[LANGUAGE_NONE]) > $band_limit) {
        $artist_string .= ", m.fl.";
      }
    }
    else {
      // If Concert:
      $artists = array();
      if (isset($node->field_artists[LANGUAGE_NONE])) {
	foreach ($node->field_artists[LANGUAGE_NONE] as $a) {
	  $artists[] = _get_artist_name($a['target_id']);
	}
      }
      if (isset($node->field_support_artists[LANGUAGE_NONE])) {
	foreach ($node->field_support_artists[LANGUAGE_NONE] as $b) {
	  $artists[] = _get_artist_name($b['target_id']);
	}
      }
      $artist_string = implode(", ", $artists);
    }
    
    // Get concert review (if any):
    $q = db_select('field_data_field_concert', 'c');
    $q->fields('c', array('entity_id'));
    $q->condition('c.field_concert_target_id', $node->nid);
    $q->range(0, 1);
    $rs = $q->execute()->fetchAssoc();
    
    $concerts[] = array(
      'type' => 'concert',
      'node' => $node,
      'artists' => $artist_string,
      'venue' => (isset($node->field_venue[LANGUAGE_NONE])) ? _get_venue_name($node->field_venue[LANGUAGE_NONE][0]['tid']) : FALSE,
      'review' => (is_array($rs)) ? $rs['entity_id'] : FALSE,
      'date' => $node->field_event_date[LANGUAGE_NONE][0]['value'],
      'endDate' => $node->field_event_date[LANGUAGE_NONE][0]['value2'],
    );
    
  }
  return $concerts;
}