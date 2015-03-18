<?php
// Used to verify if a user is logged in

// Redirect to main
if (!isValidSession())
{
    header("Location: ../index.php?badlogin");
    die();
}