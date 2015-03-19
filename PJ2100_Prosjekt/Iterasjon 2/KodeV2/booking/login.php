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
$_SESSION['user'] = $username;

// Redirect to booking
header("Location: index.php");
die();

// Redirects to main with badlogin flag
function redirect()
{
    header("Location: ../index.php?badlogin");
    die();
}