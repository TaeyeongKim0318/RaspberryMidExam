<?php
$db = new PDO("sqlite:iot.db");

if ($_POST["func"] == "table_create") {
    $db->exec("CREATE TABLE ta_iot(time NUMBER, addr TEXT, temp FLOAT, humi FLOAT)");
    die(json_encode("create table log"));
}

if ($_POST["func"] == "table_drop") {
    $db->exec("DROP TABLE ta_iot");
    die(json_encode("create table log"));
}

// create data
if ($_POST["func"] == "insert" || $_POST["func"] == "create" ) {
    $t = $_POST["t"];
    $h = $_POST["h"];
    $address = $_SERVER['REMOTE_ADDR'];
    $time = date("YmdHis");

    $sql = <<<EOT
    INSERT INTO ta_iot(
            time, 
            addr,
            temp,
            humi
        ) VALUES(
            :time,
            :addr,
            :temp,
            :humi
        )
    EOT;
    error_log($sql);
    $db->beginTransaction();
    $stmt = $db->prepare($sql);
    $r = $stmt->execute([
        ':time' => $time,
        ':addr' => $address,
        ':temp' => $t,
        ':humi' => $h
    ]);
    $db->commit();
    die(json_encode("insert count: ".$r));
}

// retrieve data
if ($_POST["func"] == "select") {
    $s = isset($_POST["jumpto"])?$_POST["jumpto"]:0;
    $c = isset($_POST["count"])?$_POST["count"]:10;
    $time = isset($_POST["date"])?$_POST["date"]:date('Ymd');

    $sql = <<<EOT
    SELECT * FROM ta_iot 
    WHERE time like :time
    LIMIT :count 
    OFFSET :jumpto
    EOT;
    $r = array();
    error_log($sql);
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':count', $c);
    $stmt->bindValue(':jumpto', $s);
    $stmt->bindValue(':time', $time."%");
    $stmt->execute();
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    die(json_encode($r));
}

// update data
if ($_POST["func"] == "update") {
    $t = $_POST["t"];
    $h = $_POST["h"];
    $address = $_SERVER['REMOTE_ADDR'];
    $otime = $_POST["time"];

    $sql = <<<EOT
    UPDATE ta_iot SET
        temp = :temp,
        humi = :humi,
        addr = :addr
    WHERE time = :time
    EOT;
    error_log($sql);
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':temp', $t);
    $stmt->bindValue(':humi', $h);
    $stmt->bindValue(':addr', $address);
    $stmt->bindValue(':time', $otime);

    $r = $stmt->execute();
    die(json_encode("update count:".$r));
}

// delete data
if ($_POST["func"] == "delete") {
    $otime = $_POST["time"];
    $sql = <<<EOT
    DELETE from ta_iot
    WHERE time = :time
    EOT;
    error_log($sql);
    $stmt = $db->prepare($sql);
    $r = $stmt->execute([
        ':time' => $otime
    ]);
    die(json_encode("delete count:".$r));
}
die($_POST["func"]." is not exist");
