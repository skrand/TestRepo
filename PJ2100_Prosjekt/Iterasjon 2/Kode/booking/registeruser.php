<?php
require 'config.php';

// Check if username and password is set
if (!isset($_POST['username'])) redirectToMain(true);
if (!isset($_POST['password'])) redirectToMain(true);

$username = $_POST['username'];
$password = $_POST['password'];

// TODO Verify if username is unique
if (!usernameIsUnique($username, $db))
{
    header("Location: registeruser.php");
    die();
}

// Check if username is unique
$sql = $db->prepare("INSERT INTO Bruker VALUES ('NULL', :user, :pass, 'em@il.com');");
$sql->execute(array(
    'user' => $username,
    'pass' => $password
));

// Go back to index
// Redirect to booking
header("Location: ./index.php");
die();