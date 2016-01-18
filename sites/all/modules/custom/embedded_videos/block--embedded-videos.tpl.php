<?php if (count($items) > 0) : ?>
<h2><?php print t('Video stream'); ?></h2>
<div id="video-slide">
    
  <ul class="bjqs">
    <?php foreach ($items as $item) : ?>
    <li>
        <div class="video-container">
          <iframe width="306" height="172" src="//www.youtube.com/embed/<?php print check_plain($item->video); ?>?rel=0" frameborder="0" allowfullscreen></iframe>
        </div>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>