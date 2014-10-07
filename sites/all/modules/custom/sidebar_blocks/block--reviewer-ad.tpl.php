<section class="sidebarItem seeking-reviewer">
  <h2><?php print variable_get('sidebar_blocks_reviewer_headline'); ?></h2>
  <?php print $content; ?>
  <?php print l(variable_get('sidebar_blocks_reviewer_button_text'),
		variable_get('sidebar_blocks_reviewer_button_destination'),
		array(
		  'attributes' => array(
		    'class' => array(
		      'pure-button',
		      'pure-button-primary',
		    )
		  )
		)
	      ); ?>
</section>