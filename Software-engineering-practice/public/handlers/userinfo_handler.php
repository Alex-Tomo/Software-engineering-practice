<?php
    require "../../db_connector.php";
    $conn = getConnection();

    $email = $_POST['email'];

    $userIdResult = $conn->query("SELECT user_id FROM users WHERE user_email = '$email'");
    $userId = $userIdResult->fetchObject();

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $language = $_POST['language'];
    $region = $_POST['region'];

    $query = "INSERT INTO user_info (user_id, user_fname, user_lname, user_gender, user_language, user_region)
        VALUES ('$userId->user_id', '$firstname', '$lastname', '$gender', '$language', '$region')";
    $result = $conn->query($query);

?>