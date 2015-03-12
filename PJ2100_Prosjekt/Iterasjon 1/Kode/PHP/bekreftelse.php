<?php
require 'config.php';

echo "<p>Time: " . $_POST['timeinput'] . "</p>";
echo "<p>Time: " . $_POST['hourinput'] . "</p>";
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