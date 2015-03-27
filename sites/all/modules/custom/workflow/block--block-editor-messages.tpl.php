<?php print $content['message']; ?>
<?php if ($content['can_edit']) : ?>
  <?php print render($content['form']); ?>
<?php endif; ?>