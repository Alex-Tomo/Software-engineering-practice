<?php
    require('../db_connector.php');
    $conn = getConnection();

    include('../pageTemplate.php');

    if (!$_SESSION['loggedin']) {
        header('Location: signin.php');
    }

$page = new pageTemplate('Logged In Home');
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");
$page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
$page->addJavaScript("<script src=\"./js/popupForm.js\"></script>");

$page->addPageBodyItem("
<div id='pageContainer2'>
    <form method='post' action='handlers/post_job_handler.php'>
        <input name='title' type='text' placeholder='Job Title...'> 
        <textarea name='desc' placeholder='Job Description...'></textarea> 
        <input name='price' type='text' placeholder='Job Price... i.e. £12.99/h'>
        <input type='submit' value='Post Job'> 
    </form>
</div>");

$page->displayPage();


