<?php if (count($content) > 0) : ?>
<div class="related">
  <h2>Læs også</h2>
  <?php foreach ($content as $node) : ?>
      <div class="pure-g item <?php print get_edit_classes($node); ?>">
        <div class="pure-u-1-3 pure-u-md-1 pure-u-lg-1-3">
          <?php if (isset($node->field_image[LANGUAGE_NONE][0])) : ?>
          <a href="<?php print url('node/' . $node->nid); ?>" onclick="ga('send', 'event', { eventCategory: 'related', eventAction: '<?php print $node->type; ?>', eventLabel: '<?php print check_plain($node->title); ?>'});">
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
          <h3><a href="<?php print url('node/' . $node->nid); ?>" onclick="ga('send', 'event', { eventCategory: 'related', eventAction: '<?php print $node->type; ?>', eventLabel: '<?php print check_plain($node->title); ?>'});">
          <?php print $node->title; ?>
          </a></h3>
          <?php if (($node->type == 'concert_review' || $node->type == 'reportage') && isset($node->field_rating[LANGUAGE_NONE][0])) : ?>
          <p class="rating-small grade<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>"><?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>/10</p>
          <?php endif; ?>
        </div>
      </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>