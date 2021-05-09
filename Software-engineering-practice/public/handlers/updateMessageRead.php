<?php

    // Updates the database to mark the messages as read when the user clicks on a message

    // Require
    require('../../db_connector.php');

    // get the database connection
    $conn = getConnection();

    try {

        // get the variables from the form
        $jobId = isset($_POST['jobId']) ? $_POST['jobId'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;

        // get the user_id from the database using the users email
        $selectUserIdStatement = $conn->prepare("SELECT user_id FROM sep_users WHERE user_email = ?");
        $selectUserIdStatement->bindParam(1, $email);
        $selectUserIdStatement->execute();
        $selectUserIdResult = $selectUserIdStatement->fetchObject();
        $userId = $selectUserIdResult->user_id;

        // update the messages to show they have been read
        $updateMessageReadStatement = $conn->prepare("UPDATE sep_read_messages SET message_read = TRUE WHERE job_id = ? AND user_id = ?");
        $updateMessageReadStatement->bindParam(1, $jobId);
        $updateMessageReadStatement->bindParam(2, $userId);
        $updateMessageReadStatement->execute();

    } catch(Exception $e) {
        logError($e);
    }

?>