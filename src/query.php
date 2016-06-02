<?php
/**
 * Created by PhpStorm.
 * User: chaohan
 * Date: 5/16/16
 * Time: 6:54 PM
 */
require_once("http://localhost:8080/JavaBridge/java/Java.inc");
//$path=new Java("java.lang.String", "/Users/chaohan/git/SearchEngine/abcd/");
$path_src='/Users/chaohan/git/SearchEngine/abcd/';

//$search_str=isset($_REQUEST["q"]) ? $_REQUEST["q"] : "";
//$page_num=isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
//$start=isset($_REQUEST["start"]) ? $_REQUEST["start"] : 0;
$query_results = null;
if (isset($_REQUEST["q"])) {
    $search_str=$_REQUEST["q"];
    $query_results=get_search_results($search_str, $path_src);
    $start=isset($_REQUEST["start"])? $_REQUEST["start"] : 0;
    return get_results_slice_json($query_results, $start, 10);
}

function get_search_results ($query_str, $path_src) {
    $path=new Java("java.lang.String", $path_src);
    //echo $path_src;
    //echo java_values($path)."<br>";
    //echo $query_str;
    $searcher=new Java("searchengine.MySearcher", $path);
    $results_array=$searcher->search($query_str);
    //echo $query_str;
    //echo "<br> # of records: ".$results_array->size()."<br>";
    return $results_array;
};

function get_results_slice_json($results, $start, $length) {
    $array_json = array();
    for ($i = $start; $i < $start + $length | $i < $results.$length; ++$i) {
        $record = $results->get($i);
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