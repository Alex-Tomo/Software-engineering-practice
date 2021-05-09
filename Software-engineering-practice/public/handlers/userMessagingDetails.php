<?php

    // Gets the user being messaged(s) details

    // Requires
    require('../../pageTemplate.php');
    require('../../db_connector.php');

    // get database connection
    $conn = getConnection();

    // Get the users id and the current users email
    $otherUserId = isset($_POST['id']) ? trim($_POST['id']) : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    $targetUserName = '';
    $userId = -1;
    $messages = [];

    // get the users name and corresponding image (if any)
    $selectUserDetailsStatement = $conn->prepare("SELECT user_fname, user_lname, user_image FROM sep_user_info WHERE user_id = ?");
    $selectUserDetailsStatement->bindParam(1, $otherUserId);
    $selectUserDetailsStatement->execute();
    $selectUserDetailsResult = $selectUserDetailsStatement->fetchObject();
    $targetUserName = "{$selectUserDetailsResult->user_fname} {$selectUserDetailsResult->user_lname}";

    // if the user does not have an image use person.svg
    if($selectUserDetailsResult->user_image != null) {
        $targetUserImage = "assets/user_images/{$selectUserDetailsResult->user_image}";
    } else {
        $targetUserImage = "assets/person.svg";
    }

    // get the current users id
    $selectUserIdStatement = $conn->prepare("SELECT user_id FROM sep_users WHERE user_email = ?");
    $selectUserIdStatement->bindParam(1, $email);
    $selectUserIdStatement->execute();
    $selectUserIdResult = $selectUserIdStatement->fetchObject();
    $userId = $selectUserIdResult->user_id;

    // Get the messages sent between both users
    $selectSentMessagesStatement = $conn->prepare("
        SELECT user_id, other_user_id, message, created_on
        FROM sep_messages 
        WHERE (user_id = ? AND other_user_id = ?)
        OR (user_id = ? AND other_user_id = ?)
        ORDER BY created_on ASC");
    $selectSentMessagesStatement->bindParam(1, $userId);
    $selectSentMessagesStatement->bindParam(2, $otherUserId);
    $selectSentMessagesStatement->bindParam(3, $otherUserId);
    $selectSentMessagesStatement->bindParam(4, $userId);
    $selectSentMessagesStatement->execute();

    // if there are any messages, store them in a messages 2d array
    $i = 0;
    if($selectSentMessagesStatement->rowCount() > 0) {
        while ($row = $selectSentMessagesStatement->fetchObject()) {
            $messages[$i]['userId'] = $row->user_id;
            $messages[$i]['targetUserId'] = $row->other_user_id;
            $messages[$i]['messages'] = $row->message;
            $messages[$i]['createdOn'] = $row->created_on;
            $i++;
        }
    } else {
        $messages = null;
    }

    echo json_encode(array($targetUserName, $targetUserImage, $messages));

?>