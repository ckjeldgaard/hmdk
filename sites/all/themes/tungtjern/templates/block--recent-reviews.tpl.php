<?php if (count($reviews) > 0) : ?>
<section class="sidebarItem latestReviews">
  <h2><?php print t('Recent reviews'); ?></h2>
  <?php foreach ($reviews as $node) : ?>
  <div class="pure-g review<?php print get_edit_classes($node); ?>">
    <div class="pure-u-1-3 pure-u-md-1 pure-u-lg-1-3">
      <a href="<?php print url('node/' . $node->nid); ?>"><img src="<?php print (function_exists('image_cache')) ? image_cache('review_sidebar', $node->image) : ''; ?>" alt="<?php print check_plain($node->title); ?>" /></a>
    </div>
    <div class="pure-u-2-3 pure-u-md-1 pure-u-lg-2-3 meta">
      <h3><?php print l($node->title, 'node/' . $node->nid); ?></h3>
      <p class="rating-small grade<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>"><?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>/10</p>
    </div>
  </div>
  <?php endforeach; ?>
</section>
<?php endif; ?>