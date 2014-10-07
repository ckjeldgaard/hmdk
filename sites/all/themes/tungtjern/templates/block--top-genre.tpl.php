<?php if (is_array($content) && count($content['reviews']) > 0): ?>
<section class="sidebarItem top-genre">
<h2><?php print t('Promoted !genre albums', array('!genre' => $content['genre'])); ?></h2>
<?php foreach ($content['reviews'] as $node) : ?>
  <div class="pure-g review<?php print get_edit_classes($node); ?>">
    <div class="pure-u-1-3 pure-u-md-1 pure-u-lg-1-3">
      <a href="<?php print url('node/' . $node->nid); ?>"><img src="<?php print (function_exists('image_cache')) ? image_cache('review_sidebar', $node->image) : ''; ?>" alt="<?php print check_plain($node->title); ?>" /></a>
    </div>
    <div class="pure-u-2-3 pure-u-md-1 pure-u-lg-2-3 meta">
      <h3><?php print l($node->title, 'node/' . $node->nid); ?> <span>(<?php print $node->release_year; ?>)</span></h3>
      <?php if (isset($node->field_classic[LANGUAGE_NONE]) && $node->field_classic[LANGUAGE_NONE][0]['value'] == 1): ?>
        <p class="classic"><i class="fa fa-star"></i> <?php print t('Classic'); ?></p>
      <?php endif; ?>
      <?php if (isset($node->field_rating[LANGUAGE_NONE])) : ?>
      <p class="rating-small grade<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>"><?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>/10</p>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</section>
<?php endif; ?>