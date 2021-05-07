<?php

// Requires
require('../pageTemplate.php');
require('../db_connector.php');

$conn = getConnection();

// remove the loggedin session
$_SESSION['loggedin'] = null;

$statement = $conn->prepare(
    "UPDATE sep_users SET user_online = false WHERE user_email = '{$_SESSION['email']}'");
$statement->execute();

// redirect the user to the homepage
header('Location: home.php');

?>