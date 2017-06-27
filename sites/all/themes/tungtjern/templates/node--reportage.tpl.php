<article itemscope itemtype="http://schema.org/WebPage">

  <div class="pure-g byline">
  <div class="pure-u-2-3 pure-u-md-4-5">
    <p class="post-meta">
      <?php print _get_type($node->type); ?>
      <i class="fa fa-user"></i> Publiseret den <?php print formatted_date($node->published_at); ?> <?php if($node->field_display_author[LANGUAGE_NONE][0]['value'] == 1): ?>af <?php print l($node->name, 'user/' . $node->uid); ?><?php endif; ?>
      <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
      <i class="fa fa-comments"></i> <a href="#comments"><?php print format_plural($node->comment_count, '1 comment', '@count comments'); ?></a>
      <?php endif; ?>
    </p>
  </div>
  <div class="pure-u-1-3 pure-u-md-1-5 fb-actions">
    <div class="fb-like" data-href="<?php print url('node/' . $node->nid, array('absolute' => TRUE)); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
  </div>
</div>

<h1><?php print $node->title; ?></h1>

<?php if (isset($node->field_image[LANGUAGE_NONE][0])) : ?>
<p>
  <img src="<?php print image_cache('top_image', $node->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->field_image[LANGUAGE_NONE][0]['alt']); ?>" class="pure-img" />
  <?php if (strlen($node->field_image[LANGUAGE_NONE][0]['title']) > 0) : ?>
    <span class="imgdesc"><?php print $node->field_image[LANGUAGE_NONE][0]['title']; ?></span>
  <?php endif; ?>
</p>
<?php endif; ?>

