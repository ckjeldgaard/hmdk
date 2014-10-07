<?php $term = taxonomy_term_load($view->args[0]); ?>

<div class="pure-g byline">
  <div class="pure-u-1-2 pure-u-md-4-5">
    <p class="post-meta">
      <?php print _get_type('genre'); ?>
    </p>
  </div>
  <div class="pure-u-1-2 pure-u-md-1-5 fb-actions">
    <div class="fb-like" data-href="<?php print url('taxonomy/term/' . $term->tid, array('absolute' => TRUE)); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
  </div>
</div>

<h1><?php print $term->name; ?></h1>
<?php print $term->description; ?>

<h2 id="anmeldelser"><?php print $term->name; ?>-anmeldelser (<?php print $view->total_rows; ?>)</h2>
<?php if ($rows): ?>
  <?php print $rows; ?>
<?php elseif ($empty): ?>
  <?php print $empty; ?>
<?php endif; ?>
<?php if ($pager): ?>
  <?php print $pager; ?>
<?php endif; ?>