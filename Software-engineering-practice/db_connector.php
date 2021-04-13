<?php
    function getConnection() {
        $serverName = 'localhost';
        $username = 'root';
        $password = '';
        $databaseName = 'software_engineering';

        try {
            $conn = new PDO("mysql:host=$serverName;dbname=$databaseName",
                $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $ex) {
            echo 'Connection failed: ' . $ex->getMessage();
        }
    }
?>