<?php

    // TODO: Put the SQL in a function

    // Require
    require('../../db_connector.php');

    // Get the database connection, create an empty array for chosen categories
    $conn = getConnection();
    $chosenCategories = array();


    // Get the jobId when the form is submitted
    $jobId = isset($_POST['jobId']) ? $_POST['jobId'] : null;

    $jobDetails = $conn->query("
        SELECT job_title, job_desc, job_price, job_image
        FROM sep_available_jobs 
        WHERE job_id = '{$jobId}' 
    ");

    if($jobDetails) {
        while($row = $jobDetails->fetchObject()) {
            $title = $row->job_title;
            $desc = $row->job_desc;
            $price = $row->job_price;
            $image = $row->job_image;
        }
    }

    $jobCategories = $conn->query("
        SELECT sep_jobs_categories.job_code
        FROM sep_jobs_categories
        JOIN sep_available_jobs
        ON sep_available_jobs.job_id = sep_jobs_categories.job_id
        WHERE sep_jobs_categories.job_id = '{$jobId}' 
    ");

    if($jobCategories) {
        while($row = $jobCategories->fetchObject()) {
            array_push($chosenCategories, $row->job_code);
        }
    }

    // return (via AJAX) the data as JSON
    echo json_encode(array($title, $desc, $price, $image, $chosenCategories));

?>