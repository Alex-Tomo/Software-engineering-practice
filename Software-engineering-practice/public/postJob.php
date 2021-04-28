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

$page->addPageBodyItem("
<div id='postJobContainer'>
    <h1>Post a Job</h1>
    <form id='postJobForm' style='position: relative;'>
        <select style='border: 1px solid #017EFC; margin-bottom: 10px; border-radius: 10px; padding-left: 10px;'>
            <option name='0'>Select a Category</option>");

list($job_codes, $job_names) = selectAll($conn, 'job_code', 'job_name', 'sep_jobs_list', 'job_name');
for($job_index = 0; $job_index < sizeof($job_codes); $job_index++) {
    $page->addPageBodyItem("<option id='{$job_names[$job_index]}' name='{$job_codes[$job_index]}'>{$job_names[$job_index]}</option>");
}

        $page->addPageBodyItem("</select>
        <textarea rows='10' cols='52' name='desc' placeholder='Job Description...'></textarea>
        <input name='price' type='text' placeholder='Job Price... i.e. £12.99/h'>
        <button id='postJobBtn' class='clickable' type='button'>Post Job</button> 
    </form>
</div>");

$page->displayPage();


