<?php
require 'config.php';
//echo "bekreftelse stoop";
//die();
// Set variables
$startTime = "12:00";
$hourCount = 1;
$date = date('Y-m-d');

if (isset($_GET["timeinput"]))
{
    $startTime = $_GET['timeinput'];
}
if (isset($_GET["hourinput"]))
{
    $hourCount = $_GET['hourinput'];
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
    if (isset($_GET['booking' . $roomid])) break;
    $roomid ++;
}

// Query
$userid = getUserIdFromName($_SESSION['user'], $db);
$sql = $db->prepare("INSERT INTO LeieAvRom VALUES (:roomid, :userid, :date, :time, :hour);");
$sql->execute(array(
    'roomid' => $roomid,
    'userid' => $userid,
    'date' => $date,
    'time' => $_GET['timeinput'],
    'hour' => $_GET['hourinput']
));


// Go back to index
header("Location: index.php");
die();
// TODO Replace redirect with function