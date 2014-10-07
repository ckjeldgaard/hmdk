<section class="sidebarItem">
  <h2><?php print t('Log in'); ?></h2>

  <label for="edit-name"><?php print t('Username'); ?> <span class="form-required" title="<?php print t('This field is required.'); ?>">*</span></label>
  <input type="text" id="edit-name" name="name" value="" size="25" maxlength="60" class="form-text required" required="required" />

  <label for="edit-pass"><?php print t('Password'); ?> <span class="form-required" title="<?php print t('This field is required.'); ?>">*</span></label>
  <input type="password" id="edit-pass" name="pass" size="25" maxlength="128" class="form-text required" required="required" />
  
  <?php print render($form['form_id']); ?>
  <?php print render($form['form_build_id']); ?>
  
  <div class="form-actions form-wrapper" id="edit-actions">
    <button type="submit" id="edit-submit" name="op" value="<?php print t('Log in'); ?>" class="form-submit pure-button pure-button-primary"><?php print t('Log in'); ?></button>
  </div>
  
  <ul>
    <li class="first"><a href="/user/register" title="<?php print t('Create a new user account.'); ?>"><?php print t('Create new account'); ?></a></li>
    <li class="last"><a href="/user/password" title="<?php print t('Request new password via e-mail.'); ?>"><?php print t('Request new password'); ?></a></li>
  </ul>
</section>