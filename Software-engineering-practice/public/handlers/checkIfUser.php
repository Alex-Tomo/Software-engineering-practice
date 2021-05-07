<?php

    // check if user exists and how many unread notifications

    require('../../db_connector.php');
    $conn = getConnection();

    $id = isset($_POST['id']) ? trim($_POST['id']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $jobId = isset($_POST['jobId']) ? trim($_POST['jobId']) : null;

    $statement = $conn->prepare("
        SELECT * FROM sep_users WHERE user_id = {$id} AND user_email = '{$email}'
    ");
    $statement->execute();
    if($statement->rowCount() > 0) {

        try {
            $statement = $conn->prepare("
            SELECT COUNT(*) as count
            FROM sep_notifications
            JOIN sep_available_jobs
            ON sep_notifications.job_id = sep_available_jobs.job_id
            JOIN sep_users
            ON sep_available_jobs.user_id = sep_users.user_id
            WHERE sep_notifications.job_id = {$jobId}
            AND sep_users.user_id = {$id}
            AND sep_notifications.notification_read = FALSE
            GROUP BY sep_notifications.notification_id
            ORDER BY sep_notifications.sent_on DESC
            LIMIT 5
        ");
            $statement->execute();
            $result = $statement->fetchObject();

            echo $result->count;
        } catch(Exception $e) { logError($e); }
    }

?>