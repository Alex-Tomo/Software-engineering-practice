<?php

    // TODO: Sanitize the data

    // Require
    require('../../db_connector.php');

    // Get database connection
    $conn = getConnection();

    // Get jobId when user submits the form
    $jobId = isset($_POST['jobId']) ? $_POST['jobId'] : null;

    if(!empty($jobId)) {
        $removeJob = $conn->query("
            UPDATE sep_available_jobs SET job_availability = FALSE WHERE job_id = {$jobId} 
        ");
    }

?>