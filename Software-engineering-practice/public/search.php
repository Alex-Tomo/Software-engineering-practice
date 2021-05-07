<?php

// Requires
require('../pageTemplate.php');
require('../db_connector.php');
require('../database_functions.php');

// If the user is not logged in, redirect the user to the homepage
if(!$_SESSION['loggedin']) { header('Location: signin.php'); }

// Initial variables, get database connection, get page template class
$conn = getConnection();
$page = new pageTemplate('Search');

// Add CSS
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

// Add JS
$page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
$page->addJavaScript("<script src=\"./js/notificationServer.js\"></script>");

// Main content
$page->addPageBodyItem("
    <div class='pageContainer'>
    <div id='refineContainer'>
        <form id='searchForm' method='get' action='search.php'>
            <div class='refineChild'>
                <label>Keyword</label><br>
                <input type='text' name='keyword' placeholder='Search keyword here'>
            </div>
            
            <div class='refineChild'>
                <label>Categories</label><br>
                <select name='categories'>
                    <option value='all'>All Categories</option>");

// Get all the available job categories from the database
list($jobCodes, $jobNames) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
for($jobIndex = 0; $jobIndex < sizeof($jobCodes); $jobIndex++) {
    $page->addPageBodyItem("<option id='{$jobNames[$jobIndex]}' name='{$jobCodes[$jobIndex]}'>{$jobNames[$jobIndex]}</option>");
}

$page->addPageBodyItem("
                </select>
            </div>
        <button type='submit' class='clickable'><i id='magGlass' class='fa fa-search'></i>Search</button>
        </form>
    </div>
    
    <div class='searchPageContainer'>            
        <a class='back clickable' onclick='openPage(`loggedinHome.php`)'>< Back to home</a> 
            <h1>Search Page</h1>");

// Get all the jobs with a specific category
if($_REQUEST['categories'] != 'all') {
    $_REQUEST['categories'] = sanitizeData($_REQUEST['categories']);
    $categoriesCodes = searchCategories($conn, $_REQUEST['categories']);
    if(!in_array($_REQUEST['categories'], $jobNames)) {
        $_REQUEST['categories'] = null;
    }
} else {
    $categoriesCodes = null;
}
if(isset($_REQUEST['keyword']) && !empty($_REQUEST['keyword'])) {
    $_REQUEST['keyword'] = sanitizeData($_REQUEST['keyword']);
}

$items = 0;

$jobInfo = searchJobInfo($conn, $categoriesCodes, $_REQUEST['keyword']);
if($jobInfo != null) {
    foreach ($jobInfo as $job) {
        $items++;
        $page->addPageBodyItem("
        <div class='resultChild clickable' onclick='openPage(`serviceInner.php?id={$job['jobId']}`)'>
            <div class='topImg'>
                <img src='assets/job_images/{$job['jobImage']}'>
            </div>
            <div class='resultText'>
                <img class='personIcon' src='assets/{$job['userImage']}' style='border-radius: 25px'>
                <h2>{$job['userFname']} {$job['userLname']}</h2>
                <h3>{$job['jobName']}</h3>
                <p>{$job['jobDesc']}</p>");

        list($sum, $total) = getStarRating($conn, $job['jobId']);
        for ($i = 0; $i < 5; $i++) {

            if ($i < $sum) {
                $page->addPageBodyItem("<span class='fa fa-star checked'></span>");
            } else {
                $page->addPageBodyItem("<span class='fa fa-star'></span>");
            }

        } // end of for loop

        $page->addPageBodyItem("
                ({$total})<p class='price'>£{$job['jobPrice']}/h</p>
            </div>
        </div>");

    } // End of while loop
}

if($items == 0) {
    $page->addPageBodyItem("<p>No Jobs to Show</p>");
}


$page->addPageBodyItem("
    </div>
</div>");

$page->displayPage();

?>