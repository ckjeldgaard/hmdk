<article>
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

<?php if ($node->field_enddate[LANGUAGE_NONE][0]['value'] > time()) : ?>
  <p>Konkurrencen slutter <strong><?php print formatted_date($node->field_enddate[LANGUAGE_NONE][0]['value'], TRUE); ?></strong>.</p>
  
  <h2>Svar på spørgsmålene og deltag i konkurrencen</h2>
  <?php if ($node->warning) : ?>
  <div class="pure-alert pure-alert-warning"><?php print $node->warning; ?></div>
  <?php endif; ?>
  
  <?php print render($node->contest_form); ?>
<?php else: ?>
  <div class="pure-alert pure-alert-warning"><?php print t('This contest has expired.'); ?></div>
<?php endif; ?>

</article>