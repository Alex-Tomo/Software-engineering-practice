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

if(!empty($title) && !empty($desc) && !empty($price) && !empty($jobId)) {

    //  If the $_FILE is set then process the image and update the database
    // Otherwise do not update the database with the new image name
    if(!empty($_FILES['image']['name'])) {
        $renamedImage = moveAndRenameImage($jobId);
        if ($renamedImage) {
            echo "here6";

            $statement = $conn->prepare("
                UPDATE sep_available_jobs
                SET job_title = ?, job_desc = ?, job_price = ?, job_image = ?
                WHERE job_id = ?");
            $statement->bindParam(1, $title);
            $statement->bindParam(2, $desc);
            $statement->bindParam(3, $price);
            $statement->bindParam(4, $renamedImage);
            $statement->bindParam(5, $jobId);
            $statement->execute();
            echo "here";

        }
    } else {
        echo "here23";

        $statement = $conn->prepare("
            UPDATE sep_available_jobs
            SET job_title = ?, job_desc = ?, job_price = ?
            WHERE job_id = ?");
        $statement->bindParam(1, $title);
        $statement->bindParam(2, $desc);
        $statement->bindParam(3, $price);
        $statement->bindParam(4, $jobId);
        $statement->execute();
        echo "here2";

    }

    echo "here";


    if(!empty($categoryIds)) {
        // Delete all the categories then insert the new categories
        $deleteSimilarCategories = $conn->prepare("DELETE FROM sep_jobs_categories WHERE job_id = ?");
        $deleteSimilarCategories->bindParam(1, $jobId);
        $deleteSimilarCategories->execute();

        $insertSimilarCategoriesQuery = "INSERT INTO sep_jobs_categories VALUES ";
        for ($i = 0; $i < sizeof($categoryIds); $i++) {
            if ($i == sizeof($categoryIds) - 1) {
                $insertSimilarCategoriesQuery .= "('$jobId', '$categoryIds[$i]')";
            } else {
                $insertSimilarCategoriesQuery .= "('$jobId', '$categoryIds[$i]'),";
            }
        }

        $statement = $conn->prepare($insertSimilarCategoriesQuery);
        $j = 1;
        for ($i = 1; $i < sizeof($categoryIds); $i++) {
            $statement->bindParam($j, $jobId);
            $statement->bindParam(($j + 1), $categoryIds[$i]);
            $j += 2;
        }
        $statement->execute();
    }
    echo "here";

    header("Location: ../userJobs.php");

} else { // End of if statement
    // When finished refresh the page
    header("Location: ../userJobs.php");
}
header("Location: ../userJobs.php");


function moveAndRenameImage($job_id) {
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
        // Randomly rename the image
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