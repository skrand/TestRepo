<?php
require 'config.php';

// Redirect to index if some variables arent set
if (!isset($_GET['roomid'])) redirect();
if (!isset($_GET['date'])) redirect();
if (!isset($_GET['time'])) redirect();

// Get the variables
$roomId = $_GET['roomid'];
$date = $_GET['date'];
$time = $_GET['time'];

// Query
$sql = $db->prepare("DELETE FROM LeieAvRom WHERE RomId LIKE :roomId AND Dato LIKE :date AND Tidspunkt LIKE :time;");
$sql->setFetchMode(PDO::FETCH_OBJ);
$sql->execute(array(
    'roomId' => $roomId,
    'date' => $date,
    'time' => $time
));

// Query done, go to index
redirect();

// Redirects to index.php
function redirect()
{
    header("Location: index.php");
    die();
}