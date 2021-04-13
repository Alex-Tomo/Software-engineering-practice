<?php
    // POST Variables
    // email
    // password

    require('../../db_connector.php');
    $conn = getConnection();

    list($vals, $errs) = verifyLogin($conn);
    if($errs) failedLogin($errs);
    else header('Location: ../loggedinHome.php');

    function verifyLogin($connection) {
        $values = array();
        $errors = array();

        $values['email'] = isset($_POST['email']) ? trim($_POST['email']) : null;
        $values['password'] = isset($_POST['password']) ? trim($_POST['password']) : null;

        if(empty($values['email'])) {
            array_push($errors, "<p style='color: red;'>Email field cannot be empty</p>");
        }

        if(empty($values['password'])) {
            array_push($errors, "<p style='color: red;'>Password field cannot be empty</p>");
        }

        $stmt = $connection->query("SELECT user_password FROM users WHERE user_email = '{$values['email']}'");
        $row = $stmt->rowCount();
        if($row == 1) {
            $data = $stmt->fetch();
            if(password_verify($values['password'], $data['user_password'])) {
                include ('../../pageTemplate.php');
                $page = new pageTemplate('Sign In');
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
        $page->addCSS('../css/formStyling.css');
        $page->addCSS('../css/footerStyling.css');
        $page->addCSS('../css/headerStyling.css');
        $page->addJavaScript("<script src=\"../js/navBar.js\"></script>");
        $page->addPageBodyItem("<div id='registerForm'>
            <form action='./signin_handler.php' method='POST'>
            <h2>Login</h2>");
        foreach($errors as $error) {
            $page->addPageBodyItem($error);
        }
        $page->addPageBodyItem("<label for='email'>Email</label><br>
                <input type='email' id='email' name='email' placeholder='Enter your email'><br>
                <label for='password'>Password</label><br>
                <input type='password'  id='password' name='password' placeholder='Enter your password'><br>
                <input type='submit' value='Login'>
                <p class='para'>Don't have an account?</p>
                <a onclick='registerPage()' id='login' style='text-decoration: underline; cursor: pointer;'>Create one!</a>
            </form>
        </div>");
        $page->displayPage();
    }

?>