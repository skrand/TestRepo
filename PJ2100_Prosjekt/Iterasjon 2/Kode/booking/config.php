<?php
session_start();

class DB
{
    // Databaseforbindelse
    public $database = null;

    // Database instillinger
    /*private $dbHost = "127.0.0.1";
    private $dbName = "GruppeRomBooking";
    private $dbUser = "root";
    private $dbPass = "";*/
    private $dbHost = "tordtroen.com.mysql";
    private $dbName = "tordtroen_com";
    private $dbUser = "tordtroen_com";
    private $dbPass = "3wzQyGsm";

    // Konstruktør
    function __construct()
    {
        $this->database = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
    }

    // Verifiser om loginen er godkjent
    public function verifyLogin($username, $password)
    {
        $sql = $this->database->prepare("SELECT Passord FROM Bruker WHERE Brukernavn LIKE :username;");
        $sql->setFetchMode(PDO::FETCH_OBJ);
        $sql->execute(array(
            'username' => $username
        ));

        // Check if the password matches the password in the database
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        if (!password_verify($password, $result['Passord']))
        {
            header("Location: ../index.php?badlogin");
            die();
        }
    }

    // Gir brukerid til et gitt brukernavn
    public function getUserIdFromName($username)
    {
        $sql = $this->database->prepare("SELECT * FROM Bruker WHERE Brukernavn LIKE :username;");
        $sql->setFetchMode(PDO::FETCH_OBJ);
        $sql->execute(array(
            'username' => $username
        ));

        // Check if the password matches the password in the database
        $userid = (int)$sql->fetch(PDO::FETCH_ASSOC)['BrukerId'];
        return $userid;
    }

    // Gir bruker rad fra databasen til en gitt brukerid
    public function getUserFromId($id)
    {
        $sql = $this->database->prepare("SELECT * FROM Bruker WHERE BrukerId LIKE :brukerid;");
        $sql->setFetchMode(PDO::FETCH_OBJ);
        $sql->execute(array(
            'brukerid' => $id
        ));

        $results = $sql->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    // Sjekker om sesjonen er valid
    public function isValidSession()
    {
        if (!isset($_SESSION['user'])) return false;

        if (strlen($_SESSION['user']) <= 0) return false;

        // Remember to return something if none of the above is true... or else the function returns NULL...
        return true;
    }

    // Sjekker om brukernavnet finnes i database
    public function usernameIsUnique($username)
    {
        $sql = $this->database->prepare("SELECT COUNT(*) FROM Bruker WHERE Brukernavn LIKE :username;");
        $sql->setFetchMode(PDO::FETCH_OBJ);
        $sql->execute(array(
            'username' => $username
        ));
        $count = (int)$sql->fetch(PDO::FETCH_NUM)[0];
        return $count === 0;
    }
}