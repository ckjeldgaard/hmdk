<p>Udgivelser som ikke har nogen tilknyttet anmeldelse (<?php print $content['count']; ?> stk. i alt).</p>
<?php print $content['table']; ?>
<?php if (user_access('delete unreviewed releases') && $content['count'] > 0) : ?>
<div class="delete-all"><br />
  <?php print render($content['form']); ?>
</div>
<?php endif; ?>