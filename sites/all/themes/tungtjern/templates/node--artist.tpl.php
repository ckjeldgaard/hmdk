<?php if (isset($node->field_logo[LANGUAGE_NONE][0]['fid'])) : ?>
  <p class="band-logo"><img src="<?php print image_cache('bandlogo', $node->field_logo[LANGUAGE_NONE][0]); ?>" alt="<?php print $node->title; ?>" /></p>
<?php endif; ?>

<?php if (isset($node->field_country[LANGUAGE_NONE][0]['value'])) : ?>
<div class="pure-g">
  <div class="pure-u-1-4 pure-u-md-1-6 pure-u-lg-1-8">
    <img src="/sites/all/themes/tungtjern/img/flags/<?php print $node->field_country[LANGUAGE_NONE][0]['value']; ?>.png" alt="<?php print $node->field_country[LANGUAGE_NONE][0]['safe_value']; ?>" title="<?php print $node->field_country[LANGUAGE_NONE][0]['safe_value']; ?>" class="pure-img" />
  </div>
  <div class="pure-u-3-4 pure-u-md-5-6 pure-u-lg-7-8 band-name">
<?php endif; ?>
    <h1><?php print $node->title; ?></h1>
<?php if (isset($node->field_country[LANGUAGE_NONE][0]['value'])) : ?>
  </div>
</div>
<?php endif; ?>

<?php if (isset($node->field_website_link[LANGUAGE_NONE][0]['url']) || $node->genres) : ?>
  <ul>
  <?php if (isset($node->field_website_link[LANGUAGE_NONE][0]['url'])) : ?>
    <li><?php print t('Homepage'); ?>: <?php print l($node->field_website_link[LANGUAGE_NONE][0]['display_url'], $node->field_website_link[LANGUAGE_NONE][0]['url'], array('attributes' => array('target' => '_blank'))); ?></li>
  <?php endif; ?>
  <?php if ($node->genres) : ?>
    <li><?php print $node->genres; ?></li>
  <?php endif; ?>
  <?php if ($node->related) : ?>
    <li><?php print $node->related; ?></li>
  <?php endif; ?>
  </ul>
<?php endif; ?>

<?php if (isset($node->field_description[LANGUAGE_NONE][0]['safe_value'])) : ?>
  <?php print $node->field_description[LANGUAGE_NONE][0]['safe_value']; ?>
<?php endif; ?>

<?php if (count($node->releases) > 0) : ?>
<h2>Udgivelser</h2>
  <?php foreach ($node->releases as $release) : ?>
  <?php if ($release['review'] && $release['review']->status == 1) : ?>
    <?php $view = node_view($release['review'], 'teaser'); print drupal_render($view); ?>
  <?php elseif ($release['release']->status == 1): ?>
    <section class="pure-g review row teaser<?php print get_edit_classes($release['release']); ?>">
    <?php if (isset($release['release']->field_image[LANGUAGE_NONE][0])): ?>
    <div class="pure-u-1-3 pure-u-md-1-6 pure-u-lg-1-5">
      <div class="img-wrapper">
        <img src="<?php print (function_exists('image_cache')) ? image_cache('review_thumbnail', $release['release']->field_image[LANGUAGE_NONE][0]) : ''; ?>" alt="<?php print $release['release']->title; ?>" class="pure-img" />
      </div>
    </div>
    <div class="pure-u-2-3 pure-u-md-5-6 pure-u-lg-4-5 meta">
    <?php else: ?>
    <div class="pure-u-1">
    <?php endif; ?>
      <p class="post-meta">
        <span class="type">Udgivelse</span>
        <?php if ($release['release']->field_release_date[LANGUAGE_NONE][0]['value'] < time()) : ?>
          <?php print t('Released'); ?>
        <?php else: ?>
          <?php print t('Is released'); ?>
        <?php endif; ?>
        <?php print formatted_date($release['release']->field_release_date[LANGUAGE_NONE][0]['value']); ?>
      </p>
      <h1 class="unreviewed"><?php print $release['release']->title; ?></h1>
    </div>
    </section>
  <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>

