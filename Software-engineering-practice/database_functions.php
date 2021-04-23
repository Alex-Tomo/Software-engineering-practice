<?php

    function selectAll($connection, $columnOne, $columnTwo, $tableName, $orderBy) {

        $columnOneArr = array();
        $columnTwoArr = array();

        $columnOneResult = '';
        $columnTwoResult = '';

        $result = $connection->query("SELECT {$columnOne} AS columnOne, {$columnTwo} AS columnTwo FROM {$tableName} ORDER BY {$orderBy}");
        if ($result) {
            while ($row = $result->fetchObject()) {

                if(isset($row->columnOne) && isset($row->columnTwo)) {
                    $columnOneResult = $row->columnOne;
                    $columnTwoResult = $row->columnTwo;

                    if (gettype($columnOneResult) == 'string') {
                        $columnOneResult = filter_var($columnOneResult, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    } elseif (gettype($columnOneResult) == 'integer') {
                        $columnOneResult = filter_var($columnOneResult, FILTER_SANITIZE_NUMBER_INT);
                    } elseif (gettype($columnOneResult) == 'double') {
                        $columnOneResult = filter_var($columnOneResult, FILTER_SANITIZE_NUMBER_FLOAT);
                    }

                    if (gettype($columnTwoResult) == 'string') {
                        $columnTwoResult = filter_var($columnTwoResult, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    } elseif (gettype($columnTwoResult) == 'integer') {
                        $columnTwoResult = filter_var($columnTwoResult, FILTER_SANITIZE_NUMBER_INT);
                    } elseif (gettype($columnTwoResult) == 'double') {
                        $columnTwoResult = filter_var($columnTwoResult, FILTER_SANITIZE_NUMBER_FLOAT);
                    }

                    array_push($columnOneArr, $columnOneResult);
                    array_push($columnTwoArr, $columnTwoResult);
                }
            }
            return array($columnOneArr, $columnTwoArr);
        }
        return null;
    }

?>