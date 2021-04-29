<?php

    require('../../db_connector.php');
    $conn = getConnection();

    $jobId = isset($_POST['jobId']) ? $_POST['jobId'] : null;

    $removeJob = $conn->query("
        UPDATE sep_available_jobs SET job_availability = FALSE WHERE job_id = {$jobId} 
    ");

?>