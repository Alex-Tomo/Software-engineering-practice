<?php

    // Requires
    require('../pageTemplate.php');
    require('../db_connector.php');
    require('../database_functions.php');

    // If the user is not loggedin, redirect the user to the signin page
    if (!$_SESSION['loggedin']) { header('Location: signin.php'); }

    // Initial variables, get the database connection, get the page template class
    $conn = getConnection();
    $page = new pageTemplate('Post A Job');

    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/selectPostJobsList.js\"></script>");
    $page->addJavaScript("<script src=\"./js/notificationServer.js\"></script>");

    // Main content
    $page->addPageBodyItem("
            <div id='postJobContainer'>
                <form id='postJobForm' enctype='multipart/form-data' method='post' action='./handlers/postJobHandler.php'>
                    <h1>Post a Job</h1>
                    <label for='title'>Enter the job title</label><br>
                    <input name='title' id='title' type='text' placeholder='Job Title...'>
                    <label for='title'>Enter the job description</label><br>
                    <textarea rows='10' cols='40' name='desc' placeholder='Job Description...'></textarea>
                    <label for='title'>Enter the job price</label><br>            
                    <input name='price' id='price' type='text' placeholder='Job Price... i.e. £12.99/h'>
                    <label for='title'>Choose a relevant image</label><br>   
                    <input type='file' name='file' id='file'>
                    <label for='title'>Choose a job category</label><br> 
                    <input id='jobsListInput' list='searchJobsList' placeholder='Job Category(s)' onkeyup='filterJobsList()' onchange='selectJob()'>
                    <datalist id='searchJobsList'>
                        <option name='0'>Job Category(s)</option>");


// Get the job categories and job codes from the database
list($jobCodes, $jobNames) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
for($jobIndex = 0; $jobIndex < sizeof($jobCodes); $jobIndex++) { // start of for loop

    $page->addPageBodyItem("<option value='{$jobNames[$jobIndex]}' id='{$jobNames[$jobIndex]}' name='{$jobCodes[$jobIndex]}'>");

} // end of for loop


    $page->addPageBodyItem("
                    </datalist>
                    <div id='suggestion'></div>
                    <button id='postJobBtn' class='clickable nextLink' type='submit'>Post Job</button> 
                </form>
            </div>");

    $page->displayPage();

?>