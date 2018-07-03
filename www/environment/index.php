<?php

function millis_to_date($mil)
{
    $seconds = floor($mil / 1000);
    $milli = $mil - ( $seconds * 1000);

    $data = date("Y-m-d H:i:s", $seconds).".".$milli;
    //echo $data;
    return $data;
}

// Create connection
$conn = new mysqli("localhost", "database", "password", "username");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
    
$x = array();
$y = array();

$hours_to_keep = 12;
$seconds = (60 * 60 * $hours_to_keep);
$epoch = time();
$after = ($epoch - $seconds) * 1000;
//echo "after: ".$after."<br>";
//echo "epoch: ".$epoch."<br>";
//echo "seconds: ".$seconds."<br>";

$result = $conn->query("SELECT * FROM environment WHERE id MOD 10 = 0 AND `date` > $after");

if($result)
{
    while ($row = $result->fetch_object())
    {
        $mq2y[] = $row->mq2 - 80;
        $x[] = millis_to_date($row->date);
        $mq4y[] = $row->mq4 - 60;
        $mq7y[] = $row->mq7 - 360;
        $mq8y[] = $row->mq8 - 440 ;
        $mq135y[] = $row->mq135 - 390;
        $hum[] = $row->humidity;
        $temp[] = $row->temp;
    }
    //print_r($x);
    //echo "<br>\n";
    //print_r($y);
    $result->close();
    $conn->next_result();
} else echo($conn->error);

$result = $conn->query("SELECT * FROM volume WHERE date MOD 10 = 0 and `date` > $after");

if($result)
{
    while ($row = $result->fetch_object())
    {
        $vx[] = millis_to_date($row->date);
        $vy[] = $row->volume /5.0;
    }
    //print_r($x);
    //echo "<br>\n";
    //print_r($y);
    $result->close();
    $conn->next_result();
} else echo($conn->error);

$conn->close();

?>

<html>
<meta http-equiv="refresh" content="300">
<head>
  <!-- Plotly.js -->
   <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <style>
        #myDiv {
            height:95vh;
            width: 100%;
        }
        .modebar{
              display: none !important;
        }
    </style>
</head>

