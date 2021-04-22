<?php

// TODO: MAYBE SPLIT THE PAGE UP AS ITS GETTING BIG??

require('../db_connector.php');
$conn = getConnection();

include('../pageTemplate.php');

if(!$_SESSION['loggedin']) {
    header('Location: home.php');
}


$page = new pageTemplate('Logged In Home');
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");
$page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
$page->addJavaScript("<script src=\"./js/popupForm.js\"></script>");
$page->addJavaScript("<script src=\"./js/selectJobsList.js\"></script>");
$page->addJavaScript("<script src=\"./js/selectImage.js\"></script>");

$page->addPageBodyItem("
<div class='pageContainer'>
    <div class='popup' id='popup-1'>
        <div class='overlay'></div>
            <form id='regForm' action='/action_page.php'>
                <div id='tab1'>
                    <p>Tell us about yourself</p>
                    <h1>General information</h1>
                    <input type='text' id='email' value='{$_SESSION['email']}' style='display: none'>
                    <label for='fname'>First name</label><br>
                    <input type='text' id='fname' name='fname' placeholder='Your name'><br>
                    <label for='lname'>Last name</label><br>
                    <input type='text'  id='lname' name='lname' placeholder='Last name'><br>
                    <label for='gender'>Gender</label>
                    <select id='gender' name='gender'>
                        <option value='0'> Select </option>
                        <option value='male'>Male</option>
                        <option value='female'>Female</option>
                        <option value='pnts'>Prefer not to say</option>
                    </select>
                    <label for='lang'>Preferred language</label><br>
                    <select id='lang' name='lang'>
                        <option value='0'>Choose your language</option>");
                    $languageResult = $conn->query("SELECT * FROM sep_languages ORDER BY language_name");
                    if($languageResult) {
                        while($row = $languageResult->fetchObject()) {
                            $language_code = filter_var($row->language_code, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                            $language_name = filter_var($row->language_name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                            $page->addPageBodyItem("<option value='$language_code'>$language_name</option>");
                        }
                    }
                    $page->addPageBodyItem("</select>
                                        <label for='reg'>Region</label><br>
                                        <select id='reg' name='reg'>
                                            <option value='0'>Choose your region</option>");
                    $regionResult = $conn->query("SELECT * FROM sep_regions ORDER BY region_name");
                    if($regionResult) {
                        while($row = $regionResult->fetchObject()) {
                            $region_code = filter_var($row->region_code, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                            $region_name = filter_var($row->region_name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                            $page->addPageBodyItem("<option value='$region_code'>$region_name</option>");
                        }
                    }
                    $page->addPageBodyItem("</select>
                                        <button type='button' id='nextBtn1'>Next</button>               
                                    </div>
                                    
                                    <div id='tab2'>
                                        <p>Tell us about yourself</p>
                                        <h1>What job(s) are you interested in?</h1>
                                        <label style='width: 100%; text-align: center;'>(Please choose at least 3)</label>
                                        <input style='width: 75%; margin: auto; text-align: center; padding: 10px; border-radius: 10px; border: 1px #828282 solid; outline: none;' 
                                            id='jobsListInput' list='searchJobsList' placeholder='Search for jobs...' onkeyup='filterJobsList()' onchange='selectJob()'>       
                                        <datalist id='searchJobsList'>");
                    $jobsResult = $conn->query("SELECT * FROM sep_jobs_list ORDER BY job_name");
                    if($jobsResult) {
                        while($row = $jobsResult->fetchObject()) {
                            $job_code = filter_var($row->job_code, FILTER_SANITIZE_NUMBER_INT);
                            $job_name = filter_var($row->job_name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                            $page->addPageBodyItem("<option value='$job_name' id='$job_name' name='$job_code'>");
                        }
                    }
                    $page->addPageBodyItem("</datalist>        
                    <p style='font-weight: normal; font-size: small; margin-top: 5px; width: 75%;'>Can't find one? <strong>Suggest one!</strong></p>
                    <div id='suggestion' style='width: 75%; margin: auto; display: flex; flex-wrap: wrap; justify-content: center; '></div>
                    <button type='button' id='nextBtn2'>Submit</button>
                    <button type='button' id='prevBtn2'>Back</button>
                </div>
            </form>
    </div>       
    <div id='refineContainer'>
        <div class='refineChild'>
            <label>Keyword</label><br>
            <input type='text' name='keyword' placeholder='Search keyword here'>
        </div>
        
        <div class='refineChild'>
            <label>Categories</label><br>
            <select name='categories'>
                <option value='javaprogrammer'>Java Programmer</option>
                <option value='developer'>Developer</option>
                <option value='programmer'>Programmer</option>
            </select>
        </div>
        
        <div class='refineChild'>
            <label>Junior</label><br>
            <select name='skillLevel'>
                <option value='junior'>Junior</option>
                <option value='beginner'>Beginner</option>
                <option value='intermediate'>Intermediate</option>
                <option value='advanced'>Advanced</option>
                <option value='expert'>Expert</option>
            </select>
        </div>
            <button class='clickable' onclick='openPage()'><i id='magGlass' class='fa fa-search'></i>Search</button>
    </div>
    
    <div id='resultContainer'>
        <h1>Recommended for you</h1>");

        $usersChosenCategories = $conn->query("
                          SELECT sep_users_interested_jobs.job_code
                          FROM sep_users_interested_jobs
                          JOIN sep_users
                          ON sep_users_interested_jobs.user_id = sep_users.user_id
                          WHERE sep_users.user_email = '{$_SESSION['email']}'
        ");

        $choiceArray = array();

        if($usersChosenCategories) {
            while($usersChoices = $usersChosenCategories->fetchObject()) {
                array_push($choiceArray, $usersChoices->job_code);
            }
        }

        $recommendedQuery = $conn->query("
                          SELECT sep_user_info.user_id, 
                                 sep_user_info.user_fname, 
                                 sep_user_info.user_lname, 
                                 sep_available_jobs.job_id,
                                 sep_available_jobs.job_title,
                                 sep_available_jobs.job_desc,
                                 sep_available_jobs.job_price,
                                 sep_available_jobs.job_date
                          FROM sep_user_info
                          JOIN sep_available_jobs
                          ON sep_user_info.user_id = sep_available_jobs.user_id
                          JOIN sep_users_interested_jobs
                          ON sep_user_info.user_id = sep_users_interested_jobs.user_id
                          WHERE sep_available_jobs.job_availability = '1'
                          AND sep_users_interested_jobs.job_code IN (" . implode(',', $choiceArray) . ")
                          GROUP BY user_id
                          ORDER BY sep_available_jobs.job_date DESC
                          LIMIT 3
        ");

        if($recommendedQuery) {
            while($recommendedRow = $recommendedQuery->fetchObject()) {
                $page->addPageBodyItem("
                    <div class='resultChild clickable' onclick='openPage(`serviceInner.php?id=`+$recommendedRow->job_id)'>
                        <div class='replaceWithImg2' style='overflow: hidden;'>
                            <img id='recommendedImage_{$recommendedRow->job_id}' style='width: 100%;'>
                        </div>
                        <script> getRecommendedImage({$recommendedRow->job_id}); </script>
                            <div class='resultText'>
                                <img src='assets/person.svg'>
                            <h2>{$recommendedRow->user_fname} {$recommendedRow->user_lname}</h2>
                            <h3>{$recommendedRow->job_title} needed!</h3>
                            <p>{$recommendedRow->job_desc}</p>");

                            $starRatingQuery = $conn->query(
                                "SELECT (SUM(sep_job_rating.job_rating)/COUNT(*)) as sum, COUNT(sep_job_rating.job_id) as total
                                                              FROM sep_job_rating
                                                              JOIN sep_available_jobs
                                                              ON sep_job_rating.job_id = sep_available_jobs.job_id
                                                              JOIN sep_users
                                                              ON sep_job_rating.user_id = sep_users.user_id
                                                              WHERE sep_job_rating.job_id = '{$recommendedRow->job_id}'
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

                            $page->addPageBodyItem("<p class='price'>£{$recommendedRow->job_price}/h</p>
                        </div>
                    </div>");
            }
        }

    $page->addPageBodyItem("</div>
    
    <div id='resultContainer'>
        <h1>Recently Added</h1>");

        $availableJobs = $conn->query(
                "SELECT sep_user_info.user_id, 
                                 sep_user_info.user_fname, 
                                 sep_user_info.user_lname,
                                 sep_available_jobs.job_id,
                                 sep_available_jobs.job_title,
                                 sep_available_jobs.job_desc,
                                 sep_available_jobs.job_price,
                                 sep_available_jobs.job_date
                          FROM sep_user_info
                          INNER JOIN sep_available_jobs
                          ON sep_user_info.user_id = sep_available_jobs.user_id
                          WHERE sep_available_jobs.job_availability = '1'
                          ORDER BY sep_available_jobs.job_date DESC
                          LIMIT 3"
                );
        if($availableJobs) {
            while($row = $availableJobs->fetchObject()) {


//              TODO: NEED TO DO FILTERING BEFORE DISPLAYING THESE!!
//              TODO: UPDATE THE DATABASE TO INCLUDE A STAR RATING SYSTEM


                $page->addPageBodyItem(
                    "<div class='resultChild clickable' onclick='openPage(`serviceInner.php?id=`+$row->job_id)'>
                        <div class='replaceWithImg2' style='overflow: hidden;'>
                            <img id='image_{$row->job_id}' style='width: 100%;'>
                        </div>
                        <script> getImage({$row->job_id}); </script>
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
                                                  WHERE sep_job_rating.job_id = '{$row->job_id}'
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
                            </div>
                    </div>");
            }
        }

    $page->addPageBodyItem("</div>      
    
    <div id='categories'>
        <h1>Popular categories</h1>");

        $popularCategoriesQuery = $conn->query("
            SELECT COUNT(sep_users_interested_jobs.job_code), sep_jobs_list.job_name
            FROM sep_users_interested_jobs
            JOIN sep_jobs_list
            ON sep_users_interested_jobs.job_code = sep_jobs_list.job_code
            GROUP BY sep_jobs_list.job_name
            ORDER BY COUNT(sep_users_interested_jobs.job_code) DESC
            LIMIT 10
        ");

        if($popularCategoriesQuery) {
            while($popularCategoriesRow = $popularCategoriesQuery->fetchObject()) {
                $page->addPageBodyItem("<button class='clickable' type='submit'>{$popularCategoriesRow->job_name}</button>");
            }
        }

    $page->addPageBodyItem("</div>  
   
        
    <div id='recViewedParent'>
        <h1>Recently viewed</h1>");

        if(isset($_SESSION['recently_viewed'])) {
            for($i = 0; $i < sizeof($_SESSION['recently_viewed']); $i++) {
                if($_SESSION['recently_viewed'][$i] == null || $_SESSION['recently_viewed'][$i] == 0) {
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
?>

<!--    <img src='assets/appStore.svg' alt='App Store'>-->
<!--    <img src='assets/googlePlay.svg' alt='App Store'>-->