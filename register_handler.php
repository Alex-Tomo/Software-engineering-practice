<?php
    require('db_connector.php');
    $conn = getConnection();

    list($vals, $err) = verifyRegistration($conn);
    if($err) echo failedRegistration($err);
    else successfulRegistration($vals, $conn);

    function verifyRegistration($connection) {
        $values = array();
        $errors = array();

        $values['email'] = isset($_POST['email']) ? trim($_POST['email']) : null;
        $values['password'] = isset($_POST['password']) ? trim($_POST['password']) : null;
        $values['password2'] = isset($_POST['password2']) ? trim($_POST['password2']) : null;
    
        // TODO : Make sure all fields are of the correct type

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

        $stmt = $connection->prepare("SELECT user_email FROM users WHERE user_email = ?");
        $stmt->bindParam(1, $values['email'], PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->rowCount();
        if($row != 0) {
            array_push($errors, "<p style='color: red;'>Email already exists</p>");
        }

        return array($values, $errors);
    }

    function failedRegistration($errors) {
        $registerForm = "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <link rel='stylesheet' href='formStyling.css'>                
            <link rel='stylesheet' href='footerStyling.css'>
            <link rel='stylesheet' href='headerStyling.css'>
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
            <title>Register</title>
        </head>
        <body> 
        
        <header>
            <h1>skip</h1><h1 id='logoText2'>CV</h1>
            <h2>Registration</h2>
        </header>";

        foreach($errors as $error) { $registerForm .= $error; }

        $registerForm .= "<form action='register_handler.php' method='POST'>
                <label for='email'>Email</label><br>
                <input type='email' id='email' name='email' placeholder='Your email'><br>
                <label for='password'>Password</label><br>
                <input type='password'  id='password' name='password' placeholder='Create password'><br>
                <label for='password2'>Repeat password</label><br>
                <input type='password' id='password2' name='password2' placeholder='Repeat password'><br>
                <input type='submit' value='Sign Up'>
            </form>
        </div>

        </body>
        </html>";
        
        return $registerForm;
    }

    function successfulRegistration($values, $connection) {
        $hashedPassword = password_hash($values['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (user_email, user_password) VALUES ('{$values['email']}', '{$hashedPassword}')";
        $connection->query($sql);
        header('Location: signin.html');
    }
    
?>