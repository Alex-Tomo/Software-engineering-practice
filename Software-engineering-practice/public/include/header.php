<?php

function getHeader() {

    //Dynamic redirect
    $path = '';
    $arr = explode("\\", __DIR__);
    foreach ($arr as $a) {
        $path .= $a.'/';
        if ($a == 'Software-engineering-practice') {
            break;
        }
    }
    include_once $path . 'db_connector.php'; // include db_connector.php
    include_once $path . 'database_functions.php'; // include database_functions.php

    $conn = getConnection(); // get the database connection

    $header = "<header>";

    // set a hidden email to use in other files
    if(isset($_SESSION['email'])) {
        $header .= "<input type='text' id='usersEmailAddress' name='{$_SESSION['email']}' style='display: none;'>";
    }

    // Desktop Navbar
    if (!$_SESSION['loggedin']) {

        // Not logged in, show the signup and login links
        $header .= "
            <div id='desktop_container'>
                <h1 class='clickable' onclick='openPage(`home.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`home.php`)'>CV</h1>
                <nav>
                    <button class='clickable' onclick='openPage(`register.php`)' id='signUp'>Sign Up</button >
                    <button class='clickable' onclick='openPage(`signin.php`)' id ='login'><a class='links'>Login</a ></button>";

    } else {

        // Show the logged in navigation bar
        $header .= "
            <div id='desktop_container'>
                <h1 class='clickable' onclick='openPage(`loggedinHome.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`loggedinHome.php`)'>CV</h1>
                <nav>
                    <div id='dropdown'>
                    <button id='dropbtn'>";

        $src = '';
        try {

            // get and display the users first name and corresponding image
            list($userId, $userFname, $imageSrc) = headerNameAndImage($conn, $_SESSION['email']);

            $header .= "<div>Hi, {$userFname} <i class='fa fa-caret-down'></i></div>";
            $src = $imageSrc;

        } catch(Exception $e) {

            // if the user has not selected a name or image, display this
            $header .= "<div>Hi, there! <i class='fa fa-caret-down'></i></div>";
            $src = "assets/navPerson.svg";
            logError($e);

        }

        //Navigation links when hovering over the users name
        $header .= "
                    </button>
                    <img src='{$src}' style='border-radius: 25px'>
                    <div id='dropdown_content'>
                        <a class='links clickable' onclick='openPage(`userProfile.php`)'>My Profile</a>
                        <a class='links clickable' onclick='openPage(`userJobs.php`)'>My Jobs</a>
                        <a class='links clickable' onclick='openPage(`logout.php`)'>Logout</a>
                    </div>
                </div>
                <ul>";

        try {

            // get the number of unread messages
            $unreadMessages = numberOfUnreadMessages($conn, $userId);

            $header .= "<a class='links clickable' onclick='openPage(`messages.php`)'>";

            // if the user has unread messages display the red mail image and display
            // the number of unread messages, other wise display the white mail image
            // with no number
            if($unreadMessages > 0) {
                $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; 
                    background-color: #FF0000; margin-top: 10px;'>{$unreadMessages}</p>";
                $header .= "<img class='navIcon' src='assets/mail-red.svg'>";
            } else {
                $header .= "<img class='navIcon' src='assets/mail.svg'>";
            }

            $header .= "</a><a class='links clickable'>";

            // get the number of notifications and the notification details
            $notifications = getNotificationDetails($conn, $_SESSION['email']);

            // unread notifications and notfication messages to add
            $unreadNotifications = 0;
            $notificationMessage = '';

            // if the user has notifications
            if ($notifications != null) {

                foreach ($notifications as $notification) { // start of for each loop

                    // if the notification has not been read, add 1 to the
                    // unread notification variable
                    if ($notification->notification_read == FALSE) {
                        $unreadNotifications++;
                    }

                    // cut the message down to 50 characters so it does not overfill the screen
                    $message = str_split($notification->notification_message);
                    $shortDesc = '';
                    for ($i = 0; $i < sizeof($message); $i++) {
                        if ($i <= 50) {
                            $shortDesc .= $message[$i];
                        } else if ($i > 50) {
                            $shortDesc .= '...';
                            break;
                        }
                    }
                    // put all notifications into a variable to display later
                    $notificationMessage .= "<p style='padding:10px; color: white;'>{$notification->user_fname} {$notification->user_lname} 
                                                sent you a message regarding '{$notification->job_title}'<br>{$shortDesc}<br>{$notification->sent_on}</p><hr>";

                }

                if ($unreadNotifications > 0) {

                    // display the red icon and number of unread notifications
                    $header .= "
                        <p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; 
                            text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'>{$unreadNotifications}</p>
                        <img class='navIcon' id='notifications' src='assets/bell-red.svg'>
                        <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 250px;'>";

                    $header .= $notificationMessage;

                } else {

                    //display the notifications on click with a white icon
                    $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; 
                        float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
                    <img class='navIcon' id='notifications' src='assets/bell.svg' height='45px'>
                    <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'>";
                    $header .= $notificationMessage;
                }

            } else {

                // no notifications to display
                $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; 
                        float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
                    <img class='navIcon' id='notifications' src='assets/bell.svg' height='45px'>
                    <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'>";

                $header .= "<p id='noNotifications' style='padding:10px;'>No notifications</p>";
            }

        } catch(Exception $e) {

            // no notifications to display on error
            $header .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
                <img class='navIcon' id='notifications' src='assets/bell.svg' height='45px'>
                <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'>";

            $header .= "<p id='noNotifications' style='padding:10px;'>No notifications</p>";
            logError($e);

        }


        $header .= "</div>
                </a>
            <li><a class='links clickable' onclick='openPage(`postJob.php`)'>Post a Job</a></li>
        </ul>";
    }

    $header .= "</nav>
        </div>";

    // Mobile NavBar
    if (!$_SESSION['loggedin']) {
        $header .= "<div id='mobile_container'>
                        <div id='topnav'>
                            <h1 class='clickable' onclick='openPage(`home.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`home.php`)'>CV</h1>
                            <div id='myLinks'>
                                 <button class='clickable' onclick='openPage(`register.php`)' id='signUpMob'>Sign Up</button><br>
                                 <button class='clickable' onclick='openPage(`signin.php`)' id='loginMob'>Login</button>";
    } else {

        // get the users first name and display it to the header
        $firstNames = getHeaderFname($conn, $_SESSION['email']);


        foreach($firstNames as $firstName) {

                $header .= "<div id='mobile_container'>
                                <div id='topnav'>
                                    <h1 class='clickable' onclick='openPage(`loggedinHome.php`)'>skip</h1><h1 class='clickable' id='logoText2' onclick='openPage(`loggedinHome.php`)'>CV</h1>
                                        <div id='myLinks'>
                                             <div class='clickable' onclick='openPage(`userProfile.php`)' id='mobName'>Hi, {$firstName}</div>";

        }

        $header .= "<a class='mobLinks clickable' onclick='openPage(`userProfile.php`)'>My Profile</a><br>
                                    <a class='mobLinks clickable' onclick='openPage(`userJobs.php`)'>My Jobs</a>
                                    <a class='mobLinks clickable' onclick='openPage(`postJob.php`)'>Post a Job</a>";

        try {

            // display the number of unread messages next to the messages link
            if ($unreadMessages > 0) {
                $header .= "<a class='mobLinks clickable' onclick='openPage(`messages.php`)'>Messages ({$unreadMessages})</a>";
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
