<?php

    // handles the signin process and allows the user to process
    // if all the details are correct

    // Require
    require('../../db_connector.php');
    require('../../database_functions.php');

    try {

        // Get database connection
        $conn = getConnection();

        list($vals, $errs) = verifyLogin($conn); // verify login details

        if ($errs) { // if errors redisplay the login page with errors
            failedLogin($errs);
        } else { // Redirect user to loggedin homepage and set Session
            header('Location: ../loggedinHome.php');
        }

    } catch(Exception $ex) {
        logError($ex);
    }

    function verifyLogin($connection) {
        $values = array();
        $errors = array();

        // Get the variables
        $values['email'] = (isset($_POST['email']) && !empty($_POST['email']))
            ? sanitizeData(trim($_POST['email'])) : null;
        $values['password'] = (isset($_POST['password']) && !empty($_POST['password']))
            ? sanitizeData(trim($_POST['password'])) : null;

        // Validate the data
        if(empty($values['email'])) {
            array_push($errors, "<p style='color: red;'>Email field cannot be empty</p>");
        }
        if(empty($values['password'])) {
            array_push($errors, "<p style='color: red;'>Password field cannot be empty</p>");
        }

        // get the user password from the database if the user email exists
        $statement = $connection->prepare("SELECT user_password FROM sep_users WHERE user_email = ?");
        $statement->bindParam(1, $values['email']);
        $statement->execute();

        // if the user email exists
        if($statement->rowCount() > 0) {
            $data = $statement->fetch();

            // if the passwords match
            if(password_verify($values['password'], $data['user_password'])) {

                include ('../../pageTemplate.php');

                // set the loggedin session to true
                $page = new pageTemplate('Sign In');
                $page->setSession('email', $values['email']);
                $page->setSession('loggedin', true);

                // update the database to say the user is loggedin
                $statement = $connection->prepare("UPDATE sep_users SET user_online = true WHERE user_email = ?");
                $statement->bindParam(1, $_SESSION['email']);
                $statement->execute();

            } else {
                array_push($errors, "<p style='color: red;'>Incorrect email or password</p>");
            }
            //if the email does not exist
        } else {
            array_push($errors, "<p style='color: red;'>Incorrect email or password</p>");
        }

        return array($values, $errors);
    }


    // redisplay the login page
    function failedLogin($errors) {

        include ('../../pageTemplate.php');

        $page = new pageTemplate('Sign In');
        $page->addCSS("<link rel=\"stylesheet\" href=\"../css/formStyling.css\">");
        $page->addCSS("<link rel=\"stylesheet\" href=\"../css/footerStyling.css\">");
        $page->addCSS("<link rel=\"stylesheet\" href=\"../css/headerStyling.css\">");
        $page->addJavaScript("<script src=\"../js/navBar.js\"></script>");
        $page->addPageBodyItem("<div>
            <form action='./signinHandler.php' method='POST'>
            <h2>Login</h2>");

        foreach($errors as $error) { $page->addPageBodyItem($error); }

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