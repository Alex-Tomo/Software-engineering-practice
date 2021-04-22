<?php

require('../db_connector.php');
$conn = getConnection();

include('../pageTemplate.php');

if(!$_SESSION['loggedin']) {
    header('Location: signin.php');
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
<div class='pageContainer' id='serInnContainer'>
    <div id='resultContainer'>
            <a id='back' class='clickable' onclick='openPage(`loggedinHome.php`)'>< Back to list</a>
            <div id='serviceResult' class='resultChild'>");

            $availableJobs = $conn->query(
                "SELECT sep_user_info.user_id,
                                 sep_user_info.user_fname,
                                 sep_user_info.user_lname,
                                 sep_available_jobs.job_title,
                                 sep_available_jobs.job_desc,
                                 sep_available_jobs.job_price,
                                 sep_available_jobs.job_date
                          FROM sep_user_info
                          INNER JOIN sep_available_jobs
                          ON sep_user_info.user_id = sep_available_jobs.user_id
                          WHERE sep_available_jobs.job_id = '{$job_id}'"
            );
            if($availableJobs) {
                while($row = $availableJobs->fetchObject()) {
                    $page->addPageBodyItem("
                        <div class='replaceWithImg2' style='overflow: hidden;'>
                            <img id='image_{$job_id}' style='width: 100%;'>
                        </div>
                        <script> getImage({$job_id}); </script>
                        <div class='resultText'>
                                <img src='assets/person.svg'>
                                <h2>{$row->user_fname} {$row->user_lname}</h2>
                                <h3>{$row->job_title} needed!</h3>
                                <p>{$row->job_desc}</p>");

                                $starRatingQuery = $conn->query(
                                    "SELECT (SUM(sep_job_rating.job_rating)/COUNT(*)) as sum, COUNT(sep_job_rating.job_id) as total
                                                              FROM sep_job_rating
                                                              JOIN sep_available_jobs
                                                              ON sep_job_rating.job_id = sep_available_jobs.job_id
                                                              JOIN sep_users
                                                              ON sep_job_rating.user_id = sep_users.user_id
                                                              WHERE sep_job_rating.job_id = '{$job_id}'
                                                              GROUP BY sep_job_rating.job_id"
                                );

                                if($starRatingQuery) {
                                    while($starRow = $starRatingQuery->fetchObject()) {
                                        $starSum = round($starRow->sum, 0, PHP_ROUND_HALF_DOWN);

                                        for($i = 0; $i < 5; $i++) {
                                            if($i < $starSum) {
                                                $page->addPageBodyItem("<span class='fa fa-star checked'></span>");
                                            } else {
                                                $page->addPageBodyItem("<span class='fa fa-star'></span>");
                                            }
                                        }
                                        $page->addPageBodyItem("({$starRow->total})");
                                    }
                                }

                                $page->addPageBodyItem("<p class='price'>£{$row->job_price}/h</p>
                            <button class='applyBtn clickable' onclick='openPage()'>Apply</button>
                            <a class='clickable' onclick='openPage()'>Refer a friend</a>
                        </div>
                    </div>");
                }
            }

            $page->addPageBodyItem("</div>
    
    <div id='recViewedParent2'>
            <h1>Recently viewed</h1>");

            if(isset($_SESSION['recently_viewed'])) {
                for($i = 0; $i < sizeof($_SESSION['recently_viewed']); $i++) {
                    if($_SESSION['recently_viewed'][$i] == null || $_SESSION['recently_viewed'][$i] == 0 || $_SESSION['recently_viewed'][$i] == $_REQUEST['id']) {
                        continue;
                    }
                    $recentlyViewedQuery = $conn->query("
                                      SELECT sep_available_jobs.job_title, sep_available_jobs.job_price
                                      FROM sep_available_jobs
                                      WHERE sep_available_jobs.job_id = '{$_SESSION['recently_viewed'][$i]}'
                            ");
                    if($recentlyViewedQuery) {
                        $recentRow = $recentlyViewedQuery->fetchObject();
                        $page->addPageBodyItem("
                                    <div class='recViewedChild clickable' onclick='openPage(`serviceInner.php?id=`+{$_SESSION['recently_viewed'][$i]})'>
                                        <div class='replaceWithImg'>
                                            <img id='recentlyViewedImage_{$_SESSION['recently_viewed'][$i]}' style='width: 100%;'>
                                        </div>
                                        <script> getRecentlyViewedImage({$_SESSION['recently_viewed'][$i]}); </script>
                                        <div class='recViewedChildChild'>
                                            <h4>{$recentRow->job_title} needed!</h4>
                                            <p>£{$recentRow->job_price}/h</p>  
                                        </div>
                                    </div>
                                ");
                    }
                }
            }

    $page->addPageBodyItem("</div>
</div>");

$page->displayPage();


