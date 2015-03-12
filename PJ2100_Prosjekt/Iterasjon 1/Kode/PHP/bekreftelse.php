<?php
require 'config.php';

/*$startTime = "";
$hourCount = 0;
if (isset($_POST['booking']))
{
    if (isset($_POST["timeinput"]))
    {
        $startTime = $_POST['timeinput'];
    }
    if (isset($_POST["hourinput"]))
    {
        $hourCount = $_POST['hourinput'];
    }
}*/

$startTime = "12:00";
$hourCount = 1;
if (isset($_POST["timeinput"]))
{
    $startTime = $_POST['timeinput'];
    //$_SESSION['book_time'] = $_POST['timeinput'];
}
if (isset($_POST["hourinput"]))
{
    $hourCount = $_POST['hourinput'];
    //$_SESSION['book_hours'] = $_POST['hourinput'];
}
$sql = $db->prepare("INSERT INTO LeieAvRom VALUES (:id, 1, '2015-03-12', :time, :hour);");
//$sql->bindParam(':roomId', $roomId, PDO::PARAM_INT);
//$sql->bindParam(':time', $startTime, PDO::PARAM_STR);
//$sql->bindParam(':hourcount', $hourCount, PDO::PARAM_STR);
//$sql->execute();
$sql->execute(array(
    'id' => $_POST['idinput'],
    'time' => $_POST['timeinput'],
    'hour' => $_POST['hourinput']
));

echo "id: ";
echo $_POST['idinput'];
echo "<br />";
echo "time: ";
echo $_POST['timeinput'];
echo "<br />";
echo "hour: ";
echo $_POST['hourinput'];
echo "<br />";
// Refresh page
header("Location: index.php");
die();

//echo "<p>Time: " . $_POST['timeinput'] . "</p>";
//echo "<p>Hours: " . $_POST['hourinput'] . "</p>";
?>
<!--<form method="get" action="bestill.php">
    <input type="submit" value="Bekreft" name="confirm">
    <input type="submit" value="Avbryt" name="cancel">
</form>-->

<?php

/*if (isset($_GET['confirm']))
{
    $insertSql = $db->prepare("INSERT INTO LeieAvRom VALUES (1, 1, '2015-03-12', :time, :hourcount);");
    $insertSql->bindParam(':time', $startTime, PDO::PARAM_STR);
    $insertSql->bindParam(':hourcount', $hourCount, PDO::PARAM_STR);
    $insertSql->execute();
}*/

/*$startTime = "";
$hourCount = 0;
if (isset($_POST['booking']))
{
    if (isset($_POST["timeinput"]))
    {
        $startTime = $_POST['timeinput'];
    }
    if (isset($_POST["hourinput"]))
    {
        $hourCount = $_POST['hourinput'];
    }

    $insertSql = $db->prepare("INSERT INTO LeieAvRom VALUES (1, 1, '2015-03-12', :time, :hourcount);");
    $insertSql->bindParam(':time', $startTime, PDO::PARAM_STR);
    $insertSql->bindParam(':hourcount', $hourCount, PDO::PARAM_STR);
    $insertSql->execute();
}*/