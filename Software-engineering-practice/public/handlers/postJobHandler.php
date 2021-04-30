<?php

    // TODO: Error handling and validate the info
    // TODO: Dynamically get the path of the image

    // Require
    require('../../pageTemplate.php');
    require('../../db_connector.php');

    // Get the database connection
    $conn = getConnection();

    // Get the user info when the form is submitted
    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $desc = isset($_POST['desc']) ? trim($_POST['desc']) : null;
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;
    $categoryIds = isset($_POST['categoryIds']) ? $_POST['categoryIds'] : null;
    // Get the user/job IDs
    $userId = getUserId($conn);
    $jobId = getJobId($conn);


    if(!empty($title) && !empty($desc) && !empty($price) && !empty($categoryIds) && !empty($userId) && !empty($jobId)) {


        $renamedImage = moveAndRenameImage($conn, $jobId);
        if($renamedImage) {
            $insertAvailableJobs = $conn->query("INSERT INTO sep_available_jobs
                  (job_id, user_id, job_title, job_desc, job_price, job_availability, job_date, job_image)
                  VALUES ('{$jobId}', '{$userId}', '{$title}', '{$desc}', '{$price}', TRUE, now(), '{$renamedImage}')");


            $insertSimilarCategoriesQuery = "INSERT INTO sep_jobs_categories VALUES ";
            for ($i = 0; $i < sizeof($categoryIds); $i++) {
                if ($i == sizeof($categoryIds) - 1) {
                    $insertSimilarCategoriesQuery .= "('$jobId', '$categoryIds[$i]')";
                } else {
                    $insertSimilarCategoriesQuery .= "('$jobId', '$categoryIds[$i]'),";
                }
            }
            $conn->query($insertSimilarCategoriesQuery);
        }
        // Refresh the page
        header("Location: ../postJob.php");

    } // End of if statement

    function getUserId($connection) {
        $result = $connection->query("SELECT user_id FROM sep_users WHERE user_email = '{$_SESSION['email']}'");
        if($result) {
            $row = $result->fetchObject();
            return $row->user_id;
        }
    }

    function getJobId($connection) {
        $result = $connection->query("SELECT MAX(job_id)+1 AS jobId FROM sep_available_jobs");
        if($result) {
            $row = $result->fetchObject();
            return $row->job_id;
        }
    }

    function moveAndRenameImage($connection, $jobId) {
        $path = '';
        $arr = explode("/", __DIR__);
        foreach ($arr as $a) {
            $path .= $a.'/';
            if ($a == 'public') {
                break;
            }
        }

        $targetDir = $path."assets/job_images/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $targetFile = $targetDir . $_FILES['image']['name'] = 'image_'.$jobId.'.'.$imageFileType;

        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            $uploadOk = 0;
            // file is not an image
            echo "File is not an image";
        }

        if ($_FILES['image']['size'] > 500000) {
            $uploadOk = 0;
            // files too big
            echo "File is too big";
        }

        if ($uploadOk == 0) {
            // Files not uploaded
            echo "File was not uploaded";
        } else {
            // If the image was successfully uploaded then return the image name to update the database
            if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                echo "File uploaded";
                return $_FILES['image']['name'] = 'image_'.$jobId.'.'.$imageFileType;
            }
        }
        return null;
    }
?>