<?php
    // POST Variables
    // email
    // password

    require('../../db_connector.php');
    $conn = getConnection();

    list($vals, $errs) = verifyLogin($conn);
    if($errs) echo failedLogin($errs);
    else header('Location: ../home.php');

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
                // successful login Stuff here
                echo "Login Successful!";
            } else {
                array_push($errors, "<p style='color: red;'>Incorrect email or password</p>");
            }
        } else {
            array_push($errors, "<p style='color: red;'>Incorrect email or password</p>");
        }

        return array($values, $errors);
    }

    function failedLogin($errors) {
        $login = "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <link rel='stylesheet' href='../css/formStyling.css'>
            <link rel='stylesheet' href='../css/footerStyling.css'>
            <link rel='stylesheet' href='../css/headerStyling.css'>
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
        
            <title>Sign In</title>
        </head>
        <body>
        <header>
            <h1>skip</h1><h1 id='logoText2'>CV</h1>
            <h2>Login</h2>
        </header>";

        foreach($errors as $error) { $login .= $error; }

        $login .= "<div id='registerForm'>
            <form action='./signin_handler.php' method='POST'>
                <label for='email'>Email</label><br>
                <input type='email' id='email' name='email' placeholder='Enter your email'><br>
                <label for='password'>Password</label><br>
                <input type='password'  id='password' name='password' placeholder='Enter your password'><br>
                <input type='submit' value='Login'>
            </form>
        </div>
        </body>
        </html>";
        
        return $login;
    }

?>