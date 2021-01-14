<!DOCTYPE html>
<html lang="en">
<?php 
    error_reporting(E_ERROR | E_PARSE);
    $jsonAmazon = file_get_contents("http://localhost:8090/amazonTitles/format=json");
    $jsonArrayAmazon = json_decode($jsonAmazon, true);

    $jsonDisneyPlus = file_get_contents("http://localhost:8090/disneyPlusTitles/format=json");
    $jsonArrayDisneyPlus = json_decode($jsonDisneyPlus, true);

    $jsonNetflix = file_get_contents("http://localhost:8090/netflixTitles/format=json");
    $jsonArrayNetflix = json_decode($jsonNetflix, true);

    $amazonTitlesPerYear = array();
    $disneyPlusTitlesPerYear = array();
    $netflixTitlesPerYear = array();

    for ($i = 0; $i < sizeof($jsonArrayAmazon); $i++)
    {
        $key = $jsonArrayAmazon[$i]['yearOfRelease'];

        if ($key != 0 && $key > 2010)
        {
            $amazonTitlesPerYear[$key] = $amazonTitlesPerYear[$key] + 1;
        }
    }

    for ($i = 0; $i < sizeof($jsonArrayDisneyPlus); $i++)
    {
        $key = $jsonArrayDisneyPlus[$i]['addedAt'];
        $key = (int)substr($key, -4);

        if ($key != 0 && $key > 2010)
        {
            $disneyPlusTitlesPerYear[$key] = $disneyPlusTitlesPerYear[$key] + 1;
        }
    }

    for ($i = 0; $i < sizeof($jsonArrayNetflix); $i++)
    {
        $key = $jsonArrayNetflix[$i]['dateAdded'];
        $key = (int)substr($key, -4);

        if ($key != 0 && $key > 2010)
        {
            $netflixTitlesPerYear[$key] = $netflixTitlesPerYear[$key] + 1;
        }
    }

    $merged = array();

     

    foreach ($amazonTitlesPerYear as $key => $value)
    {
        $merged[$key][0] = $value;
    }

    foreach ($disneyPlusTitlesPerYear as $key => $value)
    {
        $merged[$key][1] = $value;
    }

    foreach ($netflixTitlesPerYear as $key => $value)
    {
        $merged[$key][2] = $value;
    }

    ksort($merged);
    ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Visualisation</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Amazon', 'Disney+', 'Netflix'],
          <?php
            foreach ($merged as $key => $value)
            {
                $amazon = 0;
                $disneyPlus = 0;
                $netflix = 0;

                if ($value[0] != null)
                {
                    $amazon = $value[0];
                }

                if ($value[1] != null)
                {
                    $disneyPlus = $value[1];
                }

                if ($value[2] != null)
                {
                    $netflix = $value[2];
                }


                echo "[' $key ', $amazon , $disneyPlus ,  $netflix ],";
            }
          ?>
        ]);

        var options = {
          title: 'Number of titles of big streaming services from 2011',
          legend: { position: 'bottom' },
          series: {
              0: {color: '#212e3e'},
              1: {color: '#1e3e8e'},
              2: {color: '#de0912'}
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
</head>
<body>

    
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
</body>
</html>