<?php if (count($content) > 0) : ?>
<div class="related">
<?php foreach ($content as $artist) : ?>
  <?php if (count($artist['nodes']) > 0) : ?>
    <h2><?php print t('More'); ?> <?php print $artist['artist']; ?></h2>
    <?php foreach ($artist['nodes'] as $node) : ?>
      <?php if ($node->type == 'review') : ?>
      <div class="pure-g item <?php print get_edit_classes($node); ?>">
        <div class="pure-u-1-3 pure-u-md-1 pure-u-lg-1-3">
          <a href="<?php print url('node/' . $node->nid); ?>">
          <img src="<?php print (function_exists('image_cache')) ? image_cache('review_sidebar', $node->release->field_image[LANGUAGE_NONE][0]) : ''; ?>" alt="<?php print check_plain($node->title); ?>" />
          </a>
        </div>
        <div class="pure-u-2-3 pure-u-md-1 pure-u-lg-2-3 content">
          <p class="info">
            <?php print _get_type($node->type); ?>
            <time datetime="<?php print format_date($node->published_at, 'date'); ?>"><?php print format_date($node->published_at, 'displaydate_short'); ?></time>
            <?php if ($node->comment_count > 0): ?>
              <a href="<?php print url('node/' . $node->nid); ?>#comments" class="comments nowrap">
                <i class="fa fa-comments"></i> <?php print $node->comment_count; ?>
              </a>
            <?php endif; ?>
          </p>
          <h3><a href="<?php print url('node/' . $node->nid); ?>"><?php print $node->title; ?>
          <?php if ($node->release->field_release_type[LANGUAGE_NONE][0]['value'] != 'Album') : ?>
            (<?php print $node->release->field_release_type[LANGUAGE_NONE][0]['value']; ?>)
          <?php endif; ?></a></h3>
          <?php if (isset($node->field_classic[LANGUAGE_NONE]) && $node->field_classic[LANGUAGE_NONE][0]['value'] == 1): ?>
            <p class="classic"><i class="fa fa-star"></i> <?php print t('Classic'); ?></p>
          <?php endif; ?>
          <?php if (isset($node->field_rating[LANGUAGE_NONE])) : ?>
          <p class="rating-small grade<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>"><?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>/10</p>
          <?php endif; ?>
        </div>
      </div>
      <?php else: ?>
      <div class="pure-g item <?php print get_edit_classes($node); ?>">
        <div class="pure-u-1-3 pure-u-md-1 pure-u-lg-1-3">
          <?php if (isset($node->field_image[LANGUAGE_NONE][0])) : ?>
          <a href="<?php if ($node->type == 'reportage') : ?><?php print url('node/' . $node->nid, array('fragment' => $node->artist_key)); ?><?php else: ?><?php print url('node/' . $node->nid); ?><?php endif; ?>">
          <img src="<?php print (function_exists('image_cache')) ? image_cache('review_sidebar', $node->field_image[LANGUAGE_NONE][0]) : ''; ?>" alt="<?php print check_plain($node->title); ?>" />
          </a>
          <?php elseif($node->type == 'concert_review' && !isset($node->field_image[LANGUAGE_NONE][0])) : ?>
          <a href="<?php print url('node/' . $node->nid); ?>">
          <img src="/sites/all/themes/tungtjern/img/concert.png" alt="<?php print check_plain($node->title); ?>" />
          </a>
          <?php endif; ?>
        </div>
        <div class="pure-u-2-3 pure-u-md-1 pure-u-lg-2-3 content">
          <p class="info">
            <?php print _get_type($node->type); ?><?php if ($node->type == 'concert_review') : ?><br /><?php endif; ?>
            <time datetime="<?php print format_date($node->created, 'date'); ?>"><?php print format_date($node->created, 'displaydate_short'); ?></time>
            <?php if ($node->comment_count > 0): ?>
              <a href="<?php print url('node/' . $node->nid); ?>#comments" class="comments nowrap">
                <i class="fa fa-comments"></i> <?php print $node->comment_count; ?>
              </a>
            <?php endif; ?>
          </p>
          <h3><a href="<?php if ($node->type == 'reportage') : ?><?php print url('node/' . $node->nid, array('fragment' => $node->artist_key)); ?><?php else: ?><?php print url('node/' . $node->nid); ?><?php endif; ?>">
          <?php print $node->title; ?>
          </a></h3>
          <?php if ($node->type == 'concert_review' && isset($node->field_rating[LANGUAGE_NONE][0])) : ?>
          <p class="rating-small grade<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>"><?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>/10</p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
<?php endforeach; ?>

</div>
<?php endif; ?>