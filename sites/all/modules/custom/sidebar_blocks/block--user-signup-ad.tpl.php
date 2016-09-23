<?php if ($content['display']) : ?>
<section class="sidebarItem user-signup">
  <h2><?php print variable_get('sidebar_blocks_user_signup_headline'); ?></h2>
  <?php print $content['content']; ?>
  <?php print l(variable_get('sidebar_blocks_user_signup_button_text'),
		variable_get('sidebar_blocks_user_signup_button_destination'),
		array(
      'absolute' => TRUE,
		  'attributes' => array(
		    'class' => array(
		      'pure-button',
		      'pure-button-primary',
		    )
		  )
		)
	      ); ?>
</section>
<?php endif; ?>