<?php
session_start();
// Set opp forbindelse med databasen
$db_host = "127.0.0.1";
$db_name = "GruppeRomBooking";
$db_user = "root";
$db_pass = "";

$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);