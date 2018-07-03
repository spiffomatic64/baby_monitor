<?php

$mysqli = new mysqli("localhost", "database", "password", "username");

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}


/* Select queries return a resultset */
if ($result = $mysqli->query("SELECT * FROM config")) {
    
    $rows = array();
    
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    $last_motion_time = intval(file_get_contents("./motion/last_motion.txt")) * 1000;
    $lastmotion = array("name"=>"lastmotion","value"=>$last_motion_time);
    $rows[] = $lastmotion;
    //print_r($rows);
    
    print json_encode($rows);

    /* free result set */
    $result->close();
}

$query = "INSERT INTO config (name,value) VALUES ('refresh','false') ON DUPLICATE KEY UPDATE value='false';";
$result = $mysqli->query($query);

?>
