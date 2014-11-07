<article>
<h1><?php print $node->title; ?></h1>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="/sites/all/themes/tungtjern/scripts/jquery.tableofcontents.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function(){ 
    $("#toc").tableOfContents(
      $("#guidewrapper"),
      {
        startLevel: 2,
        depth: 3
      }
    ); 
  });
</script>

<h2>Indhold</h2>
<ol id="toc" class="toc"></ol>
<div id="guidewrapper">
<?php if (isset($node->body[LANGUAGE_NONE][0])) : ?>
  <?php if (strlen($node->body[LANGUAGE_NONE][0]['summary']) > 0) : ?>
    <p class="summary"><?php print $node->body[LANGUAGE_NONE][0]['summary']; ?></p>
  <?php endif; ?>
  <?php print $node->body[LANGUAGE_NONE][0]['safe_value']; ?>
<?php endif; ?>
</div>

<?php print render($content['comments']); ?>
</article>
