<header>
  <div class="wrap pure-g">
    <div class="siteTitle pure-u-1 pure-u-md-1-2">
      <?php print $content['header_left']; ?>
    </div>
    <div class="secondary pure-u-1 pure-u-md-1-2">
      <div class="social">
        <ul>
        <li><a href="https://www.facebook.com/heavymetal.dk" title="Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
        <li><a href="https://twitter.com/HEAVYMETALDK/" title="Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
        <li><a href="http://instagram.com/heavymetaldk" title="Instagram" target="_blank"><i class="fa fa-instagram"></i></a></li>
        <li><a href="https://www.youtube.com/channel/UCh9VkIMFsFaLlAElSu4CCig" title="YouTube" target="_blank"><i class="fa fa-youtube"></i></a></li>
        <li><a href="/rss" title="RSS feed"><i class="fa fa-rss"></i></a></li>
        </ul>
      </div>
      <?php print $content['header_right']; ?>
    </div>
  </div>
</header>

<div class="navWrapper">
  <div class="wrap pure-g">
    <div class="pure-u-1 pure-u-lg-20-24">
      <nav id="nav" role="navigation">
        <a href="#nav" title="<?php print t('Show navigation'); ?>"><?php print t('Show navigation'); ?><span></span></a>
        <a href="#" title="<?php print t('Hide navigation'); ?>"><?php print t('Hide navigation'); ?><span></span></a>
        <?php print $content['navigation']; ?>
      </nav>
    </div>
    <div class="search pure-u-1 pure-u-lg-4-24">
      <form class="pure-form" action="/search" method="get" id="searchform">
        <input type="text" name="s" class="query pure-input-rounded" placeholder="<?php print t('Search'); ?>" value="<?php print (isset($_GET['s'])) ? check_plain($_GET['s']) : ''; ?>">
      </form>
    </div>
  </div>
</div>

<div class="mainContent">
  <div class="wrap pure-g">
    <div class="contentInner pure-u-1 pure-u-md-17-24">
      <?php print $content['left']; ?>
    </div>
    <aside class="sidebar pure-u-1 pure-u-md-7-24">
      <?php print $content['right']; ?>
    </aside>
  </div>
</div>
<footer>
  <div class="wrap">
    <div class="pure-g">
      <div class="pure-u-1 pure-u-md-1-3 pure-u-lg-1-3">
        <div class="l-box">
          <?php print $content['footer_left']; ?>
        </div>
      </div>
      <div class="pure-u-1 pure-u-md-1-3 pure-u-lg-1-3">
        <div class="l-box">
          <?php print $content['footer_center']; ?>
        </div>
      </div>
      <div class="pure-u-1 pure-u-md-1-3 pure-u-lg-1-3">
        <div class="l-box">
          <?php print $content['footer_right']; ?>
        </div>
      </div>
    </div>
    <?php print $content['footer_bottom']; ?>
  </div>
</footer>
