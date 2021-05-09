<?php

    // updates the newly sent messages into the database and adds a new unread message notification

    // Require
    require('../../pageTemplate.php');
    require('../../db_connector.php');

    // get database connection
    $conn = getConnection();

    // get the messages details
    $message = isset($_POST['message']) ? $_POST['message'] : null;
    $otherUserId = isset($_POST['otherUserId']) ? $_POST['otherUserId'] : null;
    $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
    $jobId = isset($_POST['jobId']) ? $_POST['jobId'] : null;
    $datetime = isset($_POST['datetime']) ? $_POST['datetime'] : null;

    // insert the new message into the database
    $insertNewMessageStatement = $conn->prepare("
        INSERT INTO sep_messages (user_id, other_user_id, job_id, message, created_on) VALUES (?, ?, ?, ?, ?)");
    $insertNewMessageStatement->bindParam(1, $userId);
    $insertNewMessageStatement->bindParam(2, $otherUserId);
    $insertNewMessageStatement->bindParam(3, $jobId);
    $insertNewMessageStatement->bindParam(4, $message);
    $insertNewMessageStatement->bindParam(5, $datetime);
    $insertNewMessageStatement->execute();

    // insert the new unread message into the database so the user can be notified
    $insertNewMessageNotificationStatement = $conn->prepare("
        INSERT INTO sep_read_messages (job_id, user_id) VALUES ({$jobId}, {$userId}), ({$jobId}, {$otherUserId})");
    $insertNewMessageNotificationStatement->bindParam(1, $jobId);
    $insertNewMessageNotificationStatement->bindParam(2, $userId);
    $insertNewMessageNotificationStatement->bindParam(3, $jobId);
    $insertNewMessageNotificationStatement->bindParam(4, $otherUserId);
    if($insertNewMessageNotificationStatement->execute()) {
        echo 'true';
    }

?>
