<?php

    // changes the users password in the database

    // Require
    require('../../db_connector.php');
    require('../../database_functions.php');

    // Get the database connection
    $conn = getConnection();

    // Get the details when the form is submitted
    $email = isset($_POST['email']) ? sanitizeData($_POST['email']) : null;
    $oldPassword = isset($_POST['oldPassword']) ? sanitizeData(trim($_POST['oldPassword'])) : null;
    $newPassword = isset($_POST['newPassword']) ? sanitizeData(trim($_POST['newPassword'])) : null;
    $repeatNewPassword = isset($_POST['repeatNewPassword']) ? sanitizeData(trim($_POST['repeatNewPassword'])) : null;

    // Error handling
    if($newPassword != $repeatNewPassword) {
        echo 'false';
    } else if(empty($email) || empty($oldPassword) || empty($newPassword) || empty($repeatNewPassword)) {
        echo 'false';
    } else if(strlen($newPassword) < 8 || strlen($repeatNewPassword) < 8) {
        echo 'false';

        // if no errors
    } else {

        // get the users password from the database using the users email
        $selectPasswordStatement = $conn->prepare("SELECT user_password FROM sep_users WHERE user_email = ?");
        $selectPasswordStatement->bindParam(1, $email);
        $selectPasswordStatement->execute();

        // if the users email/password exists
        if($selectPasswordStatement->rowCount() > 0) {
            $data = $selectPasswordStatement->fetch();

            // verify if the passwords match
            if(password_verify($oldPassword, $data['user_password'])) {

                // hash the new password and update the database to reflect this new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePasswordStatement = $conn->prepare("UPDATE sep_users SET user_password = ? WHERE user_email = ?");
                $updatePasswordStatement->bindParam(1, $hashedPassword);
                $updatePasswordStatement->bindParam(2, $email);
                $updatePasswordStatement->execute();

                // return (via AJAX) true if the password is changed and false if not
                echo 'true';

            } else {
                echo 'false';
            }
        } else {
            echo 'false';
        }
    }

?>
