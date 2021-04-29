<?php

    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $desc = isset($_POST['desc']) ? trim($_POST['desc']) : null;
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;
    $image = isset($_POST['image']) ? trim($_POST['image']) : null;
    $categories = isset($_POST['categories']) ? trim($_POST['categories']) : null;

    echo $image;

?>