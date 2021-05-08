<?php

function getNotifications() {

    $messagingPage = false;
    $arr = explode("/", $_SERVER['PHP_SELF']);
    foreach ($arr as $a) {
        if ($a == 'messages.php') {
            $messagingPage = true;
            break;
        }
    }

    if(!$messagingPage) {

        //Dynamic redirect
        $path = '';
        $arr = explode("\\", __DIR__);
        foreach ($arr as $a) {
            $path .= $a . '/';
            if ($a == 'Software-engineering-practice') {
                $path .= 'db_connector.php';
                break;
            }
        }

        include_once $path;

        $notifications = "<div id='notificationLink'>";

        if ($_SESSION['loggedin']) {
            $notifications .= "<a id='bell' class='clickable'>";
            $conn = getConnection();
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
            ORDER BY sep_notifications.sent_on
            LIMIT 5
        ");
            $statement->execute();
            $j = 0;
            $h = '';
            if ($statement->rowCount() > 0) {
                while ($result = $statement->fetchObject()) {
                    if ($result->notification_read == FALSE) {
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
                    $h .= "<p style='padding:10px; color: white;'>{$result->user_fname} {$result->user_lname} sent you a message regarding '{$result->job_title}'<br>{$shortDesc}<br>{$result->sent_on}</p><hr>";
                }
                if ($j > 0) {
                    $notifications .= "<p id='floatingNumberOfNotifications' style='padding: 2px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px;'>{$j}</p>
                <img class='navIcon' id='floatingNotifications' src='assets/bell-red.svg'>
                <div id='floatingNotificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 250px;'>";
                    $notifications .= $h;
                    $notifications .= "</div></a></div>";
                } else {
                    $notifications .= "<p id='floatingNumberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
                <img class='navIcon' id='floatingNotifications' src='assets/bell.svg'>
                <div id='floatingNotificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 250px;'>";
                    $notifications .= $h;
                    $notifications .= "</div></a></div>";
                }

            } else {
                $notifications .= "<p id='floatingNumberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
            <img class='navIcon' id='floatingNotifications' src='assets/bell.svg'>
            <div id='floatingNotificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 250px;'>
                <p id='noNotifications' style='padding:10px; color: white;'>No notifications</p>
            </div></a> 
        </div>";
            }

            return $notifications;
        }
    }
}
?>