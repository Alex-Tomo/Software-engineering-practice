<?php

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

    if (!empty($title) && !empty($desc) && !empty($price) && !empty($userId) && !empty($jobId)) {

        $renamedImage = moveAndRenameImage($jobId);
        if ($renamedImage) {

            $statement = $conn->prepare("
            INSERT INTO sep_available_jobs (job_id, user_id, job_title, job_desc, job_price, job_availability, job_date, job_image)
            VALUES (?, ?, ?, ?, ?, TRUE, now(), ?)");
            $statement->bindParam(1, $jobId);
            $statement->bindParam(2, $userId);
            $statement->bindParam(3, $title);
            $statement->bindParam(4, $desc);
            $statement->bindParam(5, $price);
            $statement->bindParam(6, $renamedImage);
            $statement->execute();


            if (!empty($categoryIds)) {
                $insertSimilarCategoriesQuery = "INSERT INTO sep_jobs_categories VALUES ";
                for ($i = 0; $i < sizeof($categoryIds)-1; $i++) {
                    if ($i == sizeof($categoryIds) - 2) {
                        $insertSimilarCategoriesQuery .= "(?, ?)";
                    } else {
                        $insertSimilarCategoriesQuery .= "(?, ?),";
                    }
                }
                echo $insertSimilarCategoriesQuery;
                $statement = $conn->prepare($insertSimilarCategoriesQuery);
                $j = 1;
                for ($i = 1; $i < sizeof($categoryIds); $i++) {
                    echo $categoryIds[$i-1];
                    $statement->bindParam($j, $jobId);
                    $statement->bindParam(($j + 1), $categoryIds[$i-1]);
                    $j += 2;
                }
                $statement->execute();
            }
        }
    } // End of if statement

    // Refresh the page
    header("Location: ../postJob.php");

    function getUserId($connection) {
        $result = $connection->query("SELECT user_id FROM sep_users WHERE user_email = '{$_SESSION['email']}'");
        if($result) {
            $row = $result->fetchObject();
            return sanitizeData($row->user_id);
        }
    }

    function getJobId($connection) {
        $result = $connection->query("SELECT MAX(job_id)+1 AS jobId FROM sep_available_jobs");
        if($result) {
            $row = $result->fetchObject();
            return sanitizeData($row->jobId);
        }
    }

    function moveAndRenameImage($jobId) {
        $path = '';
        $arr = explode("\\", __DIR__);
        foreach ($arr as $a) {
            $path .= $a.'\\';
            if ($a == 'public') {
                break;
            }
        }

        $targetDir = $path."\assets\job_images\\";
        $targetFile = $targetDir . basename($_FILES['file']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES['file']['tmp_name']);
        if ($check === false) {
            $uploadOk = 0;
            // file is not an image
            echo "File is not an image";
        }

        if ($_FILES['file']['size'] > 500000) {
            $uploadOk = 0;
            // files too big
            echo "File is too big";
        }

        if ($uploadOk == 0) {
            // Files not uploaded
            echo "File was not uploaded";
        } else {
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