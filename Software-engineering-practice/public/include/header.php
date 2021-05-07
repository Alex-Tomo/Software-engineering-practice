<?php

// TODO: make the dynamic redirect an external function
// TODO: make the path an external function

function getHeader() {

    // needed a dynamic redirect
    $path = '';
    $arr = explode("\\", __DIR__);
    foreach ($arr as $a) {
        $path .= $a.'/';
        if ($a == 'Software-engineering-practice') { // Alex
//        if ($a == 'sep') {  // Graham
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
    $header .= "<div id='desktop_container'>
            <h1 class='clickable' onclick='openPage(`home.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`home.php`)'>CV</h1>
            <nav>";
    if (!$_SESSION['loggedin']) {
        $header .= "<button class='clickable' onclick='openPage(`register.php`)' id='signUp'>Sign Up</button >
                    <button class='clickable' onclick='openPage(`signin.php`)' id ='login'><a class='links'>Login</a ></button>";
    } else {
        $header .= "<div id='dropdown'>
                    <button id='dropbtn'>";
        $conn = getConnection();
        $sqlQuery = $conn->query("SELECT user_fname FROM sep_user_info 
                                  INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id 
                                  WHERE user_email = '".$_SESSION['email']."'");
        if($sqlQuery){
            while($name = $sqlQuery->fetchObject()) {
                $header .= "<div>Hi, {$name->user_fname} <i class='fa fa-caret-down'></i></div>";
            }
        }
        $header .= "</button>
                                    <img src='assets/navPerson.svg'>
                                    <div id='dropdown_content'>
                                        <a class='links clickable' onclick='openPage(`userProfile.php`)'>My Profile</a>
                                        <a class='links clickable' onclick='openPage(`userJobs.php`)'>My Jobs</a>
                                        <a class='links clickable' onclick='openPage(`logout.php`)'>Logout</a>
                                    </div>
                                 </div>
                                 <ul>
                                    <a class='links clickable' onclick='openPage(`messages.php`)'><img class='navIcon' src='assets/mail.svg'></a>
                                    <a class='links clickable'>";

$statement = $conn->prepare("
    SELECT sep_notifications.notification_message, notification_read, sep_notifications.sent_on, sep_available_jobs.job_title, sep_user_info.user_fname, sep_user_info.user_lname
    FROM sep_notifications JOIN sep_available_jobs
    ON sep_notifications.job_id = sep_available_jobs.job_id
    JOIN sep_users
    ON sep_available_jobs.user_id = sep_users.user_id
    JOIN sep_user_info
    ON sep_user_info.user_id = sep_notifications.user_id
    WHERE sep_users.user_email = '{$_SESSION['email']}'
    GROUP BY sep_notifications.job_id, sep_notifications.user_id
    ORDER BY sep_notifications.sent_on DESC
    LIMIT 5  
");
$statement->execute();
$j = 0;
$h = '';
if($statement->rowCount() > 0) {
    while ($result = $statement->fetchObject()) {
        if($result->notification_read == FALSE) {
            $j++;
        }
        $message = str_split($result->notification_message);
        $shortDesc = '';
        for ($i = 0; $i < sizeof($message); $i++) {
            if ($i <= 50) {
                $shortDesc .= $message[$i];
            } else if ($i > 50) {
                $shortDesc .= '...';
                break;
            }
        }

        $h .= "<p style='padding:10px;'>{$result->user_fname} {$result->user_lname} sent you a message regarding '{$result->job_title}'<br>{$shortDesc}<br>{$result->sent_on}</p><hr>";
    }
    if($j > 0) {
        $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px;'>{$j}</p>
                        <img class='navIcon' id='notifications' src='assets/bell-red.svg'>
                        <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'>";
        $header .= $h;
    } else {
        $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
                <img class='navIcon' id='notifications' src='assets/bell.svg'>
                <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'>";
        $header .= $h;
    }

} else {
    $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
                <img class='navIcon' id='notifications' src='assets/bell.svg'>
                <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'>";

    $header .= "<p id='noNotifications' style='padding:10px;'>No notifications</p>";
}


                                        $header .= "</div>
                                        </a>
                                    <li><a class='links clickable' onclick='openPage(`postJob.php`)'>Post a Job</a></li>
                                    </ul>";}
    $header .= "</nav>
        </div>";

    // Mobile NavBar
    $header .= "<div id='mobile_container'>
            <div id='topnav'>
                <h1 class='clickable' onclick='openPage(`home.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`home.php`)'>CV</h1>
                <div id='myLinks'>";
    if (!$_SESSION['loggedin']) {
        $header .= "<button class='clickable' onclick='openPage(`register.php`)' id='signUpMob'>Sign Up</button ><br >
                        <button class='clickable' onclick='openPage(`signin.php`)' id='loginMob'>Login</button>";
    } else {
        $conn = getConnection();
        $sqlQuery = $conn->query("SELECT user_fname FROM sep_user_info 
                                                       INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id 
                                                       WHERE user_email = '".$_SESSION['email']."'");
        if($sqlQuery){
            while($name = $sqlQuery->fetchObject()) {
                $header .= "<div class='clickable' onclick='openPage(`userProfile.php`)' id='mobName'>Hi, {$name->user_fname}</div>";
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
