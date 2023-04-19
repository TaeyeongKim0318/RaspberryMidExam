<?php
if ($_POST["func"] == "create") {
  $t = $_POST["t"];
  $h = $_POST["h"];

  if(!file_exists("log")) mkdir("log");
  $logf = 'log/'.date("Ymd").'.csv';
  $contents = date('Y/m/d H:i:s').",".$_SERVER['REMOTE_ADDR'].",".$t.",".$h."\n";
  file_put_contents($logf, $contents, FILE_APPEND);
  die(json_encode($t."/".$h));
}

if ($_POST["func"] == "filelist") {
  if(!file_exists("log")) die("not exists log path");
  $logf = 'log/*.csv';
  $filelist = glob($logf);
  $list = array();
  foreach($filelist as $filename){
    $list[] = $filename;
  }
  die(json_encode($list));
}

if ($_POST["func"] == "viewcsv") {
  if (! isset($_POST["csvfile"])) die(json_encode("Required the csvfile"));
  $f = $_POST["csvfile"];
  $c = file_get_contents($f);
  die(json_encode($c));
}

die($_POST["func"]." is not exist")
?>