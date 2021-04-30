<?php

    // TODO remove the SQL statement and put into separate file
    // TODO remove the recentlyViewed and put into separate file/method

    // Requires
    require('../pageTemplate.php');
    require('../db_connector.php');
    require('../database_functions.php');

    // If user is not logged in, redirect to signinpage
    if(!$_SESSION['loggedin']) { header('Location: signin.php'); }

    // Initial Variables, get database connection, get page template class
    // Get the id of the job being displayed
    $conn = getConnection();
    $page = new pageTemplate('Logged In Home');
    $jobId = $_REQUEST['id'];


    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");

// Checks if the jobId actually exists, otherwise redirect to loggedinHome
$checkJobExists = $conn->query("SELECT job_id FROM sep_available_jobs WHERE job_availability = TRUE");
$availableJobIds = array();
if($checkJobExists) {
    while($row = $checkJobExists->fetchObject()) {
        array_push($availableJobIds, $row->job_id);
    }
    if(!in_array($_REQUEST['id'], $availableJobIds)) {
        header('Location: loggedinHome.php');
    }
}

// Adds the current jobId to the recentlyViewed session
if(!isset($_SESSION['recentlyViewed'])) {
    $_SESSION['recentlyViewed'] = array();
    array_push($_SESSION['recentlyViewed'], $_REQUEST['id']);
} else {
    $newArray = array();
    array_push($newArray, $_REQUEST['id']);
    for($i = 0; $i < sizeof($_SESSION['recentlyViewed']); $i++) {
        if(in_array($_SESSION['recentlyViewed'][$i], $newArray) || ($_SESSION['recentlyViewed'][$i] == null)) {
            continue;
        } else {
            array_push($newArray, $_SESSION['recentlyViewed'][$i]);
        }
        if($i == 3) {
            break;
        }
    }
    $_SESSION['recentlyViewed'] = $newArray;
}

// Main content
    $page->addPageBodyItem("
    <div class='pageContainer'>
        <div id='resultContainer'>
            <a id='back' class='clickable' onclick='openPage(`loggedinHome.php`)'>< Back to list</a>
            <div id='serviceResult' class='resultChild'>");


// Get the details of the current jobId
$jobs = getJobDetails($conn, $jobId);
foreach ($jobs as $job) {
    $price = $job['jobPrice'];

                $page->addPageBodyItem("
                <div class='topImg'>
                    <img src='assets/job_images/{$job['jobImage']}'>
                </div>
                <div class='resultText'>
                   <img class='personIcon' src='assets/person.svg'>
                    <h2>{$job['userFname']} {$job['userLname']}</h2>
                    <h3>{$job['jobName']}</h3>
                    <p>{$job['jobDesc']}</p>");

// Get the star rating for the job
list($sum, $total) = getStarRating($conn, $jobId);
for ($i = 0; $i < 5; $i++) {

                if ($i < $sum) {
                    $page->addPageBodyItem("<span class='fa fa-star checked'></span>");
                } else {
                    $page->addPageBodyItem("<span class='fa fa-star'></span>");
                }

} // end of for loop

                    $page->addPageBodyItem("
                    ({$total})<p class='price'>£{$price}/h</p>
                    <button class='applyBtn clickable' onclick='openPage()'>Enquire</button>
                    <a class='clickable' onclick='openPage()'>Refer a friend</a>
                </div>
            </div>");
}

        $page->addPageBodyItem("
        </div>
    
        <div id='recViewedParent'>
            <h1>Recently viewed</h1>");

// Get the most recentlyViewed jobs and display them
// Does not display the current job
if(isset($_SESSION['recentlyViewed'])) {
    for($i = 0; $i < sizeof($_SESSION['recentlyViewed']); $i++) {
        // if the jobId is equal to the job being display, do not show it
        if($_SESSION['recentlyViewed'][$i] == null || $_SESSION['recentlyViewed'][$i] == 0 || $_SESSION['recentlyViewed'][$i] == $_REQUEST['id']) {
            continue;
        }

// Get the recentlyViewed details
list($jobTitle, $jobPrice, $jobImage) = getRecentlyViewed($conn, $_SESSION['recentlyViewed'][$i]);

            $page->addPageBodyItem("
            <div class='recViewedChild clickable' onclick='openPage(`serviceInner.php?id=`+{$_SESSION['recentlyViewed'][$i]})'>
                <div class='recViewedImg'>
                    <img src='assets/job_images/{$jobImage}'>
                </div>
                <div class='recViewedText'>
                    <h4>{$jobTitle}</h4>
                    <p>£{$jobPrice}/h</p>  
                </div>
            </div>");

    } // end of for loop
} // end of if(isset($_SESSION['recentlyViewed']))

        $page->addPageBodyItem("
        </div>
    </div>");

    $page->displayPage();

?>
