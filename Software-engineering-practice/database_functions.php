<?php

    function sanitizeData($data) {
        if (gettype($data) == 'string') {
            $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        } elseif (gettype($data) == 'integer') {
            $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        } elseif (gettype($data) == 'double') {
            $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT);
        }
        return $data;
    }

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

    function selectUsersChosenCategories($connection, $usersEmail) {
        try {
            $result = $connection->query("
                SELECT sep_users_interested_jobs.job_code
                FROM sep_users_interested_jobs
                INNER JOIN sep_users
                ON sep_users_interested_jobs.user_id = sep_users.user_id
                WHERE sep_users.user_email = '{$usersEmail}'");

            $arr = array();

            if($result) {
                while($row = $result->fetchObject()) {
                    array_push($arr, sanitizeData($row->job_code));
                }
            }
            return $arr;
        } catch(Exception $e) {
//            TODO add a body - create a logger to file.
            return null;
        }
    }

    function getRecommendedJobs($connection, $choicesArray) {

        $arr = array();
        $index = 0;

        try {
            //            TODO Jobs need types adding so this will need changing
            $result = $connection->query("
              SELECT sep_user_info.user_id, 
                     sep_user_info.user_fname, 
                     sep_user_info.user_lname, 
                     sep_available_jobs.job_id,
                     sep_available_jobs.job_title,
                     sep_available_jobs.job_desc,
                     sep_available_jobs.job_price,
                     sep_available_jobs.job_date,
                     sep_available_jobs.job_image
              FROM sep_user_info
              INNER JOIN sep_available_jobs
              ON sep_user_info.user_id = sep_available_jobs.user_id
              INNER JOIN sep_users_interested_jobs
              ON sep_user_info.user_id = sep_users_interested_jobs.user_id
              WHERE sep_available_jobs.job_availability = '1'
              AND sep_available_jobs.job_id IN (" . implode(',', $choicesArray) . ")
              GROUP BY user_id
              ORDER BY sep_available_jobs.job_date DESC
              LIMIT 3");

            if ($result) {
                while ($row = $result->fetchObject()) {
                    $arr[$index]['userId'] = sanitizeData($row->user_id);
                    $arr[$index]['userFname'] = sanitizeData($row->user_fname);
                    $arr[$index]['userLname'] = sanitizeData($row->user_lname);
                    $arr[$index]['jobId'] = sanitizeData($row->job_id);
                    $arr[$index]['jobTitle'] = sanitizeData($row->job_title);
                    $arr[$index]['jobDesc'] = sanitizeData($row->job_desc);
                    $arr[$index]['jobPrice'] = sanitizeData($row->job_price);
                    $arr[$index]['jobDate'] = sanitizeData($row->job_date);
                    $arr[$index]['jobImage'] = sanitizeData($row->job_image);
                    $index++;
                }
            }

            return $arr;
        } catch (Exception $e) {
//            TODO add logger
            return null;
        }

    }

    function getStarRating($connection, $jobId) {

        $result = $connection->query("
            SELECT (SUM(sep_job_rating.job_rating)/COUNT(*)) as sum, COUNT(sep_job_rating.job_id) as total
            FROM sep_job_rating
            INNER JOIN sep_available_jobs
            ON sep_job_rating.job_id = sep_available_jobs.job_id
            INNER JOIN sep_users
            ON sep_job_rating.user_id = sep_users.user_id
            WHERE sep_job_rating.job_id = '{$jobId}'
            GROUP BY sep_job_rating.job_id");

        if($result) {
            while ($row = $result->fetchObject()) {
                $sum = sanitizeData(round($row->sum, 0, PHP_ROUND_HALF_DOWN));
                $total = sanitizeData($row->total);
            }
        }
        if(isset($sum) && isset($total)) return array($sum, $total);
        else return array(0, 0);
    }

    function getRecentJobs($connection) {

        $arr = array();
        $index = 0;

        try {
            $result = $connection->query("
                SELECT sep_user_info.user_id, 
                    sep_user_info.user_fname, 
                    sep_user_info.user_lname,
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
                GROUP BY sep_user_info.user_id
                ORDER BY sep_available_jobs.job_date DESC
                LIMIT 3");

            if ($result) {
                while ($row = $result->fetchObject()) {

                    $arr[$index]['userId'] = sanitizeData($row->user_id);
                    $arr[$index]['userFname'] = sanitizeData($row->user_fname);
                    $arr[$index]['userLname'] = sanitizeData($row->user_lname);
                    $arr[$index]['jobId'] = sanitizeData($row->job_id);
                    $arr[$index]['jobTitle'] = sanitizeData($row->job_title);
                    $arr[$index]['jobDesc'] = sanitizeData($row->job_desc);
                    $arr[$index]['jobPrice'] = sanitizeData($row->job_price);
                    $arr[$index]['jobDate'] = sanitizeData($row->job_date);
                    $arr[$index]['jobImage'] = sanitizeData($row->job_image);
                    $index++;
                }
            }

            return $arr;

        } catch (Exception $e) {

            return null;
        }
    }

    function getPopularCategories($connection) {

        $arr = array();

        try {
            $result = $connection->query("
                SELECT COUNT(sep_users_interested_jobs.job_code), sep_jobs_list.job_name
                FROM sep_users_interested_jobs
                INNER JOIN sep_jobs_list
                ON sep_users_interested_jobs.job_code = sep_jobs_list.job_code
                GROUP BY sep_jobs_list.job_name
                ORDER BY COUNT(sep_users_interested_jobs.job_code) DESC
                LIMIT 10");

            if ($result) {
                while ($row = $result->fetchObject()) {
                    array_push($arr, sanitizeData($row->job_name));
                }
            }
            return $arr;
        } catch(Exception $e) {
//            TODO add a body
            return null;
        }
    }

    function getRecentlyViewed($connection, $recentlyViewed)
    {
        $results = $connection->query("
            SELECT sep_available_jobs.job_title, sep_available_jobs.job_price, sep_available_jobs.job_image
            FROM sep_available_jobs
            WHERE sep_available_jobs.job_id = '{$recentlyViewed}'");

        if ($results) {
            while ($row = $results->fetchObject()) {
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

    function getJobDetails($connection, $jobId) {

        $arr = array();
        $index = 0;

        $results = $connection->query("
            SELECT sep_user_info.user_id, 
                sep_user_info.user_fname, 
                sep_user_info.user_lname,
                sep_available_jobs.job_id,
                sep_available_jobs.job_title,
                sep_available_jobs.job_desc,
                sep_available_jobs.job_price,
                sep_available_jobs.job_date,
                sep_available_jobs.job_image
            FROM sep_user_info
            INNER JOIN sep_available_jobs
            ON sep_user_info.user_id = sep_available_jobs.user_id
            WHERE sep_available_jobs.job_id = '{$jobId}'");

        if($results) {
            while($row = $results->fetchObject()) {
                $arr[$index]['userId'] = sanitizeData($row->user_id);
                $arr[$index]['userFname'] = sanitizeData($row->user_fname);
                $arr[$index]['userLname'] = sanitizeData($row->user_lname);
                $arr[$index]['jobId'] = sanitizeData($row->job_id);
                $arr[$index]['jobName'] = sanitizeData($row->job_title);
                $arr[$index]['jobDesc'] = sanitizeData($row->job_desc);
                $arr[$index]['jobPrice'] = sanitizeData($row->job_price);
                $arr[$index]['jobDate'] = sanitizeData($row->job_date);
                $arr[$index]['jobImage'] = sanitizeData($row->job_image);
                $index++;
            }
        }
        return $arr;
    }

?>