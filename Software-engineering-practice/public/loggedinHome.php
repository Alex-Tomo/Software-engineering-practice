<?php
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
        <h1>Recommended for you</h1>
        
        <div class='resultChild clickable' onclick='openPage(`serviceInner.php`)'>
            <div class='replaceWithImg2'></div>
                <div class='resultText'>
                    <img src='assets/photo.svg'>
                    <h2>Potential employer</h2>
                    <h3>A Python developer is needed</h3>
                    <p>Text to write in a job description and Text to write in a job description and Text to write in a job description and</p>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star'></span>
                    <p class='price'>£12.00/h</p>
                </div>
        </div>
            
        <div class='resultChild clickable' onclick='openPage(`serviceInner.php`)'>
            <div class='replaceWithImg2'></div>
                <div class='resultText'>
                    <img src='assets/photo.svg'>
                    <h2>Potential employer</h2>
                    <h3>A Python developer is needed</h3>
                    <p>Text to write in a job description and Text to write in a job description and Text to write in a job description and</p>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star'></span>
                    <p class='price'>£12.00/h</p>
                </div>
        </div>
            
        <div class='resultChild clickable' onclick='openPage(`serviceInner.php`)'>
            <div class='replaceWithImg2'></div>
                <div class='resultText'>
                    <img src='assets/photo.svg'>
                    <h2>Potential employer</h2>
                    <h3>A Python developer is needed</h3>
                    <p>Text to write in a job description and Text to write in a job description and Text to write in a job description and</p>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star'></span>
                    <p class='price'>£12.00/h</p>
                </div>
        </div>
        
        <div class='resultChild clickable' onclick='openPage(`serviceInner.php`)'>
            <div class='replaceWithImg2'></div>
                <div class='resultText'>
                    <img src='assets/photo.svg'>
                    <h2>Potential employer</h2>
                    <h3>A Python developer is needed</h3>
                    <p>Text to write in a job description and Text to write in a job description and Text to write in a job description and</p>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star'></span>
                    <p class='price'>£12.00/h</p>
                </div>
        </div>
        
        <div class='resultChild clickable' onclick='openPage(`serviceInner.php`)'>
            <div class='replaceWithImg2'></div>
                <div class='resultText'>
                    <img src='assets/photo.svg'>
                    <h2>Potential employer</h2>
                    <h3>A Python developer is needed</h3>
                    <p>Text to write in a job description and Text to write in a job description and Text to write in a job description and</p>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star checked'></span>
                    <span class='fa fa-star'></span>
                    <p class='price'>£12.00/h</p>
                </div>
        </div>
    </div>      
    
    <div id='categories'>
        <h1>Popular categories</h1>
        <button class='clickable' type='submit'>Developer</button>
            <button class='clickable' type='submit'>Bank Accountant</button>
            <button class='clickable' type='submit'>Bank Accountant</button>        
            <button class='clickable' type='submit'>Developer</button>
            <button class='clickable' type='submit'>Developer</button>
            <button class='clickable' type='submit'>Bank Accountant</button>
            <button class='clickable' type='submit'>Bank Accountant</button>        
            <button class='clickable' type='submit'>Developer</button>
            <button class='clickable' type='submit'>Developer</button>
            <button class='clickable' type='submit'>Bank Accountant</button>
    </div>  
   
        
    <div id='recViewedParent'>
        <h1>Recently viewed</h1>        
        <div class='recViewedChild clickable' onclick='openPage()'>
            <div class='replaceWithImg'></div>
            <h4>A Python developer is needed for a project</h4>
            <p>£12.00/h</p>  
        </div>
        
        <div class='recViewedChild clickable'>
            <div class='replaceWithImg'></div>
            <h4>A Python developer is needed for a project</h4>
            <p>£12.00/h</p>  
        </div>
        
        <div class='recViewedChild clickable'>
            <div class='replaceWithImg'></div>
            <h4>A Python developer is needed for a project</h4>
            <p>£12.00/h</p>  
        </div>
        
        <div class='recViewedChild clickable'>
            <div class='replaceWithImg'></div>
            <h4>A Python developer is needed for a project</h4>
            <p>£12.00/h</p>  
        </div>
    </div>
</div>");

$page->displayPage();
?>

<!--    <img src='assets/appStore.svg' alt='App Store'>-->
<!--    <img src='assets/googlePlay.svg' alt='App Store'>-->