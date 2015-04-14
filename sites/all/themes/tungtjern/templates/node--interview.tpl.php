<h1><?php print $node->title; ?></h1>
<div class="pure-g byline">
  <div class="pure-u-2-3 pure-u-md-4-5">
    <p class="post-meta">
      <?php print _get_type($node->type); ?>
      <?php if (isset($node->field_artist[LANGUAGE_NONE][0]['entity']) && is_object($node->field_artist[LANGUAGE_NONE][0]['entity'])) : ?>
        <?php print l($node->field_artist[LANGUAGE_NONE][0]['entity']->title, 'node/' . $node->field_artist[LANGUAGE_NONE][0]['target_id']); ?>
      <?php endif; ?>
      <span class="author">
      <i class="fa fa-user"></i> Interviewet den <time datetime="<?php print format_date($node->published_at, 'date'); ?>"><?php print format_date($node->published_at, 'displaydate'); ?></time> af <?php print ($node->uid > 0) ? l($node->name, 'user/' . $node->uid) : t('Anonymous'); ?>.
      </span>
      <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
      <span class="comments"><i class="fa fa-comments"></i> <a href="#comments"><?php print format_plural($node->comment_count, '1 comment', '@count comments'); ?></a></span>
      <?php endif; ?>
    </p>
  </div>
  <div class="pure-u-1-3 pure-u-md-1-5 fb-actions">
    <div class="fb-like" data-href="<?php print url('node/' . $node->nid, array('absolute' => TRUE)); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
  </div>
</div>

<?php if (isset($node->field_image[LANGUAGE_NONE][0])) : ?>
<p>
  <img src="<?php print image_cache('top_image', $node->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->field_image[LANGUAGE_NONE][0]['alt']); ?>" class="pure-img" />
  <?php if (strlen($node->field_image[LANGUAGE_NONE][0]['title']) > 0) : ?>
    <span class="imgdesc"><?php print $node->field_image[LANGUAGE_NONE][0]['title']; ?></span>
  <?php endif; ?>
</p>
<?php endif; ?>

<?php if (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
  <p class="summary"><?php print $node->body[LANGUAGE_NONE][0]['summary']; ?></p>
<?php endif; ?>
<?php print $node->body[LANGUAGE_NONE][0]['value']; ?>

<?php if (isset($node->field_embed_code[LANGUAGE_NONE])) : ?>
<div class="video-container">
  <iframe width="640" height="360" src="//www.youtube.com/embed/<?php print $node->field_embed_code[LANGUAGE_NONE][0]['value']; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
</div>
<?php endif; ?>

<?php print render($content['comments']); ?>