<?php if ($teaser) : ?>
<section class="pure-g row teaser">
  <?php if (isset($node->field_image[LANGUAGE_NONE][0])) : ?>
  <div class="pure-u-1 pure-u-md-1-6 pure-u-lg-1-5">
    <a href="<?php print url('node/' . $node->nid); ?>">
      <img src="<?php print image_cache('teaser_thumbnail', $node->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print $node->field_image[LANGUAGE_NONE][0]['alt']; ?>" class="pure-img" />
    </a>
  </div>
  <div class="pure-u-1 pure-u-md-5-6 pure-u-lg-4-5 meta">
  <?php else: ?>
  <div class="pure-u-1">
  <?php endif; ?>
    <h1><?php print l($node->title, 'node/' . $node->nid); ?></h1>
    <p class="post-meta">
      <?php print _get_type($node->type); ?>
      <i class="fa fa-user"></i> Skrevet af <?php print l($node->name, 'user/' . $node->uid); ?>,
      <time datetime="<?php print format_date($node->created, 'date'); ?>"><?php print format_date($node->created, 'displaydate'); ?></time>
    <?php if ($node->comments_display) : ?>
      <span class="comments"><a href="<?php print url('node/' . $node->nid); ?>#comments"><i class="fa fa-comments"></i> <?php print $node->comment_count; ?></a></span>
    <?php endif; ?>  
    </p>
    <?php if (isset($node->body[LANGUAGE_NONE][0])) : ?>
      <?php if (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
        <p><?php print truncate_utf8($node->body[LANGUAGE_NONE][0]['summary'], 140, TRUE); ?></p>
      <?php else: ?>
        <p><?php print truncate_utf8(strip_tags($node->body[LANGUAGE_NONE][0]['safe_value']), 140, TRUE, TRUE); ?></p>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php else: ?>
<article>
<h1><?php print $node->title; ?></h1>
<p class="post-meta">
  <?php print _get_type($node->type); ?>
  <span>
    <i class="fa fa-user"></i> Skrevet af <?php print l($node->name, 'user/' . $node->uid); ?>,
    <time datetime="<?php print format_date($node->created, 'datetime'); ?>"><?php print drupal_strtolower(format_date($node->created, 'fulldate')); ?></time>
  </span>
  <?php if ($node->comments_display) : ?>
  <span><i class="fa fa-comments"></i> <a href="#comments"><?php print format_plural($node->comment_count, '1 comment', '@count comments'); ?></a></span>
  <?php endif; ?>
</p>

<?php if (isset($node->field_image[LANGUAGE_NONE][0])) : ?>
<p>
  <img src="<?php print image_cache('top_image', $node->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print $node->field_image[LANGUAGE_NONE][0]['alt']; ?>" />
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
<?php endif; ?>