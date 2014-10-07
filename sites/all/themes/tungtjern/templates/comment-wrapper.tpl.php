<div id="comments" class="<?php print $classes; ?>"<?php print $attributes; ?> itemscope itemtype="http://schema.org/UserComments">
  <?php if ($content['comments'] && $node->type != 'forum'): ?>
    <?php print render($title_prefix); ?>
    <h2 class="title"><?php print t('Comments'); ?> (<?php print $node->comment_count; ?>)</h2>
    <?php print render($title_suffix); ?>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

  <?php if ($content['comment_form']): ?>
    <h2 class="title comment-form"><?php print t('Add new comment'); ?></h2>
    <?php print render($content['comment_form']); ?>
  <?php else: ?>
    <p><?php print theme('comment_post_forbidden', array('node' => $node)); ?></p>
  <?php endif; ?>
</div>