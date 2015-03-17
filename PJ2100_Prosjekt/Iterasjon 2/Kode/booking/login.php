<?php
require 'config.php';

// Check if username and password is set
if (!isset($_POST['password'])) redirect();
if (!isset($_POST['username'])) redirect();

// Verify the logininfo
$username = $_POST['username'];
$password = $_POST['password'];
verifyLogin($username, $password, $db);

// Set current session
// TODO Find more robust alternative
$_SESSION['user'] = $username;

// Redirect to booking
header("Location: index.php");
die();

function redirect()
{
    $_SESSION['badlogin'] = "badlogin";
    header("Location: ../index.php?badlogin");
    die();
}