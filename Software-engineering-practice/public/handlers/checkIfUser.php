<?php

    // check if user exists and how many unread notifications

    // Requires
    require('../../db_connector.php');

    // Get database connection
    $conn = getConnection();

    // get the users information
    $id = isset($_POST['id']) ? trim($_POST['id']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $jobId = isset($_POST['jobId']) ? trim($_POST['jobId']) : null;


    // select everything from sep users if the user exists
    $checkUserExistsStatement = $conn->prepare("SELECT * FROM sep_users WHERE user_id = ? AND user_email = ?");
    $checkUserExistsStatement->bindParam(1, $id);
    $checkUserExistsStatement->bindParam(2, $email);
    $checkUserExistsStatement->execute();

    // 1 if the user exists, 0 if not
    if($checkUserExistsStatement->rowCount() > 0) {

        try {
            $countRowsStatement = $conn->prepare("
                SELECT COUNT(*) as count
                FROM sep_notifications
                JOIN sep_available_jobs
                ON sep_notifications.job_id = sep_available_jobs.job_id
                JOIN sep_users
                ON sep_available_jobs.user_id = sep_users.user_id
                WHERE sep_notifications.job_id = ?
                AND sep_users.user_id = ?
                AND sep_notifications.notification_read = FALSE
                GROUP BY sep_notifications.notification_id
                ORDER BY sep_notifications.sent_on DESC
                LIMIT 5");
            $countRowsStatement->bindParam(1, $jobId);
            $countRowsStatement->bindParam(2, $id);
            $countRowsStatement->execute();
            $countRowsResult = $countRowsStatement->fetchObject();

            echo $countRowsResult->count;

        } catch(Exception $e) {

            logError($e);

        }
    }

?>