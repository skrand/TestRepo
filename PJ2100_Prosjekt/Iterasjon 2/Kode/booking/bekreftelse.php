<?php
require 'config.php';

// Set variables
$startTime = "12:00";
$hourCount = 1;
$date = $_GET['date'];//date('Y-m-d');

if (isset($_POST["timeinput"]))
{
    $startTime = $_POST['timeinput'];
}
if (isset($_POST["hourinput"]))
{
    $hourCount = $_POST['hourinput'];
}

// Bruk arrayen med Rom til å loope gjennom for å finne ut hvilken knapp som ble trykket på
// (knappene får tilsvarende RomId lagt til på slutten av 'name' i oversikten)
// TODO Find better solution
$sql = $db->prepare("SELECT * FROM Rom;");
$sql->setFetchMode(PDO::FETCH_OBJ);
$sql->execute();
$roomid = 0;
while($rom = $sql->fetch())
{
    // Hvis knappen er satt med en av IDene, stopp å loope
    if (isset($_POST['booking' . $roomid])) break;
    $roomid ++;
}

$time = $_GET['time'];
$date = $_GET['date'];


// Query
$userid = getUserIdFromName($_SESSION['user'], $db);
$sql = $db->prepare("INSERT INTO LeieAvRom VALUES (:roomid, :userid, :date, :time, :hour);");
$sql->execute(array(
    'roomid' => $roomid,
    'userid' => $userid,
    'date' => $date,
    'time' => $time,
    'hour' => $hourCount
));


// Go back to index
header("Location: index.php?dato=" . $date);
die();
// TODO Replace redirect with function