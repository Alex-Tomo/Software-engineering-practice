<?php

    //Get number of unread notifications

    // Require
    require('../../db_connector.php');
    $conn = getConnection();

    $jobId = isset($_POST['jobId']) ? $_POST['jobId'] : null;
    $jobDesc = isset($_POST['jobDesc']) ? $_POST['jobDesc'] : null;
    $jobTitle = isset($_POST['jobTitle']) ? $_POST['jobTitle'] : null;

    $statement = $conn->prepare("
        
    ");

?>