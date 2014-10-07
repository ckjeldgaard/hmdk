<?php if (count($items) > 0) : ?>
<h2>Foto stream</h2>
<div id="instagram-slide">
  <ul class="bjqs">
    <?php foreach ($items as $item) : ?>
    <li><a href="<?php print $item->link; ?>" target="_blank"><img src="<?php print $item->image_url; ?>" title="<?php print truncate_utf8($item->caption, 150, TRUE, TRUE); ?>" alt=""></a></li>
    <?php endforeach; ?>
  </ul>
</div>
<p class="instagram_desc">Hashtag dit eget billede på <a href="http://instagram.com/" target="_blank">Instagram</a> med <span class="tags"><?php print $tags; ?></span> for at få det vist i vores foto-stream.</p>
<?php endif; ?>