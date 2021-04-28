<?php

require('../db_connector.php');
$conn = getConnection();

require('../pageTemplate.php');
require('../database_functions.php');

if(!$_SESSION['loggedin']) {
    header('Location: signin.php');
}

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

if(!isset($_SESSION['recently_viewed'])) {
    $_SESSION['recently_viewed'] = array();
    array_push($_SESSION['recently_viewed'], $_REQUEST['id']);
} else {
    $newArray = array();
    array_push($newArray, $_REQUEST['id']);
    for($i = 0; $i < sizeof($_SESSION['recently_viewed']); $i++) {
        if(in_array($_SESSION['recently_viewed'][$i], $newArray) || ($_SESSION['recently_viewed'][$i] == null)) {
            continue;
        } else {
            array_push($newArray, $_SESSION['recently_viewed'][$i]);
        }
        if($i == 3) {
            break;
        }
    }
    $_SESSION['recently_viewed'] = $newArray;
}

$page = new pageTemplate('Logged In Home');
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");
$page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
$page->addJavaScript("<script src=\"./js/popupForm.js\"></script>");
$page->addJavaScript("<script src=\"./js/selectImage.js\"></script>");

$job_id = $_REQUEST['id'];

$page->addPageBodyItem("
<div class='pageContainer'>
    <div id='resultContainer'>
            <a id='back' class='clickable' onclick='openPage(`loggedinHome.php`)'>< Back to list</a>
            <div id='serviceResult' class='resultChild'>");


$jobs = getJobDetails($conn, $job_id);

foreach ($jobs as $job) {
    $price = $job['job_price'];
    $page->addPageBodyItem("
            <div class='topImg'>
                <img id='image_{$job['job_id']}'>
            </div>
            <script> getImage({$job['job_id']}); </script>
            <div class='resultText'>
                   <img class='personIcon' src='assets/person.svg'>
                    <h2>{$job['user_fname']} {$job['user_lname']}</h2>
                    <h3>{$job['job_name']}</h3>
                    <p>{$job['job_desc']}</p>");

    list($sum, $total) = getStarRating($conn, $job_id);

    for ($i = 0; $i < 5; $i++) {
        if ($i < $sum) {
            $page->addPageBodyItem("<span class='fa fa-star checked'></span>");
        } else {
            $page->addPageBodyItem("<span class='fa fa-star'></span>");
        }
    }
    $page->addPageBodyItem("({$total})");

    $page->addPageBodyItem("<p class='price'>£{$price}/h</p>
                            <button class='applyBtn clickable' onclick='openPage()'>Enquire</button>
                            <a class='clickable' onclick='openPage()'>Refer a friend</a>
                        </div>
                    </div>");
}

$page->addPageBodyItem("</div>
    
    <div id='recViewedParent'>
            <h1>Recently viewed</h1>");

if(isset($_SESSION['recently_viewed'])) {
    for($i = 0; $i < sizeof($_SESSION['recently_viewed']); $i++) {
        if($_SESSION['recently_viewed'][$i] == null || $_SESSION['recently_viewed'][$i] == 0 || $_SESSION['recently_viewed'][$i] == $_REQUEST['id']) {
            continue;
        }
        list($job_title, $job_price) = getRecentlyViewed($conn, $_SESSION['recently_viewed'][$i]);
            $page->addPageBodyItem("
                <div class='recViewedChild clickable' onclick='openPage(`serviceInner.php?id=`+{$_SESSION['recently_viewed'][$i]})'>
                    <div class='recViewedImg'>
                        <img id='recentlyViewedImage_{$_SESSION['recently_viewed'][$i]}'>
                    </div>
                    <script> getRecentlyViewedImage({$_SESSION['recently_viewed'][$i]}); </script>
                    <div class='recViewedText'>
                        <h4>{$job_title}</h4>
                        <p>£{$job_price}/h</p>  
                    </div>
                </div>
");
    }
}

$page->addPageBodyItem("</div>
</div>");

$page->displayPage();


