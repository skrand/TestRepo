<?php
require 'config.php';

// Check if username and password is set
if (!isset($_POST['username'])) redirectToMain(true);
if (!isset($_POST['password'])) redirectToMain(true);

$username = $_POST['username'];
$password = $_POST['password'];

// TODO Verify if username is unique
if (usernameIsUnique($username, $db) === false)
{
    $_GET['usernametaken'] = "true";
    header("Location: register.php?usernametaken");
    die();
}

$passhash = password_hash($password, PASSWORD_DEFAULT);
// Check if username is unique
$sql = $db->prepare("INSERT INTO Bruker VALUES ('NULL', :user, :pass, 'em@il.com');");
$sql->execute(array(
    'user' => $username,
    'pass' => $passhash
));

// Go back to index
// Redirect to booking
header("Location: index.php");
die();