<?php

echo "test";

// Load helper functions
require dirname(__FILE__) . "/helper.php";

// Load preprocess functions
require dirname(__FILE__) . "/preprocess/preprocess.php";

/**
 * Implements hook_theme().
 */
function tungtjern_theme(&$existing, $type, $theme, $path) {
  $hooks = array();
  
  $hooks['user_login_block'] = array(
    'template' => 'templates/user-login-block',
    'render element' => 'form',
  );
 
  return $hooks;
}

/**
 * Implements hook_html_head_alter().
 */
function tungtjern_html_head_alter(&$head_elements) {
  // Remove default shortcut icon link:
  if (isset($head_elements['drupal_add_html_head_link:shortcut icon:http://dev.heavymetal.dk/misc/favicon.ico'])) {
    unset($head_elements['drupal_add_html_head_link:shortcut icon:http://dev.heavymetal.dk/misc/favicon.ico']);
  }
}

/**
 * Implements hook_form_alter().
 */
function tungtjern_form_alter(&$form, &$form_state, $form_id) {
  if (!isset($form['#attributes']['class']) || !in_array('pure-form', $form['#attributes']['class'])) {
    $classes = array('pure-form', 'pure-form-stacked');
    $form['#attributes'] = array('class' => $classes);
  }
  // The language selector is only displayed if there is more than one language.
  if (drupal_multilingual()) {
    if ($form_id == 'user_register_form' || ($form_id == 'user_profile_form' && $form['#user_category'] == 'account')) {
      if (count(element_children($form['locale'])) > 1) {
        $form['locale']['language']['#access'] = FALSE;
      }
      else {
        $form['locale']['#access'] = FALSE;
      }
    }
  }
  // Disable the personal contact form option on user edit pages:
  if ($form_id == 'user_profile_form') {
    unset($form['contact']);
  }
}

/**
 * Implements theme_preprocess_html(). Adding external Pure CSS files
 */
function tungtjern_preprocess_html(&$variables) {
  global $base_root;
  
  //drupal_add_css('http://yui.yahooapis.com/pure/0.5.0/pure-min.css', array('type' => 'external'));
  //drupal_add_css('http://yui.yahooapis.com/pure/0.5.0/grids-responsive-min.css', array('type' => 'external'));
  
  if (current_path() == 'nyheder') {
    $element = array(
      '#tag' => 'link',
      '#attributes' => array(
        'href' => $base_root . '/nyheder.rss',
        'rel' => 'alternate',
        'type' => 'application/rss+xml',
        'title' => 'Heavymetal.dk nyheder'
      ),
    );
    drupal_add_html_head($element, 'rss_news_feed');
  }
  if (current_path() == 'anmeldelser') {
    $element = array(
      '#tag' => 'link',
      '#attributes' => array(
        'href' => $base_root . '/anmeldelser.rss',
        'rel' => 'alternate',
        'type' => 'application/rss+xml',
        'title' => 'Heavymetal.dk anmeldelser'
      ),
    );
    drupal_add_html_head($element, 'rss_reviews_feed');
  }
}

/**
 * Implements hook_css_alter().
 */
function tungtjern_css_alter(&$css) {
  blackwhite_alter_files($css, BLACKWHITE_CSS);
}

/**
 * Implements hook_css_alter().
 */
function tungtjern_js_alter(&$js) {
  blackwhite_alter_files($js, BLACKWHITE_JS);
}

/**
 * Implements theme_button().
 */
function tungtjern_button($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'submit';
  element_set_attributes($element, array('id', 'name', 'value'));
  $element['#attributes']['class'][] = 'form-' . $element['#button_type'];
  $element['#attributes']['class'][] = 'pure-button';
  if ($element['#id'] != 'edit-preview') {
    $element['#attributes']['class'][] = 'pure-button-primary';
  }
  
  if (!empty($element['#attributes']['disabled'])) {
    $element['#attributes']['class'][] = 'form-button-disabled';
  }

  return '<button' . drupal_attributes($element['#attributes']) . '>' . $element['#value'] . '</button>';
}

