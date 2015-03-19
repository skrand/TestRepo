<?php
require 'config.php';
require 'verifysession.php';

// Redirect to index if variables aren't set
if (!isset($_POST['hourinput']) || (int)$_POST['hourinput'] <= 0) redirect();
if (!isset($_GET['time'])) redirect();
if (!isset($_GET['date'])) redirect();
if (!isset($_GET['roomid'])) redirect();

// Set variables
$hourCount = $_POST['hourinput'];
$date = $_GET['date'];
$time = $_GET['time'];
$roomid = $_GET['roomid'];

// Inser the new booking to the database
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
redirect();
// TODO Replace redirect with function

function redirect()
{
    header("Location: index.php?dato=" . $date . "shit");
    die();
}