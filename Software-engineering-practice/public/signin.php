<?php

    // Require
    require('../pageTemplate.php');

    // Initial variables, get the page template class
    $page = new pageTemplate('Sign In');

    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/formStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");

    // Main content
    $page->addPageBodyItem("
    <div id='registerForm'>
        <form action='handlers/signinHandler.php' method='POST'>
            <h2>Login</h2>
            <label for='email'>Email</label><br>
            <input type='email' id='email' name='email' placeholder='Enter your email'><br>
            <label for='password'>Password</label><br>
            <input type='password' id='password' name='password' placeholder='Enter your password'><br>
            <input class='clickable' type='submit' value='Login'>
            <p class='para'>Don't have an account?</p>
            <a href='./register.php'>Create one!</a>
        </form>
    </div>");

    $page->displayPage();

?>
