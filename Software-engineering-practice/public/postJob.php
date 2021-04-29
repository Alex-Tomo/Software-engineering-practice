<?php
    require('../db_connector.php');
    $conn = getConnection();

    require('../database_functions.php');

    include('../pageTemplate.php');

    if (!$_SESSION['loggedin']) {
        header('Location: signin.php');
    }

$page = new pageTemplate('Logged In Home');
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");
$page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
$page->addJavaScript("<script src=\"./js/selectJobsList.js\"></script>");
$page->addJavaScript("<script src=\"./js/postJob.js\"></script>");

$page->addPageBodyItem("
<div id='postJobContainer'>
    <h1>Post a Job</h1>
    <form id='postJobForm' style='position: relative;' enctype='multipart/form-data'>
        <input name='title' id='title' type='text' placeholder='Job Title...' style='margin-bottom: 20px;'>
        <textarea rows='10' cols='40' name='desc' id='desc' placeholder='Job Description...'></textarea>
        <input name='price' id='price' type='text' placeholder='Job Price... i.e. £12.99/h' style='margin: 20px auto;'>
        <input id='image' type='file' value='Add Image'>
        <input id='jobsListInput' list='searchJobsList' placeholder='Search for jobs...' style='border: 1px solid #017EFC; margin-top: 20px; border-radius: 10px; padding: 10px;' onkeyup='filterJobsList()' onchange='selectJob()'>
        <datalist id='searchJobsList'>
            <option name='0'>Select a Category</option>");

list($job_codes, $job_names) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
for($job_index = 0; $job_index < sizeof($job_codes); $job_index++) {
    $page->addPageBodyItem("<option value='{$job_names[$job_index]}' id='{$job_names[$job_index]}' name='{$job_codes[$job_index]}'>");
}

                $page->addPageBodyItem("</datalist>
                    <div id='suggestion'></div>");

        $page->addPageBodyItem("<button id='postJobBtn' class='clickable' type='button'>Post Job</button> 
    </form>
</div>");

$page->displayPage();


