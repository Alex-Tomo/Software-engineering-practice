<?php

// TODO: make the dynamic redirect an external function
// TODO: make the path an external function

function getHeader() {

    //Dynamic redirect
    $path = '';
    $arr = explode("\\", __DIR__);
    foreach ($arr as $a) {
        $path .= $a.'/';
        if ($a == 'Software-engineering-practice') {
            $path .= 'db_connector.php';
            break;
        }
    }

    include_once $path;

    $header = "<header>";

    if(isset($_SESSION['email'])) {
        $header .= "<input type='text' id='usersEmailAddress' name='{$_SESSION['email']}' style='display: none;'>";
    }

    // Desktop Navbar
    $conn = getConnection();


    if (!$_SESSION['loggedin']) {
        $header .= "<div id='desktop_container'>
            <h1 class='clickable' onclick='openPage(`home.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`home.php`)'>CV</h1>
                <nav>
                    <button class='clickable' onclick='openPage(`register.php`)' id='signUp'>Sign Up</button >
                    <button class='clickable' onclick='openPage(`signin.php`)' id ='login'><a class='links'>Login</a ></button>";
    } else {
        $header .= "<div id='desktop_container'>
            <h1 class='clickable' onclick='openPage(`loggedinHome.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`loggedinHome.php`)'>CV</h1>
                <nav>
                    <div id='dropdown'>
                    <button id='dropbtn'>";
        $src = '';
        try {
            $sqlQuery = $conn->query("SELECT sep_user_info.user_fname, sep_user_info.user_image 
                                  FROM sep_user_info 
                                  INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id 
                                  WHERE user_email = '".$_SESSION['email']."'");
        if($sqlQuery){
            while($name = $sqlQuery->fetchObject()) {
                $header .= "<div>Hi, {$name->user_fname} <i class='fa fa-caret-down'></i></div>";
                    if ($name->user_image != null) {
                        $src = "assets/user_images/{$name->user_image}";
                    } else {
                        $src = "assets/navPerson.svg";
                    }
            }
        }
        } catch(Exception $e) {
            $header .= "<div>Hi, there! <i class='fa fa-caret-down'></i></div>";
            $src = "assets/navPerson.svg";
            logError($e);
        }
        $header .= "</button>
                                    <img src='{$src}' style='border-radius: 25px'>
                    <div id='dropdown_content'>
                        <a class='links clickable' onclick='openPage(`userProfile.php`)'>My Profile</a>
                        <a class='links clickable' onclick='openPage(`userJobs.php`)'>My Jobs</a>
                        <a class='links clickable' onclick='openPage(`logout.php`)'>Logout</a>
                    </div>
                 </div>
                 <ul>
                    <a class='links clickable' onclick='openPage(`messages.php`)'><img class='navIcon' src='assets/mail.svg'></a>
                    <li><a class='links clickable' onclick='openPage(`postJob.php`)'>Post a Job</a></li>
                </ul>";}
    $header .= "</nav>
        </div>";

    // Mobile NavBar
    if (!$_SESSION['loggedin']) {
        $header .= "<div id='mobile_container'>
                        <div id='topnav'>
                            <h1 class='clickable' onclick='openPage(`home.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`home.php`)'>CV</h1>
                            <div id='myLinks'>
                                 <button class='clickable' onclick='openPage(`register.php`)' id='signUpMob'>Sign Up</button ><br >
                                 <button class='clickable' onclick='openPage(`signin.php`)' id='loginMob'>Login</button>";
    } else {
        $conn = getConnection();
        $sqlQuery = $conn->query("SELECT user_fname FROM sep_user_info 
                                                       INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id 
                                                       WHERE user_email = '".$_SESSION['email']."'");
        if($sqlQuery){
            while($name = $sqlQuery->fetchObject()) {
                $header .= "<div id='mobile_container'>
                                <div id='topnav'>
                                    <h1 class='clickable' onclick='openPage(`loggedinHome.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`loggedinHome.php`)'>CV</h1>
                                        <div id='myLinks'>
                                             <div class='clickable' onclick='openPage(`userProfile.php`)' id='mobName'>Hi, {$name->user_fname}</div>";
            }
        }
        $header .= "<a class='mobLinks clickable' onclick='openPage(`userProfile.php`)'>My Profile</a><br>
                                    <a class='mobLinks clickable' onclick='openPage(`userJobs.php`)'>My Jobs</a>
                                    <a class='mobLinks clickable' onclick='openPage(`postJob.php`)'>Post a Job</a>
                                    <a class='mobLinks clickable' onclick='openPage(`messages.php`)'>Messages</a>
                                    <a class='mobLinks clickable' onclick='openPage(``)'>Notifications</a>
                                    <button class='clickable' onclick='openPage(`logout.php`)' id='logout'>Logout</button>";
    }
    $header .= "</div>
                <a href='javascript:void(0);' class='icon' onclick='hambgrMenu()'>
                    <i class='fa fa-bars'></i>
                </a>
            </div>
        </div>
    </header>";

    return $header;
}

?>
