<tr<?php if ($node->field_cancelled[LANGUAGE_NONE][0]['value']): ?> class="cancelled"<?php endif; ?>>
  <td headers="event-date" scope="row" data-label="Dato" class="event-date">
    <?php if (isset($node->field_event_date[LANGUAGE_NONE])): ?>
      <time datetime="<?php print date('Y-m-d', $node->field_event_date[LANGUAGE_NONE][0]['value']); ?>"><?php print date('d-m-Y', $node->field_event_date[LANGUAGE_NONE][0]['value']); ?></time>
      <?php if($node->field_event_date[LANGUAGE_NONE][0]['value'] != $node->field_event_date[LANGUAGE_NONE][0]['value2']) : ?>
      - <time datetime="<?php print date('Y-m-d', $node->field_event_date[LANGUAGE_NONE][0]['value2']); ?>"><?php print date('d-m-Y', $node->field_event_date[LANGUAGE_NONE][0]['value2']); ?></time>
      <?php endif; ?>
    <?php endif; ?>
  </td>
  <td headers="event-name" data-label="Koncert" class="event-name">
    <span class="artists"><?php if (isset($node->field_is_festival[LANGUAGE_NONE]) && $node->field_is_festival[LANGUAGE_NONE][0]['value'] == 1) : ?>
      <strong><?php print l($node->field_festival_name[LANGUAGE_NONE][0]['value'], 'node/' . $node->nid); ?>:</strong>
    <?php endif; ?>
    <?php print (strlen($node->artists) > 0) ? $node->artists : $node->title; ?></span>
    <?php if ($node->field_cancelled[LANGUAGE_NONE][0]['value']): ?><span class="cancelled-concert"><?php print t('Cancelled'); ?></span><?php endif; ?>
  </td>
  <?php if ($node->review): ?>
  <td headers="event-review" data-label="Anmeldelse"><div class="<?php print get_edit_classes($node); ?>"></div><?php if ($node->review): print l(t('Read review'), 'node/' . $node->review); endif; ?></td>
  <?php elseif ($node->reportage): ?>
  <td headers="event-review" data-label="Anmeldelse"><?php if ($node->reportage): print l(t('Read reportage'), 'node/' . $node->reportage); endif; ?></td>
  <?php endif; ?>
</tr>
