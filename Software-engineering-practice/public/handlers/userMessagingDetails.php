<?php

    require('../../db_connector.php');

    $conn = getConnection();

    $targetUserId = isset($_POST['id']) ? trim($_POST['id']) : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    $targetUserName = '';
    $userId = -1;
    $messages = [];

    $statement = $conn->prepare("
        SELECT user_fname, user_lname FROM sep_user_info WHERE user_id = {$targetUserId}
    ");
    $statement->execute();
    $result = $statement->fetchObject();
    $targetUserName = "{$result->user_fname} {$result->user_lname}";


    $statement = $conn->prepare("
        SELECT user_id FROM sep_users WHERE user_email = '{$email}'
    ");
    $statement->execute();
    $result = $statement->fetchObject();
    $userId = $result->user_id;

    $statement = $conn->prepare("
        SELECT user_id, target_user_id, message, created_on
        FROM sep_messages 
        WHERE (user_id = {$userId} AND target_user_id = {$targetUserId})
        OR (user_id = {$targetUserId} AND target_user_id = {$userId})
        ORDER BY created_on ASC
    ");
    $statement->execute();
    $i = 0;
    if($statement->rowCount() > 0) {
        while ($row = $statement->fetchObject()) {
            $messages[$i]['userId'] = $row->user_id;
            $messages[$i]['targetUserId'] = $row->target_user_id;
            $messages[$i]['messages'] = $row->message;
            $messages[$i]['createdOn'] = $row->created_on;
            $i++;
        }
    } else {
        $messages = null;
    }

    echo json_encode(array($targetUserName, $messages));

?>