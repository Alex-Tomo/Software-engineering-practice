<?php

// TODO: make the dynamic redirect an external function

function getHeader() {
    // Get database connection
    $conn = getConnection();

    $sqlQuery = $conn->query("
        SELECT user_fname FROM sep_user_info 
        INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id 
        WHERE user_email = '".$_SESSION['email']."'");
    if($sqlQuery){
        while($row = $sqlQuery->fetchObject()) {
            $name = $row->user_fname;
        }
    }

    // need a dynamic redirect
    $path = '';
    $arr = explode("/", __DIR__);
    foreach ($arr as $a) {
        $path .= $a.'/';
        if ($a == 'Software-engineering-practice') { // Alex
//        if ($a == 'sep') {  // Graham
            $path .= 'db_connector.php';
            break;
        }
    }

    include_once $path;

    $header = "
    <header>";

    // Desktop Navbar
    $header .= "
        <div id='desktop_container'>
            <h1 class='clickable' onclick='openPage(`loggedinHome.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`loggedinHome.php`)'>CV</h1>
            <nav>";

    if (!$_SESSION['loggedin']) {

        $header .= "
                <button class='clickable' onclick='openPage(`register.php`)' id='signUp'>Sign Up</button >
                <button class='clickable' onclick='openPage(`signin.php`)' id ='login'><a class='links'>Login</a ></button>";

    } else {

        $header .= "
                <div id='dropdown'>
                    <button id='dropbtn' class='clickable' onclick='userDrpdwn()'>
                        <div>Hi, {$name} <i class='fa fa-caret-down'></i></div>
                    </button>
                    <img src='assets/navPerson.svg'>
                    <div id='dropdown_content'>
                        <a class='links clickable' onclick='openPage(`userProfile.php`)'>My Profile</a>
                        <a class='links clickable' onclick='openPage(`userJobs.php`)'>My Jobs</a>
                        <a class='links clickable' onclick='openPage(`logout.php`)'>Logout</a>
                    </div>
                 </div>
                 <ul>
                    <a class='links clickable' onclick='openPage(`#`)'><img class='navIcon' src='assets/mail.svg'></a>
                    <a class='links clickable' onclick='openPage(`#`)'><img class='navIcon' src='assets/bell.svg'></a>
                    <li><a class='links clickable' onclick='openPage(`postJob.php`)'>Post a Job</a></li>
                    <li><a class='links clickable' onclick='openPage(`loggedinHome.php`)'>Home</a></li>
                </ul>";
    }
    $header .= "
            </nav>
        </div>";

    // Mobile NavBar
    $header .= "
        <div id='mobile_container'>
            <div id='topnav'>
                <h1 class='clickable' onclick='openPage(`loggedinHome.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`loggedinHome.php`)'>CV</h1>
                <div id='myLinks'>";

    if (!$_SESSION['loggedin']) {

        $header .= "
                    <button class='clickable' onclick='openPage(`register.php`)' id='signUpMob'>Sign Up</button ><br >
                    <button class='clickable' onclick='openPage(`signin.php`)' id='loginMob'>Login</button>";

    } else {

        $header .= "
                    <div class='clickable' onclick='openPage(`userProfile.php`)' id='mobName'>Hi, {$name}</div>
                    <a class='mobLinks clickable' onclick='openPage(`loggedinHome.php`)'>Home</a>
                    <a class='mobLinks clickable' onclick='openPage(`userProfile.php`)'>My Profile</a>
                    <br>
                    <a class='mobLinks clickable' onclick='openPage(`userJobs.php`)'>My Jobs</a>
                    <a class='mobLinks clickable' onclick='openPage(`postJob.php`)'>Post a Job</a>
                    <a class='mobLinks clickable' onclick='openPage(`postJob.php`)'>Messages</a>
                    <button class='clickable' onclick='openPage(`logout.php`)' id='logout'>Logout</button>";

    }

    $header .= "
                </div>
                <a href='javascript:void(0);' class='icon' onclick='hambgrMenu()'>
                    <i class='fa fa-bars'></i>
                </a>
            </div>
        </div>
    </header>";

    return $header;
}

?>
