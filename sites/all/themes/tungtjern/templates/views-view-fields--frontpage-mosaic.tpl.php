<?php if (is_numeric($row->draggableviews_structure_weight) && $row->draggableviews_structure_weight == 0) : ?>
  <?php print render(node_view(node_load($row->nid), 'topfront')); ?>
<?php elseif (is_numeric($row->draggableviews_structure_weight) && $row->draggableviews_structure_weight < 4) : ?>
  <?php print render(node_view(node_load($row->nid), 'focus')); ?>
<?php else: ?>
  <?php print render(node_view(node_load($row->nid), 'teaser')); ?>
<?php endif;?>