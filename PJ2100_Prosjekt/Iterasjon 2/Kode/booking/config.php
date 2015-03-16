<?php
session_start();

// Set opp forbindelse med databasen
//*
$db_host = "127.0.0.1";
$db_name = "GruppeRomBooking";
$db_user = "root";
$db_pass = "";
/*/

$db_host = "tordtroen.com.mysql";
$db_name = "tordtroen_com";
$db_user = "tordtroen_com";
$db_pass = "3wzQyGsm";
//*/

$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

function redirectToMain($showBadLogin)
{
    echo "Redirect: ";
    if ($showBadLogin)
    {
        echo "Badlogin";
        //die();
        $_SESSION['badlogin'] = "badlogin";
        header("Location: ../");
        die();
    }
    echo "Goodlogin";
    die();
    header("Location: ./");
    die();
}

function verifyLogin($username, $password, $db)
{
    $sql = $db->prepare("SELECT * FROM Bruker WHERE Brukernavn LIKE :username;");
    $sql->setFetchMode(PDO::FETCH_OBJ);
    $sql->execute(array(
        'username' => $username
    ));

    // Sjekk om passordet matcher brukernavnet
    // TODO Lagre som hash
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    $pass = $result['Passord'];
    if ($pass !== $password)
    {
        redirectToMain(true);
    }
}

function isValidSession()
{
    if (!isset($_SESSION['user'])) return false;

    if (strlen($_SESSION['user']) <= 0) return false;

    // Remember to return something if none of the above is true... or else the function returns NULL...PHYAY
    return true;
}

function usernameIsUnique($username, $db)
{
    return false;
}