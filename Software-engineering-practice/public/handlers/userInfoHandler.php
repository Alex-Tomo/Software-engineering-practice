<?php

    // inserts the users information into the database

    // Requires
    require "../../db_connector.php";
    require('../../database_functions.php');

    // get database connection
    $conn = getConnection();

    // Get all the variable when the form is submitted
    $email = isset($_POST['email']) ? sanitizeData(trim($_POST['email'])) : null;
    $firstname = isset($_POST['firstname']) ? sanitizeData(trim($_POST['firstname'])) : null;
    $lastname = isset($_POST['lastname']) ? sanitizeData(trim($_POST['lastname'])) : null;
    $gender = isset($_POST['gender']) ? sanitizeData(trim($_POST['gender'])) : null;
    $language = isset($_POST['language']) ? sanitizeData(trim($_POST['language'])) : null;
    $region = isset($_POST['region']) ? sanitizeData(trim($_POST['region'])) : null;
    $jobsArray = isset($_POST['jobsArray']) ? $_POST['jobsArray'] : null;
    $userId = null;

    // Sanitize the data
    if(!empty($jobsArray)) {
        $jobsArray = filter_var_array($jobsArray, FILTER_SANITIZE_NUMBER_INT);
    }

    // if the email is not empty, then select the current users id
    if(!empty($email)) {
        $selectUserIdStatement = $conn->prepare("SELECT user_id FROM sep_users WHERE user_email = ?");
        $selectUserIdStatement->bindParam(1, $email);
        $selectUserIdStatement->execute();
        $selectUserIdResult = $selectUserIdStatement->fetchObject();
        $userId = $selectUserIdResult->user_id;
        $userId = validateData($userId);
    }

    // if the users details are not empty, then insert them into the database
    if(!empty($firstname) && !empty($lastname) && !empty($gender) && !empty($language) && !empty($region)) {
        $insertUsersDetailsStatement = $conn->prepare("INSERT INTO sep_user_info (user_id, user_fname, user_lname, user_gender, user_language, user_region)
        VALUES (?, ?, ?, ?, ?, ?)");
        $insertUsersDetailsStatement->bindParam(1, $userId, PDO::PARAM_INT);
        $insertUsersDetailsStatement->bindParam(2, $firstname);
        $insertUsersDetailsStatement->bindParam(3, $lastname);
        $insertUsersDetailsStatement->bindParam(4, $gender);
        $insertUsersDetailsStatement->bindParam(5, $language);
        $insertUsersDetailsStatement->bindParam(6, $region);
        $insertUsersDetailsStatement->execute();
    }

    if(!empty($jobsArray)) {
        foreach ($jobsArray as $jobCode) {
            // if jobs array is not empty, insert the jobs into the database
            $insertInterestedJobsStatement = $conn->prepare("INSERT INTO sep_users_interested_jobs (user_id, job_code) VALUES (?, ?)");
            $insertInterestedJobsStatement->bindParam(1, $userId, PDO::PARAM_INT);
            $insertInterestedJobsStatement->bindParam(2, $jobCode, PDO::PARAM_INT);
            $insertInterestedJobsStatement->execute();
        }
    }

?>