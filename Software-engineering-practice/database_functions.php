<?php

// TODO: Change the way the sanitize data works use $data and $type

// This file contains all functions for the sql for the php files which get displayed

function sanitizeData($data) {

    if (gettype($data) == 'string') {
        $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    } else if(gettype($data)  == 'integer') {
        $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
    } else if(gettype($data)  == 'double') {
        $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    } else if(gettype($data)  == 'email') {
        $data = filter_var($data, FILTER_SANITIZE_EMAIL, FILTER_FLAG_NO_ENCODE_QUOTES);
    }

    return $data;
}

function validateData($data) {

    if (gettype($data)  == 'string') {
        $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    } else if(gettype($data)  == 'integer') {
        $data = filter_var($data, FILTER_VALIDATE_INT);
    } else if(gettype($data)  == 'double') {
        $data = filter_var($data, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    } else if(gettype($data)  == 'email') {
        $data = filter_var($data, FILTER_VALIDATE_EMAIL, FILTER_FLAG_NO_ENCODE_QUOTES);
    }

    return $data;

}

// leave as query because prepare doesnt work
function selectAll($connection, $columnOne, $columnTwo, $tableName, $orderBy) {

    $columnOneArr = array();
    $columnTwoArr = array();

    $result = $connection->query("SELECT {$columnOne}, {$columnTwo} FROM {$tableName} ORDER BY {$orderBy}");

    if ($result) {
        while ($row = $result->fetchObject()) {
            if(isset($row->$columnOne) && isset($row->$columnTwo)) {
                array_push($columnOneArr, sanitizeData($row->$columnOne));
                array_push($columnTwoArr, sanitizeData($row->$columnTwo));
            }
        }
        return array($columnOneArr, $columnTwoArr);
    }
    return null;
}

// used in loggedinHome.php
function selectUsersChosenCategories($connection, $usersEmail) {
    $arr = array();

    $statement = $connection->prepare("
        SELECT sep_users_interested_jobs.job_code
        FROM sep_users_interested_jobs
        INNER JOIN sep_users
        ON sep_users_interested_jobs.user_id = sep_users.user_id
        WHERE sep_users.user_email = ?");
    $statement->bindParam(1, $usersEmail);

    if($statement->execute()) {
        while($row = $statement->fetchObject()) {
            array_push($arr, sanitizeData($row->job_code));
        }
    }
    return $arr;
}

// used in loggedinHome.php
function getRecommendedJobs($connection, $choicesArray, $email) {

    $arr = array();
    $index = 0;

    $statement = $connection->prepare("
          SELECT sep_user_info.user_id, 
                 sep_user_info.user_fname, 
                 sep_user_info.user_lname, 
                 sep_user_info.user_image, 
                 sep_available_jobs.job_id,
                 sep_available_jobs.job_title,
                 sep_available_jobs.job_desc,
                 sep_available_jobs.job_price,
                 sep_available_jobs.job_date,
                 sep_available_jobs.job_image
          FROM sep_user_info
          JOIN sep_users
          ON sep_user_info.user_id = sep_users.user_id
          JOIN sep_available_jobs
          ON sep_users.user_id = sep_available_jobs.user_id
          JOIN sep_jobs_categories
          ON sep_available_jobs.job_id = sep_jobs_categories.job_id
          WHERE sep_available_jobs.job_availability = '1'
          AND sep_jobs_categories.job_code IN (" . implode(',', $choicesArray) . ")
          AND sep_users.user_email != ?
          GROUP BY job_id
          ORDER BY sep_available_jobs.job_date DESC
          LIMIT 3");
    $statement->bindParam(1, $email);

    if ($statement->execute()) {
        while($row = $statement->fetchObject()) {
            $arr[$index]['userId'] = sanitizeData($row->user_id);
            $arr[$index]['userFname'] = sanitizeData($row->user_fname);
            $arr[$index]['userLname'] = sanitizeData($row->user_lname);
            $arr[$index]['jobId'] = sanitizeData($row->job_id);
            $arr[$index]['jobTitle'] = sanitizeData($row->job_title);
            $arr[$index]['jobDesc'] = sanitizeData($row->job_desc);
            $arr[$index]['jobPrice'] = sanitizeData($row->job_price);
            $arr[$index]['jobDate'] = sanitizeData($row->job_date);
            $arr[$index]['jobImage'] = sanitizeData($row->job_image);
            if($row->user_image != null) {
                $arr[$index]['userImage'] = 'user_images/' . sanitizeData($row->user_image);
            } else {
                $arr[$index]['userImage'] = 'person.svg';
            }
            $index++;
        }
    }

    return $arr;

}

// used in multiple files
function getStarRating($connection, $jobId) {

    $statement = $connection->prepare("
        SELECT (SUM(sep_job_rating.job_rating)/COUNT(*)) as sum, COUNT(sep_job_rating.job_id) as total
        FROM sep_job_rating
        JOIN sep_available_jobs
        ON sep_job_rating.job_id = sep_available_jobs.job_id
        JOIN sep_users
        ON sep_job_rating.user_id = sep_users.user_id
        WHERE sep_job_rating.job_id = ?
        GROUP BY sep_job_rating.job_id");
    $statement->bindParam(1, $jobId);

    if($statement->execute()) {
        while ($row = $statement->fetchObject()) {
            $sum = sanitizeData(round($row->sum, 0, PHP_ROUND_HALF_DOWN));
            $total = sanitizeData($row->total);
        }
    }
    if(isset($sum) && isset($total)) {
        return array($sum, $total);
    } else {
        return array(0, 0);
    }
}

// used in loggedinHome.php
function getRecentJobs($connection, $email) {

    $arr = array();
    $index = 0;

    $statement = $connection->prepare("
        SELECT sep_user_info.user_id, 
            sep_user_info.user_fname, 
            sep_user_info.user_lname,
            sep_user_info.user_image,
            sep_available_jobs.job_id,
            sep_available_jobs.job_title,
            sep_available_jobs.job_desc,
            sep_available_jobs.job_price,
            sep_available_jobs.job_date,
             sep_available_jobs.job_image                       
        FROM sep_user_info
        JOIN sep_available_jobs
        ON sep_user_info.user_id = sep_available_jobs.user_id
        JOIN sep_users
        ON sep_user_info.user_id = sep_users.user_id
        WHERE sep_available_jobs.job_availability = '1'
        AND sep_users.user_email != ?
        GROUP BY sep_user_info.user_id
        ORDER BY sep_available_jobs.job_date DESC
        LIMIT 3");
    $statement->bindParam(1, $email);

    if($statement->execute()) {
        while($row = $statement->fetchObject()) {

            $arr[$index]['userId'] = sanitizeData($row->user_id);
            $arr[$index]['userFname'] = sanitizeData($row->user_fname);
            $arr[$index]['userLname'] = sanitizeData($row->user_lname);
            $arr[$index]['jobId'] = sanitizeData($row->job_id);
            $arr[$index]['jobTitle'] = sanitizeData($row->job_title);
            $arr[$index]['jobDesc'] = sanitizeData($row->job_desc);
            $arr[$index]['jobPrice'] = sanitizeData($row->job_price);
            $arr[$index]['jobDate'] = sanitizeData($row->job_date);
            $arr[$index]['jobImage'] = sanitizeData($row->job_image);
            if($row->user_image != null) {
                $arr[$index]['userImage'] = 'user_images/' . sanitizeData($row->user_image);
            } else {
                $arr[$index]['userImage'] = 'person.svg';
            }
            $index++;
        }
    }
    return $arr;
}

// used in loggedinHome.php
function getPopularCategories($connection) {

    $arr = array();

    $statement = $connection->prepare("
        SELECT COUNT(sep_users_interested_jobs.job_code), sep_jobs_list.job_name
        FROM sep_users_interested_jobs
        JOIN sep_jobs_list
        ON sep_users_interested_jobs.job_code = sep_jobs_list.job_code
        GROUP BY sep_jobs_list.job_name
        ORDER BY COUNT(sep_users_interested_jobs.job_code) DESC
        LIMIT 10");

    if($statement->execute()) {
        while($row = $statement->fetchObject()) {
            array_push($arr, sanitizeData($row->job_name));
        }
    }
    return $arr;
}

// used in multiple files
function getRecentlyViewed($connection, $recentlyViewed) {

    $statement = $connection->prepare("
        SELECT sep_available_jobs.job_title, sep_available_jobs.job_price, sep_available_jobs.job_image
        FROM sep_available_jobs
        WHERE sep_available_jobs.job_id = ?");
    $statement->bindParam(1, $recentlyViewed);

    if($statement->execute()) {
        while($row = $statement->fetchObject()) {
            if(isset($row->job_title) && isset($row->job_price) && isset($row->job_image)) {
                $jobTitle = sanitizeData($row->job_title);
                $jobPrice = sanitizeData($row->job_price);
                $jobImage = sanitizeData($row->job_image);
            }
        }
        if(isset($jobTitle) && isset($jobPrice) && isset($jobImage)) {
            return array($jobTitle, $jobPrice, $jobImage);
        }
    }
    return array(null, null);
}

// used in innerService.php
function getJobDetails($connection, $jobId) {

    $arr = array();
    $index = 0;

    $statement = $connection->prepare("
        SELECT sep_user_info.user_id, 
            sep_user_info.user_fname, 
            sep_user_info.user_lname,
            sep_user_info.user_image,
            sep_available_jobs.job_id,
            sep_available_jobs.job_title,
            sep_available_jobs.job_desc,
            sep_available_jobs.job_price,
            sep_available_jobs.job_date,
            sep_available_jobs.job_image
        FROM sep_user_info
        INNER JOIN sep_available_jobs
        ON sep_user_info.user_id = sep_available_jobs.user_id
        WHERE sep_available_jobs.job_id = ?");
    $statement->bindParam(1, $jobId);

    if($statement->execute()) {
        while($row = $statement->fetchObject()) {
            $arr[$index]['userId'] = sanitizeData($row->user_id);
            $arr[$index]['userFname'] = sanitizeData($row->user_fname);
            $arr[$index]['userLname'] = sanitizeData($row->user_lname);
            $arr[$index]['jobId'] = sanitizeData($row->job_id);
            $arr[$index]['jobName'] = sanitizeData($row->job_title);
            $arr[$index]['jobDesc'] = sanitizeData($row->job_desc);
            $arr[$index]['jobPrice'] = sanitizeData($row->job_price);
            $arr[$index]['jobDate'] = sanitizeData($row->job_date);
            $arr[$index]['jobImage'] = sanitizeData($row->job_image);
            if($row->user_image != null) {
                $arr[$index]['userImage'] = 'user_images/' . sanitizeData($row->user_image);
            } else {
                $arr[$index]['userImage'] = 'person.svg';
            }
            $index++;
        }
    }
    return $arr;
}

// used in userJobs.php
function getUsersJobs($connection, $email) {

    $arr = array();
    $index = 0;

    // Get the users ID
    $userIdStatement = $connection->prepare("
    SELECT user_id FROM sep_users WHERE user_email = ?");
    $userIdStatement->bindParam(1, $email);
    $userIdStatement->execute();
    $userId = $userIdStatement->fetchColumn();

    // Get all the users jobs
    $statement = $connection->prepare("
        SELECT sep_user_info.user_id, 
            sep_user_info.user_fname, 
            sep_user_info.user_lname,
            sep_user_info.user_image,
            sep_available_jobs.job_id,
            sep_available_jobs.job_title,
            sep_available_jobs.job_desc,
            sep_available_jobs.job_price,
            sep_available_jobs.job_date,
             sep_available_jobs.job_image                       
        FROM sep_user_info
        INNER JOIN sep_available_jobs
        ON sep_user_info.user_id = sep_available_jobs.user_id
        WHERE sep_available_jobs.job_availability = '1'
        AND sep_available_jobs.user_id = ?
        ORDER BY sep_available_jobs.job_date DESC");
    $statement->bindParam(1, $userId);

    if($statement->execute()) {
        while($row = $statement->fetchObject()) {
            $arr[$index]['userId'] = sanitizeData($row->user_id);
            $arr[$index]['userFname'] = sanitizeData($row->user_fname);
            $arr[$index]['userLname'] = sanitizeData($row->user_lname);
            $arr[$index]['jobId'] = sanitizeData($row->job_id);
            $arr[$index]['jobName'] = sanitizeData($row->job_title);
            $arr[$index]['jobDesc'] = sanitizeData($row->job_desc);
            $arr[$index]['jobPrice'] = sanitizeData($row->job_price);
            $arr[$index]['jobDate'] = sanitizeData($row->job_date);
            $arr[$index]['jobImage'] = sanitizeData($row->job_image);
            if($row->user_image != null) {
                $arr[$index]['userImage'] = 'user_images/' . sanitizeData($row->user_image);
            } else {
                $arr[$index]['userImage'] = 'person.svg';
            }
            $index++;
        }
    }
    return $arr;
}

// used in userProfile.php
function getUserDetails($connection, $email) {

    $userDetailsArray = array();
    $chosenJobsCode = array();
    $chosenJobsName = array();

    // Get all user information
    $userDetailsStatement = $connection->prepare("
        SELECT * FROM sep_user_info 
        INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id
        INNER JOIN sep_languages ON sep_user_info.user_language = sep_languages.language_code
        INNER JOIN sep_regions ON sep_user_info.user_region = sep_regions.region_code
        WHERE sep_users.user_email = ?");
    $userDetailsStatement->bindParam(1, $email);

    // Put all of the users details into variables
    if($userDetailsStatement->execute()){
        while($userDetailsRow = $userDetailsStatement->fetchObject()) {
            $userDetailsArray['fname'] = sanitizeData($userDetailsRow->user_fname);
            $userDetailsArray['lname'] = sanitizeData($userDetailsRow->user_lname);
            $userDetailsArray['gender'] = sanitizeData($userDetailsRow->user_gender);
            $userDetailsArray['language'] = sanitizeData($userDetailsRow->user_language);
            $userDetailsArray['region'] = sanitizeData($userDetailsRow->user_region);
        }
    } else {
        $userDetailsArray = null;
    }

    $jobsStatement = $connection->prepare("
        SELECT * FROM sep_user_info 
        INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id
        INNER JOIN sep_users_interested_jobs ON sep_users_interested_jobs.user_id = sep_user_info.user_id
        INNER JOIN sep_jobs_list ON sep_jobs_list.job_code = sep_users_interested_jobs.job_code
        WHERE sep_users.user_email = ?");
    $jobsStatement->bindParam(1, $email);

    if($jobsStatement->execute()) {
        while ($jobsRow = $jobsStatement->fetchObject()) {
            array_push($chosenJobsCode, sanitizeData($jobsRow->job_code));
            array_push($chosenJobsName, sanitizeData($jobsRow->job_name));
        }
    } else {
        $chosenJobsCode = null;
        $chosenJobsName = null;
    }

    return array($userDetailsArray, $chosenJobsCode, $chosenJobsName);
}

// used in innerService.php
function checkJobIdExists($connection) {
    $availableJobIds = array();

    $statement = $connection->prepare("SELECT job_id FROM sep_available_jobs WHERE job_availability = TRUE");
    if($statement->execute()) {
        while ($row = $statement->fetchObject()) {
            array_push($availableJobIds, sanitizeData($row->job_id));
        }
    }
    return $availableJobIds;
}

// used in search.php
function searchCategories($connection, $categories) {
    $categoriesCodes = array();

    $statement = $connection->prepare("
        SELECT sep_jobs_categories.job_id
        FROM sep_jobs_categories
        JOIN sep_jobs_list
        ON sep_jobs_categories.job_code = sep_jobs_list.job_code
        WHERE sep_jobs_list.job_name = ?
        GROUP BY sep_jobs_categories.job_id");
    $statement->bindParam(1, $categories);

    if($statement->execute()) {
        while($row = $statement->fetchObject()) {
            array_push($categoriesCodes, $row->job_id);
        }
    }

    return $categoriesCodes;
}

// used in search.php
function searchJobInfo($connection, $categoriesCodes, $keyword) {

    $keyword = "%$keyword%";

    // Get all jobs similar to the chosen category if a category is chosen
    // Get all jobs where fname, lname, job_name, job_desc or job_price
    // contain similar to the search keyword
    $query = "SELECT sep_user_info.user_id, 
            sep_user_info.user_fname, 
            sep_user_info.user_lname, 
            sep_user_info.user_image, 
            sep_available_jobs.job_id,
            sep_available_jobs.job_title,
            sep_available_jobs.job_desc,
            sep_available_jobs.job_price,
            sep_available_jobs.job_date,
            sep_available_jobs.job_image
        FROM sep_user_info
        JOIN sep_available_jobs
        ON sep_user_info.user_id = sep_available_jobs.user_id
        JOIN sep_users_interested_jobs
        ON sep_user_info.user_id = sep_users_interested_jobs.user_id  
        WHERE sep_available_jobs.job_availability = '1' ";

    if(!empty($categoriesCodes)) {
        $query .= "AND sep_available_jobs.job_id IN (" . implode(',', $categoriesCodes) . ") ";
    }

    if(isset($keyword)) {
        $query .= "AND (sep_user_info.user_fname LIKE :keyword
            OR sep_user_info.user_lname LIKE :keyword
            OR sep_available_jobs.job_title LIKE :keyword
            OR sep_available_jobs.job_desc LIKE :keyword
            OR sep_available_jobs.job_price LIKE :keyword) ";
    }

    $query .= "GROUP BY job_id
        ORDER BY sep_available_jobs.job_date DESC";

    // Execute query
    $statement = $connection->prepare($query);
    $statement->bindParam(':keyword', $keyword);
    $statement->execute();
    $index = 0;

    if($statement->rowCount() > 0) {
        while ($row = $statement->fetchObject()) {
            $arr[$index]['userId'] = sanitizeData($row->user_id);
            $arr[$index]['userFname'] = sanitizeData($row->user_fname);
            $arr[$index]['userLname'] = sanitizeData($row->user_lname);
            $arr[$index]['jobId'] = sanitizeData($row->job_id);
            $arr[$index]['jobName'] = sanitizeData($row->job_title);
            $arr[$index]['jobDesc'] = sanitizeData($row->job_desc);
            $arr[$index]['jobPrice'] = sanitizeData($row->job_price);
            $arr[$index]['jobDate'] = sanitizeData($row->job_date);
            $arr[$index]['jobImage'] = sanitizeData($row->job_image);
            if($row->user_image != null) {
                $arr[$index]['userImage'] = 'user_images/' . sanitizeData($row->user_image);
            } else {
                $arr[$index]['userImage'] = 'person.svg';
            }
            $index++;
        }
        return $arr;
    } else {
        return null;
    }
}

// Messages SQL below

function messagesGetUserId($connection, $email) {
    try {
        $statement = $connection->prepare("
        SELECT sep_users.user_id
        FROM sep_users
        WHERE sep_users.user_email = ?");
        $statement->bindParam(1, $email);
        $statement->execute();
        $result = $statement->fetchObject();
        return $result->user_id;
    } catch(Exception $e) { logError($e); }
}

function messagesGetChatUsers($connection, $userId) {
    try {
        $statement = $connection->prepare("
        SELECT sep_messages.user_id, sep_messages.other_user_id
        FROM sep_messages
        WHERE sep_messages.user_id = ? OR sep_messages.other_user_id = ?
        ORDER BY sep_messages.created_on DESC");
        $statement->bindParam(1, $userId);
        $statement->bindParam(2, $userId);
        $statement->execute();

        $chatIds = array();

        while ($result = $statement->fetchObject()) {
            if (($result->user_id != $userId) && (!in_array($result->user_id, $chatIds))) {
                array_push($chatIds, $result->user_id);
            } else if (($result->other_user_id != $userId) && (!in_array($result->other_user_id, $chatIds))) {
                array_push($chatIds, $result->other_user_id);
            }
        }

        return $chatIds;
    } catch(Exception $e) { logError($e); }
}

function messagesGetUserDetails($connection, $chatIds) {
    try {
        $name = array();
        $fullName = array();
        $online = array();
        $userImages = array();

        foreach ($chatIds as $chatId) {
            $statement = $connection->prepare("
            SELECT sep_user_info.user_fname, sep_user_info.user_lname, sep_users.user_online, sep_user_info.user_image
            FROM sep_user_info JOIN sep_users ON sep_user_info.user_id = sep_users.user_id
            WHERE (sep_user_info.user_id = ?)");
            $statement->bindParam(1, $chatId);
            $statement->execute();
            $result = $statement->fetchObject();
            array_push($name, "{$result->user_fname}");
            array_push($fullName, "{$result->user_fname} {$result->user_lname}");
            array_push($online, $result->user_online);
            if ($result->user_image != null) {
                array_push($userImages, 'user_images/' . $result->user_image);
            } else {
                array_push($userImages, 'person.svg');
            }
        }

        return array($name, $fullName, $online, $userImages);
    } catch(Exception $e) { logError($e); }
}

function messagesGetMessageDetails($connection, $chatId, $userId) {
    try {
        $arr = array();

        $statement = $connection->prepare("
        SELECT sep_messages.message, sep_messages.created_on, sep_messages.job_id, sep_messages.user_id
        FROM sep_messages 
        WHERE (sep_messages.user_id = ? AND sep_messages.other_user_id = ?)
            OR (sep_messages.other_user_id = ? AND sep_messages.user_id = ?)
        GROUP BY sep_messages.message_id
        ORDER BY sep_messages.created_on DESC LIMIT 1");
        $statement->bindParam(1, $chatId);
        $statement->bindParam(2, $userId);
        $statement->bindParam(3, $chatId);
        $statement->bindParam(4, $userId);
        $statement->execute();
        while ($row = $statement->fetchObject()) {
            array_push($arr, $row);
        }
        return $arr;
    } catch(Exception $e)  { logError($e); }
}

function messagesGetJobTitle($connection, $jobId) {
    try {
        $statement = $connection->prepare("
        SELECT job_title FROM sep_available_jobs WHERE job_id = ?");
        $statement->bindParam(1, $jobId);
        $statement->execute();
        return $statement->fetchObject();
    } catch(Exception $e) { logError($e); }
}

// below are notification SQL statements
function getNotificationDetails($connection, $email) {
    try {
        $arr = array();

        $statement = $connection->prepare("
        SELECT sep_notifications.notification_message, notification_read, sep_notifications.sent_on, sep_available_jobs.job_title, sep_user_info.user_fname, sep_user_info.user_lname
        FROM sep_notifications JOIN sep_available_jobs
        ON sep_notifications.job_id = sep_available_jobs.job_id
        JOIN sep_users
        ON sep_available_jobs.user_id = sep_users.user_id
        JOIN sep_user_info
        ON sep_user_info.user_id = sep_notifications.user_id
        WHERE sep_users.user_email = ?
        GROUP BY sep_notifications.job_id, sep_notifications.user_id
        ORDER BY sep_notifications.sent_on
        LIMIT 5");
        $statement->bindParam(1, $email);
        $statement->execute();
        while ($row = $statement->fetchObject()) {
            array_push($arr, $row);
        }
        return $arr;
    } catch(Exception $e) { logError($e); }
}

// used in header.php
function headerNameAndImage($connection, $email) {
    $statement = $connection->prepare("
        SELECT sep_user_info.user_fname, sep_user_info.user_image, sep_users.user_id    
        FROM sep_user_info 
        JOIN sep_users ON sep_user_info.user_id = sep_users.user_id 
        WHERE user_email = ?");
    $statement->bindParam(1, $email);

    if($statement->execute()) {
        while($row = $statement->fetchObject()) {
            $userId = $row->user_id;
            $userFname = $row->user_fname;
            if ($row->user_image != null) {
                $src = "assets/user_images/{$row->user_image}";
            } else {
                $src = "assets/navPerson.svg";
            }
        }
    }

    return array($userId, $userFname, $src);
}

// used in header.php
function numberOfUnreadMessages($connection, $userId) {
    $unreadMessages = 0;

    $statement = $connection->prepare("
        SELECT message_read
        FROM sep_read_messages
        WHERE sep_read_messages.user_id = ?
        AND sep_read_messages.message_read = FALSE
        GROUP BY job_id");
    $statement->bindParam(1, $userId);
    $statement->execute();
    while($row = $statement->fetchObject()) {
        $unreadMessages++;
    }
    return $unreadMessages;
}

// used in header.php
function getHeaderFname($connection, $email) {
    $userNames = array();

    $statement = $connection->prepare("
        SELECT user_fname FROM sep_user_info 
        INNER JOIN sep_users ON sep_user_info.user_id = sep_users.user_id 
        WHERE user_email = ?");
    $statement->bindParam(1, $email);

    if($statement->execute()){
        while($row = $statement->fetchObject()) {
            array_push($userNames, $row->user_fname);
        }
    }

    return $userNames;
}


?>





