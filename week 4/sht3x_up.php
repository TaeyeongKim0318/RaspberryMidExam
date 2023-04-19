<?php
$t = $_POST["t"];
$h = $_POST["h"];
if(!file_exists("log")) mkdir("log");
$logf = 'log/'.date("Ymd").'.csv';
$contents = date('Y/m/d H:i:s').",".$t.",".$h." \n";
file_put_contents($logf, $contents, FILE_APPEND);
echo $contents;
?>