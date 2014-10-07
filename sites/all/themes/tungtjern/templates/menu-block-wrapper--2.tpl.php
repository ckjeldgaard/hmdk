<?php
// Get parent name:
reset($content);
$first_key = key($content);
$parent = ($plid = $content[$first_key]['#original_link']['plid']) ? menu_link_load($plid) : FALSE;
?>
<div class="submenu">
  <?php if ($parent) : ?>
  <h2><?php print $parent['link_title']; ?></h2>
  <?php endif; ?>
  <div class="submenuList">
    <?php print render($content); ?>
  </div>
</div>