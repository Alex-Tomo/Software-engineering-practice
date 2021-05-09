<?php

    // Adds the enquiry message to the users messages and adds a new notification

    //Require
    require('../../db_connector.php');

    // get database connection
    $conn = getConnection();

    // get the job id, the message and the users email
    $jobId = isset($_POST['jobId']) ? trim($_POST['jobId']) : null;
    $desc = isset($_POST['desc']) ? trim($_POST['desc']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;

    $userDesc = array();

    try {

        // get the users id
        $selectUserDetailsStatement = $conn->prepare("
            SELECT sep_users.user_id, sep_user_info.user_fname, sep_user_info.user_lname
            FROM sep_users JOIN sep_user_info ON sep_users.user_id = sep_user_info.user_id
            WHERE user_email = ?");
        $selectUserDetailsStatement->bindParam(1, $email);
        if($selectUserDetailsStatement->execute()) {
            $selectUserDetailsResult = $selectUserDetailsStatement->fetchObject();
            $userId = $selectUserDetailsResult->user_id;

            // store details in an array to send to websockets
            $userDesc['jobId'] = $jobId;
            $userDesc['name'] = "{$selectUserDetailsResult->user_fname} {$selectUserDetailsResult->user_lname}";
            $userDesc['desc'] = $desc;
        }

        // get the other users id
        $selectOtherUserIdStatement = $conn->prepare("
            SELECT sep_users.user_id
            FROM sep_users JOIN sep_available_jobs
            ON sep_users.user_id = sep_available_jobs.user_id
            WHERE sep_available_jobs.job_id = ?
            GROUP BY sep_users.user_id");
        $selectOtherUserIdStatement->bindParam(1, $jobId);
        if($selectOtherUserIdStatement->execute()) {
            $selectOtherUserIdResult = $selectOtherUserIdStatement->fetchObject();
            $otherUserId = $selectOtherUserIdResult->user_id;
        }

        // check if the job has already been enquired before by the same user
        $selectNotificationIdStatement = $conn->prepare("SELECT notification_id FROM sep_notifications WHERE (user_id = ? AND job_id = ?)");
        $selectNotificationIdStatement->bindParam(1, $otherUserId);
        $selectNotificationIdStatement->bindParam(2, $jobId);
        $selectNotificationIdStatement->execute();

        if($selectNotificationIdStatement->rowCount() == 0) {

            // insert the enquiry into the users messages
            $insertMessageStatement = $conn->prepare("
                INSERT INTO sep_messages (user_id, other_user_id, job_id, message, created_on) VALUES
                (?, ?, ?, ?, now())");
            $insertMessageStatement->bindParam(1, $userId);
            $insertMessageStatement->bindParam(2, $otherUserId);
            $insertMessageStatement->bindParam(3, $jobId);
            $insertMessageStatement->bindParam(4, $desc);
            $insertMessageStatement->execute();

            // insert a new row into messages to show the user has an unread message
            $insertUnreadMessageStatement = $conn->prepare("INSERT INTO sep_read_messages (job_id, user_id) VALUES (?, ?), (?, ?)");
            $insertUnreadMessageStatement->bindParam(1, $jobId);
            $insertUnreadMessageStatement->bindParam(2, $userId);
            $insertUnreadMessageStatement->bindParam(3, $jobId);
            $insertUnreadMessageStatement->bindParam(4, $otherUserId);
            $insertUnreadMessageStatement->execute();

            // insert the enquiry so the user gets a new notification
            $insertNotificationStatement = $conn->prepare("
                INSERT INTO sep_notifications (job_id, user_id, notification_message, sent_on) VALUES
                (?, ?, ?, now())");
            $insertNotificationStatement->bindParam(1, $jobId);
            $insertNotificationStatement->bindParam(2, $userId);
            $insertNotificationStatement->bindParam(3, $desc);

            if($insertNotificationStatement->execute()) {
                // Select the job title and user_id to send to the websockets
                $selectJobDetailsStatement = $conn->prepare("SELECT job_title, user_id FROM sep_available_jobs WHERE job_id = ?");
                $selectJobDetailsStatement->bindParam(1, $jobId);
                $selectJobDetailsStatement->execute();
                $selectJobDetailsResult = $selectJobDetailsStatement->fetchObject();

                // insert details into array to send to the websockets
                $userDesc['jobTitle'] = $selectJobDetailsResult->job_title;
                $userDesc['userId'] = $selectJobDetailsResult->user_id;
                $userDesc['dt'] = date('Y-m-d H:i:s');

                echo json_encode($userDesc);
            }
        }
    } catch(Exception $e) {
        logError($e);
    }

?>