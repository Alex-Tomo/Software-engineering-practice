<?php

require('../db_connector.php');
require('../pageTemplate.php');
require('../database_functions.php');

// TODO: Add a show all button

if(!$_SESSION['loggedin']) { header('Location: home.php'); }
$conn = getConnection();

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
     <div class='popup' id='popup-1' style='display: none;'>
        <div class='overlay'></div>
            <form id='regForm' action='/action_page.php'>
                <div id='tab1'>
                    <h2>Tell us about yourself</h2>
                    <h1>General information</h1>
                    <input type='text' id='email' value='{$_SESSION['email']}' style='display: none'>
                    <label for='fname'>   First name</label><br>
                    <input type='text' id='fname' name='fname' placeholder='Your name'><br>
                    <label for='lname'>   Last name</label><br>
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

list($language_codes, $language_names) = selectAll($conn, 'language_code', 'language_name', 'sep_languages', 'language_name');
for($language_index = 0; $language_index < sizeof($language_codes); $language_index++) {
    $page->addPageBodyItem("<option value='{$language_codes[$language_index]}'>{$language_names[$language_index]}</option>");
}

                    $page->addPageBodyItem("</select>
                        <label for='reg'>Region</label><br>
                        <select id='reg' name='reg'>
                            <option value='0'>Choose your region</option>");

list($region_codes, $region_names) = selectAll($conn, 'region_code', 'region_name', 'sep_regions', 'region_name');
for($region_index = 0; $region_index < sizeof($region_codes); $region_index++) {
    $page->addPageBodyItem("<option value='{$region_codes[$region_index]}'>{$region_names[$region_index]}</option>");
}

                    $page->addPageBodyItem("</select>
                            <button class='clickable' type='button' id='nextBtn1'>Next</button>               
                        </div>
                        
                        <div id='tab2'>
                            <h2>Tell us about yourself</h2>
                            <h1>What job(s) are you interested in?</h1>
                            <label>(Please choose at least 3)</label>
                            <input
                                id='jobsListInput' list='searchJobsList' placeholder='Search for jobs...' onkeyup='filterJobsList()' onchange='selectJob()'>       
                            <datalist id='searchJobsList'>");

list($job_codes, $job_names) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
for($job_index = 0; $job_index < sizeof($job_codes); $job_index++) {
    $page->addPageBodyItem("<option value='{$job_names[$job_index]}' id='{$job_names[$job_index]}' name='{$job_codes[$job_index]}'>");
}

                $page->addPageBodyItem("</datalist>   
                    <p>Can't find one? <strong>Suggest one!</strong></p>
                    <div id='suggestion'></div>
                    <button class='clickable' type='button' id='nextBtn2'>Submit</button><br>
                    <button class='clickable' type='button' id='prevBtn2'>Back</button>
                </div>
            </form>
    </div>       
    <div id='refineContainer'>
        <form id='searchForm' method='get' action='search.php' style='position: relative; display: none;'>
        <div class='refineChild'>
            <label>Keyword</label><br>
            <input type='text' name='keyword' placeholder='Search keyword here'>
        </div>
        
        <div class='refineChild'>
            <label>Categories</label><br>
            <select name='categories'>
                <option value='all'>All Categories</option>");

list($job_codes, $job_names) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
for($job_index = 0; $job_index < sizeof($job_codes); $job_index++) {
    $page->addPageBodyItem("<option id='{$job_names[$job_index]}' name='{$job_codes[$job_index]}'>{$job_names[$job_index]}</option>");
}

            $page->addPageBodyItem("</select>
        </div>");
        
//        TODO NEED TO ADD THESE TO THE DATABASE
//        <div class='refineChild'>
//            <label>Junior</label><br>
//            <select name='skillLevel'>
//                <option value='junior'>Junior</option>
//                <option value='beginner'>Beginner</option>
//                <option value='intermediate'>Intermediate</option>
//                <option value='advanced'>Advanced</option>
//                <option value='expert'>Expert</option>
//            </select>
//        </div>
            $page->addPageBodyItem("<button type='submit' class='clickable'><i id='magGlass' class='fa fa-search'></i>Search</button>
        </form>
    </div>
    
<div id='recommendedContainer'>
        <h1>Recommended for you</h1>");

$choiceArray = selectUsersChosenCategories($conn, $_SESSION['email']);
$recommenders = getRecommendedJobs($conn, $choiceArray);
$price = 0;
if(!empty($recommenders)) {
    foreach ($recommenders as $recommender) {
        $price = $recommender['job_price'];
        $page->addPageBodyItem("
            <div class='resultChild clickable' onclick='openPage(`serviceInner.php?id={$recommender['job_id']}`)'>
                <div class='topImg'>
                    <img id='recommendedImage_{$recommender['job_id']}'>
                </div>
                <script> getRecommendedImage({$recommender['job_id']}); </script>
                    <div class='resultText'>
                        <img class='personIcon' src='assets/person.svg'>
                    <h2>{$recommender['user_fname']} {$recommender['user_lname']}</h2>
                    <h3>{$recommender['job_title']}</h3>
                    <p>{$recommender['job_desc']}</p>");

        list($sum, $total) = getStarRating($conn, $recommender['job_id']);

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
}

$page->addPageBodyItem("</div>
    
    <div id='recAddedContainer'>
        <h1>Recently Added</h1>");

$recents = getRecentJobs($conn);
$price = 0;
foreach($recents as $recent) {
    $price = $recent['job_price'];
        $page->addPageBodyItem("
            <div class='resultChild clickable' onclick='openPage(`serviceInner.php?id={$recent['job_id']}`)'>
                <div class='topImg'>
                    <img id='recentImage_{$recent['job_id']}'>
                </div>
                <script> getRecentImage({$recent['job_id']}); </script>
                    <div class='resultText'>
                        <img class='personIcon' src='assets/person.svg'>
                    <h2>{$recent['user_fname']} {$recent['user_lname']}</h2>
                    <h3>{$recent['job_title']}</h3>
                    <p>{$recent['job_desc']}</p>");

                list($sum, $total) = getStarRating($conn, $recent['job_id']);

                for($i = 0; $i < 5; $i++) {
                    if($i < $sum) {
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

$page->addPageBodyItem("</div>      
    
    <div id='categories'>
        <h1>Popular categories</h1>");

$popularCategories = getPopularCategories($conn);
foreach ($popularCategories as $popularCategory) {
    $page->addPageBodyItem("<button class='clickable' onclick='openPage(`search.php?categories={$popularCategory}`)'>{$popularCategory}</button>");
}



$page->addPageBodyItem("</div>  
   
        
    <div id='recViewedParent'>
        <h1>Recently viewed</h1>");

if(isset($_SESSION['recently_viewed'])) {
    for($i = 0; $i < sizeof($_SESSION['recently_viewed']); $i++) {
        if($_SESSION['recently_viewed'][$i] == null || $_SESSION['recently_viewed'][$i] == 0) {
            continue;
        }
        list($job_title, $job_price) = getRecentlyViewed($conn, $_SESSION['recently_viewed'][$i]);
        if(isset($job_title) && isset($job_price)) {
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
            </div>");
        }
    }
}

$page->addPageBodyItem("</div>
</div>");

$page->displayPage();
?>

<!--    <img src='assets/appStore.svg' alt='App Store'>-->
<!--    <img src='assets/googlePlay.svg' alt='App Store'>-->