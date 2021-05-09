<?php

function getNotifications() {

    // get the current URL, if on messages.php, do not show the
    // floating notification button
    $messagingPage = false;
    $arr = explode("/", $_SERVER['PHP_SELF']);
    foreach ($arr as $a) {
        if ($a == 'messages.php') {
            $messagingPage = true;
            break;
        }
    }

    // if not on messages.php
    if (!$messagingPage) { // start of if statement - 1

        //Dynamic path to get the database connection
        $path = '';
        $arr = explode("\\", __DIR__);
        foreach ($arr as $a) {
            $path .= $a . '/';
            if ($a == 'Software-engineering-practice') {
                break;
            }
        }
        include_once $path . 'db_connector.php'; // include db_connector.php
        include_once $path . 'database_functions.php'; // include database_functions.php

        try {

            $conn = getConnection(); //database connection variable

            $notificationsText = "<div id='notificationLink'>";

            if ($_SESSION['loggedin']) { // start of if statement - 2

                $notificationsText .= "<a id='bell' class='clickable'>";

                // get the notification details
                $notifications = getNotificationDetails($conn, $_SESSION['email']);

                // unread notifications and notfication messages to add
                $unreadNotifications = 0;
                $notificationMessage = '';

                // if the user has notifications
                if ($notifications != null) { // start of if statement - 3

                    foreach ($notifications as $notification) { // start of for each loop

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
                        $notificationMessage .= "<p class='notificationMessage'>{$notification->user_fname} {$notification->user_lname} 
                                                sent you a message regarding '{$notification->job_title}'<br>{$shortDesc}<br>{$notification->sent_on}</p><hr>";

                    } // end of for each loop

                    if ($unreadNotifications > 0) { // start of if statement - 4

                        $notificationsText .= "
                        <p class='numOfNotifications' id='floatingNumberOfNotifications'>{$unreadNotifications}</p>
                        <img class='navIcon' id='floatingNotifications' src='assets/bell-red.svg' alt='Notifications icon with notifications'>
                        <div class='notificationsDivBoth' id='floatingNotificationsDiv'>";

                        $notificationsText .= $notificationMessage;

                        $notificationsText .= "</div></a></div>";

                    } else {

                        $notificationsText .= "
                        <p class='numOfNotifications' id='floatingNumberOfNotifications'></p>
                        <img class='navIcon' id='floatingNotifications' src='assets/bell.svg' alt='Notifications icon without notifications'>
                        <div class='notificationsDivBoth' id='floatingNotificationsDiv'>";

                        $notificationsText .= $notificationMessage;

                        $notificationsText .= "</div></a></div>";

                    } // end of if statement - 4

                } else { // else of if statement - 3

                    $notificationsText .= "
                    <p class='numOfNotifications' id='floatingNumberOfNotifications'></p>
                    <img class='navIcon' id='floatingNotifications' src='assets/bell.svg' alt='Notifications icon without notifications'>
                    <div class='notificationsDivBoth' id='floatingNotificationsDiv'>
                        <p id='noNotifications'>No notifications</p>
                    </div></a> 
                </div>";

                } // end of if statement 3

                return $notificationsText;

            } // end of if statement - 2

        } catch (Exception $e) { logError($e);}
    } // end of if statement - 1

} // end of function

?>