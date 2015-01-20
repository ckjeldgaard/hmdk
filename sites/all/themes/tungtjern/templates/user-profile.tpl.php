<div class="user-profile-header">
<h1><?php print $elements['#account']->name; ?></h1>
<?php if ($role = _user_role($elements['#account']->uid)) : ?>
  <p class="role"><?php print $role; ?></p>
<?php endif; ?>
</div>
<div class="pure-g">
  <div class="pure-u-1 pure-u-md-4-5">
    <table class="user-data pure-table pure-table-horizontal">
      <?php if (isset($elements['#account']->field_address[LANGUAGE_NONE][0]['name_line']) && strlen($elements['#account']->field_address[LANGUAGE_NONE][0]['name_line']) > 0): ?>
      <tr>
        <td>Navn</td>
        <td><?php print $elements['#account']->field_address[LANGUAGE_NONE][0]['name_line']; ?></td>
      </tr>
      <?php endif; ?>
      <?php if (isset($variables['age'])) : ?>
      <tr>
        <td>Alder</td>
        <td><?php print $variables['age']; ?> år</td>
      </tr>
      <?php endif; ?>
      <?php if (isset($variables['gender'])) : ?>
      <tr>
        <td><?php print t('Gender'); ?></td>
        <td><?php print $variables['gender']; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td>Medlem siden</td>
        <td><?php print formatted_date($elements['#account']->created); ?></td>
      </tr>
      <tr>
        <td>Sidste besøg</td>
        <td><?php print formatted_date($elements['#account']->login); ?></td>
      </tr>
      <?php if (isset($variables['address'])) : ?>
      <tr>
        <td>Adresse</td>
        <td>
          <address><?php print $variables['address']; ?></address>
        </td>
      </tr>
      <?php endif; ?>
      <tr>
        <td>E-mail</td>
        <td>
          <?php if ($variables['display']) : ?>
          <a href="mailto:<?php print $elements['#account']->mail; ?>"><?php print $elements['#account']->mail; ?></a>
          <?php else: ?>
          <?php print t('Address hidden'); ?>
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </div>
  <div class="pure-u-1 pure-u-md-1-5">
    <?php print render($user_profile['user_picture']); ?>
  </div>
</div>
<?php if (count($user_reviews) > 0) : ?>
<h2><?php print _genitive($elements['#account']->name); ?> anmeldelser</h2>
<div class="page_container">
<table class="pure-table pure-table-horizontal pure-table-striped">
  <caption class="info_text"></caption>
  <thead>
    <tr>
      <th>Dato</th>
      <th>Artist</th>
      <th>Titel</th>
    </tr>
  </thead>
  <tbody class="content">
  <?php foreach ($user_reviews as $review) : ?>
    <tr>
      <td><time datetime="<?php print date('Y-m-d', $review->review_date); ?>"><?php print date('d-m-Y', $review->review_date); ?></time></td>
      <td><?php print $review->artist; ?></td>
      <td><?php print l($review->title, 'node/' . $review->nid, array('attributes' => array('title' => $review->artist . ' - ' . $review->title))); ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<div class="page_navigation"></div>
</div>
<?php endif; ?>

<?php if (count($user_concerts) > 0) : ?>
<h2><?php print _genitive($elements['#account']->name); ?> koncertanmeldelser</h2>
<div class="concerts_page_container">
<table class="pure-table pure-table-horizontal pure-table-striped">
  <caption class="info_text"></caption>
  <thead>
    <tr>
      <th>Dato</th>
      <th>Titel</th>
    </tr>
  </thead>
  <tbody class="content">
  <?php foreach ($user_concerts as $concert) : ?>
    <tr>
      <td><time datetime="<?php print date('Y-m-d', $concert->created); ?>"><?php print date('d-m-Y', $concert->created); ?></time></td>
      <td><?php print l($concert->title, 'node/' . $concert->nid); ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<div class="page_navigation"></div>
</div>
<?php endif; ?>

<?php if (count($user_interviews) > 0) : ?>
<h2><?php print _genitive($elements['#account']->name); ?> interviews</h2>
<ul>
  <?php foreach($user_interviews as $interview) : ?>
  <li><?php print l($interview->title, 'node/' . $interview->nid); ?> (<time datetime="<?php print date('Y-m-d', $interview->created); ?>"><?php print date('d-m-Y', $interview->created); ?></time>)</li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (count($user_news) > 0) : ?>
<h2><?php print _genitive($elements['#account']->name); ?> nyheder</h2>
<div class="news_page_container">
<table class="pure-table pure-table-horizontal pure-table-striped">
  <caption class="info_text"></caption>
  <thead>
    <tr>
      <th>Dato</th>
      <th>Titel</th>
    </tr>
  </thead>
  <tbody class="content">
  <?php foreach ($user_news as $news) : ?>
    <tr>
      <td><time datetime="<?php print date('Y-m-d', $news->created); ?>"><?php print date('d-m-Y', $news->created); ?></time></td>
      <td><?php print l($news->title, 'node/' . $news->nid); ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<div class="page_navigation"></div>
</div>
<?php endif; ?>

<?php if (count($user_comments) > 0) : ?>
<h2><?php print _genitive($elements['#account']->name); ?> seneste kommentarer</h2>
<ul>
  <?php foreach ($user_comments as $c) : ?>
  <li><strong><a href="<?php print url('node/' . $c->nid); ?>#comment-<?php print $c->cid; ?>"><?php print $c->subject; ?></a></strong> <?php print t('%time ago', array('%time' => format_interval(time() - $c->created))); ?> til <?php print l($c->title, 'node/' . $c->nid); ?></li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>