<?php

    // Get the latest messages from each of the users which have messaged
    // the currently logged in user

    // Require
    require('../../db_connector.php');

    // Get database connection
    $conn = getConnection();

    // get the users email address and the other users id
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $otherUsersId = isset($_POST['id']) ? $_POST['id'] : null; //
    $usersLastMessage = array();

    // get the current users id
    $selectUserIdStatement = $conn->prepare("SELECT user_id FROM sep_users WHERE user_email = ?");
    $selectUserIdStatement->bindParam(1, $email);
    $selectUserIdStatement->execute();
    $selectUserIdResult = $selectUserIdStatement->fetchObject();
    $userId = $selectUserIdResult->user_id;

    // checks if the user is online or not and returns the value
    $checkUserOnlineStatement = $conn->prepare("
        SELECT user_online 
        FROM sep_users 
        JOIN sep_user_info
        ON sep_users.user_id = sep_user_info.user_id
        WHERE sep_user_info.user_id = ?");
    $checkUserOnlineStatement->bindParam(1, $otherUsersId);
    $checkUserOnlineStatement->execute();
    $checkUserOnlineResult = $checkUserOnlineStatement->fetchObject();
    $usersLastMessage['online'] = $checkUserOnlineResult->user_online;

    // Gets the users last message and when it was created
    $getMessageDetailsStatement = $conn->prepare("
        SELECT message, created_on
        FROM sep_messages 
        WHERE user_id = ?
        AND job_id = ? 
        ORDER BY created_on DESC 
        LIMIT 1");
    $getMessageDetailsStatement->bindParam(1, $userId);
    $getMessageDetailsStatement->bindParam(2, $otherUsersId);
    $getMessageDetailsStatement->execute();

    // Gets the lastest messages and the data and stores them in an array;
    if($getMessageDetailsStatement->rowCount() > 0) {
        while($row = $getMessageDetailsStatement->fetchObject()) {
            $usersLastMessage['message'] = $row->message;
            $usersLastMessage['created_on'] = $row->created_on;
        }
    } else {
        $usersLastMessage = null;
    }

    echo json_encode(array($usersLastMessage));

?>