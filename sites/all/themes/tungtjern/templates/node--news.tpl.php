<article>
<h1><?php print $node->title; ?></h1>
<div class="pure-g byline">
  <div class="pure-u-2-3 pure-u-md-4-5">
    <p class="post-meta">
      <?php print _get_type($node->type); ?>
      <span class="author">
        <i class="fa fa-user"></i> Skrevet af <?php print ($node->uid > 0) ? l($node->name, 'user/' . $node->uid) : t('Anonymous'); ?>,
        <?php print formatted_date($node->published_at, TRUE); ?>
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
<?php print $node->body['und'][0]['value']; ?>


<?php if (isset($node->field_source[LANGUAGE_NONE][0]['url']) || strlen($node->artists) > 0): ?>
<ul class="newsmeta">
  <?php if (isset($node->field_source[LANGUAGE_NONE][0]['url'])): ?>
  <li><strong><i class="fa fa-link"></i> <?php print t('Source'); ?>:</strong> <?php print l($node->field_source[LANGUAGE_NONE][0]['title'], $node->field_source[LANGUAGE_NONE][0]['url'], array('attributes' => array('target' => '_blank', 'title' => t('Opens in new window')))); ?></li>
  <?php endif; ?>
  <?php if (strlen($node->artists) > 0): ?>
  <li><strong><i class="fa fa-music"></i> Bands:</strong> <?php print $node->artists; ?></li>
  <?php endif; ?>
</ul>
<?php endif; ?>

<?php if (isset($node->field_embed_code[LANGUAGE_NONE])) : ?>
<div class="video-container">
  <iframe width="640" height="360" src="//www.youtube.com/embed/<?php print $node->field_embed_code[LANGUAGE_NONE][0]['value']; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
</div>
<?php endif; ?>

<?php print render($content['comments']); ?>
</article>