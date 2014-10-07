<article>
<h1><?php print t('Search results'); ?></h1>

<?php if (is_array($search)): ?>
  <?php print drupal_render($search['form']); ?>
  <?php if (isset($search['info']['items']) && count($search['info']['items']) > 0): ?>
    <p><?php print t('Your search on %search matched !results results. Showing !x-!y of !z.', array(
      '%search' => check_plain($search['info']['queries']['request'][0]['searchTerms']),
      '!results' => $search['info']['searchInformation']['totalResults'],
      '!x' => $search['info']['queries']['request'][0]['startIndex'],
      '!y' => $search['end_index'],
      '!z' => $search['info']['searchInformation']['totalResults'])); ?></p>
    
    <?php foreach ($search['info']['items'] as $item): ?>
    <section class="pure-g search-item row teaser">
      <?php if (isset($item['pagemap']['cse_thumbnail'][0]['src'])) : ?>
	<div class="pure-u-1-3 pure-u-md-1-6 pure-u-lg-1-5">
	  <a href="<?php print $item['link']; ?>"><?php print theme('imagecache_external', array('path' => $item['pagemap']['cse_thumbnail'][0]['src'], 'style_name'=> 'teaser_thumbnail', 'alt' => $item['title'], 'attributes' => array('class' => 'pure-img'))); ?></a>
	</div>
	<div class="pure-u-2-3 pure-u-md-5-6 pure-u-lg-4-5 meta">
      <?php else: ?>
      <div class="pure-u-1">
      <?php endif; ?>
	<h1><a href="<?php print $item['link']; ?>"><?php  print $item['htmlTitle']; ?></a></h1>
	<p class="url"><a href="<?php print $item['link']; ?>"><?php  print $item['htmlFormattedUrl']; ?></a></p>
	<p><?php  print $item['htmlSnippet']; ?></p>
      </div>
    </section>
    <?php endforeach; ?>
    <?php print $search['pager']; ?>
  <?php else: ?>
    <p><?php print t('Your search yielded no results'); ?></p>
  <?php endif; ?>
<?php else: ?>
  <p><?php print $search; ?></p>
<?php endif; ?>
</article>