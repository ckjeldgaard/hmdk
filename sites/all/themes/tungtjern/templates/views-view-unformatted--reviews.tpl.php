<?php $c = 0; ?>
<?php foreach ($rows as $id => $row): ?>
  <?php if ($c == 0) : ?>
    <div class="pure-g focus">
  <?php endif; ?>
  <?php if ($c == 0 || $c == 1 || $c == 2) : ?>
    <div class="pure-u-1-1 pure-u-sm-1-3 l-box<?php if ($c == 0) : ?> first<?php endif; ?><?php if ($c == 2) : ?> last<?php endif; ?>">
  <?php endif; ?>
  <?php print $row; ?>
  <?php if ($c == 0 || $c == 1 || $c == 2) : ?>
    </div>
  <?php endif; ?>
  <?php if ($c == 2) : ?>
    </div>
  <?php endif; ?>
  <?php if ($c == 2) { $c = 0; } else { $c++; } ?>
<?php endforeach; ?>
