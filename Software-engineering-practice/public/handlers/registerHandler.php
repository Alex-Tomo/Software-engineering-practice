<?php

    // handles the registration process and inserts the users data if no errors

    // Require
    require('../../db_connector.php');
    require('../../database_functions.php');

    try {
        // Get database connection
        $conn = getConnection();

        list($vals, $err) = verifyRegistration($conn); // verify data

        if ($err) { // Redisplay the signin page with errors
            failedRegistration($err);
        } else { // Redirect the user to the login page
            successfulRegistration($vals, $conn);
        }

    } catch(Exception $ex) {
        logError($ex);
    }

    function verifyRegistration($connection) {
        $values = array();
        $errors = array();

        $values['email'] = isset($_POST['email']) ? sanitizeData(trim($_POST['email'])) : null;
        $values['password'] = isset($_POST['password']) ? sanitizeData(trim($_POST['password'])) : null;
        $values['password2'] = isset($_POST['password2']) ? sanitizeData(trim($_POST['password2'])) : null;

        // Validate the data
        if(empty($values['email'])) {
            array_push($errors, "<p style='color: red;'>Email field cannot be empty</p>");
        }
        if(empty($values['password'])) {
            array_push($errors, "<p style='color: red;'>Password field cannot be empty</p>");
        } else if(strlen($values['password']) < 8) {
            array_push($errors, "<p style='color: red;'>Password must be at least 8 characters long</p>");
        }
        if(($values['password'] != $values['password2'])) {
            array_push($errors, "<p style='color: red;'>Passwords do not match</p>");
        }

        // Select the users emails to check if the email actually exists
        $selectUserEmailStatement = $connection->prepare('SELECT user_email FROM sep_users WHERE user_email = ?');
        $selectUserEmailStatement->bindParam(1, $values['email']);
        $selectUserEmailStatement->execute();
        $numberOfRows = $selectUserEmailStatement->rowCount();

        // more validation
        if($numberOfRows != 0) {
            array_push($errors, "<p style='color: red;'>Email already exists</p>");
        }

        return array($values, $errors);
    }


    function failedRegistration($errors) {

        include ('../../pageTemplate.php');

        $page = new pageTemplate('Register');
        $page->addCSS("<link rel=\"stylesheet\" href=\"../css/formStyling.css\">");
        $page->addCSS("<link rel=\"stylesheet\" href=\"../css/footerStyling.css\">");
        $page->addCSS("<link rel=\"stylesheet\" href=\"../css/headerStyling.css\">");
        $page->addJavaScript("<script src=\"../js/navBar.js\"></script>");
        $page->addPageBodyItem("<form action='./registerHandler.php' method='POST'>
            <h2>Register</h2>");

        foreach($errors as $error) { $page->addPageBodyItem($error); }

        $page->addPageBodyItem("<label for='email'>Email</label><br>
                <input type='email' id='email' name='email' placeholder='Your email'><br>
                <label for='password'>Password</label><br>
                <input type='password'  id='password' name='password' placeholder='Create password'><br>
                <label for='password2'>Repeat password</label><br>
                <input type='password' id='password2' name='password2' placeholder='Repeat password'><br>
                <input class='clickable' type='submit' value='Sign Up'>
                <p class='para'>Creating the account you agree with our</p>
                <a id='tandc' href='#'>Terms & Conditions</a>
                <p class='para'>Already have an account?</p>
                <a onclick='openPage(`signin.php`)' style='text-decoration: underline; cursor: pointer;'>Login</a>             
            </form>
        </div>");

        $page->displayPage();
    }

    function successfulRegistration($values, $connection) {
        // hash the users password and insert the users details into the database
        $hashedPassword = password_hash($values['password'], PASSWORD_DEFAULT);
        $statement = $connection->prepare("INSERT INTO sep_users (user_email, user_password) VALUES (?, ?)");
        $statement->bindParam(1, $values['email']);
        $statement->bindParam(2, $hashedPassword);
        $statement->execute();

        header('Location: ../signin.php');
    }
    
?>