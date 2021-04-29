<?php
    require('../../pageTemplate.php');
    require('../../db_connector.php');
    $conn = getConnection();

    // TODO Error handling and validate the info

    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $desc = isset($_POST['desc']) ? trim($_POST['desc']) : null;
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;
    $categoryIds = isset($_POST['categoryIds']) ? $_POST['categoryIds'] : null;
    $user_id = getUserId($conn);
    $job_id = getJobId($conn);

    if(!empty($title) && !empty($desc) && !empty($price) && !empty($categoryIds) && !empty($user_id) && !empty($job_id)) {

        $renamedImage = moveAndRenameImage($conn, $job_id);
        if($renamedImage) {
            $insertAvailableJobs = $conn->query("INSERT INTO sep_available_jobs
                  (job_id, user_id, job_title, job_desc, job_price, job_availability, job_date, job_image)
                  VALUES ('{$job_id}', '{$user_id}', '{$title}', '{$desc}', '{$price}', TRUE, now(), '{$renamedImage}')");

            // Get the last id rather than number of rows
            $insertSimilarCategoriesQuery = "INSERT INTO sep_jobs_categories VALUES ";
            for ($i = 0; $i < sizeof($categoryIds); $i++) {
                if ($i == sizeof($categoryIds) - 1) {
                    $insertSimilarCategoriesQuery .= "('$job_id', '$categoryIds[$i]')";
                } else {
                    $insertSimilarCategoriesQuery .= "('$job_id', '$categoryIds[$i]'),";
                }
            }
            $conn->query($insertSimilarCategoriesQuery);
        }
        header("Location: ../postJob.php");
    }

    function getUserId($connection) {
        $result = $connection->query("SELECT user_id FROM sep_users WHERE user_email = '{$_SESSION['email']}'");
        if($result) {
            $row = $result->fetchObject();
            return $row->user_id;
        }
    }

    function getJobId($connection) {
        $result = $connection->query("SELECT MAX(job_id)+1 AS job_id FROM sep_available_jobs");
        if($result) {
            $row = $result->fetchObject();
            return $row->job_id;
        }
    }

    function moveAndRenameImage($connection, $job_id) {
        $path = '';
        $arr = explode("/", __DIR__);
        foreach ($arr as $a) {
            $path .= $a.'/';
            if ($a == 'public') {
                break;
            }
        }

        $target_dir = $path."assets/job_images/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $target_file = $target_dir . $_FILES['image']['name'] = 'image_'.$job_id.'.'.$imageFileType;

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
            if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                echo "File uploaded";
                return $_FILES['image']['name'] = 'image_'.$job_id.'.'.$imageFileType;
            }
        }
    }
?>