<?php $term = taxonomy_term_load($view->args[0]); $country = (isset($term->field_country[LANGUAGE_NONE][0]['iso2'])) ? country_load($term->field_country[LANGUAGE_NONE][0]['iso2']) : FALSE; ?>
<?php if ($country) : ?>
<div class="pure-g">
  <div class="pure-u-1-4 pure-u-md-1-6 pure-u-lg-1-8">
    <img src="/sites/all/themes/tungtjern/img/flags/<?php print $country->iso2; ?>.png" alt="<?php print $country->name; ?>" title="<?php print $country->name; ?>" class="pure-img" />
  </div>
  <div class="pure-u-3-4 pure-u-md-5-6 pure-u-lg-7-8 band-name">
<?php endif; ?>
    <h1><?php print $term->name; ?></h1>
<?php if ($country) : ?>
  </div>
</div>
<?php endif; ?>

<?php if (isset($term->field_website_link[LANGUAGE_NONE][0]['url'])) : ?>
<p><?php print t('Homepage'); ?>: <?php print l($term->field_website_link[LANGUAGE_NONE][0]['url'], $term->field_website_link[LANGUAGE_NONE][0]['url'], array('attributes' => array('target' => '_blank'))); ?></p>
<?php endif; ?>

<p><? print format_plural($view->total_rows, 'There are 1 review from this label.', 'There are @count reviews from this label.'); ?></p>

<?php if ($rows): ?>
  <?php print $rows; ?>
<?php elseif ($empty): ?>
  <?php print $empty; ?>
<?php endif; ?>

<?php if ($pager): ?>
  <?php print $pager; ?>
<?php endif; ?>