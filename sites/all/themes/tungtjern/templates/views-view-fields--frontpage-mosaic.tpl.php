<?php $node = node_load($row->nid); ?>
<?php if (is_numeric($row->draggableviews_structure_weight) && $row->draggableviews_structure_weight == 0) : ?>
  <?php $node_view = node_view($node, 'topfront'); ?>
  <?php print render($node_view); ?>
<?php elseif (is_numeric($row->draggableviews_structure_weight) && $row->draggableviews_structure_weight < 4) : ?>
  <?php $node_view = node_view($node, 'focus'); ?>
  <?php print render($node_view); ?>
<?php else: ?>
  <?php $node_view = node_view($node, 'teaser'); ?>
  <?php print render($node_view); ?>
<?php endif;?>
