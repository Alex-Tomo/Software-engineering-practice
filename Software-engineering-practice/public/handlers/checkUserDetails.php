<?php

    // Require
    require('../../db_connector.php');

    // Get the database connection
    $conn = getConnection();

    // Get the email when the form is submitted
    $email = isset($_POST['email']) ? (trim($_POST['email'])) : null;

    // if the user has entered details then return any result
    if(!empty($email)) {
        $statement = $conn->prepare("
            SELECT sep_user_info.userinfo_id
            FROM sep_user_info JOIN sep_users
            ON sep_user_info.user_id = sep_users.user_id
            WHERE sep_users.user_email = ?");
        $statement->execute(array($email));
        if($statement->rowCount() > 0) {
            echo 'true';
        }
    }

?>