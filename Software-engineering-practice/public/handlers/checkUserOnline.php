<?php

    // Checks if the user is online in the database (True value)

    // Require
    require('../../db_connector.php');

    // get database connection
    $conn = getConnection();

    // get the id of the targeted used
    $targetUserId = isset($_POST['target_id']) ? $_POST['target_id'] : null;

    // Get the online status
    $checkIfOnlineStatement = $conn->prepare("SELECT user_online FROM sep_users WHERE sep_users.user_id = ?");
    $checkIfOnlineStatement->bindParam(1, $targetUserId);
    $checkIfOnlineStatement->execute();
    $result = $checkIfOnlineStatement->fetchObject();

    // if the value is 1 (True) return 'online'
    if($result->user_online == 1) {
        echo "online";
    }

?>