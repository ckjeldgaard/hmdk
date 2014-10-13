<?php $node = node_load($row->nid); ?>
<?php if ($view->row_index == 0) : ?>
  <?php $node_view = node_view($node, 'topfront'); ?>
  <?php print render($node_view); ?>
<?php elseif ($view->row_index < 4) : ?>
  <?php $node_view = node_view($node, 'focus'); ?>
  <?php print render($node_view); ?>
<?php else: ?>
  <?php $node_view = node_view($node, 'teaser'); ?>
  <?php print render($node_view); ?>
<?php endif;?>
