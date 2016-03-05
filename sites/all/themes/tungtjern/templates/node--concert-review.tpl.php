<article>
  
<div class="pure-g byline">
  <div class="pure-u-2-3 pure-u-md-4-5">
    <p class="post-meta">
      <?php print _get_type($node->type); ?>
      <span class="author">
        <i class="fa fa-user"></i> Anmeldt den <?php print formatted_date($node->published_at); ?> af <?php print ($node->uid > 0) ? l($node->name, 'user/' . $node->uid) : t('Anonymous'); ?>
      </span>
      <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
      <span class="comments">
        <i class="fa fa-comments"></i> <a href="#comments"><?php print format_plural($node->comment_count, '1 comment', '@count comments'); ?></a>
      </span>
      <?php endif; ?>
    </p>
  </div>
  <div class="pure-u-1-3 pure-u-md-1-5 fb-actions">
    <div class="fb-like" data-href="<?php print url('node/' . $node->nid, array('absolute' => TRUE)); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
  </div>
</div>

<?php if ($node->primary_img) : ?>
<div class="pure-g">
  <div class="pure-u-1 pure-u-md-1 pure-u-lg-1-2">
    <img src="<?php print image_cache('story', $node->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->title); ?>" class="pure-img" />
    
    <br /><span class="imgdesc">
    <?php if (strlen($node->field_image[LANGUAGE_NONE][0]['title']) > 0): ?><?php print $node->field_image[LANGUAGE_NONE][0]['title']; ?><?php endif; ?>
    <?php if (strlen($node->field_image[LANGUAGE_NONE][0]['title']) > 0 && $node->has_galley): ?>·<?php endif; ?>
    <?php if ($node->has_galley) : ?><i class="fa fa-picture-o"></i> <a href="#galleri"><?php print t('See more photos in the gallery'); ?></a><?php endif; ?>
    </span>
  </div>
  <div class="pure-u-1 pure-u-md-1 pure-u-lg-1-2 review-meta">
<?php else: ?>
  <div class="review-meta noimg">
<?php endif; ?>
    <h1><?php print $node->headline; ?></h1>
    <p class="title"><a href="<?php print url('taxonomy/term/' . $node->venue_tid); ?>"><?php print $node->venue; ?></a></p>
    <p><?php print formatted_date($node->concertdate); ?><?php if ($node->enddate) : ?> - <?php print formatted_date($node->enddate); ?><?php endif; ?>
    </p>
    <ul>
      <li><strong><?php print format_plural($node->num_headliners, 'Headliner', 'Headliners'); ?>:</strong> <?php print $node->headliners; ?><?php if ($node->full_lineup) : ?>, (<?php print $node->full_lineup; ?>)<?php endif; ?></li>
      <?php if (strlen($node->support) > 0): ?>
      <li><strong>Support:</strong> <?php print $node->support; ?></li>
      <?php endif; ?>
    </ul>
    
    <?php if (isset($node->field_rating[LANGUAGE_NONE][0]['value'])) : ?>
    <p>Officiel vurdering:
      <span class="rating grade<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>"><?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>/10</span>
    </p>
    <?php endif; ?>
    
    <?php if (!$node->primary_img && $node->has_galley) : ?><p><i class="fa fa-picture-o"></i> <a href="#galleri"><?php print t('See photos in the gallery'); ?></a></p><?php endif ?>
    
<?php if ($node->primary_img) : ?>
  </div></div>
<?php else: ?>
</div>
<?php endif; ?>

<?php if (isset($node->body[LANGUAGE_NONE][0])) : ?>
  <?php if (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
    <p class="summary"><?php print $node->body[LANGUAGE_NONE][0]['summary']; ?></p>
  <?php endif; ?>
  <?php print $node->body[LANGUAGE_NONE][0]['safe_value']; ?>
<?php endif; ?>

<?php if (count($node->concert_reviews) > 0) : ?>
  <?php $c = 1; $swap = 0; ?>
  <?php foreach($node->concert_reviews as $r) : ?>
    <div class="performance">
    <?php if (isset($r->field_headline[LANGUAGE_NONE][0]['value'])): ?>
      <h2 id="concert-<?php print $c; ?>"><?php print $r->field_headline[LANGUAGE_NONE][0]['value']; ?></h2>
    <?php endif; ?>
    <?php if (isset($r->field_image[LANGUAGE_NONE][0])): ?>
      <div class="img <?php if ($swap == 0) : $swap = 1; ?>left<?php else: $swap = 0; ?>right<?php endif; ?>">
      <a href="<?php print image_cache('gallery_large', $r->field_image[LANGUAGE_NONE][0]); ?>" data-lightbox="reviewimage" <?php if (strlen($r->field_image[LANGUAGE_NONE][0]['title']) > 0): ?>data-title="<?php print $r->field_image[LANGUAGE_NONE][0]['title']; ?>"<?php endif; ?>><img src="<?php print image_cache('concert_thumb', $r->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print $r->field_image[LANGUAGE_NONE][0]['alt']; ?>" /></a>
      </div>
    <?php endif; ?>
    <?php print (isset($r->field_review_text[LANGUAGE_NONE][0]['safe_value'])) ? $r->field_review_text[LANGUAGE_NONE][0]['safe_value'] : ''; ?>
    <?php if (isset($r->field_rating[LANGUAGE_NONE][0]['value'])) : ?>
    <p>
      <span class="rating grade<?php print $r->field_rating[LANGUAGE_NONE][0]['value']; ?>"><?php print $r->field_rating[LANGUAGE_NONE][0]['value']; ?>/10</span>
    </p>
    <?php endif; ?>
    </div>
    <?php $c++; ?>
  <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($node->field_conclusion[LANGUAGE_NONE][0])) : ?>
  <?php print $node->field_conclusion[LANGUAGE_NONE][0]['safe_value']; ?>
<?php endif; ?>

</article>

<?php if ($node->has_galley) : ?>
<div class="gallery">
<h2 id="galleri">Billedegalleri</h2>
<?php foreach ($node->field_photos[LANGUAGE_NONE] as $img) : ?>
  <a href="<?php print image_cache('gallery_large', $img); ?>" data-lightbox="concertgallery" <?php if (strlen($img['title']) > 0): ?>data-title="<?php print check_plain($img['title']); ?>"<?php endif; ?>><img src="<?php print image_cache('gallery_thumbnail', $img); ?>" alt="<?php print check_plain($img['alt']); ?>" /></a>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php print render($content['comments']); ?>