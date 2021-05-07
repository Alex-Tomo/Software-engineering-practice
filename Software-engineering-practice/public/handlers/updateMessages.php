<?php

require('../../pageTemplate.php');
require('../../db_connector.php');
$conn = getConnection();

$message = isset($_POST['message']) ? $_POST['message'] : null;
$otherUserId = isset($_POST['otherUserId']) ? $_POST['otherUserId'] : null;
$userId = isset($_POST['userId']) ? $_POST['userId'] : null;
$jobId = isset($_POST['jobId']) ? $_POST['jobId'] : null;
$datetime = isset($_POST['datetime']) ? $_POST['datetime'] : null;

$statement = $conn->prepare("
    INSERT INTO sep_messages (user_id, other_user_id, job_id, message, created_on) VALUES (?, ?, ?, ?, ?)
");
$statement->bindParam(1, $userId);
$statement->bindParam(2, $otherUserId);
$statement->bindParam(3, $jobId);
$statement->bindParam(4, $message);
$statement->bindParam(5, $datetime);
if($statement->execute()) {
    echo 'true';
}
