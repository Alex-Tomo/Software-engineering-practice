<?php

    // Requires
    require('../pageTemplate.php');
    require('../db_connector.php');
    require('../database_functions.php');

    // If the user is not logged in, then redirect to the login page
    if(!$_SESSION['loggedin']) { header('Location: signin.php'); }

    // Initial variables, get database connection, get the page template class
    $conn = getConnection();
    $page = new pageTemplate('Logged In Home');

    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/editProfile.js\"></script>");
    $page->addJavaScript("<script src=\"./js/selectJobsList.js\"></script>");
    $page->addJavaScript("<script src=\"./js/changePasswordForm.js\"></script>");

    // Get users details
    list($user, $chosenJobsCode, $chosenJobsName) = getUserDetails($conn, $_SESSION['email']);
    // Dynamically include gender details
    $gendersValue = ['Select', 'Male', 'Female', 'Prefer not to say'];
    $gendersArray = ['0', 'male', 'female', 'pnts'];

    $page->addPageBodyItem("
    <div class='pageContainer' style='width: 100%; text-align: center;'> 
        <div class='popup' id='popup-2' style='display: none;'>
            <div class='overlay'></div>
                <form id='regForm'>
                    <div id='tab1'>
                        <button onclick='closeInfoForm()'>Close Window</button>
                        <h2>Tell us about yourself</h2>
                        <h1>General information</h1>
                        <input type='text' id='email' value='{$_SESSION['email']}' style='display: none'>
                        <label for='fname'>   First name</label><br>
                        <input type='text' id='fname' name='fname' value='{$user['fname']}' placeholder='Your name'><br>
                        <label for='lname'>   Last name</label><br>
                        <input type='text'  id='lname' name='lname' value='{$user['lname']}' placeholder='Last name'><br>
                        <label for='gender'>Gender</label>
                        <select id='gender' name='gender'>");

        for ($i = 0; $i < sizeof($gendersArray); $i++) {

            if ($user['gender'] == $gendersArray[$i]) {
                $page->addPageBodyItem("<option selected value='{$gendersArray[$i]}'>{$gendersValue[$i]}</option>");
            } else {
                $page->addPageBodyItem("<option value='{$gendersArray[$i]}'>{$gendersValue[$i]}</option>");
            }

        } // end of for loop

        $page->addPageBodyItem("
                        </select>
                        <label for='lang'>Preferred language</label><br>
                        <select id='lang' name='lang'>
                            <option value='0'>Choose your language</option>");

    // Get all languages from the database
        list($languageCodes, $languageNames) = selectAll($conn, 'language_code', 'language_name', 'sep_languages', 'language_name');
        for ($languageIndex = 0; $languageIndex < sizeof($languageCodes); $languageIndex++) {

            if ($user['language'] == $languageCodes[$languageIndex]) {
                $page->addPageBodyItem("<option selected value='{$languageCodes[$languageIndex]}'>{$languageNames[$languageIndex]}</option>");
            } else {
                $page->addPageBodyItem("<option value='{$languageCodes[$languageIndex]}'>{$languageNames[$languageIndex]}</option>");
            }

        } // end of for loop

        $page->addPageBodyItem("
                        </select>
                        <label for='reg'>Region</label><br>
                        <select id='reg' name='reg'>
                            <option value='0'>Choose your region</option>");

    // Get all regions from the database
        list($regionCodes, $regionNames) = selectAll($conn, 'region_code', 'region_name', 'sep_regions', 'region_name');
        for ($regionIndex = 0; $regionIndex < sizeof($regionCodes); $regionIndex++) {

            if ($user['region'] == $regionCodes[$regionIndex]) {
                $page->addPageBodyItem("<option selected value='{$regionCodes[$regionIndex]}'>{$regionNames[$regionIndex]}</option>");
            } else {
                $page->addPageBodyItem("<option value='{$regionCodes[$regionIndex]}'>{$regionNames[$regionIndex]}</option>");
            }

        } // end of for loop

        $page->addPageBodyItem("
                        </select>
                        <button class='clickable' type='button' id='nextProfileBtn1'>Next</button>               
                    </div>
                            
                    <div id='tab2'>                    
                        <button onclick='closeInfoForm()'>Close Window</button>
                        <h2>Tell us about yourself</h2>
                        <h1>What job(s) are you interested in?</h1>
                        <label>(Please choose at least 3)</label>
                        <input id='jobsListInput' list='searchJobsList' placeholder='Search for jobs...' onkeyup='filterJobsList()' onchange='selectJob()'>       
                        <datalist id='searchJobsList'>");

    // Get all job categories from the database
        list($jobCodes, $jobNames) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
        for ($jobIndex = 0; $jobIndex < sizeof($jobCodes); $jobIndex++) {

            if (in_array($jobCodes[$jobIndex], $chosenJobsCode)) {
                $page->addPageBodyItem("<option disabled value='{$jobNames[$jobIndex]}' id='{$jobNames[$jobIndex]}' name='{$jobCodes[$jobIndex]}'>");
            } else {
                $page->addPageBodyItem("<option value='{$jobNames[$jobIndex]}' id='{$jobNames[$jobIndex]}' name='{$jobCodes[$jobIndex]}'>");
            }

        } // end of for loop

        $page->addPageBodyItem("
                        </datalist>   
                        <p>Can't find one? <strong>Suggest one!</strong></p>
                        <div id='suggestion'>");

    // Add the users chosen jobs
        for ($i = 0; $i < sizeof($chosenJobsName); $i++) {

            $page->addPageBodyItem("
                                <p onclick='removeJob(`{$chosenJobsCode[$i]}`, `{$chosenJobsName[$i]}`)' id='{$chosenJobsCode[$i]}' class='clickable chosenJob' 
                                style='background-color: #017EFC; color: #FFFFFF; font-size: small; font-weight: normal; width: fit-content; padding: 10px; 
                                margin: 2.5px 5px 2.5px 0; border-radius: 5px;'>{$chosenJobsName[$i]} X</p>");

        } // end of for loop

        $page->addPageBodyItem("
                        <script> getSelectedJob(); </script>
                        </div>
                    <button class='clickable' type='button' id='nextProfileBtn2'>Submit</button><br>
                    <button class='clickable' type='button' id='prevProfileBtn2'>Back</button>
                    </div>
                </form>
            </div>
            <div class='popup' id='passwordForm' style='display: none;'>
                <div class='overlay'></div>
                <form id='passwordForm'>
                    <div id='passwordTab'>
                        <button id='closePasswordWindow'>Close Window</button><br>
                        <label for='oldPassword'>Old Password</label><br>
                        <input id='oldPassword' name='oldPassword' type='password'>            
                        <label for='newPassword'>New Password</label><br>      
                        <input id='newPassword' name='newPassword' type='password'>
                        <label for='repeatNewPassword'>Repeat New Password</label><br>
                        <input id='repeatNewPassword' name='repeatNewPassword' type='password'>
                        <button id='submitPasswordForm' type='button'>Change Password</button>
                    </div>
                </form>
            </div>
    
            <div id='bookContainer'>
                <h1>My Details</h1>
                <div>Email: {$_SESSION['email']}</div>
                <div>First name: {$user['fname']}</div>
                <div id='title'>Last name: {$user['lname']}</div>
                <div id='year'>Gender: {$user['gender']}</div>
                <div id='description'>Language: {$user['language']}</div>
                <div>Region: {$user['region']}</div><br>
                <div>Favourite Categories: </div><br>");

        foreach ($chosenJobsName as $jobName) {

            $page->addPageBodyItem("
                <div>{$jobName}</div>");

        } // end of foreach loop

        $page->addPageBodyItem("
            </div>
        <button onclick='displayEditProfile();' style='margin: 25px auto;'>Edit Profile</button>
        <button id='showPasswordForm' style='margin: 25px auto;'>Change Password</button>
    </div>");

    $page->displayPage();

?>
