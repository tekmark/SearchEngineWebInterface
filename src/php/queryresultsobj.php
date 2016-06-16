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
    var $searching_time_msec;
    var $ranking_time_msec;
    function __construct($results_obj) {
        $this->total_number_of_hits = java_values($results_obj->getTotalNumberOfHits());
        $this->eclipsed_time_msec = java_values($results_obj->getEclipsedTimeMilliSec());
        $this->searching_time_msec = java_values($results_obj->getSearchingTimeMilliSec());
        $this->ranking_time_msec = java_values($results_obj->getRankingTimeMilliSec());
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

    function getEclipsedTimeMilliSec() {
        return $this->eclipsed_time_msec;
    }

    function getSearchingTimeMilliSec() {
        return $this->searching_time_msec;
    }
    function getRankingTimeMilliSec() {
        return $this->ranking_time_msec;
    }


    function getRecordsJSON() {
        $array_json = array();
        $length = count($this->records);

        for ($i = 0; $i < $length; ++$i) {
            $record = $this->records[$i];
            $url=java_values($record->getUrl());
            $title=java_values($record->getTitle());
            $score = java_values($record->getUrlScore());
            $relevance = java_values($record->getLuceneScore());
            $inlink = java_values($record->getInlink());
            $outlink = java_values($record->getOutlink());
            $record_json = array (
                "recno" => $i,
                "url" => $url,
                "title" => $title,
                "score" => $score,
                "relevance" => $relevance,
                "inlink" => $inlink,
                "outlink" => $outlink
            );
            array_push($array_json, $record_json);
        }
        return json_encode($array_json);
    }

    function dump() {
        echo $this->total_number_of_hits;
        echo "<br>";
        echo $this->eclipsed_time_msec;
        echo "<br>";
        echo count($this->records);
        echo "<br>";
        echo $this->getRecordsJSON();
    }
}