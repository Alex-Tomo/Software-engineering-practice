<?php

    // TODO: Error handling and validate the info
    // TODO: rename and overwrite the image - Might not be possible (Leave if needed)
    // TODO: Dynamically get the path of the image

    // Requires
    require('../../pageTemplate.php');
    require('../../db_connector.php');

    // Get database connection
    $conn = getConnection();

    // Get all data when the form is submitted
    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $desc = isset($_POST['desc']) ? trim($_POST['desc']) : null;
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;
    $categoryIds = isset($_POST['categoryIds']) ? $_POST['categoryIds'] : null;
    $jobId = isset($_POST['jobId']) ? $_POST['jobId'] : null;

if(!empty($title) && !empty($desc) && !empty($price) && !empty($categoryIds) && !empty($jobId)) {

    //  If the $_FILE is set then process the image and update the database
    // Otherwise do not update the database with the new image name
    if(isset($_FILES['image']['name'])) {
        $renamedImage = moveAndRenameImage($jobId);
        if ($renamedImage) {
            $insertAvailableJobs = $conn->query("UPDATE sep_available_jobs
                  SET job_title = '{$title}', job_desc = '{$desc}', job_price = '{$price}', job_image = '{$renamedImage}'
                  WHERE job_id = '{$jobId}'");
        }
    } else {
        $insertAvailableJobs = $conn->query("UPDATE sep_available_jobs
              SET job_title = '{$title}', job_desc = '{$desc}', job_price = '{$price}'
              WHERE job_id = '{$jobId}'");
    }

    // Delete all the categories then insert the new categories
    $deleteSimilarCategories = $conn->query("DELETE FROM sep_jobs_categories WHERE job_id = '{$jobId}'");
    $insertSimilarCategoriesQuery = "INSERT INTO sep_jobs_categories VALUES ";
    for ($i = 0; $i < sizeof($categoryIds); $i++) {
        if ($i == sizeof($categoryIds) - 1) {
            $insertSimilarCategoriesQuery .= "('$jobId', '$categoryIds[$i]')";
        } else {
            $insertSimilarCategoriesQuery .= "('$jobId', '$categoryIds[$i]'),";
        }
    }
    $conn->query($insertSimilarCategoriesQuery);

    // When finished refresh the page
    header("Location: ../userJobs.php");

} // End of if statement

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