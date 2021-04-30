<?php

    // TODO: remove the sql and put in a separate file

    // Requires
    require('../pageTemplate.php');
    require('../db_connector.php');
    require('../database_functions.php');

    // If the user is not logged in, then redirect the user to the login page
    if(!$_SESSION['loggedin']) { header('Location: signin.php'); }

    // Initial Variables, get database connection, get page template class
    $conn = getConnection();
    $page = new pageTemplate('Logged In Home');

    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/myJobs.js\"></script>");
    $page->addJavaScript("<script src=\"./js/selectPostJobsList.js\"></script>");

    $page->addPageBodyItem("
    <div class='pageContainer' style='width: 100%; text-align: center;'>
        <h1>My Job(s)</h1>
        <div class='popup' id='popup-3' style='display: none;'>
            <div class='overlay'></div>
                <form id='updateJobForm' style='position: relative;' enctype='multipart/form-data' method='post' action='./handlers/updateJob.php'>
                    <button id='closePopup'>Close Window</button>
                    <input name='jobId' id='jobId' type='text' style='margin-bottom: 20px; display: none;'>
                    <input name='title' id='title' type='text' placeholder='Job Title...' style='margin-bottom: 20px;'>
                    <textarea rows='10' cols='40' name='desc' id='desc' placeholder='Job Description...'></textarea>
                    <input name='price' id='price' type='text' placeholder='Job Price... i.e. £12.99/h' style='margin: 20px auto;'>
                    <input id='image' name='image' type='file' value='Add Image'>
                    <img id='jobImage' src=''>
                    <input id='jobsListInput' list='searchJobsList' placeholder='Job Category(s)' style='border: 1px solid #017EFC; margin-top: 20px; border-radius: 10px; padding: 10px;' onkeyup='filterJobsList()' onchange='selectJob()'>
                    <datalist id='searchJobsList'>
                        <option name='0'>Job Category(s)</option>");

// Get all jobs from the database
list($jobCodes, $jobNames) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
for($jobIndex = 0; $jobIndex < sizeof($jobCodes); $jobIndex++) {
    $page->addPageBodyItem("<option value='{$jobNames[$jobIndex]}' id='{$jobNames[$jobIndex]}' name='{$jobCodes[$jobIndex]}'>");
}

                    $page->addPageBodyItem("
                    </datalist>
                    <div id='suggestion'></div>
                    <button id='updateJobBtn' class='clickable' type='submit'>Update Job</button> 
                </form>
            </div>
        </div>");


// Get the users ID
$userId = $conn->query("SELECT user_id FROM sep_users WHERE user_email = '{$_SESSION['email']}'")->fetchColumn();

// Get all the users jobs
$result = $conn->query("
                SELECT sep_user_info.user_id, 
                    sep_user_info.user_fname, 
                    sep_user_info.user_lname,
                    sep_available_jobs.job_id,
                    sep_available_jobs.job_title,
                    sep_available_jobs.job_desc,
                    sep_available_jobs.job_price,
                    sep_available_jobs.job_date,
                     sep_available_jobs.job_image                       
                FROM sep_user_info
                INNER JOIN sep_available_jobs
                ON sep_user_info.user_id = sep_available_jobs.user_id
                WHERE sep_available_jobs.job_availability = '1'
                AND sep_available_jobs.user_id = '{$userId}'
                ORDER BY sep_available_jobs.job_date DESC");

if($result) {
    while($row = $result->fetchObject()) {
        $price = $row->job_price;

        $page->addPageBodyItem("
        <div class='resultChild clickable' onclick='openPage(`serviceInner.php?id={$row->job_id}`)'>
            <div class='topImg'>
                <img src='assets/job_images/{$row->job_image}'>
            </div>
            <div class='resultText'>
                <img class='personIcon' src='assets/person.svg'>
                <h2>{$row->user_fname} {$row->user_lname}</h2>
                <h3>{$row->job_title}</h3>
                <p>{$row->job_desc}</p>");

// get star rating for each job
list($sum, $total) = getStarRating($conn, $row->job_id);
for($i = 0; $i < 5; $i++) {

            if($i < $sum) {
                $page->addPageBodyItem("<span class='fa fa-star checked'></span>");
            } else {
                $page->addPageBodyItem("<span class='fa fa-star'></span>");
            }

} // end of for loop

                $page->addPageBodyItem("
                ({$total})<p class='price'>£{$price}/h</p>
            </div>
            <button type='button' id='$row->job_id' class='removeJob'>Delete Job</button>
            <button type='button' id='$row->job_id' class='editJob'>Edit Job</button>
        </div>");

    } // end of while loop
} // end of if($result)

    $page->addPageBodyItem("
    </div>");

    $page->displayPage();

?>