<?php if (isset($node->body[LANGUAGE_NONE][0])) : ?>
  <?php if (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
    <p class="summary"><?php print $node->body[LANGUAGE_NONE][0]['summary']; ?></p>
  <?php endif; ?>
  <?php print $node->body[LANGUAGE_NONE][0]['safe_value']; ?>
<?php endif; ?>

<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
  <meta itemprop="ratingValue" content="<?php print $node->rating_value; ?>">
  <meta itemprop="reviewCount" content="<?php print $node->rating_count; ?>">
</div>

  <?php if (count($node->tab_names) > 0 && count($node->tabs) > 0) : ?>
  <ul class="reportage-tabs">
  <?php foreach ($node->tabs as $tab): ?>
    <?php if (isset($tab->field_tab_headline[LANGUAGE_NONE][0]['machine'])): ?>
    <li><a <?php if ($tab->active): ?>class="active"<?php endif; ?> href="#/<?php print $tab->field_tab_headline[LANGUAGE_NONE][0]['machine']; ?>"><?php print $tab->field_tab_headline[LANGUAGE_NONE][0]['human']; ?></a></li>
    <?php endif; ?>
  <?php endforeach; ?>
  </ul>

  <div id="reportage-content">
    <?php foreach ($node->tabs as $tab): ?>
    <div id="<?php print $tab->field_tab_headline[LANGUAGE_NONE][0]['machine']; ?>" class="tab-content <?php if ($tab->active): ?>current<?php endif; ?>">

      <p><strong><?php print $tab->field_tab_headline[LANGUAGE_NONE][0]['human']; ?>:</strong></p>

      <ol>
        <?php foreach ($tab->reviews as $review): ?>
          <li><a href="#<?php print $review->artist_key; ?>"><?php print $review->field_artist[LANGUAGE_NONE][0]['entity']->title; ?></a></li>
        <?php endforeach; ?>
      </ol>

      <?php $swap = 0; ?>
      <?php foreach ($tab->reviews as $review): ?>
        <div class="performance" itemscope itemtype="http://schema.org/Review">
          <div class="header" itemprop="itemReviewed" itemscope itemtype="http://schema.org/MusicEvent">
            <h2 id="<?php print $review->artist_key; ?>"><a href="<?php print url('node/' . $review->field_artist[LANGUAGE_NONE][0]['target_id']); ?>" itemprop="name" title="<?php print $review->field_artist[LANGUAGE_NONE][0]['entity']->title; ?> bandprofil"><?php print $review->field_artist[LANGUAGE_NONE][0]['entity']->title; ?></a>
              <?php if (isset($review->field_stage[LANGUAGE_NONE][0]['value']) || isset($review->field_time[LANGUAGE_NONE][0]['value'])) : ?>
              <span class="location-time">
	  <?php if (isset($review->field_stage[LANGUAGE_NONE][0]['value'])) : ?>
      <span itemprop="location"><?php print $review->field_stage[LANGUAGE_NONE][0]['value']; ?></span><?php endif; ?><?php if (isset($review->field_stage[LANGUAGE_NONE][0]['value']) && isset($review->field_time[LANGUAGE_NONE][0]['value'])) : ?>,<?php endif; ?>
                <?php if (isset($review->field_time[LANGUAGE_NONE][0]['value'])) : ?>
                  kl. <span itemprop="startDate" content="<?php print date("Y-m-d\TH:i", $review->field_time[LANGUAGE_NONE][0]['value']); ?>"><?php print date('H:i', $review->field_time[LANGUAGE_NONE][0]['value']); ?></span>
                <?php endif; ?>
	</span>
            </h2>
            <?php endif; ?>
          </div>
          <?php if (isset($review->field_image[LANGUAGE_NONE][0])): ?>
            <div class="img <?php if ($swap == 0) : $swap = 1; ?>left<?php else: $swap = 0; ?>right<?php endif; ?>">
              <a href="<?php print image_cache('gallery_large', $review->field_image[LANGUAGE_NONE][0]); ?>" data-lightbox="reviewimage" <?php if (strlen($review->field_image[LANGUAGE_NONE][0]['title']) > 0): ?>data-title="<?php print $review->field_image[LANGUAGE_NONE][0]['title']; ?>"<?php endif; ?>><img data-src="<?php print image_cache('concert_thumb', $review->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print $review->field_image[LANGUAGE_NONE][0]['alt']; ?>" /></a>
            </div>
          <?php endif; ?>
          <div itemprop="reviewBody">
            <?php print $review->field_review_text[LANGUAGE_NONE][0]['safe_value']; ?>
          </div>
          <?php if (isset($review->field_rating[LANGUAGE_NONE][0]['value'])) : ?>
            <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
              <p>
                <meta itemprop="worstRating" content="1">
                <span class="rating grade<?php print $review->field_rating[LANGUAGE_NONE][0]['value']; ?>"><span itemprop="ratingValue"><?php print $review->field_rating[LANGUAGE_NONE][0]['value']; ?></span>/<span itemprop="bestRating">10</span></span>
              </p>
            </div>
          <?php endif; ?>
          <?php if (isset($review->field_author[LANGUAGE_NONE][0]['target_id'])) : ?>
            <p class="author"><i class="fa fa-user"></i> Anmeldt af <a href="<?php print url("user/" . $review->field_author[LANGUAGE_NONE][0]['target_id']); ?>" itemprop="author"><?php print $review->field_author[LANGUAGE_NONE][0]['entity']->name; ?></a>.</p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <?php if ($node->has_gallery) : ?>
  <div class="reportage-gallery">
    <h2 class="hdr" id="galleri">Billedegalleri</h2>
    <?php foreach ($node->field_photos[LANGUAGE_NONE] as $img) : ?>
      <a href="<?php print image_cache('gallery_large', $img); ?>" data-lightbox="concertgallery" <?php if (strlen($img['title']) > 0): ?>data-title="<?php print check_plain($img['title']); ?>"<?php endif; ?>><img src="<?php print image_cache('gallery_thumbnail', $img); ?>" alt="<?php print check_plain($img['alt']); ?>" /></a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

<?php print render($content['comments']); ?>
</article>
