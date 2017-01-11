<h1>Statistik</h1>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<?php if ($artist_countries['countries']) : ?>
<h2>Hvilke lande er mest repræsenteret i anmeldelser?</h2>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Country', 'Quantity'],
          <?php foreach ($artist_countries['countries'] as $country): ?>
          ['<?php print $country['country']; ?>', <?php print $country['quantity']; ?>],
          <?php endforeach; ?>
        ]);

        var options = {
          title: 'Anmeldelser pr. artistland'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
<div id="piechart" style="width: 900px; height: 500px;"></div>
<?php endif; ?>

<?php if ($artist_countries['exotic_countries']) : ?>
<h2>Eksotiske udgivelser</h2>
<p>Lande som kun er repræsenteret med én anmeldelse.</p>
<ul>
<?php foreach ($artist_countries['exotic_countries'] as $release): ?>
  <li><strong><?php print $release['country']; ?></strong> (<?php print l($release['title'], 'node/' . $release['nid']); ?>)</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<h2>Fordeling af anmeldelsers karakterer</h2>

<?php if ($ratings_overall) : ?>
  <p>Alle pladeanmeldelser nogensinde udgivet på heavymetal.dk:</p>
  <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Karakter", "Antal anmeldelser"],
        <?php foreach ($ratings_overall as $r => $q): ?>
        ["<?php print $r; ?>", <?php print $q; ?>],
        <?php endforeach; ?>
      ]);

      var options = {
        title: "Alle anmeldelsers karakterer",
        width: 750,
        height: 450,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Karakterer',
        },
        vAxis: {
          title: 'Antal anmeldelser'
        }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("ratings_overall"));
      chart.draw(data, options);
  }
  </script>
<div id="ratings_overall" style="width: 750px; height: 450px;"></div>
<?php endif; ?>

<?php if ($ratings_last_year) : ?>
  <p>Sidste år (<?php print (date('Y') - 1); ?>):</p>
  <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Karakter", "Antal anmeldelser"],
        <?php foreach ($ratings_last_year as $r => $q): ?>
        ["<?php print $r; ?>", <?php print $q; ?>],
        <?php endforeach; ?>
      ]);

      var options = {
        title: "Sidste år (<?php print (date('Y') - 1); ?>)",
        width: 750,
        height: 450,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Karakterer',
        },
        vAxis: {
          title: 'Antal anmeldelser'
        }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("ratings_last_year"));
      chart.draw(data, options);
  }
  </script>
<div id="ratings_last_year" style="width: 750px; height: 450px;"></div>
<?php endif; ?>

<?php if ($ratings_year) : ?>
  <p>År til dato:</p>
  <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Karakter", "Antal anmeldelser"],
        <?php foreach ($ratings_year as $r => $q): ?>
        ["<?php print $r; ?>", <?php print $q; ?>],
        <?php endforeach; ?>
      ]);

      var options = {
        title: "År til dato (<?php print date('Y'); ?>)",
        width: 750,
        height: 450,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Karakterer',
        },
        vAxis: {
          title: 'Antal anmeldelser'
        }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("ratings_year"));
      chart.draw(data, options);
  }
  </script>
<div id="ratings_year" style="width: 750px; height: 450px;"></div>
<?php endif; ?>