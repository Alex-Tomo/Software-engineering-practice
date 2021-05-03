<?php

    function getConnection() {
        // Newnumyspace data
//        $serverName = 'localhost';
//        $username = 'unn_w19007452';
//        $password = 'Password';
//        $databaseName = 'unn_w19007452';

        // Localhost (xampp)
         $serverName = '';
         $username = 'root';
         $password = '';
         $databaseName = 'software_engineering';
        try {
            $conn = new PDO("mysql:host=$serverName;dbname=$databaseName",
                $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(Exception $ex) {
            logError($ex);
            throw new Exception("Connection error: " . $ex->getMessage() . "\n" . $ex->getFile() . "\n" . $ex->getLine(), 0, $ex);
        }
    }

    function exceptionHandler($e) {
        logError($e);
    }

    set_exception_handler("exceptionHandler");

    function errorHandler($errno, $errstr, $errfile, $errline) {
        if(!(error_reporting() & $errno)) {
            return;
        }
        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
    }

    set_error_handler("errorHandler");

    function logError($e) {
        $file = fopen("error_log_file.log", "ab");
        if ($file === false) {
            echo "<p>Could not open file</p>";
        } else {
            $errorDate = date("D M j G:i:s T Y'");
            $errorMessage = $e->getMessage();
            $toReplace = array("\r\n", "\n", "\r"); //Characters to replace
            $replaceWith = "";
            $errorMessage = str_replace($toReplace, $replaceWith, $errorMessage);

            fwrite($file, "Error date: " . $errorDate . " | Error details: " . $errorMessage . PHP_EOL);
            fclose($file);
        }
    }

?>