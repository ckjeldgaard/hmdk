<h2><?php print t('Newsletter'); ?></h2>
<p><?php print t('Subscribe to our newsletter.'); ?></p>
<form class="pure-form" action="<?php print $form['#action']; ?>" method="<?php print $form['#method']; ?>" id="<?php print $form['#id']; ?>" accept-charset="UTF-8">
  <input placeholder="<?php print t('E-mail'); ?>" type="email" id="<?php print $form['email']['#id']; ?>" name="<?php print $form['email']['#name']; ?>" value="<?php print $form['email']['#value']; ?>" maxlength="<?php print $form['email']['#maxlength']; ?>" class="form-text required" required="required" />
  <?php print drupal_render($form['subscribe_button']); ?>
  <?php print drupal_render($form['form_build_id']); ?>
  <?php print drupal_render($form['form_token']); ?>
  <?php print drupal_render($form['form_id']); ?>
</form>