<body style="background-color: #F000F0;">
  <div id="myDiv"><!-- Plotly chart will be drawn inside this DIV --></div>
  <script>
    var mq2 = {
        <?php
        //x: [1, 2, 3, 4],
        //y: [10, 15, 13, 17]
        echo "\t\tx: [";
        foreach ($x as $value) {
            //$value = (abs($value - $x[0])) / 1000.0;
            echo "\"".$value."\",";
            //'yyyy-mm-dd HH:MM:SS.ssssss'
        }
        echo "],\n";
        
        echo "\t\ty: [";
        foreach ($mq2y as $value) {
            echo $value.",";
        }
        echo "],";
        ?>
      mode: 'line',
      name: 'AirQ',
      line: {
        color: 'rgb(200, 64, 255)',
        width: 3
      }
    };
    //actually mq4
    var mq4 = {
        <?php
        //x: [1, 2, 3, 4],
        //y: [10, 15, 13, 17]
        echo "\t\tx: [";
        foreach ($x as $value) {
            //$value = (abs($value - $x[0])) / 1000.0;
            echo "\"".$value."\",";
            //'yyyy-mm-dd HH:MM:SS.ssssss'
        }
        echo "],\n";
        
        echo "\t\ty: [";
        foreach ($mq4y as $value) {
            $value = $value / 3.0;
            echo $value.",";
        }
        echo "],";
        ?>
      mode: 'line',
      name: 'Fart1M',
      line: {
        color: 'rgb(0, 128, 0)',
        width: 3
      }
    };
    
    var mq7 = {
        <?php
        //x: [1, 2, 3, 4],
        //y: [10, 15, 13, 17]
        echo "\t\tx: [";
        foreach ($x as $value) {
            //$value = (abs($value - $x[0])) / 1000.0;
            echo "\"".$value."\",";
            //'yyyy-mm-dd HH:MM:SS.ssssss'
        }
        echo "],\n";
        
        echo "\t\ty: [";
        foreach ($mq7y as $value) {
            $value = $value / 1.7;
            echo $value.",";
        }
        echo "],";
        ?>
      mode: 'line',
      name: 'CO',
      line: {
        color: 'rgb(200, 200, 200)',
        width: 3
      }
    };
    
    var mq8 = {
        <?php
        //x: [1, 2, 3, 4],
        //y: [10, 15, 13, 17]
        echo "\t\tx: [";
        foreach ($x as $value) {
            //$value = (abs($value - $x[0])) / 1000.0;
            echo "\"".$value."\",";
            //'yyyy-mm-dd HH:MM:SS.ssssss'
        }
        echo "],\n";
        
        echo "\t\ty: [";
        foreach ($mq8y as $value) {
            echo $value.",";
        }
        echo "],";
        ?>
      mode: 'line',
      name: 'Fart2H',
      line: {
        color: 'rgb(128, 255, 128)',
        width: 3
      }
    };
    
    var mq135 = {
        <?php
        //x: [1, 2, 3, 4],
        //y: [10, 15, 13, 17]
        echo "\t\tx: [";
        foreach ($x as $value) {
            //$value = (abs($value - $x[0])) / 1000.0;
            echo "\"".$value."\",";
            //'yyyy-mm-dd HH:MM:SS.ssssss'
        }
        echo "],\n";
        
        echo "\t\ty: [";
        foreach ($mq135y as $value) {
            echo $value.",";
        }
        echo "],";
        ?>
      mode: 'line',
      name: 'Fart3A',
      line: {
        color: 'rgb(255, 255, 0)',
        width: 3
      }
     
    };
    
    var hum = {
        <?php
        //x: [1, 2, 3, 4],
        //y: [10, 15, 13, 17]
        echo "\t\tx: [";
        foreach ($x as $value) {
            //$value = (abs($value - $x[0])) / 1000.0;
            echo "\"".$value."\",";
            //'yyyy-mm-dd HH:MM:SS.ssssss'
        }
        echo "],\n";
        
        echo "\t\ty: [";
        foreach ($hum as $value) {
            echo $value.",";
        }
        echo "],";
        ?>
      mode: 'line',
      name: 'Hum',
      line: {
        color: 'rgb(128, 128, 255)',
        width: 3
      }
    };
    
    var temp = {
        <?php
        //x: [1, 2, 3, 4],
        //y: [10, 15, 13, 17]
        echo "\t\tx: [";
        foreach ($x as $value) {
            //$value = (abs($value - $x[0])) / 1000.0;
            echo "\"".$value."\",";
            //'yyyy-mm-dd HH:MM:SS.ssssss'
        }
        echo "],\n";
        
        echo "\t\ty: [";
        foreach ($temp as $value) {
            echo $value.",";
        }
        echo "],";
        ?>
      mode: 'line',
      name: 'Temp',
      line: {
        color: 'rgb(255, 0, 0)',
        width: 3
      }
    };
    
    var vol = {
        <?php
        //x: [1, 2, 3, 4],
        //y: [10, 15, 13, 17]
        echo "\t\tx: [";
        foreach ($vx as $value) {
            //$value = (abs($value - $vx[0])) / 1000.0;
            echo "\"".$value."\",";
            //'yyyy-mm-dd HH:MM:SS.ssssss'
        }
        echo "],\n";
        
        echo "\t\ty: [";
        foreach ($vy as $value) {
            echo $value.",";
        }
        echo "],";
        ?>
      mode: 'line',
      name: 'Vol',
      line: {
        color: 'rgb(0, 0, 0)',
        width: 2
      }
    };

    var data = [ temp, hum, mq8, mq135, mq4, mq2, mq7, vol];

    var layout = {
        margin: {
            l: 0,
            r: 0,
            b: 0,
            t: 30,
            pad: 0,
          },
          paper_bgcolor:"#F000F0",
          plot_bgcolor:"#F000F0",
          yaxis: {
            overlaying: 'y',
            side: 'right',
            showgrid: false,
          },
          xaxis: {
            overlaying: 'x',
            side: 'top',
            showgrid: false,
          },
          legend: {
              "orientation": "h",
            x: 0,
            y: 1,
            font: {
              family: 'sans-serif',
              size: 12,
              color: '#000'
            },
          },
    };

    Plotly.newPlot('myDiv', data, layout);

  </script>
</body>
</html>
