<?php
    require('db_connector.php');
    $conn = getConnection();

    list($vals, $err) = verifyRegistration($conn);
    if($err) failedRegistration($err);
    else successfulRegistration($vals, $conn);

    function verifyRegistration($connection) {
        $values = array();
        $errors = array();

        $values['email'] = isset($_POST['email']) ? trim($_POST['email']) : null;
        $values['password'] = isset($_POST['password']) ? trim($_POST['password']) : null;
        $values['password2'] = isset($_POST['password2']) ? trim($_POST['password2']) : null;
    
        if(empty($values['email'])) {
            array_push($errors, "<p style='color: red;'>Email cannot be empty</p>");
        } 
    
        if(empty($values['password'])) {
            array_push($errors, "<p style='color: red;'>Password cannot be empty</p>");
        }
    
        if(empty($values['password2'])) {
            array_push($errors, "<p style='color: red;'>Password2 cannot be empty</p>");
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
        foreach($errors as $error) { echo $error; }
    }

    function successfulRegistration($values, $connection) {
        $hashedPassword = password_hash($values['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (user_email, user_password) VALUES ('{$values['email']}', '{$hashedPassword}')";
        $connection->query($sql);
    }
    
?>