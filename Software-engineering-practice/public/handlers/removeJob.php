<?php

    // Require
    require('../../db_connector.php');
    require('../../database_functions.php');

    // Get database connection
    $conn = getConnection();

    // Get jobId when user submits the form
    $jobId = isset($_POST['jobId']) ? sanitizeData($_POST['jobId']) : null;

    if(!empty($jobId)) {
        $conn->query("
            UPDATE sep_available_jobs SET job_availability = FALSE WHERE job_id = {$jobId} 
        ");
    }

?>