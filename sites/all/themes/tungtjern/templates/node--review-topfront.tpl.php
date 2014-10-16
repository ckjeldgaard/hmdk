<section class="pure-g review row top<?php print get_edit_classes($node); ?>">
    <div class="pure-u-1 pure-u-sm-1-3 pure-u-md-5-12">
      <a href="<?php print url('node/' . $node->nid); ?>">
        <img src="<?php print image_cache('review', $node->release->field_image[LANGUAGE_NONE][0]); ?>" alt="<?php print check_plain($node->release->title); ?>" class="pure-img" />
      </a>
    </div>
    <div class="pure-u-1 pure-u-sm-2-3 pure-u-md-7-12 meta">
      <p class="post-meta">
        <?php print _get_type($node->type); ?><br />
        <i class="fa fa-user"></i> Anmeldt af <?php print ($node->uid > 0) ? l($node->name, 'user/' . $node->uid) : t('Anonymous'); ?>,
        <time datetime="<?php print format_date($node->published_at, 'date'); ?>"><?php print format_date($node->published_at, 'displaydate'); ?></time>

      <?php if (isset($node->first_genre)) : ?>
        <span class="genre"><i class="fa fa-music"></i> <?php print l($node->first_genre->name, 'taxonomy/term/'.$node->first_genre->tid); ?></span>
      <?php endif; ?> 
      <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
        <span class="comments"><a href="<?php print url('node/' . $node->nid); ?>#comments"><i class="fa fa-comments"></i> <?php print $node->comment_count; ?></a></span>
      <?php endif; ?>
      </p>
      <h1><a href="<?php print url('node/' . $node->nid); ?>"><?php print _get_artist_name($node->release->field_artist[LANGUAGE_NONE][0]['target_id']); ?> <span class="release-title"><?php print $node->release->field_release_title[LANGUAGE_NONE][0]['value']; ?></span></a></h1>
      
      <?php if (isset($node->field_tagline[LANGUAGE_NONE][0])) : ?>
        <p><?php print $node->field_tagline[LANGUAGE_NONE][0]['value']; ?></p>
      <?php endif; ?>
      
      <?php if (isset($node->field_rating[LANGUAGE_NONE])) : ?>
        <p>
          <span class="rating grade<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>"><?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>/10</span>
        </p>
      <?php endif; ?>
    </div>
</section>
