<div class="comment pure-g <?php print $zebra; ?>" id="comment-<?php print $comment->cid; ?>">
  <div class="commentMeta pure-u-1-3 pure-u-md-1-4 pure-u-lg-1-5">
    <?php if (is_object($comment->picture)) : ?>
      <div class="user-image">
        <a href="<?php print url('user/' . $comment->uid); ?>"><img src="<?php print image_cache('avatar', $comment->picture); ?>" alt="<?php print $comment->name; ?>" class="pure-img" /></a>
      </div>
    <?php endif; ?>
    <?php if ($comment->uid > 0) : ?>
      <p itemprop="creator"><?php print l($comment->name, 'user/' . $comment->uid); ?></p>
    <?php else: ?>
      <p itemprop="creator"><?php print (strlen($comment->name) > 0) ? $comment->name : t('Anonymous'); ?></p>
    <?php endif; ?>
    <?php if ($role = _user_role($comment->uid)) : ?>
      <p class="role"><?php print $role; ?></p>
    <?php endif; ?>
    <time datetime="<?php print format_date($comment->created, 'datetime'); ?>" itemprop="commentTime"><?php print format_date($comment->created, 'shortdate'); ?></time>
  </div>
  <div class="commentContent pure-u-2-3 pure-u-md-3-4 pure-u-lg-4-5">
    <h3><a href="#comment-<?php print $comment->cid; ?>"><?php print $content['comment_body']['#object']->subject; ?></a><?php if ($new): ?> <i class="new fa fa-star" title="<?php print t('New'); ?>"></i><?php endif; ?></h3>
    <?php hide($content['links']); print $comment->comment_body['und'][0]['safe_value']; ?>
    <?php if($rating = _get_user_rating($comment->uid, $comment->nid)): ?>
      <p class="comment-userrating"><i class="fa fa-star"></i> <?php print t('!user gave !title <span>!rating/10</span>.', array('!user' => $comment->name, '!title' => _get_node_title($comment->nid), '!rating' => $rating)); ?></p>
    <?php endif; ?>
    <?php print render($content['links']); ?>
  </div>
</div>