<?php

$netv_ip = '192.168.1.201';

$connection = ssh2_connect($netv_ip, 22);
ssh2_auth_password($connection, 'root', '');

$stream = ssh2_exec($connection, 'fpga_ctl H;fpga_ctl h;sleep 2;echo done;');

stream_set_blocking($stream, true);

print stream_get_contents($stream);

?>
