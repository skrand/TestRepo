<?php
require 'config.php';

// Set variables
$startTime = "12:00";
$hourCount = 1;
$date = date('Y-m-d');
$roomId = $_GET['romid'];
if (isset($_GET["timeinput"]))
{
    $startTime = $_GET['timeinput'];
}
if (isset($_GET["hourinput"]))
{
    $hourCount = $_GET['hourinput'];
}

// Query
$sql = $db->prepare("INSERT INTO LeieAvRom VALUES (:id, 1, :date, :time, :hour);");
$sql->execute(array(
    'id' => $roomId,
    'date' => $date,
    'time' => $_GET['timeinput'],
    'hour' => $_GET['hourinput']
));

// Go back to index
header("Location: index.php");
die();
