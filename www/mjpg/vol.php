<?php



$mysqli = new mysqli("localhost", "database", "password", "username");

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}


/* Select queries return a resultset */
if ($result = $mysqli->query("SELECT volume FROM `volume` ORDER BY `date` DESC LIMIT 1")) {
    
    $largest = 0;
    
    while($r = mysqli_fetch_assoc($result)) {
        if ($r["volume"]>$largest) $largest = $r["volume"];
    }
    
    print $largest;

    /* free result set */
    $result->close();
}
