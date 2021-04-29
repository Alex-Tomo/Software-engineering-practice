<?php
require('../../pageTemplate.php');
require('../../db_connector.php');
$conn = getConnection();

// TODO Error handling and validate the info
// TODO rename and overwrite the image

$title = isset($_POST['title']) ? trim($_POST['title']) : null;
$desc = isset($_POST['desc']) ? trim($_POST['desc']) : null;
$price = isset($_POST['price']) ? trim($_POST['price']) : null;
$categoryIds = isset($_POST['categoryIds']) ? $_POST['categoryIds'] : null;
$job_id = isset($_POST['jobId']) ? $_POST['jobId'] : null;

if(!empty($title) && !empty($desc) && !empty($price) && !empty($categoryIds) && !empty($job_id)) {

    if(!isset($_FILES['image']['name'])) {
        $renamedImage = moveAndRenameImage($conn, $job_id);
        if ($renamedImage) {
            $insertAvailableJobs = $conn->query("UPDATE sep_available_jobs
                  SET job_title = '{$title}', job_desc = '{$desc}', job_price = '{$price}', job_image = '{$renamedImage}'
                  WHERE job_id = '{$job_id}'");
        }
    } else {
        $insertAvailableJobs = $conn->query("UPDATE sep_available_jobs
              SET job_title = '{$title}', job_desc = '{$desc}', job_price = '{$price}'
              WHERE job_id = '{$job_id}'");
    }

    $deleteSimilarCategories = $conn->query("DELETE FROM sep_jobs_categories WHERE job_id = '{$job_id}'");

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
    header("Location: ../userJobs.php");

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