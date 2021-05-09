<?php

    // Update the notifications to be read when the user clicks the notification icon

    // Require
    require('../../db_connector.php');

    // Get database connection
    $conn = getConnection();

    try {

        // Get the users email address and notifications
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $notifications = isset($_POST['notifications']) ? $_POST['notifications'] : null;
        $notificationsMessages = isset($_POST['notificationsMessages']) ? $_POST['notificationsMessages'] : null;

        // Select the current users id using the email address
        $selectUserIdStatement = $conn->prepare("SELECT user_id FROM sep_users WHERE user_email = ?");
        $selectUserIdStatement->bindParam(1, $email);
        $selectUserIdStatement->execute();
        $selectUserIdResult = $selectUserIdStatement->fetchObject();
        $userId = $selectUserIdResult->user_id;


        $i = 0;
        foreach ($notifications as $notification) {

            // for each notification get the corresponding job id
            $selectJobIdStatement = $conn->prepare("
                SELECT sep_available_jobs.job_id 
                FROM sep_available_jobs 
                JOIN sep_notifications
                ON sep_available_jobs.job_id = sep_notifications.job_id
                WHERE sep_available_jobs.job_title = ?
                AND sep_notifications.notification_message = ?");
            $selectJobIdStatement->bindParam(1, $notification);
            $selectJobIdStatement->bindParam(2, $notificationsMessages[$i]);
            $selectJobIdStatement->execute();

            $selectJobIdResult = $selectJobIdStatement->fetchObject();
            $jobId = $selectJobIdResult->job_id;

            // using the notifications job id update the database to show the notification has been read
            $updateNotificationsStatement = $conn->prepare("UPDATE sep_notifications SET notification_read = true WHERE job_id = ?");
            $updateNotificationsStatement->bindParam(1, $jobId);
            $updateNotificationsStatement->execute();

            $i++;
        }

        echo $jobId;

    } catch (Exception $e) {
        logError($e);
    }

?>