<article itemprop="event" itemscope itemtype="http://schema.org/Event">
<h1 itemprop="name"><?php print $node->title; ?></h1>
<?php if ($node->field_cancelled[LANGUAGE_NONE][0]['value'] == 1) : ?>
  <p itemprop="eventStatus" content="http://schema.org/EventCancelled" class="cancelled-concert"><?php print t('Cancelled'); ?></p>
<?php endif; ?>
<p>
  <time datetime="<?php print format_date($node->field_event_date[LANGUAGE_NONE][0]['value'], 'date'); ?>" itemprop="startDate" content="<?php print date ('Y-m-d', $node->field_event_date[LANGUAGE_NONE][0]['value']); ?>"><?php print format_date($node->field_event_date[LANGUAGE_NONE][0]['value'], 'displaydate'); ?></time>
  <?php if ($node->field_event_date[LANGUAGE_NONE][0]['value'] != $node->field_event_date[LANGUAGE_NONE][0]['value2']) : ?>
  - <time datetime="<?php print format_date($node->field_event_date[LANGUAGE_NONE][0]['value2'], 'date'); ?>" itemprop="endDate" content="<?php print date ('Y-m-d', $node->field_event_date[LANGUAGE_NONE][0]['value2']); ?>"><?php print format_date($node->field_event_date[LANGUAGE_NONE][0]['value2'], 'displaydate'); ?></time>
  <?php endif; ?>
</p>
<?php if (isset($node->field_venue[LANGUAGE_NONE])) : ?>
  <p>Spillested:
    <span itemprop="location" itemscope itemtype="http://schema.org/Place">
    <a href="<?php print url('taxonomy/term/' . $node->field_venue[LANGUAGE_NONE][0]['tid']); ?>" itemprop="url"><?php print $node->field_venue[LANGUAGE_NONE][0]['taxonomy_term']->name; ?></a>
    </span>
  </p>
<?php endif; ?>
<h2>Line-up</h2>
<ul>
<?php foreach ($node->field_artists[LANGUAGE_NONE] as $artist) : ?>
  <li><?php print l($artist['entity']->title, 'node/' . $artist['target_id']); ?></li>
<?php endforeach; ?>
</ul>
<?php if (isset($node->field_support_artists[LANGUAGE_NONE])) : ?>
<h2>Opvarmningsbands</h2>
<ul>
<?php foreach ($node->field_support_artists[LANGUAGE_NONE] as $artist) : ?>
  <li><?php print l($artist['entity']->title, 'node/' . $artist['target_id']); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</article>