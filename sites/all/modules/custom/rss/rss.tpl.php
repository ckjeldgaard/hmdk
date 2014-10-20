<?php print '<?xml version="1.0" encoding="utf-8" ?>'; ?>
<rss version="2.0" xml:base="http://dev.heavymetal.dk/rss" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
  <title>Heavymetal.dk</title>
  <link>http://dev.heavymetal.dk/rss</link>
  <description>Metal på den hårde måde</description>
  <language>da</language>
<?php foreach ($rss_items as $node) : ?>
  <item>
    <title><![CDATA[<?php print $node->rss_title; ?>]]></title>
    <link><?php print url('node/' . $node->nid, array('absolute' => TRUE)); ?>?utm_source=RSS_feed&amp;utm_medium=RSS&amp;utm_campaign=RSS_Syndication</link>
    <description><![CDATA[<?php print $node->desc; ?>]]></description>
    <pubDate><?php print date("D, d M Y H:i:s", $node->published_at); ?> +0200</pubDate>
    <dc:creator><?php print $node->name; ?></dc:creator>
  </item>
<?php endforeach; ?>
</channel>
</rss>
