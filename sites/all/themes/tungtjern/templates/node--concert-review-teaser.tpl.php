<section class="pure-g livereview concert-review-teaser row teaser<?php print get_edit_classes($node); ?>">
  <?php if (isset($node->field_image[LANGUAGE_NONE][0])) : ?>
    <div class="pure-u-1-3 pure-u-md-1-6 pure-u-lg-1-5">
      <div class="img-wrapper">
        <a href="<?php print url('node/' . $node->nid); ?>">
          <img data-src="<?php print image_cache('teaser_thumbnail', $node->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->field_image[LANGUAGE_NONE][0]['alt']); ?>" class="pure-img" />
        </a>
      </div>
    </div>
    <div class="pure-u-2-3 pure-u-md-5-6 pure-u-lg-4-5 meta">
  <?php else: ?>
  <div class="pure-u-1">
  <?php endif; ?>
      <p class="post-meta">
        <?php print _get_type($node->type); ?>
        <span class="author">
        <i class="fa fa-user"></i> Skrevet af <?php print ($node->uid > 0) ? l($node->name, 'user/' . $node->uid) : t('Anonymous'); ?>,
        <time datetime="<?php print format_date($node->published_at, 'date'); ?>"><?php print format_date($node->published_at, 'displaydate'); ?></time>
        </span>
      <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
        <span class="comments"><a href="<?php print url('node/' . $node->nid); ?>#comments"><i class="fa fa-comments"></i> <?php print $node->comment_count; ?></a></span>
      <?php endif; ?>
      </p>
    <h1><a href="<?php print url('node/' . $node->nid); ?>"><?php print $node->headline; ?> <span class="venue"><?php print $node->venue; ?></span></a></h1>
    <span class="date"><?php print formatted_date($node->concertdate); ?><?php if ($node->enddate) : ?> - <?php print formatted_date($node->enddate); ?><?php endif; ?></span>
    <?php if (isset($node->field_tagline[LANGUAGE_NONE][0])) : ?>
      <p><?php print $node->field_tagline[LANGUAGE_NONE][0]['value']; ?></p>
    <?php elseif (isset($node->body[LANGUAGE_NONE][0]['summary']) && strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
      <p><?php print truncate_utf8($node->body[LANGUAGE_NONE][0]['summary'], 140, TRUE); ?></p>
    <?php elseif (isset($node->body[LANGUAGE_NONE][0]['safe_value'])): ?>
      <p><?php print truncate_utf8(strip_tags($node->body[LANGUAGE_NONE][0]['safe_value']), 140, TRUE, TRUE); ?></p>
    <?php endif; ?>
    </div>
</section>
