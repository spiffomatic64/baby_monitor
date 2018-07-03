<html>
<body>

<script>

function change()
{
    document.getElementById("config").submit();
}

</script>

<?php

$debug = False;

$mysqli = new mysqli("localhost", "database", "password", "username");

$config_values = array();

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$config_names = array();
if ($result = $mysqli->query("SELECT * FROM config")) {
    while ($row = $result->fetch_object()) {
        $config_names[] = $row->name;
    }
    $result->close();
}


if ($debug && isset($_POST) && sizeof($_POST)>0 )
{
    echo "postlen: ".sizeof($_POST)."<br>\n";
    foreach ($_POST as $key => $value) 
    {
        echo "Key: ".$key." Value: ".$value."<br>\n";
    }
}

if (isset($_POST) && sizeof($_POST)>0 )
{
    foreach ($config_names as $value) 
    {
        if(isset($_POST[$value]))
        {
            if ( $_POST[$value] != "true" && $_POST[$value] != "false" )
            {
                echo "Post value is not true or false!!";
                die();
            } else
            {
                $safe_name = $mysqli->real_escape_string($value);
                $safe_value = $mysqli->real_escape_string($_POST[$value]);
                
                $query = "INSERT INTO config (name,value) VALUES ('".$safe_name."','".$safe_value."') ON DUPLICATE KEY UPDATE value='".$safe_value."';";
                if ($debug) echo "query: ".$query."<br>\n";
                $result = $mysqli->query($query);
            }
        } else
        {
            echo "Missing post value!";
            die();
        }
    }
}

if ($result = $mysqli->query("SELECT * FROM config")) {
    while ($row = $result->fetch_object()) {
        $config_values[$row->name] = $row->value;
    }
    $result->close();
}

$mysqli->close();

function config_bool($config_name)
{
    global $config_values;
    
    $lower_case_config = strtolower($config_name);
    echo $config_name." <input type=\"radio\" name=\"".$lower_case_config."\" value=\"true\"";
    if($config_values[$lower_case_config]=="true") echo " checked";
    echo " onchange=\"change();\"> On";
    echo "<input type=\"radio\" name=\"".$lower_case_config."\" value=\"false\"";
    if($config_values[$lower_case_config]=="false") echo " checked";
    echo " onchange=\"change();\"> Off<br>\n";
    
}

?>

<form method="post" id="config">
    <?php
    foreach ($config_names as $value) 
    {
        $upper = ucfirst($value);
        config_bool($upper);
    }
    ?>
    
    <input type="submit" name="Update">
</form> 
<a href="./netv.php">Run command on NeTV</a>
</body>
</html>
