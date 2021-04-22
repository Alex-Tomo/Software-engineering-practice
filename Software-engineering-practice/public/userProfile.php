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
<div class='pageContainer'>");
$sqlQuery = $conn->query("SELECT * FROM sep_user_info 
                     INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id
                     INNER JOIN sep_languages ON sep_user_info.user_language = sep_languages.language_code
                     INNER JOIN sep_regions ON sep_user_info.user_region = sep_regions.region_code
                     WHERE user_email = '".$_SESSION['email']."'");
if($sqlQuery){
    while($rowObj = $sqlQuery->fetchObject()) {
        $page->addPageBodyItem("
        <div id='bookContainer'>
            <h1>My Details</h1>
                    <div>First name: {$rowObj->user_fname}</div>
                    <div id='title'>Last name: {$rowObj->user_lname}</div>
                    <div id='year'>Gender: {$rowObj->user_gender}</div>
                    <div id='description'>Language: {$rowObj->language_name}</div>
                    <div>Region: {$rowObj->region_name}</div>
            </div>");
    }
}
$page->addPageBodyItem("
</div>");

$page->displayPage();
?>
