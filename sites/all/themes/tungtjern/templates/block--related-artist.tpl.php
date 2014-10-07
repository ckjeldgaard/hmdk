<?php if (is_array($content)) : ?>
  <div class="related-artist">
    <h3><?php print t('Read also'); ?></h3>
    <p class="related-artist-description"><?php print t("If you're interested in %artist, you might also like %related_artist. Check out our review of %review:", array('%artist' => $content['artist'], '%related_artist' => $content['related_artist'], '%review' => $content['review_node']->title)); ?></p>
    <?php print render(node_view($content['review_node'], 'teaser')); ?>
  </div>
<?php endif; ?>