/**
 * Overrides theme_status_messages().
 */
function tungtjern_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= '<div class="pure-alert pure-alert-' . $type . '">' ."\n";
    if (!empty($status_heading[$type])) {
      $output .= '<label><strong>' . $status_heading[$type] . "</strong></label>\n";
    }
    
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>\n";
  }
  return $output;
}

/**
 * Implements [theme]_menu_tree__menu_block__[block id number]().
 */
function tungtjern_menu_tree__menu_block__1(&$variables) {
  return '<ul class="clearfix">' . $variables['tree'] . '</ul>';
}

/**
 * Implements [theme]_menu_tree__menu_block__[block id number]().
 * The submenu displayed in the sidebar (Main menu level 2-3).
 */
function tungtjern_menu_tree__menu_block__2(&$variables) {
  $out = $variables["tree"];
  return '<ul>' . $out . '</ul>';
}

/**
 * Implements [theme]_menu_tree__menu_block__[block id number]().
 */
function tungtjern_menu_tree__menu_block__3(&$variables) {
  $out = $variables["tree"];
  return '<h2>' . t('Information') . '</h2><ul>' . $out . '</ul>';
}

/**
 * Implements theme_menu_link().
 */
function tungtjern_menu_link(&$variables) {
  if ($variables['element']['#original_link']['menu_name'] == 'user-menu' && strpos($variables['element']['#original_link']['link_path'], 'facebook') !== FALSE) {
    $variables['element']['#attributes']['class'][] = 'facebook';
  }
  
  $element = $variables['element'];
  $sub_menu = '';
  
  if ($element['#below']) {
    // Wrap in dropdown-menu.
    unset($element['#below']['#theme_wrappers']);
    $sub_menu = '<ul>' . drupal_render($element['#below']) . '</ul>';
  }
  
  if (in_array('expanded', $element['#attributes']['class'])) {
    $element['#localized_options']['html'] = TRUE;
    $output = l('<span>' . $element['#title'] . '</span>', $element['#href'], $element['#localized_options']);
  }
  else {
    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  }

  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Overrides theme_form_element().
 */
function tungtjern_form_element($variables) {
  $element = &$variables['element'];

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

  if ($element['#type'] == 'checkbox' || $element['#type'] == 'radio') {
    $variables['rendered_element'] = ' ' . $prefix . $element['#children'] . $suffix . "\n";
    $output .= theme('form_element_label', $variables);
  }
  else {
    switch ($element['#title_display']) {
      case 'before':
      case 'invisible':
        $output .= ' ' . theme('form_element_label', $variables);
        $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
        break;
  
      case 'after':
        $output .= ' ' . $prefix . $element['#children'] . $suffix;
        $output .= ' ' . theme('form_element_label', $variables) . "\n";
        break;
  
      case 'none':
      case 'attribute':
        // Output no label and no required marker, only the children.
        $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
        break;
    }
  }

  if (!empty($element['#description'])) {
    $output .= '<div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}

/**
 * Overrides theme_form_element_label().
 */
function tungtjern_form_element_label($variables) {
  $element = $variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // If title and required marker are both empty, output no label.
  if ((!isset($element['#title']) || $element['#title'] === '') && empty($element['#required'])) {
    return '';
  }

  // If the element is required, a required marker is appended to the label.
  $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';

  $title = filter_xss_admin($element['#title']);

  $attributes = array();
  // Style the label as class option to display inline with the element.
  if ($element['#title_display'] == 'after') {
    $attributes['class'] = 'option';
  }
  // Show label only to screen readers to avoid disruption in visual flows.
  elseif ($element['#title_display'] == 'invisible') {
    $attributes['class'] = 'element-invisible';
  }

  if (!empty($element['#id'])) {
    $attributes['for'] = $element['#id'];
  }

  if ($element['#type'] == 'checkbox') {
    $attributes['class'] = 'pure-checkbox';
  }
  if ($element['#type'] == 'radio') {
    $attributes['class'] = 'pure-radio';
  }
  
  // The leading whitespace helps visually separate fields from inline labels.
  if (!empty($variables['rendered_element'])) {
    return ' <label' . drupal_attributes($attributes) . '>' . $variables['rendered_element'] . $t('!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
  }
  else {
    return ' <label' . drupal_attributes($attributes) . '>' . $t('!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
  }
}

/**
 * Overrides theme_textfield().
 */
function tungtjern_textfield($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'text';
  element_set_attributes($element, array('id', 'name', 'value', 'size', 'maxlength'));
  _form_set_class($element, array('form-text'));
  
  $extra = '';
  if ($element['#autocomplete_path'] && drupal_valid_path($element['#autocomplete_path'])) {
    drupal_add_library('system', 'drupal.autocomplete');
    $element['#attributes']['class'][] = 'form-autocomplete';

    $attributes = array();
    $attributes['type'] = 'hidden';
    $attributes['id'] = $element['#attributes']['id'] . '-autocomplete';
    $attributes['value'] = url($element['#autocomplete_path'], array('absolute' => TRUE));
    $attributes['disabled'] = 'disabled';
    $attributes['class'][] = 'autocomplete';
    $extra = '<input' . drupal_attributes($attributes) . ' />';
  }
  $required = (in_array('required', $element['#attributes']['class'])) ? 'required="required"' : '';

  $output = '<input' . drupal_attributes($element['#attributes']) . ' ' . $required . ' />';

  return $output . $extra;
}

/**
 * Overrides theme_pager().
 */
function tungtjern_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  //$li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => t('«'), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => t('»'), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  //$li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  $rel_prev = FALSE;
  $rel_next = FALSE;
  
  if ($pager_total[$element] > 1) {
    if (isset($li_first)) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }
    
    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '<span class="pure-button">…</span>',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
          $rel_prev = (($pager_current - 2) > 0) ? url(current_path(), array('query' => array('page' => ($pager_current - 2)), 'absolute' => TRUE)) : url(current_path(), array('absolute' => TRUE));
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current'),
            //'data' => $i,
            'data' => '<span class="pure-button pure-button-active">' . $i . '</span>',
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
          $rel_next = url(current_path(), array('query' => array('page' => $pager_current), 'absolute' => TRUE));
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '<span class="pure-button">…</span>',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    if (isset($li_last)) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }
    
    if ($rel_prev) {
      $link_prev = array(
        '#type' => 'html_tag',
        '#tag' => 'link',
        '#attributes' => array(
          'href' =>  $rel_prev,
          'rel' => 'prev',
        ),
      );
      drupal_add_html_head($link_prev, 'link_rel_prev');
    }
    if ($rel_next) {
      $link_next = array(
        '#type' => 'html_tag',
        '#tag' => 'link',
        '#attributes' => array(
          'href' =>  $rel_next,
          'rel' => 'next',
        ),
      );
      drupal_add_html_head($link_next, 'link_rel_next');
    }
    
    return theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager', 'pure-paginator')),
    ));
  }
}

