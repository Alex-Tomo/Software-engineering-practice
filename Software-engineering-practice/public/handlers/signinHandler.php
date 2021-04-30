<?php

    // TODO: Filter the data dynamically

    // Require
    require('../../db_connector.php');

    // Get database connection
    $conn = getConnection();

    list($vals, $errs) = verifyLogin($conn); // verify login details
    if($errs) failedLogin($errs); // if errors redisplay the login page with errors
    else header('Location: ../loggedinHome.php'); // Redirect user to loggedin homepage and set Session

    function verifyLogin($connection) {
        $values = array();
        $errors = array();

        $values['email'] = isset($_POST['email']) ? trim($_POST['email']) : null;
        $values['password'] = isset($_POST['password']) ? trim($_POST['password']) : null;

        if(!empty($values['email'])) {
            $values['email'] = filter_var($values['email'], FILTER_SANITIZE_EMAIL);
        }
        if(!empty($values['password'])) {
            $values['password'] = filter_var($values['password'], FILTER_SANITIZE_STRING);
        }

        if(empty($values['email'])) {
            array_push($errors, "<p style='color: red;'>Email field cannot be empty</p>");
        }
        if(empty($values['password'])) {
            array_push($errors, "<p style='color: red;'>Password field cannot be empty</p>");
        }

        $statement = $connection->prepare("SELECT user_password FROM sep_users WHERE user_email = ?");
        $statement->execute(array($values['email']));
        if($statement->rowCount() > 0) {
            $data = $statement->fetch();
            if(password_verify($values['password'], $data['user_password'])) {
                include ('../../pageTemplate.php');
                $page = new pageTemplate('Sign In');
                $page->setSession('email', $values['email']);
                $page->setSession('loggedin', true);
            } else {
                array_push($errors, "<p style='color: red;'>Incorrect email or password</p>");
            }
        } else {
            array_push($errors, "<p style='color: red;'>Incorrect email or password</p>");
        }

        return array($values, $errors);
    }

    function failedLogin($errors) {
        include ('../../pageTemplate.php');
        $page = new pageTemplate('Sign In');
        $page->addCSS("<link rel=\"stylesheet\" href=\"../css/formStyling.css\">");
        $page->addCSS("<link rel=\"stylesheet\" href=\"../css/footerStyling.css\">");
        $page->addCSS("<link rel=\"stylesheet\" href=\"../css/headerStyling.css\">");
        $page->addJavaScript("<script src=\"../js/navBar.js\"></script>");
        $page->addPageBodyItem("<div id='registerForm'>
            <form action='./signinHandler.php' method='POST'>
            <h2>Login</h2>");
        foreach($errors as $error) {
            $page->addPageBodyItem($error);
        }
        $page->addPageBodyItem("<label for='email'>Email</label><br>
                <input type='email' id='email' name='email' placeholder='Enter your email'><br>
                <label for='password'>Password</label><br>
                <input type='password'  id='password' name='password' placeholder='Enter your password'><br>
                <input class='clickable' type='submit' value='Login'>
                <p class='para'>Don't have an account?</p>
                <a onclick='openPage(`register.php`)' style='text-decoration: underline; cursor: pointer;'>Create one!</a>
            </form>
        </div>");
        $page->displayPage();
    }

?>