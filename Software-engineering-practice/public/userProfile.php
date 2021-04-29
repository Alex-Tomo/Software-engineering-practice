<?php
require('../db_connector.php');
$conn = getConnection();

require('../pageTemplate.php');
require('../database_functions.php');

if (!$_SESSION['loggedin']) {
    header('Location: signin.php');
}

// TODO add a change password

$page = new pageTemplate('Logged In Home');
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");
$page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
$page->addJavaScript("<script src=\"./js/editProfile.js\"></script>");
$page->addJavaScript("<script src=\"./js/selectJobsList.js\"></script>");
$page->addJavaScript("<script src=\"./js/changePasswordForm.js\"></script>");


$result = $conn->query("SELECT * FROM sep_user_info 
                     INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id
                     INNER JOIN sep_languages ON sep_user_info.user_language = sep_languages.language_code
                     INNER JOIN sep_regions ON sep_user_info.user_region = sep_regions.region_code
                     INNER JOIN sep_users_interested_jobs ON sep_users_interested_jobs.user_id = sep_user_info.user_id
                     INNER JOIN sep_jobs_list ON sep_jobs_list.job_code = sep_users_interested_jobs.job_code
                     WHERE user_email = '{$_SESSION['email']}'");

if($result){
    $chosenJobsCode = array();
    $chosenJobsName = array();
    while($row = $result->fetchObject()) {
        if(!isset($fname)) {$fname = $row->user_fname;}
        if(!isset($lname)) {$lname = $row->user_lname;}
        if(!isset($gender)) {$gender = $row->user_gender;}
        if(!isset($language)) {$language = $row->user_language;}
        if(!isset($region)) {$region = $row->user_region;}
        array_push($chosenJobsCode, $row->job_code);
        array_push($chosenJobsName, $row->job_name);
    }
}

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
                    <input type='text' id='fname' name='fname' value='{$fname}' placeholder='Your name'><br>
                    <label for='lname'>   Last name</label><br>
                    <input type='text'  id='lname' name='lname' value='{$lname}' placeholder='Last name'><br>
                    <label for='gender'>Gender</label>
                    <select id='gender' name='gender'>");
                        for($i = 0; $i < sizeof($gendersArray); $i++) {
                            if($gender == $gendersArray[$i]) {
                                $page->addPageBodyItem("<option selected value='{$gendersArray[$i]}'>{$gendersValue[$i]}</option>");
                            } else {
                                $page->addPageBodyItem("<option value='{$gendersArray[$i]}'>{$gendersValue[$i]}</option>");
                            }
                        }
                    $page->addPageBodyItem("</select>
                    <label for='lang'>Preferred language</label><br>
                    <select id='lang' name='lang'>
                        <option value='0'>Choose your language</option>");

list($language_codes, $language_names) = selectAll($conn, 'language_code', 'language_name', 'sep_languages', 'language_name');
for($language_index = 0; $language_index < sizeof($language_codes); $language_index++) {
    if($language == $language_codes[$language_index]) {
        $page->addPageBodyItem("<option selected value='{$language_codes[$language_index]}'>{$language_names[$language_index]}</option>");
    } else {
        $page->addPageBodyItem("<option value='{$language_codes[$language_index]}'>{$language_names[$language_index]}</option>");
    }
}

                    $page->addPageBodyItem("</select>
                        <label for='reg'>Region</label><br>
                        <select id='reg' name='reg'>
                            <option value='0'>Choose your region</option>");

list($region_codes, $region_names) = selectAll($conn, 'region_code', 'region_name', 'sep_regions', 'region_name');
for($region_index = 0; $region_index < sizeof($region_codes); $region_index++) {
    if($region == $region_codes[$region_index]) {
        $page->addPageBodyItem("<option selected value='{$region_codes[$region_index]}'>{$region_names[$region_index]}</option>");
    } else {
        $page->addPageBodyItem("<option value='{$region_codes[$region_index]}'>{$region_names[$region_index]}</option>");
    }
}

                    $page->addPageBodyItem("</select>
                            <button class='clickable' type='button' id='nextProfileBtn1'>Next</button>               
                        </div>
                        
                        <div id='tab2'>                    
                            <button onclick='closeInfoForm()'>Close Window</button>
                            <h2>Tell us about yourself</h2>
                            <h1>What job(s) are you interested in?</h1>
                            <label>(Please choose at least 3)</label>
                            <input id='jobsListInput' list='searchJobsList' placeholder='Search for jobs...' onkeyup='filterJobsList()' onchange='selectJob()'>       
                            <datalist id='searchJobsList'>");

list($job_codes, $job_names) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
for($job_index = 0; $job_index < sizeof($job_codes); $job_index++) {
    if(in_array($job_codes[$job_index], $chosenJobsCode)) {
        $page->addPageBodyItem("<option disabled value='{$job_names[$job_index]}' id='{$job_names[$job_index]}' name='{$job_codes[$job_index]}'>");
    } else {
        $page->addPageBodyItem("<option value='{$job_names[$job_index]}' id='{$job_names[$job_index]}' name='{$job_codes[$job_index]}'>");
    }
}

                $page->addPageBodyItem("</datalist>   
                    <p>Can't find one? <strong>Suggest one!</strong></p>
                    <div id='suggestion'>");

for($i = 0; $i < sizeof($chosenJobsName); $i++) {
    $page->addPageBodyItem("<p onclick='removeJob(`{$chosenJobsCode[$i]}`, `{$chosenJobsName[$i]}`)' id='{$chosenJobsCode[$i]}' class='clickable chosenJob' style='background-color: #017EFC; color: #FFFFFF; font-size: small;
            font-weight: normal; width: fit-content; padding: 10px; 
            margin: 2.5px 5px 2.5px 0; border-radius: 5px;'>{$chosenJobsName[$i]} X</p>");
}

                    $page->addPageBodyItem("<script>getSelectedJob();</script>
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
    </div>");

$sqlQuery = $conn->query("SELECT * FROM sep_user_info 
                     INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id
                     INNER JOIN sep_languages ON sep_user_info.user_language = sep_languages.language_code
                     INNER JOIN sep_regions ON sep_user_info.user_region = sep_regions.region_code
                     WHERE user_email = '{$_SESSION['email']}'");
if($sqlQuery){
    while($rowObj = $sqlQuery->fetchObject()) {
        $page->addPageBodyItem("
            <div id='bookContainer'>
                <h1>My Details</h1>
                    <div>Email: {$_SESSION['email']}</div>
                    <div>First name: {$rowObj->user_fname}</div>
                    <div id='title'>Last name: {$rowObj->user_lname}</div>
                    <div id='year'>Gender: {$rowObj->user_gender}</div>
                    <div id='description'>Language: {$rowObj->language_name}</div>
                    <div>Region: {$rowObj->region_name}</div><br>
                    <div>Favourite Categories: </div><br>");

                    foreach($chosenJobsName as $jobName) {
                        $page->addPageBodyItem("<div>{$jobName}</div>");
                    }

            $page->addPageBodyItem("</div>");
    }
}
$page->addPageBodyItem("<button onclick='displayEditProfile();' style='margin: 25px auto;'>Edit Profile</button>
<button id='showPasswordForm' style='margin: 25px auto;'>Change Password</button>
</div>");

$page->displayPage();
?>
