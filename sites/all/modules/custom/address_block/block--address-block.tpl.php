<div class="postal-address">
  <h2><?php print t('Postal address'); ?></h2>
  <?php if (strlen($content->description) > 0): ?>
  <p><?php print $content->description; ?></p>
  <?php endif; ?>
  <address itemscope itemtype="http://schema.org/Organization">
    <strong itemprop="name"><?php print $content->organization_name; ?></strong><br />
    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
      <?php if (strlen($content->name) > 0) : ?><span itemprop="name"><?php print $content->name; ?></span><br /><?php endif; ?>
      <span itemprop="streetAddress"><?php print $content->street_address; ?></span><br />
      <span itemprop="postalCode"><?php print $content->postal_code; ?></span> <span itemprop="addressLocality"><?php print $content->address_locality; ?></span><br />
      <span itemprop="addressCountry"><?php print $content->country; ?></span><br />
      <span class="address-email"><?php print t('Email'); ?>: <?php print hide_email($content->email_address); ?></span>
    </div>
  </address>
</div>
