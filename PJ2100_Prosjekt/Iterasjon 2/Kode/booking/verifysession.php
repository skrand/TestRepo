<?php
//require 'config.php';

if (!isValidSession())
{
    $_SESSION['badlogin'] = "badlogin";
    header("Location: ../index.php?badlogin");
    die();
}