/**
 * Overrides theme_pager_link().
 */
function tungtjern_pager_link($variables) {
  $text = $variables['text'];
  $page_new = $variables['page_new'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $attributes = $variables['attributes'];

  $attributes['class'][] = 'pure-button';
  
  $page = isset($_GET['page']) ? $_GET['page'] : '';
  if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
    $parameters['page'] = $new_page;
  }

  $query = array();
  if (count($parameters)) {
    $query = drupal_get_query_parameters($parameters, array());
  }
  if ($query_pager = pager_get_query_parameters()) {
    $query = array_merge($query, $query_pager);
  }

  // Set each pager link title
  if (!isset($attributes['title'])) {
    static $titles = NULL;
    if (!isset($titles)) {
      $titles = array(
        t('« first') => t('Go to first page'),
        t('‹ previous') => t('Go to previous page'),
        t('next ›') => t('Go to next page'),
        t('last »') => t('Go to last page'),
        t('»') => t('Go to next page'),
        t('«') => t('Go to previous page'),
      );
    }
    if (isset($titles[$text])) {
      $attributes['title'] = $titles[$text];
    }
    elseif (is_numeric($text)) {
      $attributes['title'] = t('Go to page @number', array('@number' => $text));
    }
  }
  
  if ($text == t('»')) {
    $attributes['class'][] = 'next';
  }
  
  if ($text == t('«')) {
    $attributes['class'][] = 'prev';
  }

  // @todo l() cannot be used here, since it adds an 'active' class based on the
  //   path only (which is always the current path for pager links). Apparently,
  //   none of the pager links is active at any time - but it should still be
  //   possible to use l() here.
  // @see http://drupal.org/node/1410574
  $attributes['href'] = url($_GET['q'], array('query' => $query));
  return '<a' . drupal_attributes($attributes) . '>' . check_plain($text) . '</a>';
}

