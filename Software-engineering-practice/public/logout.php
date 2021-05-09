<?php

    // Requires
    require('../pageTemplate.php');
    require('../db_connector.php');

    // get the database connection
    $conn = getConnection();

    // remove the loggedin session
    $_SESSION['loggedin'] = null;

    $logoutStatement = $conn->prepare("UPDATE sep_users SET user_online = false WHERE user_email = ?");
    $logoutStatement->bindParam(1, $_SESSION['email']);
    $logoutStatement->execute();

    // redirect the user to the homepage
    header('Location: home.php');

?>