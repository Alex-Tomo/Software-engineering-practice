<?php

    // Requires
    require('../pageTemplate.php');

    //Initial Variables, get the page template class
    $page = new pageTemplate('Register');

    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/formStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/formErrors.js\"></script>");

    // Displays register form
    $page->addPageBodyItem("
    <div>
        <form action='handlers/registerHandler.php' method='POST'>
            <h2>Register</h2>
            <label for='email'>Email</label><br>
            <input type='email' id='email' name='email' placeholder='Your email'><br>
            <label for='password'>Password</label><br>
            <input type='password' id='password' name='password' placeholder='Create password'><br>
            <div class='error'><p style='display: none;' id='passwordLength'></p></div>
            <label for='password2'>Repeat password</label><br>
            <input type='password' id='password2' name='password2' placeholder='Repeat password'><br>
            <div class='error'><p style='display: none;' id='passwordMatch'></p></div>
            <input class='clickable' type='submit' value='Sign Up'>
            <p class='para'>Creating the account you agree with our</p>
            <a id='tandc' href='#'>Terms & Conditions</a>
            <p class='para'>Already have an account?</p>
            <a href='./signin.php'>Login</a>
        </form>
    </div>");

    $page->displayPage();

?>