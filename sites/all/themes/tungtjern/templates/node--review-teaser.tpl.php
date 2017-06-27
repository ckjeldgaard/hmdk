  <section class="pure-g review row teaser<?php print get_edit_classes($node); ?>">
    <div class="pure-u-1-3 pure-u-md-1-6 pure-u-lg-1-5">
      <div class="img-wrapper">
        <a href="<?php print url('node/' . $node->nid); ?>">
          <img data-src="<?php print image_cache('review_thumbnail', $node->release->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->release->title); ?>" class="pure-img" />
        </a>
      </div>
    </div>
    <div class="pure-u-2-3 pure-u-md-5-6 pure-u-lg-4-5 meta">
      <p class="post-meta">
        <?php print _get_type($node->type); ?>
        <span class="author">
          <i class="fa fa-user"></i> Anmeldt af <?php print ($node->uid > 0) ? l($node->name, 'user/' . $node->uid) : t('Anonymous'); ?>,
          <time datetime="<?php print format_date($node->published_at, 'date'); ?>"><?php print format_date($node->published_at, 'displaydate'); ?></time>
        </span>
      <?php if (isset($node->first_genre)) : ?>
        <span class="genre"><i class="fa fa-music"></i> Genre: <?php print l($node->first_genre->name, 'taxonomy/term/'.$node->first_genre->tid); ?></span>
      <?php endif; ?>
      <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
        <span class="comments"><a href="<?php print url('node/' . $node->nid); ?>#comments"><i class="fa fa-comments"></i> <?php print $node->comment_count; ?></a></span>
      <?php endif; ?>
      <?php if (isset($node->field_classic[LANGUAGE_NONE]) && $node->field_classic[LANGUAGE_NONE][0]['value'] == 1): ?>
        <span class="classic"><i class="fa fa-star"></i> <?php print t('Classic'); ?></span>
      <?php endif; ?>
      </p>
      <h1><a href="<?php print url('node/' . $node->nid); ?>"><?php print $node->title; ?>
      <?php if ($node->release->field_release_type[LANGUAGE_NONE][0]['value'] != 'Album') : ?>
        (<?php print $node->release->field_release_type[LANGUAGE_NONE][0]['value']; ?>)
      <?php endif; ?></a></h1>
      <?php if (isset($node->field_tagline[LANGUAGE_NONE][0])) : ?>
      <p><?php print $node->field_tagline[LANGUAGE_NONE][0]['value']; ?></p>
      <?php endif; ?>
      <?php if (isset($node->field_rating[LANGUAGE_NONE])) : ?>
        <p class="rating-small grade<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>"><?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>/10</p>
      <?php endif; ?>
    </div>
  </section>
