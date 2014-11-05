<?php $node = node_load($row->nid); ?>
<item>
  <title><?php print $node->title; ?> [<?php print $node->field_rating[LANGUAGE_NONE][0]['value']; ?>/10]</title>
  <link><?php print $row->link; ?></link>
  <?php if ($node->comment == COMMENT_NODE_OPEN) : ?>
  <comments><?php print $row->link; ?>#comments</comments>
  <slash:comments><?php print $node->comment_count; ?></slash:comments>
  <?php endif; ?>
  <description><?php if (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?><?php print truncate_utf8(strip_tags($node->body[LANGUAGE_NONE][0]['summary']), 600, TRUE); ?><?php else: ?><?php print truncate_utf8(str_replace(array("\r", "\n"), '', strip_tags($node->body[LANGUAGE_NONE][0]['safe_value'])), 600, TRUE, TRUE); ?><?php endif; ?></description>
  <pubDate><?php print date('r', $node->published_at); ?></pubDate>
  <dc:creator><![CDATA[<?php print $row->elements[1]['value']; ?>]]></dc:creator>
  <guid isPermaLink="true"><?php print $row->link; ?></guid>
</item>
