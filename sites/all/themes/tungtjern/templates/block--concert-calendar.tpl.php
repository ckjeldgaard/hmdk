<h1>Koncertkalender</h1>
<form method="get" action="" id="province-form">
  <label for="landsdel">Filtrér efter landsdel:</label>
  <select name="landsdel" id="landsdel">
    <option value="all">Vis alle</option>
    <?php foreach($provinces as $key => $province) : ?>
    <option value="<?php print $key; ?>"<?php if ($province['selected']) : ?> selected="selected"<?php endif; ?>><?php print $province['name']; ?></option>
    <?php endforeach; ?>
  </select>
</form>
<?php if (count($concerts) > 0) : ?>
<?php foreach ($concerts as $month => $events) : ?>
  <h2><?php print $month; ?></h2>
  <table class="event-table pure-table pure-table-horizontal pure-table-striped">
  <?php foreach ($events as $concert) : ?>
    <tr<?php if ($concert['cancelled']): ?> class="cancelled"<?php endif; ?> itemprop="event" itemscope itemtype="http://schema.org/Event">
      <td class="event-date" scope="row" data-label="Dato">
        <?php if ($concert['cancelled']): ?><meta itemprop="eventStatus" content="http://schema.org/EventCancelled"><?php endif; ?>
        <?php print $concert['startdate']; ?><?php if ($concert['enddate']) : ?> - <?php print $concert['enddate']; ?><?php endif; ?>
      </td>
      <td class="event-name" data-label="Koncert">
        <span itemprop="name" class="artists"><?php print (strlen($concert['artists']) > 0) ? $concert['artists'] : $concert['node']->title; ?></span>
        <?php if ($concert['cancelled']): ?> <span class="cancelled-concert"><?php print t('Cancelled'); ?></span><?php endif; ?>
        <?php if ($concert['new']): ?> <span class="new-concert"><?php print t('New'); ?></span><?php endif; ?>
      </td>
      <td class="event-venue" data-label="Spillested" itemprop="location" itemscope itemtype="http://schema.org/Place">
        <div class="<?php print get_edit_classes($concert['node']); ?>"></div>
        <a href="<?php print url('taxonomy/term/' . $concert['venue_tid']); ?>" itemprop="url"><?php print $concert['venue']; ?></a></td>
      <td class="event-meta"<?php if ($concert['more']): ?> data-label="<?php print t('Info'); ?>"<?php endif; ?>>
        <?php if ($concert['more']): ?>
        <a href="<?php print url('node/' . $concert['node']->nid); ?>" title="<?php print t('More information'); ?>"><i class="fa fa-plus-circle"></i></a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </table>
<?php endforeach; ?>
<?php else : ?>
  <p><em><?php print t("Unfortunately, we couldn't find any concerts."); ?></em></p>
<?php endif; ?>

<div class="add-concert-box">
  <p>Har du kendskab til en metalkoncert som ikke står på listen, så klik på knappen nedenfor for at tilføje den!</p>
  <p><a href="/tilfoej-koncert" class="pure-button pure-button-primary">Opret ny koncert</a></p>
</div>