<?php if (count($node->news) > 0) : ?>
<h2>Relaterede nyheder</h2>
<div class="news_page_container">
<table class="pure-table pure-table-horizontal pure-table-striped">
  <caption class="info_text"></caption>
  <thead>
    <tr>
      <th>Dato</th>
      <th>Titel</th>
    </tr>
  </thead>
  <tbody class="content">
  <?php foreach ($node->news as $news) : ?>
    <tr>
      <td data-label="Dato"><time datetime="<?php print date('Y-m-d', $news->created); ?>"><?php print date('d-m-Y', $news->created); ?></time></td>
      <td data-label="Titel"><?php print l($news->title, 'node/' . $news->nid); ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<div class="page_navigation"></div>
</div>
<?php endif; ?>

<?php if (count($node->concerts) > 0) : ?>
<h2>Koncerter og festivaler</h2>
<div class="page_container">
<table class="event-table pure-table pure-table-horizontal pure-table-striped">
  <caption class="info_text"></caption>
  <thead>
    <tr>
      <th>Dato</th>
      <th>Koncert</th>
      <th>Spillested</th>
      <th>Anmeldelse</th>
    </tr>
  </thead>
  <tbody class="content">
  <?php foreach ($node->concerts as $c) : ?>
    <tr<?php if ($c['cancelled']): ?> class="cancelled"<?php endif; ?> itemprop="event" itemscope itemtype="http://schema.org/Event">
      <td scope="row" data-label="Dato" class="event-date">
        <time datetime="<?php print date('Y-m-d', $c['date']); ?>" itemprop="startDate"><?php print date('d-m-Y', $c['date']); ?></time>
        <?php if ($c['date'] != $c['endDate']) : ?>
        - <time datetime="<?php print date('Y-m-d', $c['endDate']); ?>" itemprop="endDate"><?php print date('d-m-Y', $c['endDate']); ?></time>
        <?php endif ; ?>
      </td>
      <td itemprop="name" data-label="Koncert" class="event-name">
        <span class="artists"><?php print $c['artists']; ?></span>
        <?php if ($c['cancelled']): ?> <span class="cancelled-concert"><?php print t('Cancelled'); ?></span><?php endif; ?>
      </td>
      <?php if (isset($c['venue'])) : ?>
      <td itemprop="location" data-label="Sted" itemscope itemtype="http://schema.org/Place" class="event-venue">
        <?php if (isset($c['node']->field_venue[LANGUAGE_NONE])): ?>
        <a href="<?php print url('taxonomy/term/' . $c['node']->field_venue[LANGUAGE_NONE][0]['tid']); ?>" itemprop="url"><?php print $c['venue']; ?></a>
        <?php elseif (isset($c['node']->field_venue_text[LANGUAGE_NONE])): ?>
          <?php print $c['node']->field_venue_text[LANGUAGE_NONE][0]['value']; ?>
        <?php endif; ?>
      </td>
      <?php else: ?>
      <td data-label="Sted"></td>
      <?php endif; ?>
      <td data-label=""><div class="<?php print get_edit_classes($c['node']); ?>"></div><?php if ($c['review']): print l('Læs anmeldelse', 'node/' . $c['review']); endif; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<div class="page_navigation"></div>
</div>
<?php endif; ?>

<?php if (count($node->reports) > 0): ?>
<h2>Reportager</h2>
<ul>
  <?php foreach ($node->reports as $r) : ?>
  <li><?php print l($r->title, 'node/' . $r->nid); ?> <em>(<time datetime="<?php print format_date($r->created, 'date'); ?>"><?php print format_date($r->created, 'displaydate'); ?></time>)</em></li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (count($node->interviews) > 0) : ?>
<h2>Interviews</h2>
<?php foreach ($node->interviews as $node) : ?>
  <?php $node_view = node_view($node, 'teaser'); print render($node_view); ?>
<?php endforeach; ?>
<?php endif; ?>

<?php if (count($node->blog_posts) > 0) : ?>
<h2>Blog-indlæg</h2>
<?php foreach ($node->blog_posts as $node) : ?>
  <?php $node_view = node_view($node, 'teaser'); print render($node_view); ?>
<?php endforeach; ?>
<?php endif; ?>
