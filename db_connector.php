<?php
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'software_engineering';

    $conn = new mysqli($server, $username, $password, $dbname);

    if($conn->connect_error) {
        echo 'Could not connect: ' . $conn->connect_error;
    } else {
        echo 'Connected';
    }
?>