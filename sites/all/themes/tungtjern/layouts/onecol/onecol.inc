<?php

/**
 * implementation of hook_panels_layouts()
 */
// Plugin definition
$plugin = array(
  'title' => t('One Column'),
  'category' => t('tungtjern_layouts'),
  'icon' => 'onecol.png',
  'theme' => 'panels_onecol',
  'regions' => array(
    'content' => t('Content'),
  ),
);
