<?php

    // Requires
    require('../pageTemplate.php');

    // Initial variables
    // Get the page template class
    $page = new pageTemplate('Home');

    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/reviews.js\"></script>");

    // Main content
    $page->addPageBodyItem("
        <!--Top section-->
        <div id='top'>
            <div id='header2'>
                <h2>Find the right </h2><h2 id='textChange'> specialist</h2><h2> just when you need it!</h2>
                <p>This platform helps you to skipCV and find the right job on the right time!</p>
                <button class='clickable' onclick='openPage(`register.php`)'>Get started!</button>
            </div>
            <div id='topImage'>
                <img id='mobileImg' src='assets/discuss_talent_requirements_mob.svg' alt='Talent requirements'>
                <img id='desktopImg' src='assets/discuss_talent_requirements_des.svg' alt='Talent requirements'>
            </div>
        </div>

        <!--Info section-->
        <div id='infoSec'>
            <div id='infoSecAbout'>
                <h3><img src='assets/info.svg' alt='Exclamation mark'>What is it about?</h3>
                <p>The project skipCV is for employees and employers who are in need of each other at certain times.</p>
            </div>
    
            <div id='infoSecFor'>
                <h3><img src='assets/help-circle.svg' alt='Question mark'>What is it for?</h3>
                <p>The platform is for employees, employers who on their free time needs each other and would like to spend their free time in a meaningful way.</p>
            </div>
        </div>

        <!--Logo section-->
        <div id='logoContainer'>
            <img id='logo2' src='assets/logo1.png' alt='Logo'>
            <img id='logo1' src='assets/logo2.png' alt='Logo'>
            <img id='logo3' src='assets/logo3.png' alt='Logo'>
            <img id='logo4' src='assets/logo4.png' alt='Logo'>
        </div>

        <!--Benefits section-->
        <div id='container'>
            <div id='benefitsSec'>
                <!--YouTube video embedded-->
                <iframe id='videoContainer' src='https://www.youtube.com/embed/1niK4K-Kwts' title='Benefits Section' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>
            </div>
        
        <!--Swipe section-->
        <div id='swipeSecMob'>
            <img src='assets/swipe_left.svg' alt='Swipe left icon'>
            <h4>Swipe left</h4>
            <p>We provide the templated CV for you to fill out and place in the platform</p>

            <img src='assets/swipe_right.svg' alt='Swipe right icon'>
            <h4>Swipe right</h4>
            <p>Browse through offers, engage and wait until someone messages you</p>

            <img src='assets/comment2.svg' alt='Message icon'>
            <h4>Message</h4>
            <p>When you get the message, show the best of yourself!</p>
        </div>
    
        <div id='swipeSecDes'>
            <div id='swipeChild'>
                <img src='assets/swipe_left.svg' alt='Swipe left icon'>
                <h4>Fill out CV</h4>
                <p>We provide the templated CV for you to fill out and place in the platform</p>
            </div>

            <div id='swipeChild2'>
                <img src='assets/swipe_right.svg' alt='Swipe right icon'>
                <h4>Browse through offers</h4>
                <p>Browse through offers, engage and wait until someone messages you</p>
            </div>

            <div id='swipeChild3'>
                <img src='assets/comment2.svg' alt='Message icon'>
                <h4>Message</h4>
                <p>When you get the message, show the best of yourself!</p>
            </div>
        </div>        
    
        <!--Review section-->
        <div id='slideshow_container'>
            <h5>Trusted by the world’s most innovative businesses – big and small</h5>
            <div class='mySlides'>
                <q>The best application for fast job all imaginable people needs. Walk dogs, fix cars, find freelancers.</q>
                <p class='author'>Viella Malkovich</p>
                <p class='jobDes'>Creative Director at Johnson</p>
            </div>
            
            <div class='mySlides'>
                <q>Really good application to find or post jobs.</q>
                <p class='author'>Jean Hilton</p>
                <p class='jobDes'>Programmer</p>
            </div>
            
            <div class='mySlides'>
                <q>Best application! Registering is really easy and quick to do.</q>
                <p class='author'>John Smith</p>
                <p class='jobDes'>Director</p>
            </div>
               
            <a class='prev'><img src='assets/leftArrow.svg' alt='Left arrow'></a>
            <a class='next'><img src='assets/rightArrow.svg' alt='Right arrow'></a>
        </div>
            
        <div class='dot-container'>
            <span class='dot dot1'></span> 
            <span class='dot dot2'></span> 
            <span class='dot dot3'></span> 
        </div>
    </div>");

    // Display the page
    $page->displayPage();

?>

