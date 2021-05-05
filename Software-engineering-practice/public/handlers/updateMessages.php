<?php

require('../../db_connector.php');
$conn = getConnection();

$message = isset($_POST['message']) ? $_POST['message'] : null;
$targetId = isset($_POST['targetId']) ? $_POST['targetId'] : null;
$userId = isset($_POST['userId']) ? $_POST['userId'] : null;
$datetime = isset($_POST['datetime']) ? $_POST['datetime'] : null;

$statement = $conn->prepare("
    INSERT INTO sep_messages (user_id, target_user_id, message, created_on) VALUES (?, ?, ?, ?)
");
$statement->bindParam(1, $userId);
$statement->bindParam(2, $targetId);
$statement->bindParam(3, $message);
$statement->bindParam(4, $datetime);
if($statement->execute()) {
    echo 'true';
}
