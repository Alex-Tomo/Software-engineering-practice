<?php
    function getConnection() {
        $serverName = 'localhost';
        $username = 'unn_w19007452';
        $password = 'Password';
        $databaseName = 'unn_w19007452';

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