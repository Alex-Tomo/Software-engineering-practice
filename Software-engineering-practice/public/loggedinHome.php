<?php

    // TODO popup is not showing

    // Requires
    require('../db_connector.php');
    require('../pageTemplate.php');
    require('../database_functions.php');

    // If the user is not logged in, redirect the user to the signin page
    if(!$_SESSION['loggedin']) { header('Location: signin.php'); }

    // Inital variables
    // Get database connection, Get the page template class
    $conn = getConnection();
    $page = new pageTemplate('Logged In Home');

    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/popupForm.js\"></script>");
    $page->addJavaScript("<script src=\"./js/selectJobsList.js\"></script>");
    $page->addJavaScript("<script src=\"./js/notificationServer.js\"></script>");

    // Main content
    $page->addPageBodyItem("
        <div class='pageContainer'>
             <div class='popup' id='popup-1' style='display: none;'>
                <div class='overlay'></div>        
                    <form class='popupForm' action='/action_page.php'>
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

// Get all the available languages from the database
list($languageCodes, $languageNames) = selectAll($conn, 'language_code', 'language_name', 'sep_languages', 'language_name');
for($languageIndex = 0; $languageIndex < sizeof($languageCodes); $languageIndex++) {
    $page->addPageBodyItem("<option value='{$languageCodes[$languageIndex]}'>{$languageNames[$languageIndex]}</option>");
}

                            $page->addPageBodyItem("
                            </select>
                                <label for='reg'>Region</label><br>
                                <select id='reg' name='reg'>
                                    <option value='0'>Choose your region</option>");

// Get all the available regions from the database
list($regionCodes, $regionNames) = selectAll($conn, 'region_code', 'region_name', 'sep_regions', 'region_name');
for($regionIndex = 0; $regionIndex < sizeof($regionCodes); $regionIndex++) {
    $page->addPageBodyItem("<option value='{$regionCodes[$regionIndex]}'>{$regionNames[$regionIndex]}</option>");
}

                                $page->addPageBodyItem("
                                </select>
                                    <button  class='clickable nextLink' type='button' id='nextBtn1'>Next</button>               
                                </div>
                                
                                <div id='tab2'>
                                    <h2>Tell us about yourself</h2>
                                    <h1>What job(s) are you interested in?</h1>
                                    <label>(Please choose at least 3)</label>
                                    <input id='jobsListInput' list='searchJobsList' placeholder='Search for jobs...' onkeyup='filterJobsList()' onchange='selectJob()'>       
                                    <datalist id='searchJobsList'>");

// Get all the available job categories from the database
list($jobCodes, $jobNames) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
for($jobIndex = 0; $jobIndex < sizeof($jobCodes); $jobIndex++) {
    $page->addPageBodyItem("<option value='{$jobNames[$jobIndex]}' id='{$jobNames[$jobIndex]}' name='{$jobCodes[$jobIndex]}'>");
}

                        $page->addPageBodyItem("
                        </datalist>   
                            <div id='suggestion'></div>
                            <button  class='clickable nextLink' type='button' id='nextBtn2'>Submit</button><br>
                            <button  class='clickable backLink' type='button' id='prevBtn2'>Back</button>
                        </div> 
                    </form>
                </div>
                       
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
            
            <div id='recommendedContainer'>
                <h1>Recommended for you</h1>");

try{
    // Get the recommended job details from the database
    $choiceArray = selectUsersChosenCategories($conn, $_SESSION['email']);
    $recommenders = getRecommendedJobs($conn, $choiceArray, $_SESSION['email']);
    $price = 0;
    if(!empty($recommenders)) {
        foreach($recommenders as $recommender) {
            $price = $recommender['jobPrice'];

            $page->addPageBodyItem("
                    <div class='resultChild clickable' onclick='openPage(`serviceInner.php?id={$recommender['jobId']}`)'>
                        <div class='topImg'>
                            <img src='assets/job_images/{$recommender['jobImage']}' alt='Job image'>
                        </div>
                        <div class='resultText'>
                            <img class='personIcon' src='assets/{$recommender['userImage']}' style='border-radius: 25px' alt='User icon'>
                            <h2>{$recommender['userFname']} {$recommender['userLname']}</h2>
                            <h3>{$recommender['jobTitle']}</h3>
                            <p>{$recommender['jobDesc']}</p>");

            list($sum, $total) = getStarRating($conn, $recommender['jobId']);
            for ($i = 0; $i < 5; $i++) {

                if ($i < $sum) {
                    $page->addPageBodyItem("<span class='fa fa-star checked'></span>");
                } else {
                    $page->addPageBodyItem("<span class='fa fa-star'></span>");
                }
        } // End of for loop

                        $page->addPageBodyItem("
                        ({$total})<p class='price'>£{$price}/h</p>
                    </div>
                </div>");

    } // End of foreach($recommenders as $recommender)
} // end of if(!empty($recommenders))
} catch(Exception $e){ logError($e); }

            $page->addPageBodyItem("
            </div>
            
            <div id='recAddedContainer'>
                <h1>Recently Added</h1>");

// Get the most recently posted jobs from the database
$recents = getRecentJobs($conn, $_SESSION['email']);
$price = 0;
foreach($recents as $recent) {
    $price = $recent['jobPrice'];

                $page->addPageBodyItem("
                <div class='resultChild clickable' onclick='openPage(`serviceInner.php?id={$recent['jobId']}`)'>
                    <div class='topImg'>
                        <img src='assets/job_images/{$recent['jobImage']}' alt='Job image'>
                    </div>
                    <div class='resultText'>
                        <img class='personIcon' src='assets/{$recent['userImage']}' style='border-radius: 25px' alt='User icon'>
                        <h2>{$recent['userFname']} {$recent['userLname']}</h2>
                        <h3>{$recent['jobTitle']}</h3>
                        <p>{$recent['jobDesc']}</p>");

list($sum, $total) = getStarRating($conn, $recent['jobId']);
for($i = 0; $i < 5; $i++) {

                    if($i < $sum) {
                        $page->addPageBodyItem("<span class='fa fa-star checked'></span>");
                    } else {
                        $page->addPageBodyItem("<span class='fa fa-star'></span>");
                    }

} // End of for loop

                        $page->addPageBodyItem("
                        ({$total})<p class='price'>£{$price}/h</p>
                    </div>
                </div>");

} // End of foreach($recents as $recent)

            $page->addPageBodyItem("
            </div>      
            
            <div id='categories'>
                <h1>Popular categories</h1>");

// Get the most popular categories from the database
$popularCategories = getPopularCategories($conn);
foreach ($popularCategories as $popularCategory) {
    $page->addPageBodyItem("<button class='clickable' onclick='openPage(`search.php?categories={$popularCategory}`)'>{$popularCategory}</button>");
}

            $page->addPageBodyItem("
            </div>  
           
                
            <div id='recViewedParent'>
                <h1>Recently viewed</h1>");

// If the user has view any jobs previously, get the job details and display them
if(isset($_SESSION['recentlyViewed'])) {
    for($i = 0; $i < sizeof($_SESSION['recentlyViewed']); $i++) {
        if($_SESSION['recentlyViewed'][$i] == null || $_SESSION['recentlyViewed'][$i] == 0) {
            continue;
        }
        list($jobTitle, $jobPrice, $jobImage) = getRecentlyViewed($conn, $_SESSION['recentlyViewed'][$i]);
        if(isset($jobTitle) && isset($jobPrice) && isset($jobImage)) {

                $page->addPageBodyItem("
                <div class='recViewedChild clickable' onclick='openPage(`serviceInner.php?id=`+{$_SESSION['recentlyViewed'][$i]})'>
                    <div class='recViewedImg'>
                        <img src='assets/job_images/{$jobImage}' alt='Job image'>
                    </div>
                    <div class='recViewedText'>
                        <h4>{$jobTitle}</h4>
                        <p>£{$jobPrice}/h</p>  
                    </div>
                </div>");

        } // end of if if(isset($jobTitle) && isset($jobPrice) && isset($jobImage))
    } // End of for loop
} // End of if(isset($_SESSION['recentlyViewed']))

            $page->addPageBodyItem("
            </div>
        </div>");

    $page->displayPage();

?>