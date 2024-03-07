<?php

class DB
{
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = '';

    public function connect(): PDO
    {
        try {
            $pdo = new PDO("mysql:host=$this->hostname;dbname=rofia", $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            
            return $pdo;
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}
