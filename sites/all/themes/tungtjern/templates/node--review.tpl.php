<article itemprop="review" itemscope itemtype="http://schema.org/Review">
  
<?php if ($node->upcoming_concert) : ?>
<div class="pure-alert pure-alert-info">
  <?php print $node->upcoming_concert; ?>
</div>
<?php endif; ?>

<div class="pure-g byline">
  <div class="pure-u-2-3 pure-u-md-4-5">
    <p class="post-meta">
      <?php print _get_type($node->type); ?>
      <span class="author">
      <i class="fa fa-user"></i> Anmeldt <meta itemprop="datePublished" content="<?php print date('Y-m-d', $node->published_at); ?>"><?php print formatted_date($node->published_at); ?>
      af <?php print ($node->uid > 0) ? l($node->name, 'user/' . $node->uid, array('attributes' => array('itemprop' => 'author'))) : t('Anonymous'); ?>
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

<div class="pure-g">
  <div class="pure-u-1 pure-u-sm-1-3 pure-u-md-1-3 pure-u-lg-1-2">
    <img src="<?php print image_cache('review', $node->release->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->release->title); ?>" itemprop="image" class="pure-img" />
  </div>
  <div class="pure-u-1 pure-u-sm-2-3 pure-u-md-2-3 pure-u-lg-1-2 review-meta">
    <div itemprop="itemReviewed" itemscope itemtype="http://schema.org/MusicAlbum">
      <h1 itemprop="byArtist" itemscope itemtype="http://schema.org/MusicGroup"><a href="<?php print url('node/' . $node->artist->nid); ?>" itemprop="url" title="<?php print $node->artist->title; ?> bandprofil"><?php print $node->artist->title; ?></a></h1>
      <p class="title" itemprop="name"><?php print $node->release->field_release_title[LANGUAGE_NONE][0]['value']; ?></p>
      <?php if (isset($node->field_classic[LANGUAGE_NONE]) && $node->field_classic[LANGUAGE_NONE][0]['value'] == 1): ?>
        <p class="classic"><i class="fa fa-star"></i> <?php print t('Classic'); ?></p>
      <?php endif; ?>
      <p>
        <?php if ($node->label || $node->distributor) : ?>
          <?php if ($node->label) : ?>
          <span itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
            <a href="<?php print url('taxonomy/term/' . $node->label->tid); ?>" itemprop="url"><?php print $node->label->name; ?></a>
          </span>
          <?php endif; ?>
          <?php if ($node->distributor) : ?>
            / <?php print $node->distributor->name; ?>
          <?php endif; ?>
          ·
        <?php endif; ?>
        <?php if ($node->release->field_release_date[LANGUAGE_NONE][0]['value'] < time()) : ?>
          <?php print t('Released'); ?>
        <?php else: ?>
          <?php print t('Is released'); ?>
        <?php endif; ?>
        <?php print formatted_date($node->release->field_release_date[LANGUAGE_NONE][0]['value']); ?>
        <meta itemprop="dateCreated" content="<?php print date('Y-m-d', $node->release->field_release_date[LANGUAGE_NONE][0]['value']); ?>" />
      </p>
      
      <table class="record-details" border="0" cellspacing="0" cellpadding="0">
      <?php if (isset($node->release->field_release_type[LANGUAGE_NONE])) : ?><tr><th>Type:</th><td><?php print $node->release->field_release_type[LANGUAGE_NONE][0]['value']; ?></td></tr><?php endif; ?>
      <?php if (isset($node->genre) && strlen($node->genre) > 0) : ?><tr><th><?php print $node->genre_label; ?>:</th><td><?php print $node->genre; ?></td></tr><?php endif; ?>
      <?php if (isset($node->release->field_running_time[LANGUAGE_NONE][0])) : ?><tr><th>Spilletid:</th><td><?php print $node->release->field_running_time[LANGUAGE_NONE][0]['safe_value']; ?></td></tr><?php endif; ?>
      <?php if (isset($node->release->field_tracks[LANGUAGE_NONE][0])) : ?><tr><th>Antal numre:</th><td itemprop="numTracks"><?php print $node->release->field_tracks[LANGUAGE_NONE][0]['value']; ?></td></tr><?php endif; ?>
      </table>
    </div>
    
    <?php if (isset($node->field_rating[LANGUAGE_NONE])) : ?>
    <p itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"><meta itemprop="worstRating" content ="1">Officiel vurdering:
      <span class="rating grade<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>"><span itemprop="ratingValue"><?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?></span>/<span itemprop="bestRating">10</span></span>
    </p>
    <?php endif; ?>
    <p class="userRatingTxt">Brugervurdering:
    <span class="userRatingStats"><?php if ($node->user_ratings) : ?>
      <?php print $node->user_ratings['text']; ?>
    <?php else: ?>
      Vær den første til at stemme.
    <?php endif; ?></span>
    </p>
    <div class="userrating" data-score="<?php if ($node->user_ratings) : ?><?php print $node->user_ratings['avg']; ?><?php else: ?>0<?php endif; ?>" data-itemid="<?php print $node->nid; ?>"></div>
    <p class="userRatingMsg"></p>
  </div>
</div>

<div itemprop="reviewBody">
<?php if (isset($node->body[LANGUAGE_NONE][0]) && strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
  <p class="summary"><?php print $node->body[LANGUAGE_NONE][0]['summary']; ?></p>
<?php endif; ?>

<?php if (isset($node->body[LANGUAGE_NONE][0])) : ?>
  <?php print $node->body[LANGUAGE_NONE][0]['value']; ?>
<?php endif; ?>
</div>

</article>

<?php if (isset($node->field_embed_code[LANGUAGE_NONE])) : ?>
<div class="video-container">
  <iframe width="640" height="360" src="//www.youtube.com/embed/<?php print check_plain($node->field_embed_code[LANGUAGE_NONE][0]['value']); ?>?rel=0" frameborder="0" allowfullscreen></iframe>
</div>
<?php endif; ?>

<?php if (count($node->tracklist) > 0) : ?>
<h3 id="tracklist"><?php print t('Tracklist'); ?></h3>
<ol>
  <?php foreach ($node->tracklist as $track) : ?>
    <li><?php print $track; ?></li>
  <?php endforeach; ?>
</ol>
<?php endif; ?>

<?php
$block = module_invoke('related', 'block_view', 'block-related-artist');
print render($block['content']);
?>

<?php print render($content['comments']); ?>
