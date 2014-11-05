<section class="focus-item <?php print get_edit_classes($node); ?>">
  <div class="focus-header">
    <?php print _get_type($node->type); ?>
    <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
      <p class="comments meta-inline"><a href="<?php print url('node/' . $node->nid); ?>#comments"><i class="fa fa-comments"></i> <?php print $node->comment_count; ?></a></p>
    <?php endif; ?>
  </div>
  <a href="<?php print url('node/' . $node->nid); ?>">
    <?php if ($node->type == 'review' && isset($node->release->field_image[LANGUAGE_NONE])) : ?>
    <img src="<?php print image_cache('review', $node->release->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->release->title); ?>" class="pure-img" />
    <?php elseif (isset($node->field_image[LANGUAGE_NONE])): ?>
    <img src="<?php print image_cache('review', $node->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->title); ?>" class="pure-img" />
    <?php endif; ?>
  </a>
  <?php if ($node->type == 'review') : ?>
    <h1><a href="<?php print url('node/' . $node->nid); ?>"><?php print _get_artist_name($node->release->field_artist[LANGUAGE_NONE][0]['target_id']); ?> <span class="release-title"><?php print $node->release->field_release_title[LANGUAGE_NONE][0]['value']; ?></span></a></h1>
  <?php else: ?>
    <h1><?php print l($node->title, 'node/' . $node->nid); ?></h1>
  <?php endif; ?>
  <?php if (isset($node->field_tagline[LANGUAGE_NONE][0])) : ?>
    <p><?php print $node->field_tagline[LANGUAGE_NONE][0]['value']; ?></p>
  <?php elseif (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
    <p><?php print truncate_utf8($node->body[LANGUAGE_NONE][0]['summary'], 80, TRUE, TRUE); ?></p>
  <?php else: ?>
    <p><?php print truncate_utf8(strip_tags($node->body[LANGUAGE_NONE][0]['value']), 80, TRUE, TRUE); ?></p>
  <?php endif; ?>
	
  <?php if (isset($node->field_rating[LANGUAGE_NONE])) : ?>
    <p class="rating-small grade<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>"><?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>/10</p>
  <?php endif; ?>

  <p class="author meta-inline">
    <i class="fa fa-user"></i> Af <?php print ($node->uid > 0) ? l($node->name, 'user/' . $node->uid) : t('Anonymous'); ?>,
    <time datetime="<?php print format_date($node->published_at, 'date'); ?>"><?php print format_date($node->published_at, 'displaydate'); ?></time>
  </p>
  <?php if (isset($node->first_genre)) : ?>
    <p class="genre meta-inline"><i class="fa fa-music"></i> Genre: <?php print l($node->first_genre->name, 'taxonomy/term/'.$node->first_genre->tid); ?></p>
  <?php endif; ?> 
  
</section>
