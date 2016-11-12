<h2><?php print t('Reviews'); ?></h2>
  <?php if ($rows): ?>
    <div class="view-content focus-list">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>