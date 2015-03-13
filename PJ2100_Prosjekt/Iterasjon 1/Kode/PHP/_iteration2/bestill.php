<?php
require 'config.php';

$startTime = "00:00";
$hourCount = 1;
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

header("Location: index.php");
die();