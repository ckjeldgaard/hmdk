<?php if (is_array($concerts) && count($concerts) > 0) : ?>
<section class="sidebarItem next-concerts">
  <h2><?php print t('Next concerts'); ?></h2>
  <div id="next-concerts">
    <ul class="bjqs next-concerts-slide">
    <?php foreach ($concerts as $c) : ?>
      <li itemprop="event" itemscope itemtype="http://schema.org/Event">
	<h3 class="concert-name" itemprop="name"><?php print $c->artist_string; ?></h3>
	<p class="concert-date">
	  <time datetime="<?php print format_date($c->field_event_date[LANGUAGE_NONE][0]['value'], 'date'); ?>" itemprop="startDate" content="<?php print date ('Y-m-d', $c->field_event_date[LANGUAGE_NONE][0]['value']); ?>"><?php print format_date($c->field_event_date[LANGUAGE_NONE][0]['value'], 'displaydate'); ?></time>
	  <?php if ($c->field_event_date[LANGUAGE_NONE][0]['value'] != $c->field_event_date[LANGUAGE_NONE][0]['value2']) : ?>
	  - <time datetime="<?php print format_date($c->field_event_date[LANGUAGE_NONE][0]['value2'], 'date'); ?>" itemprop="endDate" content="<?php print date ('Y-m-d', $c->field_event_date[LANGUAGE_NONE][0]['value2']); ?>"><?php print format_date($c->field_event_date[LANGUAGE_NONE][0]['value2'], 'displaydate'); ?></time>
	  <?php endif; ?>
	</p>
	<p class="concert-location" itemprop="location" itemscope itemtype="http://schema.org/Place">
	  <a href="<?php print url('taxonomy/term/' . $c->field_venue[LANGUAGE_NONE][0]['tid']); ?>" itemprop="url"><?php print _get_venue_name($c->field_venue[LANGUAGE_NONE][0]['tid']); ?></a>
	</p>
      </li>
    <?php endforeach; ?>
    </ul>
  </div>
  <p class="action"><a class="pure-button pure-button-primary" href="<?php print url('koncertkalender'); ?>"><?php print t('View full concert calendar'); ?></a></p>
</section>
<?php endif; ?>