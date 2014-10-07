<section class="pure-g interview row top<?php print get_edit_classes($node); ?>">
  <?php if (isset($node->field_image[LANGUAGE_NONE][0])) : ?>
    <div class="pure-u-1 pure-u-md-1-2">
      <a href="<?php print url('node/' . $node->nid); ?>">
      <img src="<?php print image_cache('story', $node->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->field_image[LANGUAGE_NONE][0]['alt']); ?>" class="pure-img" />
      </a>
    </div>
    <div class="pure-u-1 pure-u-md-1-2 meta">
  <?php else: ?>
  <div class="pure-u-1">
  <?php endif; ?>
      <p class="post-meta">
        <?php print _get_type($node->type); ?>
        <i class="fa fa-user"></i> af <?php print ($node->uid > 0) ? l($node->name, 'user/' . $node->uid) : t('Anonymous'); ?>,
        <time datetime="<?php print format_date($node->published_at, 'date'); ?>"><?php print format_date($node->published_at, 'displaydate'); ?></time>
      <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
        <span class="comments"><a href="<?php print url('node/' . $node->nid); ?>#comments"><i class="fa fa-comments"></i> <?php print $node->comment_count; ?></a></span>
      <?php endif; ?>  
      </p>
    <h1><?php print l($node->title, 'node/' . $node->nid); ?></h1>
<?php if (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
  <p><?php print $node->body[LANGUAGE_NONE][0]['summary']; ?></p>
<?php else: ?>
  <p><?php print truncate_utf8(strip_tags($node->body[LANGUAGE_NONE][0]['safe_value']), 140, TRUE, TRUE); ?></p>
<?php endif; ?>
    </div>
</section>