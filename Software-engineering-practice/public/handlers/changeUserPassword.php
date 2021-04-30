<?php

    // TODO: sanitize the data

    // Require
    require('../../db_connector.php');

    // Get the database connection
    $conn = getConnection();

    // Get the details when the form is submitted
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $oldPassword = isset($_POST['oldPassword']) ? trim($_POST['oldPassword']) : null;
    $newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : null;
    $repeatNewPassword = isset($_POST['repeatNewPassword']) ? trim($_POST['repeatNewPassword']) : null;

    // Change the password
    if($newPassword == $repeatNewPassword) {
        $statement = $conn->prepare("SELECT user_password FROM sep_users WHERE user_email = ?");
        $statement->execute(array($email));
        if ($statement->rowCount() > 0) {
            $data = $statement->fetch();
            if (password_verify($oldPassword, $data['user_password'])) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $statement = $conn->prepare("UPDATE sep_users SET user_password = ? WHERE user_email = ?");
                $statement->bindParam(1, $hashedPassword);
                $statement->bindParam(2, $email);
                $statement->execute();

                // return (via AJAX) true if the password is changed and false if not
                echo password_verify($oldPassword, $data['user_password']) ? 'true' : 'false';
            }
        }
    }

?>
