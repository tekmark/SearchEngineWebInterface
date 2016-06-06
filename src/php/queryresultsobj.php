<?php
/**
 * Created by PhpStorm.
 * User: chaohan
 * Date: 6/5/16
 * Time: 5:47 PM
 */
class QueryResultsObj {
    var $total_number_of_hits;
    var $records;
    var $eclipsed_time_msec;
    var $start;
    function __construct($results_obj) {
        $this->total_number_of_hits = java_values($results_obj->getTotalNumberOfHits());
        $this->eclipsed_time = java_values($results_obj->getEclipsedTime());
//            $this->start = java_values($results_obj->getStart());
        $this->records = java_values($results_obj->getRecords());
    }

    function getTotalNumberOfHits() {
        return $this->total_number_of_hits;
    }

    function getNumberOfRecords() {
        return count($this->records);
    }

    function getRecords() {
        return $this->records;
    }

    function getEclipsedTime() {
        return $this->eclipsed_time;
    }

    function getRecordsJSON() {
        $array_json = array();
        $length = count($this->records);

        for ($i = 0; $i < $length; ++$i) {
            $record = $this->records[$i];
            $url=java_values($record->getUrl());
            $title=java_values($record->getTitle());
            $record_json = array (
                "recno" => $i,
                "url" => $url,
                "title" => $title
            );
            array_push($array_json, $record_json);
        }
        return json_encode($array_json);
    }

    function dump() {
        echo $this->total_number_of_hits;
        echo "<br>";
        echo $this->eclipsed_time;
        echo "<br>";
        echo count($this->records);
        echo "<br>";
        echo $this->getRecordsJSON();
    }
}