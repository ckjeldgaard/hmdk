<section class="pure-g livereview row top<?php print get_edit_classes($node); ?>">
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
        <?php print _get_type($node->type); ?><br />
        <i class="fa fa-user"></i> Anmeldt af <?php print ($node->uid > 0) ? l($node->name, 'user/' . $node->uid) : t('Anonymous'); ?>,
        <time datetime="<?php print format_date($node->published_at, 'date'); ?>"><?php print format_date($node->published_at, 'displaydate'); ?></time>
      <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
        <span class="comments"><a href="<?php print url('node/' . $node->nid); ?>#comments" title="<?php print format_plural($node->comment_count, '1 comment', '@count comments'); ?>"><i class="fa fa-comments"></i> <?php print $node->comment_count; ?></a></span>
      <?php endif; ?>  
      </p>
    <h1><?php print l($node->headline, 'node/' . $node->nid); ?></h1>
    <p class="title"><?php print $node->venue; ?></p>
    <p class="date"><?php print formatted_date($node->concertdate); ?><?php if ($node->enddate) : ?> - <?php print formatted_date($node->enddate); ?><?php endif; ?></p>
    <?php if (isset($node->field_tagline[LANGUAGE_NONE][0])) : ?>
      <p><?php print $node->field_tagline[LANGUAGE_NONE][0]['value']; ?></p>
    <?php elseif (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
      <p><?php print truncate_utf8($node->body[LANGUAGE_NONE][0]['summary'], 140, TRUE); ?></p>
    <?php else: ?>
      <p><?php print truncate_utf8(strip_tags($node->body[LANGUAGE_NONE][0]['safe_value']), 140, TRUE, TRUE); ?></p>
    <?php endif; ?>
    </div>
</section>