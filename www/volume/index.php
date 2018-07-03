<?php


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

$result = $conn->query("SELECT * FROM volume WHERE date MOD 10 = 0 and `date` > $after");

if($result)
{
    while ($row = $result->fetch_object())
    {
        $x[] = $row->date;
        $y[] = $row->volume;
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
<meta http-equiv="refresh" content="10">
<head>
  <!-- Plotly.js -->
   <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <style>
        #myDiv {
            height:95vh;
            wifth: 100vw;
        }
    </style>
</head>

<body>
  <div id="myDiv"><!-- Plotly chart will be drawn inside this DIV --></div>
  <script>
    var trace1 = {
        <?php
        //x: [1, 2, 3, 4],
        //y: [10, 15, 13, 17]
        echo "\t\tx: [";
        $first = $x[0];
        foreach ($x as $value) {
            $value = (abs($value - $x[0])) / 1000.0;
            echo $value.",";
            //'yyyy-mm-dd HH:MM:SS.ssssss'
        }
        echo "],\n";
        
        echo "\t\ty: [";
        foreach ($y as $value) {
            $adjust = (float)$value;
            echo $adjust.",";
        }
        echo "],";
        ?>
      mode: 'line'
    };

    var data = [ trace1];

    var layout = {};

    Plotly.newPlot('myDiv', data, layout);

  </script>
</body>
</html>
