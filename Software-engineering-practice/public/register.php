<?php

    include ('../pageTemplate.php');
    $page = new pageTemplate('Register');
    $page->addCSS('./css/formStyling.css');
    $page->addCSS('./css/footerStyling.css');
    $page->addCSS('./css/headerStyling.css');
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addPageBodyItem("<form action='./handlers/register_handler.php' method='POST'>
        <h2>Register</h2>
        <label for='email'>Email</label><br>
        <input type='email' id='email' name='email' placeholder='Your email'><br>
        <label for='password'>Password</label><br>
        <input type='password' name='password' placeholder='Create password'><br>
        <label for='password2'>Repeat password</label><br>
        <input type='password' id='password2' name='password2' placeholder='Repeat password'><br>
        <input type='submit' value='Sign Up'>
        <p class='para'>Creating the account you agree with our</p>
        <a id='tandc' href='#'>Terms & Conditions</a>
        <p class='para'>Already have an account?</p>
        <a href='./signin.php'>Login</a>
    </form>");
    $page->displayPage();


//<!--<div>-->
//<!--    <img src='photos/search.svg' alt='Google search logo'>-->
//<!--    <p>Register manually</p>-->
//<!--</div>-->
//
//
//<!--<div id='registerForm2'>-->
//<!--    <h2>General information</h2>-->
//<!--    <form>-->
//<!--        <label for='fname'>First name</label><br>-->
//<!--        <input type='text' id='fname' name='fname' placeholder='Your name'><br>-->
//<!--        <label for='lname'>Last name</label><br>-->
//<!--        <input type='text'  id='lname' name='lname' placeholder='Last name'><br>-->
//<!--        <label for='gender'>Sex</label>-->
//<!--        <select id='gender' name='gender'>-->
//<!--            <option value='0'> Select </option>-->
//<!--            <option value='male'>Male</option>-->
//<!--            <option value='female'>Female</option>-->
//<!--            <option value='pnts'>Prefer not to say</option>-->
//<!--        </select>-->
//<!--        <label for='birthday'>Birth date</label><br>-->
//<!--        <input type='date' id='birthday' name='birthday'><br>-->
//<!--        <label for='lang'>Preferred language</label><br>-->
//<!--        <select id='lang' name='lang'>-->
//<!--            <option value='0'>Choose your language</option>-->
//<!--        </select>-->
//<!--        <label for='reg'>Region</label><br>-->
//<!--        <select id='reg' name='reg'>-->
//<!--            <option value='0'>Choose your region</option>-->
//<!--        </select>-->
//<!--&lt;!&ndash;        <div id='tandc'>&ndash;&gt;-->
//<!--&lt;!&ndash;            <p>Creating the account you agree with our <a id='tandcLink' href=''>Terms & Conditions</a>.</p>&ndash;&gt;-->
//<!--&lt;!&ndash;        </div>&ndash;&gt;-->
//<!--        <input id='submit' type='submit' value='Next'>-->
//<!--    </form>-->
//<!--</div>-->
//<!--<a id='back' href=''>Back</a>-->