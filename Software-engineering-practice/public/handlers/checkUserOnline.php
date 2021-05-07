<?php

require('../../db_connector.php');
$conn = getConnection();

$id = isset($_POST['target_id']) ? $_POST['target_id'] : null;

$statement = $conn->prepare("
    SELECT user_online 
    FROM sep_users
    WHERE sep_users.user_id = {$id}");
$statement->execute();
$result = $statement->fetchObject();
if($result->user_online == 1) {
    echo "online";
}

?>