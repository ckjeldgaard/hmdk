<?php if (user_access('reorder frontpage')) : ?>
<div class="tabs">
  <ul class="tabs primary">
    <li><a href="/admin/content/frontpage-mosaic-order"><?php print t('Edit frontpage'); ?></a></li>
  </ul>
</div>
<?php endif; ?>
<article class="frontpage-mosaic">

<?php if ($rows): ?>
  <?php print $rows; ?>
<?php elseif ($empty): ?>
  <?php print $empty; ?>
<?php endif; ?>
  
</article>