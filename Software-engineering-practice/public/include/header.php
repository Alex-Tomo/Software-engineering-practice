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
            $sqlQuery = $conn->query("SELECT sep_user_info.user_fname, sep_user_info.user_image, sep_users.user_id 
                                  FROM sep_user_info 
                                  INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id 
                                  WHERE user_email = '".$_SESSION['email']."'");
        if($sqlQuery){
            while($name = $sqlQuery->fetchObject()) {
                $userId = $name->user_id;
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
                 <ul>";

        try {
            $statement = $conn->prepare("SELECT message_read
                                               FROM sep_read_messages
                                               WHERE sep_read_messages.user_id = {$userId}
                                               AND sep_read_messages.message_read = FALSE
                                               GROUP BY job_id
                                                ");
            $statement->execute();
            $k = 0;
            while($result = $statement->fetchObject()) {
                $k++;
            }

        $header .= "<a class='links clickable' onclick='openPage(`messages.php`)'>";

        if($k > 0) {
            $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px;'>{$k}</p>";
            $header .= "<img class='navIcon' src='assets/mail-red.svg'>";
        } else {
            $header .= "<img class='navIcon' src='assets/mail.svg'>";
        }


        $header .= "</a><a class='links clickable'>";

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
                        <img class='navIcon' id='notifications' src='assets/bell-red.svg' height='45px'>
                        <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'>";
                $header .= $h;
            } else {
                $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
                <img class='navIcon' id='notifications' src='assets/bell.svg' height='45px'>
                <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'>";
                $header .= $h;
            }

        } else {
            $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
                <img class='navIcon' id='notifications' src='assets/bell.svg' height='45px'>
                <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'>";

            $header .= "<p id='noNotifications' style='padding:10px;'>No notifications</p>";
        }

        } catch(Exception $e) {
            $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
                <img class='navIcon' id='notifications' src='assets/bell.svg' height='45px'>
                <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'>";

            $header .= "<p id='noNotifications' style='padding:10px;'>No notifications</p>";
            logError($e);
        }


        $header .= "</div>
                    </a>
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
                                    <a class='mobLinks clickable' onclick='openPage(`postJob.php`)'>Post a Job</a>";

        try {
            if ($k > 0) {
                $header .= "<a class='mobLinks clickable' onclick='openPage(`messages.php`)'>Messages ({$k})</a>";
            } else {
                $header .= "<a class='mobLinks clickable' onclick='openPage(`messages.php`)'>Messages</a>";
            }
        } catch(Exception $e) {
            $header .= "<a class='mobLinks clickable' onclick='openPage(`messages.php`)'>Messages</a>";

            logError($e);
        }


        $header .= "<button class='clickable' onclick='openPage(`logout.php`)' id='logout'>Logout</button>";
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
