<h1>Statistik</h1>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Country', 'Quantity'],
          <?php foreach ($world_map as $country): ?>
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