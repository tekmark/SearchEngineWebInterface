<?php
/**
 * Created by PhpStorm.
 * User: chaohan
 * Date: 5/16/16
 * Time: 6:10 PM
 */
  $array = array();
  for ($i = 0; $i < 100; ++$i) {
      array_push($array, $i);
  }


  $page_num = $_REQUEST["page"];
  //$result ="haha";
//  $result = "result is :".$page_num;
    function dummy($page, $array) {
        $start = ($page - 1) * 10;
        $result = array_slice($array, $start, 10);
        return $result;
    }
    function test($i) {
        return $i + 1;
    }

    echo json_encode(test($page_num));
