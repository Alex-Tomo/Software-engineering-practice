<?php

function getNotifications() {

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

    $notifications = "<div id='notificationLink'>";

    if (isset($_SESSION['email'])) {
        $notifications .= "<input type='text' id='usersEmailAddress' name='{$_SESSION['email']}' style='display: none;'>";
    }

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
        ORDER BY sep_notifications.sent_on DESC
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

                $notifications .= "$h = <p style='padding:10px;'>{$result->user_fname} {$result->user_lname} sent you a message regarding '{$result->job_title}'<br>{$shortDesc}<br>{$result->sent_on}</p><hr>";
            }
            if ($j > 0) {
                $notifications .= "<p id='numberOfNotifications' style='padding: 2px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px;'>{$j}</p>
                <img class='navIcon' id='notifications' src='assets/bell-red.svg'>
                <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'></div>";
                $notifications .= $h;
            } else {
                $notifications .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
                <img class='navIcon' id='notifications' src='assets/bell.svg'>
                <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'></div>";
                $notifications .= $h;
            }

        } else {
            $notifications .= "<p id='numberOfNotifications' style='padding: 2.5px; font-size: 8px; border-radius: 10px; text-align: center; float: right; background-color: #FF0000; margin-top: 10px; display:none;'></p>
            <img class='navIcon' id='notifications' src='assets/bell.svg'>
            <div id='notificationsDiv' style='position: absolute; display: none; background-color: #017EFC; min-width: 190px;'></div>
<!--            <p id='noNotifications' style='padding:10px;'>No notifications</p>-->
    </div>";
        }
        return $notifications;
    }
}
?>