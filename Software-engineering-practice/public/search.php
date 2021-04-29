<?php
require('../db_connector.php');
$conn = getConnection();

// TODO add the search bar at the top again and add a show more
//
// TODO if no results then show recently added

require('../database_functions.php');

require('../pageTemplate.php');
$page = new pageTemplate('Home');

$page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");
$page->addJavaScript("<script src=\"./js/navBar.js\"></script>");

$page->addPageBodyItem("
<div class='searchPageContainer'>
    <h1>Search Page</h1>");

if($_REQUEST['categories'] != 'all') {
    $categoriesCodes = array();
    $r = $conn->query("
        SELECT sep_jobs_categories.job_id
        FROM sep_jobs_categories
        JOIN sep_jobs_list
        ON sep_jobs_categories.job_code = sep_jobs_list.job_code
        WHERE sep_jobs_list.job_name = '{$_REQUEST['categories']}'
        GROUP BY sep_jobs_categories.job_id
    ");
    if($r) {
        while($row = $r->fetchObject()) {
            array_push($categoriesCodes, $row->job_id);
        }
//        print_r($categoriesCodes);
    }
}

            $query = "SELECT sep_user_info.user_id, 
                sep_user_info.user_fname, 
                sep_user_info.user_lname, 
                sep_available_jobs.job_id,
                sep_jobs_list.job_name,
                sep_available_jobs.job_desc,
                sep_available_jobs.job_price,
                sep_available_jobs.job_date,
                sep_available_jobs.job_image
            FROM sep_user_info
            JOIN sep_available_jobs
            ON sep_user_info.user_id = sep_available_jobs.user_id
            JOIN sep_users_interested_jobs
            ON sep_user_info.user_id = sep_users_interested_jobs.user_id
            JOIN sep_jobs_list
            ON sep_jobs_list.job_code = sep_available_jobs.job_code    
            WHERE sep_available_jobs.job_availability = '1' ";

            if($_REQUEST['categories'] != 'all') {
                $query .= "AND sep_available_jobs.job_id IN (" . implode(',', $categoriesCodes) . ") ";
            }

            if(!empty($_REQUEST['keyword'])) {
                $query .= "AND (sep_user_info.user_fname LIKE '%{$_REQUEST['keyword']}%'
                OR sep_user_info.user_lname LIKE '%{$_REQUEST['keyword']}%'
                OR sep_jobs_list.job_name LIKE '%{$_REQUEST['keyword']}%'
                OR sep_available_jobs.job_desc LIKE '%{$_REQUEST['keyword']}%'
                OR sep_available_jobs.job_price LIKE '%{$_REQUEST['keyword']}%') ";
            }

            $query .= "GROUP BY user_id
            ORDER BY sep_available_jobs.job_date DESC
            LIMIT 10";

            $result = $conn->query($query);
            $items = 0;

            if($result) {
                while($row = $result->fetchObject()) {
                    $items++;
                    $price = $row->job_price;
                    $page->addPageBodyItem("<div class='clickable' onclick='openPage(`serviceInner.php?id={$row->job_id}`)'>
                    <div class='topImg'>
                        <img src='assets/job_images/{$row->job_image}'>
                    </div>
                        <div class='resultText'>
                            <img class='personIcon' src='assets/person.svg'>
                        <h2>{$row->user_fname} {$row->user_lname}</h2>
                        <h3>{$row->job_name}</h3>
                        <p>{$row->job_desc}</p>");

                    list($sum, $total) = getStarRating($conn, $row->job_id);

                    for ($i = 0; $i < 5; $i++) {
                        if ($i < $sum) {
                            $page->addPageBodyItem("<span class='fa fa-star checked'></span>");
                        } else {
                            $page->addPageBodyItem("<span class='fa fa-star'></span>");
                        }
                    }
                    $page->addPageBodyItem("({$total})");


                    $page->addPageBodyItem("<p class='price'>£{$price}/h</p>
                    </div>
                </div>");
                }
                if($items == 0) {
                    $page->addPageBodyItem("<p>No Jobs to Show</p>");
                }
            }

$page->addPageBodyItem("</div>");

$page->displayPage();
?>

<!--    <img src='assets/appStore.svg' alt='App Store'>-->
<!--    <img src='assets/googlePlay.svg' alt='App Store'>-->

