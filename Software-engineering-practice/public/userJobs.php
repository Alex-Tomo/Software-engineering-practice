<?php

    // TODO FIX THIS, ITS NOT UPLOADING THE JOB ANYMORE. AGAIN!

    // Requires
    require('../pageTemplate.php');
    require('../db_connector.php');
    require('../database_functions.php');

    // If the user is not logged in, then redirect the user to the login page
    if(!$_SESSION['loggedin']) { header('Location: signin.php'); }

    // Initial Variables, get database connection, get page template class
    $conn = getConnection();
    $page = new pageTemplate('My Job(s)');

    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/myJobs.js\"></script>");
    $page->addJavaScript("<script src=\"./js/selectPostJobsList.js\"></script>");
    $page->addJavaScript("<script src=\"./js/notificationServer.js\"></script>");

    $page->addPageBodyItem("
    <div class='pageContainer'>
        <h1 id='userJobHeader'>My Job(s)</h1>
        <div id='resultContainer'>
            <div class='popup' id='popup-3' style='display: none;'>
                <div id='overlay' class='clickable overlay'></div>
                    <form class='popupForm' id='updateJobForm' enctype='multipart/form-data' method='post' action='./handlers/updateJob.php'>
                        <button class='clickable backLink' id='closePopup'>Close Window</button>
                        <input name='jobId' id='jobId' type='text' style='display: none;'>
                        <input name='title' id='title' type='text' placeholder='Job Title...'>
                        <textarea rows='10' cols='40' name='desc' id='desc' placeholder='Job Description...'></textarea>
                        <input name='price' id='price' type='text' placeholder='Job Price... i.e. £12.99/h'>
                        <input id='image' name='image' type='file' value='Add Image'>
                        <img id='jobImage' src=''>
                        <input id='jobsListInput' list='searchJobsList' placeholder='Job Category(s)' onchange='selectJob()'>
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
                    <button id='updateJobBtn' class='clickable nextLink' type='submit'>Update Job</button> 
                </form>
            </div>");

// Gets all the users jobs
$jobsArray = getUsersJobs($conn, $_SESSION['email']);
foreach($jobsArray as $job) {

        $page->addPageBodyItem("
        <div id='userJob' class='resultChild'>
            <div class='topImg'>
                <img src='assets/job_images/{$job['jobImage']}'>
            </div>
            <div class='resultText'>
                <img class='personIcon' src='assets/{$job['userImage']}' style='border-radius: 25px'>
                <h2>{$job['userFname']} {$job['userLname']}</h2>
                <h3>{$job['jobName']}</h3>
                <p>{$job['jobDesc']}</p>");

// get star rating for each job
list($sum, $total) = getStarRating($conn, $job['jobId']);
for($i = 0; $i < 5; $i++) {

            if($i < $sum) {
                $page->addPageBodyItem("<span class='fa fa-star checked'></span>");
            } else {
                $page->addPageBodyItem("<span class='fa fa-star'></span>");
            }

} // end of for loop

                $page->addPageBodyItem("
                ({$total})<p class='price'>£{$job['jobPrice']}/h</p>
                <div id='btnContainer'>
                <button type='button' id='{$job['jobId']}' class='clickable removeJob jobEditBtn'>Delete Job</button>
                <button type='button' id='{$job['jobId']}' class='clickable editJob jobEditBtn'>Edit Job</button>
                </div>
            </div>
        </div>");

} // end of foreach loop

    $page->addPageBodyItem("
    </div>
</div>");

    $page->displayPage();

?>
