<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Visualisation</title>
</head>
<body>

    <?php 
    error_reporting(E_ERROR | E_PARSE);
    $jsonAmazon = file_get_contents("http://localhost:8090/amazonTitles/format=json");
    $jsonArrayAmazon = json_decode($jsonAmazon, true);

    $jsonDisneyPlus = file_get_contents("http://localhost:8090/disneyPlusTitles/format=json");
    $jsonArrayDisneyPlus = json_decode($jsonDisneyPlus, true);

    $jsonNetflix = file_get_contents("http://localhost:8090/netflixTitles/format=json");
    $jsonArrayNetflix = json_decode($jsonNetflix, true);

    $amazonTitlesPerYear = array();

    for ($i = 0; $i < sizeof($jsonArrayAmazon); $i++)
    {
        $key =  $jsonArrayAmazon[$i]['yearOfRelease'];

        $amazonTitlesPerYear[$key] = $amazonTitlesPerYear[$key] + 1;
    }


    
    echo "<pre>";
    var_dump($amazonTitlesPerYear);
    echo "</pre>";
    ?>
    
</body>
</html>