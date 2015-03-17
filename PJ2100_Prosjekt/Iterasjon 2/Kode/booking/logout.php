<?php
require 'config.php';

// Unser the session
$_SESSION['user'] = "";
unset($_SESSION['user']);

// Go back to the main page
header("Location: ../");
die();