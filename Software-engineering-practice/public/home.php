<?php
    include('../pageTemplate.php');
    $page = new pageTemplate('Home');
    $page->addCSS('./css/styling.css');
    $page->addCSS('./css/footerStyling.css');
    $page->addCSS('./css/headerStyling.css');
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/reviews.js\"></script>");

    $page->addPageBodyItem("
        <div id='top'>
            <div id='header2'>
                <h2>Find the right </h2><h2 id='textChange'>specialist</h2><h2> just when you need it!</h2>
                <p>This platform helps you to skipCV and find the right job on the right time!</p>
                <button>Get started!</button>
            </div>
            <div id='topImage'>
                <img id='mobileImg' src='assets/discuss_talent_requirements_mob.svg' alt='Talent requirements'>
                <img id='desktopImg' src='assets/discuss_talent_requirements_des.svg' alt='Talent requirements'>
            </div>
        </div>

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

        <div id='startupLith'>
    
            <!--    Add image-->
    
        </div>

        <div id='container'>
            <div id='benefitsSec'>
                <div id='text'>
                    <h3>Easy to use</h3>
                    <h4>Easily find a job or employees for your job</h4>
                    <p>Startups, small companies and teams, hard workers and similar people. Better stories.</p>
                </div>
                    <img id='downIcons' src='assets/download_icons.png' alt='Download icons'>
        
                <div id='text2'>
                    <h3>Completely free</h3>
                    <h4>Hire people and work completely free of fees</h4>
                    <p>Startups, small companies and teams, hard workers and similar people. Better stories.</p>
                </div>
                    <img id='clipCalc' src='assets/clip_calculating.png' alt='Clip calculating'>
        
                <div id='text3'>
                    <h3>Communicate better</h3>
                    <h4>Built for people in need of a job or employees</h4>
                    <p id='bottomPara'>More and more people are searching how to spend their spare time meaningfully. This platform solves the problem.</p>
                </div>
                    <img id='clipDone' src='assets/clip_done.png' alt='Clip done'>
            </div>
        
            <div id='swipeSecMob'>
                <img src='assets/swipe_left.svg'>
                <h4>Swipe left</h4>
                <p>We provide the templated CV for you to fill out and place in the platform</p>
    
                <img src='assets/swipe_right.svg'>
                <h4>Swipe right</h4>
                <p>Browse through offers, engage and wait until someone messages you</p>
    
                <img src='assets/comment2.svg'>
                <h4>Message</h4>
                <p>When you get the message, show the best of yourself!</p>
            </div>
        
            <div class='slideshow-container'>
                <div class='mySlides'>
                    <q>I love you the more in that I believe you had liked me for my own sake and for nothing else</q>
                    <p class='author'>- John Keats</p>
                </div>
                
                <div class='mySlides'>
                    <q>But man is not made for defeat. A man can be destroyed but not defeated.</q>
                    <p class='author'>- Ernest Hemingway</p>
                </div>
                
                <div class='mySlides'>
                    <q>I have not failed. I've just found 10,000 ways that won't work.</q>
                    <p class='author'>- Thomas A. Edison</p>
                </div>
                
                <a class='prev'>❮</a>
                <a class='next'>❯</a>
            </div>
                
            <div class='dot-container'>
                <span class='dot' onclick='currentSlide(1)'></span> 
                <span class='dot' onclick='currentSlide(2)'></span> 
                <span class='dot' onclick='currentSlide(3)'></span> 
            </div>
        
            <div id='swipeSecDes'>
                <div id='swipeChild'>
                    <img src='assets/swipe_left.svg'>
                    <h4>Fill out CV</h4>
                    <p>We provide the templated CV for you to fill out and place in the platform</p>
                </div>
    
                <div id='swipeChild2'>
                    <img src='assets/swipe_right.svg'>
                    <h4>Browse through offers</h4>
                    <p>Browse through offers, engage and wait until someone messages you</p>
                </div>
    
                <div id='swipeChild3'>
                    <img src='assets/comment2.svg' alt=''>
                    <h4>Message</h4>
                    <p>When you get the message, show the best of yourself!</p>
                </div>
            </div>
        </div>
    </div>
</div>");

    $page->displayPage();
?>

<!--    <img src='assets/appStore.svg' alt='App Store'>-->
<!--    <img src='assets/googlePlay.svg' alt='App Store'>-->

