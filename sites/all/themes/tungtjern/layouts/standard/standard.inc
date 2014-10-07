<?php

/**
 * implementation of hook_panels_layouts()
 */
// Plugin definition
$plugin = array(
  'title' => t('Standard layout'),
  'category' => t('tungtjern_layouts'),
  'icon' => 'standard.png',
  'theme' => 'panels_standard',
  'css' => 'standard.css',
  'regions' => array(
    'header_left' => t('Header left'),
    'header_right' => t('Header right'),
    'navigation' => t('Navigation'),
    'left' => t('Left side'),
    'right' => t('Right side'),
    'footer_left' => t('Footer left'),
    'footer_center' => t('Footer center'),
    'footer_right' => t('Footer right'),
    'footer_bottom' => t('Footer bottom'),
  ),
);
