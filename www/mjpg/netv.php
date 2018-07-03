<html>
<head>
</head>
<body>
NeTV command status:
<?php
    $data = file_get_contents("http://netvipaddress/scripts/get_date.sh");
    
    if (is_numeric(trim($data))) echo "<font color=green>Good</font>";
    else echo "<font color=red>Error: ".$data."</font>";
?>
<br><br>
<input type=button value="Test Command" onclick="location.href='netv.php?command=myscript.sh';"><br>
<input type=button value="Reboot NeTV" onclick="location.href='netv.php?command=restart_device.sh';"><br>
<input type=button value="Restart NeTV Services" onclick="location.href='netv.php?command=restart_netv.sh';"><br>
<input type=button value="Restart NeTV Browser" onclick="location.href='netv.php?command=restart_browser.sh';"><br>
<input type=button value="Restart NeTV Server" onclick="location.href='netv.php?command=restart_server.sh';"><br>
<br>
<?php
if (isset($_GET['command']))
{
    $command = $_GET['command'];
    $data = file_get_contents("http://netvip/scripts/".$command);
    echo "Command: ".$command."<br>\n";
    echo "Output: ".$data."<br>\n";
}
?>
</body>
</html>
