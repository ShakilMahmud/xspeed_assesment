<?php

class DBConn
{
    private $conn;

    function connect_development_database()
    {
        include_once __DIR__ . '/Constants.php';

        @$this->conn = new mysqli(DEV_DB_HOST, DEV_DB_USER, DEV_DB_PASSWORD, DEV_DB_NAME);

        if ($this->conn->connect_error) {
            die("Database (" . DEV_DB_HOST . ") Connection Failed: " . $this->conn->connect_error);
            return null;
        }
        return $this->conn;
    }

}
