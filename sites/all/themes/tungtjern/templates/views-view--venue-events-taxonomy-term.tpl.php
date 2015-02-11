<?php
$term = taxonomy_term_load($view->args[0]);
$country = (isset($term->field_address[LANGUAGE_NONE][0]['country'])) ? country_load($term->field_address[LANGUAGE_NONE][0]['country']) : FALSE;
?>
<article class="venue">
<div itemscope itemtype="http://schema.org/Organization">
  <h1 itemprop="name"><?php print $term->name; ?></h1>
  <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
    <address>
      <span itemprop="streetAddress"><?php print $term->field_address[LANGUAGE_NONE][0]['thoroughfare']; ?></span><br />
      <span itemprop="postalCode"><?php print $term->field_address[LANGUAGE_NONE][0]['postal_code']; ?></span> <span itemprop="addressLocality"><?php print $term->field_address[LANGUAGE_NONE][0]['locality']; ?></span><br />
      <span itemprop="addressCountry"><?php print $country->name; ?></span>
    </address>
    <?php if (isset($term->field_website_link[LANGUAGE_NONE][0]['url'])) : ?>
    <p><?php print t('Homepage'); ?>: <?php print l($term->field_website_link[LANGUAGE_NONE][0]['url'], $term->field_website_link[LANGUAGE_NONE][0]['url'], array('attributes' => array('target' => '_blank', 'itemprop' => 'url'))); ?></p>
    <?php endif; ?>
  </div>
</div>

<?php $events = _venue_get_events($term->tid); ?>

<?php if (count($events[0]) > 0) : ?>
<h2>Kommende arrangementer</h2>
<div><table class="pure-table pure-table-horizontal pure-table-striped">
  <caption class="info_text"></caption>
  <thead>
    <tr>
      <th id="event-date">Dato</th>
      <th id="event-name">Koncert</th>
    </tr>
  </thead>
  <tbody class="content">
<?php foreach ($events[0] as $n) : ?>
  <?php $view = node_view($n, 'teaser'); ?>
  <?php print drupal_render($view); ?>
<?php endforeach; ?>
  </tbody>
</table></div>
<?php endif; ?>

<?php if (count($events[2]) > 0) : ?>
<h2>Anmeldte arrangementer</h2>
<div><table class="pure-table pure-table-horizontal pure-table-striped">
  <caption class="info_text"></caption>
  <thead>
    <tr>
      <th id="event-date">Dato</th>
      <th id="event-name">Koncert</th>
      <th id="event-review">Anmeldelse</th>
    </tr>
  </thead>
  <tbody class="content">
<?php foreach ($events[2] as $n) : ?>
  <?php $view = node_view($n, 'teaser'); ?>
  <?php print drupal_render($view); ?>
<?php endforeach; ?>
  </tbody>
</table></div>
<?php endif; ?>

<?php if (count($events[1]) > 0) : ?>
<h2>Tidligere arrangementer</h2>
<div><table class="pure-table pure-table-horizontal pure-table-striped">
  <caption class="info_text"></caption>
  <thead>
    <tr>
      <th id="event-date">Dato</th>
      <th id="event-name">Koncert</th>
    </tr>
  </thead>
  <tbody class="content">
<?php foreach ($events[1] as $n) : ?>
  <?php $view = node_view($n, 'teaser'); ?>
  <?php print drupal_render($view); ?>
<?php endforeach; ?>
  </tbody>
</table></div>
<?php endif; ?>

<!-- <h2>Arrangementer</h2>
<?php if ($rows): ?>
<div><table class="pure-table pure-table-horizontal pure-table-striped">
  <caption class="info_text"></caption>
  <thead>
    <tr>
      <th id="event-date">Dato</th>
      <th id="event-name">Koncert</th>
      <th id="event-review">Anmeldelse</th>
    </tr>
  </thead>
  <tbody class="content">
  <?php print $rows; ?>
  </tbody>
</table><br /></div>

<?php elseif ($empty): ?>
  <?php print $empty; ?>
<?php endif; ?>
<?php if ($pager): ?>
  <?php print $pager; ?>
<?php endif; ?> -->
</article>
