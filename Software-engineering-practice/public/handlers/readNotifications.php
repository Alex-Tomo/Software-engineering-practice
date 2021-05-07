<?php

require('../../db_connector.php');
$conn = getConnection();

try {

    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $notifications = isset($_POST['notifications']) ? $_POST['notifications'] : null;
    $notificationsMessages = isset($_POST['notificationsMessages']) ? $_POST['notificationsMessages'] : null;

    $statement = $conn->prepare("SELECT user_id FROM sep_users WHERE user_email = '{$email}'");
    $statement->execute();
    $result = $statement->fetchObject();
    $userId = $result->user_id;


    $i = 0;
    foreach ($notifications as $notification) {
        $statement = $conn->prepare("
            SELECT sep_available_jobs.job_id 
            FROM sep_available_jobs 
            JOIN sep_notifications
            ON sep_available_jobs.job_id = sep_notifications.job_id
            WHERE sep_available_jobs.job_title = '{$notification}'
            AND sep_notifications.notification_message = '{$notificationsMessages[$i]}'
        ");
        $i++;
        $statement->execute();
        echo 'here';
        $result = $statement->fetchObject();
        $jobId = $result->job_id;

        $statement = $conn->prepare("UPDATE sep_notifications SET notification_read = true WHERE job_id = {$jobId}");
        $statement->execute();

        echo $jobId;
    }

    echo $jobId;
} catch (Exception $e) { logError($e); }
