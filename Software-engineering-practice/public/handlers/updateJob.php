<?php

    // Requires
    require('../../pageTemplate.php');
    require('../../db_connector.php');
    require('../../database_functions.php');

    // Get database connection
    $conn = getConnection();

    // Get all data when the form is submitted
    $title = isset($_POST['title']) ? sanitizeData(trim($_POST['title'])) : null;
    $desc = isset($_POST['desc']) ? sanitizeData(trim($_POST['desc'])) : null;
    $price = isset($_POST['price']) ? sanitizeData(trim($_POST['price'])) : null;
    $categoryIds = isset($_POST['categoryIds']) ? sanitizeData($_POST['categoryIds']) : null;
    $jobId = isset($_POST['jobId']) ? sanitizeData($_POST['jobId']) : null;

    try {

        // if the variables are not empty
        if (!empty($title) && !empty($desc) && !empty($price) && !empty($jobId)) {

            //  If the $_FILE is set then process the image and update the database
            // Otherwise do not update the database with the new image name
            if (!empty($_FILES['image']['name'])) {

                // rename the image and move into the jobs_images folder
                $renamedImage = moveAndRenameImage($jobId);
                if ($renamedImage) {
                    // update the users details with the new details and a new image
                    $updateUsersDetailsStatement = $conn->prepare("
                        UPDATE sep_available_jobs
                        SET job_title = ?, job_desc = ?, job_price = ?, job_image = ?
                        WHERE job_id = ?");
                    $updateUsersDetailsStatement->bindParam(1, $title);
                    $updateUsersDetailsStatement->bindParam(2, $desc);
                    $updateUsersDetailsStatement->bindParam(3, $price);
                    $updateUsersDetailsStatement->bindParam(4, $renamedImage);
                    $updateUsersDetailsStatement->bindParam(5, $jobId);
                    $updateUsersDetailsStatement->execute();

                }
            } else {
                // update the users details with the new details without a new image
                $updateUsersDetailsStatement = $conn->prepare("
                    UPDATE sep_available_jobs
                    SET job_title = ?, job_desc = ?, job_price = ?
                    WHERE job_id = ?");
                $updateUsersDetailsStatement->bindParam(1, $title);
                $updateUsersDetailsStatement->bindParam(2, $desc);
                $updateUsersDetailsStatement->bindParam(3, $price);
                $updateUsersDetailsStatement->bindParam(4, $jobId);
                $updateUsersDetailsStatement->execute();

            }

            // if the user selected new categories
            if (!empty($categoryIds)) {
                try {

                    // Delete all the categories then insert the new categories
                    $deleteSimilarCategories = $conn->prepare("DELETE FROM sep_jobs_categories WHERE job_id = ?");
                    $deleteSimilarCategories->bindParam(1, $jobId);
                    $deleteSimilarCategories->execute();

                    // insert the new categories dynamically using a for loop
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
                    for ($i = 1; $i < sizeof($categoryIds) + 1; $i++) {
                        echo $categoryIds[$i - 1];
                        $statement->bindParam($j, $jobId);
                        $statement->bindParam(($j + 1), $categoryIds[$i - 1]);
                        $j += 2;
                    }
                    $statement->execute();

                } catch (Exception $e) {
                    logError($e);
                }
            }
            // refresh the page
            header("Location: ../userJobs.php");
        }
        // refresh the page
        header("Location: ../userJobs.php");

    } catch(Exception $e) { logError($e);}


function moveAndRenameImage($job_id) {
    // dynamically locate the public folder
    $path = '';
    $arr = explode("\\", __DIR__);
    foreach ($arr as $a) {
        $path .= $a.'\\';
        if ($a == 'public') {
            break;
        }
    }

    // get the path to the target directory
    $target_dir = $path."assets\job_images\\";
    $target_file = $target_dir . basename($_FILES['image']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // check if the file is actually an image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        $uploadOk = 0;
        // file is not an image
        echo "File is not an image";
    }

    // check if the file size is less than 500000
    if ($_FILES['image']['size'] > 500000) {
        $uploadOk = 0;
        // files too big
        echo "File is too big";
    }

    if ($uploadOk == 0) {
        // Files not uploaded
        echo "File was not uploaded";
    } else {
        // Randomly rename the image by adding 10 random numbers to the end of the new file name
        $newImage = 'image_'.$job_id;
        for($i = 0; $i < 10; $i++) {
            $newImage .= rand(1, 9);
        }
        $newImage .= '.'.$imageFileType;

        // If the image is successfully moves then return the image name to update the database
        if(move_uploaded_file($_FILES['image']['tmp_name'], $target_dir.$newImage)) {
            echo "File uploaded";
            return $_FILES['image']['name'] = $newImage;
        }
    }
}

?>