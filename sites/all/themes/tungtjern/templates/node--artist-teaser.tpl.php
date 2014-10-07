<section class="pure-g news  newsteaser row teaser<?php print get_edit_classes($node); ?>">
  <?php if (isset($node->field_logo[LANGUAGE_NONE][0])) : ?>
    <div class="pure-u-1 pure-u-md-1-6 pure-u-lg-1-5">
      <a href="<?php print url('node/' . $node->nid); ?>">
      <img src="<?php print image_cache('teaser_thumbnail', $node->field_logo[LANGUAGE_NONE][0]); ?>" alt="<?php print $node->title; ?>" class="pure-img" />
      </a>
    </div>
    <div class="pure-u-1 pure-u-md-5-6 pure-u-lg-4-5 meta">
  <?php else: ?>
  <div class="pure-u-1">
  <?php endif; ?>
    <h1><?php print l($node->title, 'node/' . $node->nid); ?></h1>
    <?php if (strlen($node->field_description[LANGUAGE_NONE][0]['safe_value']) > 0) : ?>
      <p><?php print truncate_utf8(strip_tags($node->field_description[LANGUAGE_NONE][0]['safe_value']), 140, TRUE, TRUE); ?></p>
    <?php endif; ?>
    </div>
</section>