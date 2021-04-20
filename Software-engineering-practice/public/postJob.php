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

</div>");

$page->displayPage();


