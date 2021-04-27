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
                JOIN sep_users
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
                     sep_available_jobs.job_date
              FROM sep_user_info
              JOIN sep_available_jobs
              ON sep_user_info.user_id = sep_available_jobs.user_id
              JOIN sep_users_interested_jobs
              ON sep_user_info.user_id = sep_users_interested_jobs.user_id
              WHERE sep_available_jobs.job_availability = '1'
              AND sep_users_interested_jobs.job_code IN (" . implode(',', $choicesArray) . ")
              GROUP BY user_id
              ORDER BY sep_available_jobs.job_date DESC
              LIMIT 3");

            if ($result) {
                while ($row = $result->fetchObject()) {
                    $arr[$index]['user_id'] = sanitizeData($row->user_id);
                    $arr[$index]['user_fname'] = sanitizeData($row->user_fname);
                    $arr[$index]['user_lname'] = sanitizeData($row->user_lname);
                    $arr[$index]['job_id'] = sanitizeData($row->job_id);
                    $arr[$index]['job_title'] = sanitizeData($row->job_title);
                    $arr[$index]['job_desc'] = sanitizeData($row->job_desc);
                    $arr[$index]['job_price'] = sanitizeData($row->job_price);
                    $arr[$index]['job_date'] = sanitizeData($row->job_date);
                    $index++;
                }
            }

            return $arr;
        } catch (Exception $e) {
//            TODO add logger
            return null;
        }

    }

    function getStarRating($connection, $job_id) {

        $result = $connection->query("
            SELECT (SUM(sep_job_rating.job_rating)/COUNT(*)) as sum, COUNT(sep_job_rating.job_id) as total
            FROM sep_job_rating
            JOIN sep_available_jobs
            ON sep_job_rating.job_id = sep_available_jobs.job_id
            JOIN sep_users
            ON sep_job_rating.user_id = sep_users.user_id
            WHERE sep_job_rating.job_id = '{$job_id}'
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
                    sep_available_jobs.job_date
                FROM sep_user_info
                INNER JOIN sep_available_jobs
                ON sep_user_info.user_id = sep_available_jobs.user_id
                WHERE sep_available_jobs.job_availability = '1'
                ORDER BY sep_available_jobs.job_date DESC
                LIMIT 3");

            if ($result) {
                while ($row = $result->fetchObject()) {
                    $arr[$index]['user_id'] = sanitizeData($row->user_id);
                    $arr[$index]['user_fname'] = sanitizeData($row->user_fname);
                    $arr[$index]['user_lname'] = sanitizeData($row->user_lname);
                    $arr[$index]['job_id'] = sanitizeData($row->job_id);
                    $arr[$index]['job_title'] = sanitizeData($row->job_title);
                    $arr[$index]['job_desc'] = sanitizeData($row->job_desc);
                    $arr[$index]['job_price'] = sanitizeData($row->job_price);
                    $arr[$index]['job_date'] = sanitizeData($row->job_date);
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
                JOIN sep_jobs_list
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
            SELECT sep_available_jobs.job_title, sep_available_jobs.job_price
            FROM sep_available_jobs
            WHERE sep_available_jobs.job_id = '{$recentlyViewed}'");

        if ($results) {
            $row = $results->fetchObject();
            $job_title = sanitizeData($row->job_title);
            $job_price = sanitizeData($row->job_price);
        }

        return array($job_title, $job_price);
    }

?>