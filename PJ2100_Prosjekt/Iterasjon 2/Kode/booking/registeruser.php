<?php
require 'config.php';

// Check if username and password is set
if (!isset($_POST['username'])) redirect();
if (!isset($_POST['password'])) redirect();

// Get the variables
$username = $_POST['username'];
$password = $_POST['password'];

// Verify that the username is unique
if (usernameIsUnique($username, $db) === false)
{
    // Redirect to registerpage with name taken flag
    header("Location: register.php?usernametaken");
    die();
}

// Hash password
$passhash = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user into the database
$sql = $db->prepare("INSERT INTO Bruker (Brukernavn, Passord) VALUES (:user, :pass);");
$sql->execute(array(
    'user' => $username,
    'pass' => $passhash
));

// Go back to index
header("Location: ../index.php?registersuccess");
die();

// Redirects to registerpage
function redirect()
{
    header("Location: register.php");
    die();
}