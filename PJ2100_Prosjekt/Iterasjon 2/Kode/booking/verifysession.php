<?php
$db = new DB();
// Used to verify if a user is logged in

// Redirect to main
if (!$db->isValidSession())
{
    header("Location: ../index.php?badlogin");
    die();
}