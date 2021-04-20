<?php
    include('../pageTemplate.php');
    $_SESSION['loggedin'] = null;
    header('Location: home.php');
?>