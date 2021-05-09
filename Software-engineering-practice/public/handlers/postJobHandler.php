<?php

    // inserts the new job details to the database
    // renames and moves the image to the correct location

    // Require
    require('../../pageTemplate.php');
    require('../../db_connector.php');
    require('../../database_functions.php');

    // Get the database connection
    $conn = getConnection();

    // Get the user info when the form is submitted
    $title = isset($_POST['title']) ? sanitizeData(trim($_POST['title'])) : null;
    $desc = isset($_POST['desc']) ? sanitizeData(trim($_POST['desc'])) : null;
    $price = isset($_POST['price']) ? sanitizeData(trim($_POST['price'])) : null;
    $categoryIds = isset($_POST['categoryIds']) ? sanitizeData($_POST['categoryIds']) : null;
    // Get the user/job IDs
    $userId = getUserId($conn);
    $jobId = getJobId($conn);

    // executes if all the data except the image and categoryIds are not empty
    if(!empty($title) && !empty($desc) && !empty($price) && !empty($userId) && !empty($jobId)) { // start of outer if statement - 1

        // Moves the image to the job_images folder and renames the image
        $renamedImage = moveAndRenameImage($jobId);
        if ($renamedImage) { // start of inner if statement - 2

            // if the image is successfully moved and renamed
            $insertJobDetailsStatement = $conn->prepare("
            INSERT INTO sep_available_jobs (job_id, user_id, job_title, job_desc, job_price, job_availability, job_date, job_image)
            VALUES (?, ?, ?, ?, ?, TRUE, now(), ?)");
            $insertJobDetailsStatement->bindParam(1, $jobId);
            $insertJobDetailsStatement->bindParam(2, $userId);
            $insertJobDetailsStatement->bindParam(3, $title);
            $insertJobDetailsStatement->bindParam(4, $desc);
            $insertJobDetailsStatement->bindParam(5, $price);
            $insertJobDetailsStatement->bindParam(6, $renamedImage);
            $insertJobDetailsStatement->execute();

            // If the user has chosen categories then insert these categories
            // use a for loop to add them dynamically
            if (!empty($categoryIds)) { // start of if statement - 3

                $insertSimilarCategoriesQuery = "INSERT INTO sep_jobs_categories VALUES ";
                for ($i = 0; $i < sizeof($categoryIds); $i++) {
                    if ($i == sizeof($categoryIds) - 1) {
                        $insertSimilarCategoriesQuery .= "(?, ?)";
                    } else {
                        $insertSimilarCategoriesQuery .= "(?, ?),";
                    }
                }

                $statement = $conn->prepare($insertSimilarCategoriesQuery);
                $j = 1;

                for ($i = 0; $i < sizeof($categoryIds); $i++) {
                    echo $categoryIds[$i];
                    $statement->bindParam($j, $jobId);
                    $statement->bindParam(($j + 1), $categoryIds[$i]);
                    $j += 2;
                }

                $statement->execute();
            } // end of inner if statement 3
        } // end of inner if statement - 2
    } // End of outer if statement - 1

    // Refresh the page
    header("Location: ../postJob.php");

    // get the users id
    function getUserId($connection) {
        $getUserIdStatement = $connection->prepare("SELECT user_id FROM sep_users WHERE user_email = ?");
        $getUserIdStatement->bindParam(1, $_SESSION['email']);
        if($getUserIdStatement->execute()) {
            $row = $getUserIdStatement->fetchObject();
            return sanitizeData($row->user_id);
        }
    }

    //get the new jobs job id
    function getJobId($connection) {
        $getJobIdStatement = $connection->prepare("SELECT MAX(job_id)+1 AS jobId FROM sep_available_jobs");
        if($getJobIdStatement->execute()) {
            $row = $getJobIdStatement->fetchObject();
            return sanitizeData($row->jobId);
        }
    }

    function moveAndRenameImage($jobId) {
        // get the path to the public folder
        $path = '';
        $arr = explode("\\", __DIR__);
        foreach ($arr as $a) {
            $path .= $a.'\\';
            if ($a == 'public') {
                break;
            }
        }

        // create the path to the target directory
        $targetDir = $path."\assets\job_images\\";
        $targetFile = $targetDir . basename($_FILES['file']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // check is the file is actually an image
        $check = getimagesize($_FILES['file']['tmp_name']);
        if ($check === false) {
            $uploadOk = 0;
            // file is not an image
            echo "File is not an image";
        }

        // check if the file if less than 500000
        if ($_FILES['file']['size'] > 500000) {
            $uploadOk = 0;
            // files too big
            echo "File is too big";
        }

        if ($uploadOk == 0) {
            // Files not uploaded
            echo "File was not uploaded";
        } else {
            // rename the file
            $newImage = 'image_'.$jobId.'.'.$imageFileType;
            // If the image was successfully uploaded then return the image name to update the database
            if(move_uploaded_file($_FILES['file']['tmp_name'], $targetDir.$newImage)) {
                echo "File uploaded";
                return $_FILES['file']['name'] = 'image_'.$jobId.'.'.$imageFileType;
            }
        }
        return null;
    }

?>