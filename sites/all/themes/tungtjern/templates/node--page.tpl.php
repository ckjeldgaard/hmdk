<article>
<?php if ($node->field_show_byline[LANGUAGE_NONE][0]['value'] == 1) : ?>
<div class="pure-g byline">
  <div class="pure-u-2-3 pure-u-md-4-5">
    <p class="post-meta">
      <?php print _get_type($node->type); ?>
      <i class="fa fa-user"></i> Skrevet den <?php print formatted_date($node->published_at); ?> af <?php print l($node->name, 'user/' . $node->uid); ?>
      <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
      <i class="fa fa-comments"></i> <a href="#comments"><?php print format_plural($node->comment_count, '1 comment', '@count comments'); ?></a>
      <?php endif; ?>
    </p>
  </div>
  <div class="pure-u-1-3 pure-u-md-1-5 fb-actions">
    <div class="fb-like" data-href="<?php print url('node/' . $node->nid, array('absolute' => TRUE)); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
  </div>
</div>
<?php endif; ?>
<h1><?php print $node->title; ?></h1>

<?php if (isset($node->field_image[LANGUAGE_NONE][0])) : ?>
<p>
  <img src="<?php print image_cache('top_image', $node->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->field_image[LANGUAGE_NONE][0]['alt']); ?>" class="pure-img" />
  <?php if (strlen($node->field_image[LANGUAGE_NONE][0]['title']) > 0) : ?>
    <span class="imgdesc"><?php print $node->field_image[LANGUAGE_NONE][0]['title']; ?></span>
  <?php endif; ?>
</p>
<?php endif; ?>

<?php if (isset($node->body[LANGUAGE_NONE][0])) : ?>
  <?php if (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
    <p class="summary"><?php print $node->body[LANGUAGE_NONE][0]['summary']; ?></p>
  <?php endif; ?>
  <?php print $node->body[LANGUAGE_NONE][0]['safe_value']; ?>
<?php endif; ?>

<?php print render($content['comments']); ?>
</article>