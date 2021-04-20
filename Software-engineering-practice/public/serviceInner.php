<?php

require('../db_connector.php');
$conn = getConnection();

include('../pageTemplate.php');

if(!$_SESSION['loggedin']) {
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
    <div id='resultContainer'>
            <a id='back' class='clickable' onclick='openPage()'>< Back to list</a>
            <div id='serviceResult' class='resultChild'>
                <div class='replaceWithImg2'></div>
                    <div class='resultText'>
                        <img src='assets/photo.svg'>
                        <h2>Employer</h2>
                        <h3>A Python developer is needed</h3>
                        <p>Text to write in a job description and Text to write in a job description and Text to write in a job description and</p>
                        <span class='fa fa-star checked'></span>
                        <span class='fa fa-star checked'></span>
                        <span class='fa fa-star checked'></span>
                        <span class='fa fa-star checked'></span>
                        <span class='fa fa-star'></span>
                        <p class='price'>£12.00/h</p>
                        <button class='applyBtn clickable' onclick='openPage()'>Apply</button>
                        <a class='clickable' onclick='openPage()'>Refer a friend</a>
                    </div>
            </div> 
    </div>
    
    <div id='recViewedParent2'>
            <h1>Recently viewed</h1>
            <div class='recViewedChild'>
                <div class='replaceWithImg'></div>
                <h4>A Python developer is needed for a project</h4>
                <p>£12.00/h</p>  
            </div>
            
            <div class='recViewedChild'>
                <div class='replaceWithImg'></div>
                <h4>A Python developer is needed for a project</h4>
                <p>£12.00/h</p>  
            </div>
            
            <div class='recViewedChild'>
                <div class='replaceWithImg'></div>
                <h4>A Python developer is needed for a project</h4>
                <p>£12.00/h</p>  
            </div>
            
            <div class='recViewedChild'>
                <div class='replaceWithImg'></div>
                <h4>A Python developer is needed for a project</h4>
                <p>£12.00/h</p>  
            </div>
    </div>
</div>");

$page->displayPage();


