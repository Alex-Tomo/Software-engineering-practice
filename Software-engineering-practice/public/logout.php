<?php

    // Requires
    require('../pageTemplate.php');

    // remove the loggedin session
    $_SESSION['loggedin'] = null;

    // redirect the user to the homepage
    header('Location: home.php');

?>