<?php

    require('../../db_connector.php');
    $conn = getConnection();

    $jobId = isset($_POST['jobId']) ? trim($_POST['jobId']) : null;
    $desc = isset($_POST['desc']) ? trim($_POST['desc']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;

    $userDesc = array();

    try {
        // other users id
        $statement = $conn->prepare("
            SELECT sep_users.user_id, sep_user_info.user_fname, sep_user_info.user_lname
            FROM sep_users JOIN sep_user_info ON sep_users.user_id = sep_user_info.user_id
            WHERE user_email = '{$email}'
        ");
        $statement->execute();
        $result = $statement->fetchObject();
        $userId = $result->user_id;
        $userDesc['jobId'] = $jobId;
        $userDesc['name'] = "{$result->user_fname} {$result->user_lname}";
        $userDesc['desc'] = $desc;

        // my user id
        $statement = $conn->prepare("
            SELECT sep_users.user_id
            FROM sep_users JOIN sep_available_jobs
            ON sep_users.user_id = sep_available_jobs.user_id
            WHERE sep_available_jobs.job_id = {$jobId}
            GROUP BY sep_users.user_id
        ");
        $statement->execute();
        $result = $statement->fetchObject();
        $otherUserId = $result->user_id;


        $statement = $conn->prepare("
            SELECT notification_id FROM sep_notifications WHERE (user_id = {$otherUserId} AND job_id = {$jobId})
        ");
        $statement->execute();
        if ($statement->rowCount() == 0) {
            $statement = $conn->prepare("
                INSERT INTO sep_messages (user_id, other_user_id, job_id, message, created_on) VALUES
                (?, ?, ?, ?, now())
            ");
            $statement->bindParam(1, $userId);
            $statement->bindParam(2, $otherUserId);
            $statement->bindParam(3, $jobId);
            $statement->bindParam(4, $desc);
            $statement->execute();

            $statement = $conn->prepare("
                INSERT INTO sep_notifications (job_id, user_id, notification_message, sent_on) VALUES
                (?, ?, ?, now())
            ");
            $statement->bindParam(1, $jobId);
            $statement->bindParam(2, $userId);
            $statement->bindParam(3, $desc);
            if ($statement->execute()) {
                $statement = $conn->prepare("
            SELECT job_title, user_id FROM sep_available_jobs WHERE job_id = {$jobId}
        ");
                $statement->execute();
                $result = $statement->fetchObject();
                $userDesc['jobTitle'] = $result->job_title;
                $userDesc['userId'] = $result->user_id;
                $userDesc['dt'] = date('Y-m-d H:i:s');

                echo json_encode($userDesc);
            }
        }
    } catch(Exception $e) { logError($e); }

?>