<?php
$output = shell_exec('cd /home/devasttrolok/public_html && git pull origin dev 2>&1');
echo $output;
?>
