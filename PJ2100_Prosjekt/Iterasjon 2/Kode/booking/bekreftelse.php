<?php
require_once 'config.php';
require 'verifysession.php';
$db = new DB();

// Redirect to index if variables aren't set
//if (!isset($_POST['hourinput']) || (int)$_POST['hourinput'] <= 0) redirect();
if (!isset($_GET['time'])) redirect();
if (!isset($_GET['date'])) redirect();
if (!isset($_GET['roomid'])) redirect();

// Set variables
//$hourCount = $_POST['hourinput'];
$hourCount = 1;
$date = $_GET['date'];
$time = $_GET['time'];
$roomid = $_GET['roomid'];

// Inser the new booking to the database
$userid = $db->getUserIdFromName($_SESSION['user']);
$sql = $db->database->prepare("INSERT INTO LeieAvRom (RomId, BrukerId, Dato, Tidspunkt) VALUES (:roomid, :userid, :date, :time);");
$sql->execute(array(
    'roomid' => $roomid,
    'userid' => $userid,
    'date' => $date,
    'time' => $time
));

// Go back to index
redirect();

function redirect()
{
    // Legg på dato hvis den er satt, for å gå tilbake til den datoen når man har bestilt
    $dateAppendix = "";
    if (isset($_GET['date']))
    {
        $dateAppendix = "?dato=" . $_GET['date'];
    }
    header("Location: index.php" . $dateAppendix);
    die();
}