/**
 * Get the url of a styled image
 *
 * @param $field the field to get the file from
 * @param $style name of the style to use
 *
 * @return url of the styled field
 */
function image_cache($style, $field) {
  $add = "";

  if (is_object($field)) {
    $uri = $field->uri;
  }
  else {
    $uri = $field["uri"];
    $sql = "SELECT entity_id as nid FROM {field_data_field_image} WHERE field_image_fid = :fid";

    $result = db_query($sql, array(":fid" => $field["fid"]));
    foreach ($result as $r) {
      $node = node_load($r->nid);
      if (is_object($node)) {
        $add = "?" . $node->changed;
      }
    }
  }

  $out = image_style_url($style, $uri);
  //$out .= $add;

  return $out;
}

function _get_type($node_type, $css = TRUE) {
  switch ($node_type) {
    case 'news':
      $type = t('News item');
      break;
    case 'review':
      $type = t('Review');
      break;
    case 'concert_review':
      $type = t('Concert review');
      break;
    case 'genre':
      $type = t('Genre');
      break;
    case 'interview':
      $type = t('Interview');
      break;
    case 'reportage':
      $type = t('Reportage');
      break;
    case 'contest':
      $type = t('Contest');
      break;
    default:
      $type = FALSE;
      break;
  }
  if ($type) {
    if ($css) {
      return '<span class="type">' . $type . '</span>';
    } else {
      return $type;
    }
  }
  return '';
}

/**
 * Format a timestamp integer as an HTML5 <time> tag string.
 *
 * @param int $timestamp The timestamp to convert.
 * @param bool $clock Whether to display the clock or not. Defaults to FALSE.
 * @return string Returns a <time> string.
 */
function formatted_date($timestamp, $clock = FALSE) {
  if ($timestamp > 0) {
    if ($clock) {
      $datetime = format_date($timestamp, 'datetime');
      $display_date = format_date($timestamp, 'fulldate');
    }
    else {
      $datetime = format_date($timestamp, 'date');
      $display_date = format_date($timestamp, 'displaydate');
    }
    return format_string('<time datetime="!datetime">!display_date</time>', array('!datetime' => $datetime, '!display_date' => $display_date));
  }
  return NULL;
}

/**
 * Primitive function to apply the correct gentive to a noun in Danish.
 * 
 * @param string $noun The noun.
 * @return string Returns the noun with an s-ending or an apostrophe.
 */
function _genitive($noun) {
  if (ctype_upper($noun)) {
    // Noun considered abbreviation:
    return $noun . "'s";
  }
  $last_char = drupal_strtolower(substr($noun, -1));
  if (is_numeric($last_char)) {
    return $noun . "'s";
  }
  return (in_array($last_char, array('s', 'z', 'x'))) ? $noun . "'" : $noun . "s";
}
