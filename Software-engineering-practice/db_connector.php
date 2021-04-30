<?php

    // TODO create a logged and log the connection failed error

    function getConnection() {
        // Newnumyspace data
        $serverName = 'localhost';
        $username = 'unn_w19007452';
        $password = 'Password';
        $databaseName = 'unn_w19007452';

        // Localhost (xampp)
        // $serverName = '';
        // $username = 'root';
        // $password = '';
        // $databaseName = 'software_engineering';
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