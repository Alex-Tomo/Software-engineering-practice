<?php

    // Requires
    require('../pageTemplate.php');
    require('../db_connector.php');
    require('../database_functions.php');

    // If the user is not loggedin, redirect the user to the signin page
    if (!$_SESSION['loggedin']) { header('Location: signin.php'); }

    // Initial variables, get the database connection, get the page template class
    $conn = getConnection();
    $page = new pageTemplate('Logged In Home');

    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/selectPostJobsList.js\"></script>");

    // Main content
    $page->addPageBodyItem("
    <div id='postJobContainer'>
        <h1>Post a Job</h1>
        <form id='postJobForm' style='position: relative;' enctype='multipart/form-data' method='post' action='handlers/postJobHandler.php'>
            <input name='title' id='title' type='text' placeholder='Job Title...' style='margin-bottom: 20px;'>
            <textarea rows='10' cols='40' name='desc' id='desc' placeholder='Job Description...'></textarea>
            <input name='price' id='price' type='text' placeholder='Job Price... i.e. £12.99/h' style='margin: 20px auto;'>
            <input id='image' name='image' type='file' value='Add Image'>
            <input id='jobsListInput' list='searchJobsList' placeholder='Job Category(s)' style='border: 1px solid #017EFC; margin-top: 20px; border-radius: 10px; padding: 10px;' onkeyup='filterJobsList()' onchange='selectJob()'>
            <datalist id='searchJobsList'>
                <option name='0'>Job Category(s)</option>");

// Get the job categories and job codes from the database
list($jobCodes, $jobNames) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
for($jobIndex = 0; $jobIndex < sizeof($jobCodes); $jobIndex++) {
    $page->addPageBodyItem("<option value='{$jobNames[$jobIndex]}' id='{$jobNames[$jobIndex]}' name='{$jobCodes[$jobIndex]}'>");
}

            $page->addPageBodyItem("
            </datalist>
            <div id='suggestion'></div>
            <button id='postJobBtn' class='clickable' type='submit'>Post Job</button> 
        </form>
    </div>");

    $page->displayPage();

?>

