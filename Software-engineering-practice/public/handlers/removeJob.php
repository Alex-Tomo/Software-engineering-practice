<?php

    // Sets the job availability to 0 when the user deletes the job

    // Require
    require('../../db_connector.php');
    require('../../database_functions.php');

    // Get database connection
    $conn = getConnection();

    // Get jobId when user submits the form
    $jobId = isset($_POST['jobId']) ? sanitizeData($_POST['jobId']) : null;

    // if the job id is set then update the job to be unavailable
    if(!empty($jobId)) {

        $statement = $conn->prepare("UPDATE sep_available_jobs SET job_availability = FALSE WHERE job_id = ?");
        $statement->bindParam(1, $jobId);
        $statement->execute();

    }

?>