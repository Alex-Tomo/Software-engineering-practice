<?php

    // Require
    require("../../db_connector.php");
    require('../../database_functions.php');

    // Get database connection
    $conn = getConnection();

    // Get data when the form is submitted
    $email = isset($_POST['email']) ? sanitizeData(trim($_POST['email'])): null;
    $firstname = isset($_POST['firstname']) ? sanitizeData(trim($_POST['firstname'])) : null;
    $lastname = isset($_POST['lastname']) ? sanitizeData(trim($_POST['lastname'])) : null;
    $gender = isset($_POST['gender']) ? sanitizeData(trim($_POST['gender'])) : null;
    $language = isset($_POST['language']) ? sanitizeData(trim($_POST['language'])) : null;
    $region = isset($_POST['region']) ? sanitizeData(trim($_POST['region'])) : null;
    $jobsArray = isset($_POST['jobsArray']) ? $_POST['jobsArray'] : null;
    $userId = null;

    if(!empty($jobsArray)) {
        $jobsArray = filter_var_array($jobsArray, FILTER_SANITIZE_NUMBER_INT);
    }

    if (!empty($email)) {
        $statement = $conn->prepare("SELECT user_id FROM sep_users WHERE user_email = ?");
        $statement->execute(array($email));
        $userId = $statement->fetchObject();
        $userId = $userId->user_id;
        $userId = validateData($userId);
    }

    if (!empty($firstname) && !empty($lastname) && !empty($gender) && !empty($language) && !empty($region)) {
        $statement = $conn->prepare("UPDATE sep_user_info SET 
        user_fname = ?, 
        user_lname = ?, 
        user_gender = ?,
        user_language = ?,
        user_region = ?
    WHERE user_id = ?");
        $statement->bindParam(1, $firstname);
        $statement->bindParam(2, $lastname);
        $statement->bindParam(3, $gender);
        $statement->bindParam(4, $language);
        $statement->bindParam(5, $region);
        $statement->bindParam(6, $userId, PDO::PARAM_INT);
        $statement->execute();
    }

    $statement = $conn->prepare("SELECT * FROM sep_users_interested_jobs WHERE user_id = ?");
    $statement->bindParam(1, $userId, PDO::PARAM_INT);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $statement = $conn->prepare("DELETE FROM sep_users_interested_jobs WHERE user_id = ?");
        $statement->bindParam(1, $userId, PDO::PARAM_INT);
        $statement->execute();
    }

    if (!empty($jobsArray)) {
        foreach ($jobsArray as $jobCode) {
            $statement = $conn->prepare("INSERT INTO sep_users_interested_jobs (user_id, job_code) VALUES (?, ?)");
            $statement->bindParam(1, $userId, PDO::PARAM_INT);
            $statement->bindParam(2, $jobCode, PDO::PARAM_INT);
            $statement->execute();
        }
    }

?>