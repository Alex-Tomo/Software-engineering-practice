<?php

    require('../../db_connector.php');
    $conn = getConnection();

    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $id = isset($_POST['id']) ? $_POST['id'] : null; //
    $usersLastMessage = array();

    $statement = $conn->prepare("SELECT user_id FROM sep_users WHERE user_email = '{$email}'");
    $statement->execute();
    $result = $statement->fetchObject();
    $userId = $result->user_id;


    $statement = $conn->prepare("
        SELECT user_online 
        FROM sep_users JOIN sep_user_info
        ON sep_users.user_id = sep_user_info.user_id
        WHERE sep_user_info.user_id = {$id}");
    $statement->execute();
    $result = $statement->fetchObject();
    $usersLastMessage['online'] = $result->user_online;

    $statement = $conn->prepare("
        SELECT message, created_on
        FROM sep_messages 
        WHERE user_id = '{$userId}' 
        AND job_id = '{$id}' 
        ORDER BY created_on DESC 
        LIMIT 1
    ");
    $statement->execute();

    $i = 0;
    if($statement->rowCount() > 0) {
        while ($row = $statement->fetchObject()) {
            $usersLastMessage['message'] = $row->message;
            $usersLastMessage['created_on'] = $row->created_on;
            $i++;
        }
    } else {
        $usersLastMessage = null;
    }

    echo json_encode(array($usersLastMessage));

?>