<?php

class DB {

    private $db = null;

    // SETTINGS
    private $username = "root";
    private $password = "";
    private $host = "localhost";
    private $database = "GruppeRomBooking";

    function __construct()
    {
        $this->db = new PDO("mysql:host=$this->host;dbname=$this->database;charset=utf8", $this->username, $this->password);
    }

    public function rentRoom($romId, $brukerId, $date, $startTime, $hours)
    {
        $statement = $this->db->prepare("INSERT INTO LeieAvRom VALUES (:romId, :brukerId, :date, :startTime, :hours),");
        $statement->bindParam(':romId', $romId, PDO::PARAM_STR);
        $statement->bindParam(':brukerId', $brukerId, PDO::PARAM_STR);
        $statement->bindParam(':date', $date, PDO::PARAM_STR);
        $statement->bindParam(':startTime', $startTime, PDO::PARAM_STR);
        $statement->bindParam(':hours', $hours, PDO::PARAM_STR);

        $statement->execute();
    }

    public function persistField($persist, $data)
    {
        if ($persist)
        {
            echo 'value="' . $data . '"';
        }
    }
}