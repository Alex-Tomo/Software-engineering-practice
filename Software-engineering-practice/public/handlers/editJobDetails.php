<?php

    // returns the jobs current details to be edited by the user

    // Require
    require('../../db_connector.php');
    require('../../database_functions.php');

    // Get the database connection, create an empty array for chosen categories
    $conn = getConnection();
    $chosenCategories = array();

    // Get the jobId when the form is submitted
    $jobId = isset($_POST['jobId']) ? sanitizeData($_POST['jobId']) : null;

    if(!empty($jobId)) {

        // select job details
        $jobDetailsStatement = $conn->prepare("
            SELECT job_title, job_desc, job_price, job_image
            FROM sep_available_jobs 
            WHERE job_id = ?");
        $jobDetailsStatement->bindParam(1, $jobId);

        if($jobDetailsStatement->execute()) {
            while($row = $jobDetailsStatement->fetchObject()) {
                $title = $row->job_title;
                $desc = $row->job_desc;
                $price = $row->job_price;
                $image = $row->job_image;
            }
        }

        // select job categories
        $jobCategoriesStatement = $conn->prepare("
            SELECT sep_jobs_categories.job_code
            FROM sep_jobs_categories
            JOIN sep_available_jobs
            ON sep_available_jobs.job_id = sep_jobs_categories.job_id
            WHERE sep_jobs_categories.job_id = ?");
        $jobCategoriesStatement->bindParam(1, $jobId);

        if($jobCategoriesStatement->execute()) {
            while($row = $jobCategoriesStatement->fetchObject()) {
                array_push($chosenCategories, $row->job_code);
            }
        }

        // return (via AJAX) the data as JSON
        echo json_encode(array($title, $desc, $price, $image, $chosenCategories));
    } else {
        echo 'false';
    }

?>