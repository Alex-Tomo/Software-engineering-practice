<?php

    // updates the users profile details

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

    // sanitize the job array
    if(!empty($jobsArray)) {
        $jobsArray = filter_var_array($jobsArray, FILTER_SANITIZE_NUMBER_INT);
    }

    // if the email is not empty
    if (!empty($email)) {
        // get the user id from the database
        $selectUserIdStatement = $conn->prepare("SELECT user_id FROM sep_users WHERE user_email = ?");
        $selectUserIdStatement->execute(array($email));
        $selectUserIdResult = $selectUserIdStatement->fetchObject();
        $userId = $selectUserIdResult->user_id;
        $userId = validateData($userId);
    }

    // if the users details are not empty
    if (!empty($firstname) && !empty($lastname) && !empty($gender) && !empty($language) && !empty($region)) {
        // update the users details in the database
        $updateUsersDetailsStatement = $conn->prepare("
            UPDATE sep_user_info SET 
                user_fname = ?, 
                user_lname = ?, 
                user_gender = ?,
                user_language = ?,
                user_region = ?
            WHERE user_id = ?");
        $updateUsersDetailsStatement->bindParam(1, $firstname);
        $updateUsersDetailsStatement->bindParam(2, $lastname);
        $updateUsersDetailsStatement->bindParam(3, $gender);
        $updateUsersDetailsStatement->bindParam(4, $language);
        $updateUsersDetailsStatement->bindParam(5, $region);
        $updateUsersDetailsStatement->bindParam(6, $userId);
        $updateUsersDetailsStatement->execute();
    }

    // Select all the users interest job categories and delete the categories, then insert the new categories

    $selectUsersInterestedJobsStatement = $conn->prepare("SELECT * FROM sep_users_interested_jobs WHERE user_id = ?");
    $selectUsersInterestedJobsStatement->bindParam(1, $userId, PDO::PARAM_INT);
    $selectUsersInterestedJobsStatement->execute();
    if ($selectUsersInterestedJobsStatement->rowCount() > 0) {
        // delete the jobs categories
        $deleteUsersInterestedJobsStatement = $conn->prepare("DELETE FROM sep_users_interested_jobs WHERE user_id = ?");
        $deleteUsersInterestedJobsStatement->bindParam(1, $userId, PDO::PARAM_INT);
        $deleteUsersInterestedJobsStatement->execute();
    }

    if(!empty($jobsArray)) {
        foreach ($jobsArray as $jobCode) {
            // insert the new job categories
            $insertUsersInterestedJobsStatement = $conn->prepare("INSERT INTO sep_users_interested_jobs (user_id, job_code) VALUES (?, ?)");
            $insertUsersInterestedJobsStatement->bindParam(1, $userId, PDO::PARAM_INT);
            $insertUsersInterestedJobsStatement->bindParam(2, $jobCode, PDO::PARAM_INT);
            $insertUsersInterestedJobsStatement->execute();
        }
    }

?>