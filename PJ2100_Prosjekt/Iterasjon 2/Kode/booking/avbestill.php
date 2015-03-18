<?php
require 'config.php';

$roomId = $_GET['roomid'];
$date = $_GET['date'];
$time = $_GET['time'];

echo "ROOM: " . $roomId;
echo "<br />";
echo "DATE: " . $date;
echo "<br />";
echo "TIME: " . $time;
echo "<br />";

$sql = $db->prepare("DELETE FROM LeieAvRom WHERE RomId LIKE :roomId AND Dato LIKE :date AND Tidspunkt LIKE :time;");
$sql->setFetchMode(PDO::FETCH_OBJ);
$sql->execute(array(
    'roomId' => $roomId,
    'date' => $date,
    'time' => $time
));
//echo $sql->fetch()['Tidspunkt'];
var_dump($sql->fetch());