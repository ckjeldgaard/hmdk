<section class="pure-g page row top<?php print get_edit_classes($node); ?>">
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
        <time datetime="<?php print format_date($node->published_at, 'date'); ?>"><?php print format_date($node->published_at, 'displaydate'); ?></time>
      </p>
      
    <h1><?php print l($node->title, 'node/' . $node->nid); ?></h1>
<?php if (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
  <p><?php print truncate_utf8($node->body[LANGUAGE_NONE][0]['summary'], 140, TRUE); ?></p>
<?php else: ?>
  <p><?php print truncate_utf8(strip_tags($node->body[LANGUAGE_NONE][0]['safe_value']), 140, TRUE, TRUE); ?></p>
<?php endif; ?>
    </div>
</section>