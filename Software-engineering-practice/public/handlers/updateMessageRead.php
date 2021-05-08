<?php

require('../../db_connector.php');
$conn = getConnection();

try {
    $jobId = isset($_POST['jobId']) ? $_POST['jobId'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    $statement = $conn->prepare("SELECT user_id FROM sep_users WHERE user_email = '{$email}'");
    $statement->execute();
    $result = $statement->fetchObject();
    $userId = $result->user_id;

    $statement = $conn->prepare("UPDATE sep_read_messages SET message_read = TRUE WHERE job_id = {$jobId} AND user_id = {$userId}");
    $statement->execute();

    echo 'message read';
} catch(Exception $e) { logError($e); }

?>