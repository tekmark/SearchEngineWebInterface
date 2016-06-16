<?php header("Content-type: text/html; charset=UTF-8"); ?>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <link rel="stylesheet" type="text/css" href="css/resultpage.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<?php
    require_once("http://localhost:8080/JavaBridge/java/Java.inc");
    include('log4php/Logger.php');
    include('settings.php');
    include('php/queryresultsobj.php');

    Logger::configure('log4phpconfig.xml');
    $log = Logger::getRootLogger();

    $absolute_db_dir_path = DB_LOCATION;

    //parse arguments.
    if (isset($_REQUEST[ARG_LENGTH])) {
        $arg_length = $_REQUEST[ARG_LENGTH];
        if ($arg_length > MAX_RECORDS_PER_PAGE) {
            $results_per_page = MAX_RECORDS_PER_PAGE;
        } else if ($arg_length < 0) {
            $results_per_page = DEFAULT_RECORDS_PER_PAGE;
        } else {
            $results_per_page = $arg_length;
        }
    } else {
        $results_per_page = DEFAULT_RECORDS_PER_PAGE;
    }

    if (isset($_REQUEST[ARG_START])) {
        $start = $_REQUEST[ARG_START];
        if ($start < 0) {
            $start = DEFAULT_START;
        }
    } else {
        $start = DEFAULT_START;
    }

    if (isset($_REQUEST[ARG_QUERY])) {
        $search_str=$_REQUEST[ARG_QUERY];

        if (!empty($search_str)) {

            $results = get_search_results_slice($search_str, $start, $results_per_page, $absolute_db_dir_path);
            if (!empty($results)) {
                $obj = new QueryResultsObj($results);
                $time = $obj->getEclipsedTimeMilliSec();
                $totalHits = $obj->getTotalNumberOfHits();
                $log->info("Query: ".$search_str.", found ".$totalHits." hits in ".$time." milliseconds. records from: " . $start . " records/page: " . $results_per_page);
                //$obj->dump();
            } else {
                $log->error("Query String: ".$search_str);
                $obj = null;
            }
        } else {
            $log->debug("Empty Query String");
        }
    } else {
        $search_str="";
    }
/*
    function get_search_results ($query_str, $path_src) {
        $path=new Java("java.lang.String", $path_src);
        $searcher=new Java("searchengine.MySearcher", $path);
        $results_array=$searcher->search($query_str);
        return $results_array;
    };
*/
    function get_search_results_slice($query_str, $start, $how_many, $path_src) {
        $path=new Java("java.lang.String", $path_src);
        $searcher=new Java("searchengine.MySearcher", $path);
        $results_array=$searcher->getResultsSlice($query_str, $start, $how_many);
        return $results_array;
    }
/*
   function parse_records_to_json($records) {
       $array_json = array();
       $length = java_values($records->size());
       for ($i = 0; $i < $length; ++$i) {
           $record = $records->get($i);
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
   }*/
?>

<div id="container">
    <!-- html block for search box-->
    <div id = "top">
        <div id="image_holder">

        </div>
        <div id="search_box">
            <form id ="searchbox" name="searchbox" action="resultpage.php" method="get">
                <input id="search_text_box" type="text" placeholder="Type to search ..." title="searchbox" name="query"
                       value="<?php echo $search_str; ?>" />
                <input id="search_btn" type="submit"  value="" onclick="return hasInput()"/>
                <!-- Searching and Ranking Options can be added bellow search box -->
            </form>
        </div>
<!--        <div id="buttons">-->
<!--        </div>-->
        <script>
            function hasInput() {
                var x;
                x = document.getElementById("search_text_box").value;
                if (x == "") {
                    return false;
                }
            }
        </script>
    </div>

    <?php
    //$query = $search_str;
    //include "query.php";
    //                echo "Debug: query string: ".$query."<br>";
    //                echo "Debug: path: ".$path_src."<br>";
    //$results=get_search_results($query, $path_src);
    //encode($array);
    //                echo "Debug:  # of records: ".$results->size()."<br>";
    //print_results_test($array);
    ?>
    <div id = "main-container">
<!--        <div id="button-frame">-->
<!--        </div>-->
<!--        <div>-->
        <div class="left">
            <div id="buttons">
                <button type="button" class="btn btn-info" id = "get-crawldb-stats">crawldb_stats</button>
                <button type="button" class="btn btn-info" id = "log-monitor" value="log monitor">log_monitor</button>
                <button type="button" class="btn btn-info" id = "nagios" value="service_monitor">service_monitor</button>
            </div>
            <div id="results">
                <div id="info">

                </div>
                <div id="records">

                </div>
                <div id="pagination">
                    <ul id="nav"></ul>
                </div>
            </div>
        </div>

        <div class="right">
<!--            <div id="options">-->
<!--                <button type="button" id = "get-db-stats" value="db status" data-toggle="collapse" data-target="#extra">-->
<!--                    db_status-->
<!--                </button>-->
<!--            </div>-->
            <div id="extra">
                <script>
                    
                    $('button#log-monitor').click(function () {
                        var host = window.location.host;
                        host += ":28778";
                        console.log("Open url: " + host);
                        window.open(host);
                    });

                    $('button#nagios').click(function () {
                        window.location="/nagios";
                    });
                    
                    $('button#get-crawldb-stats').click(function () {
                        console.log("get_db_stats Onclick()");
                        $.ajax({
                            url: 'php/getdbstats.php',
                            type: 'GET',
                            beforeSend : function () {
                                console.log("Loading...");
                                $('div#extra').html("<img src='img/loading-default.gif' />");
                            },
                            success: function(data) {
                                console.log("Get db status: " + data);
                                $('div#extra').html(data);
//                                    .collapse('show');
                            }
                        })
                    })
                </script>
<!--                Show Extra Infomation here.-->
            </div>
        </div>
    </div>
    <div id="footer">
        This page is developed by <a href="http://github.com/tekmark">Chao Han</a>.
        Contact: <a href="mailto:helloworld.c@icloud.com">helloworld.c@icloud.com</a>
    </div>
</div>
    
    <script>
        //parse results object.
        var total_time = <?php echo $obj->getEclipsedTimeMilliSec(); ?>;
        var searching_time = <?php echo $obj->getSearchingTimeMilliSec(); ?>;
        var ranking_time = <?php echo $obj->getRankingTimeMilliSec(); ?>;
        var total_hits = <?php echo $obj->getTotalNumberOfHits(); ?>;
        var slice = <?php echo $obj->getRecordsJSON();
            $log->debug($obj->getRecordsJSON())?>;
        var records_per_page = <?php echo $results_per_page; ?>;
        var start = <?php echo $start; ?>;

        function printResultInfo(eclipsed_time_msec, number_of_hits, searching_time_msec, ranking_time_msec) {
            console.log("total time: " + eclipsed_time_msec + ", # of total hits: " + number_of_hits);
            console.log("searching time " + searching_time_msec, ", ranking time: " + ranking_time_msec);
//            var infohtml = number_of_hits + " results (" + eclipsed_time_msec + " milliseconds, searching: "
//                + searching_time_msec + " milliseconds, ranking: " + ranking_time_msec + " milliseconds)";
            var infohtml = number_of_hits + " results (" + eclipsed_time_msec + " milliseconds)";
            document.getElementById("info").innerHTML = infohtml;
        }

        window.onload = printResultInfo(total_time, total_hits, searching_time, ranking_time);
        
        window.onload = getResultsSlice(slice);
        
        function getResultsSlice(slice) {
//            console.log("request result slice starting from: " + start + " length: " + length);
//            var slice = <?php ////echo get_results_slice_json($results, $start, 10); ?>////;
//            var slice = <?php //$obj->getRecordsJSON(); ?>//;
            document.getElementById("records").innerHTML = getResultsHtmlBlock(slice);
        }

        function getRecordHtmlBlock(record) {
//            var moreBtn = "<button type = \"button\" data-toggle=\"collapse\">more</Button>";
            var content = "<ul>" +
                "<li> url score: " + record.score.toFixed(4) + "</li> <li>Revelance:" + record.relevance.toFixed(4) + "</li>"
                +"</ul>";

            var rechtml =
                "<div class=\"result_block\"> " +
                "<div class=\"result_title\">" +
                "<a href=" + record.url + ">" + record.title + "</a>" +
                "</div>" +
                "<div class=\"result_url\">" + record.url + "</div>" +
                "<div class=\"result_content\">"  + content + "</div>" +
                "</div>";
            return rechtml;
        }

        function getResultsHtmlBlock(results) {
            var html = "";
            console.log("# of records: " + results.length);
            for (var i = 0; i < results.length; ++i) {
                html += getRecordHtmlBlock(results[i]);
            }
            return html;
        }

            //this function is used to generate a navigation bar below results.
            function getPaginationHtml(query, start, total_hits) {
                console.log("Start: " + start + ", records/page " + records_per_page + ", total hits: " + total_hits);
                var current_page_number = Math.ceil(start / records_per_page) + 1;
                var total_number_of_pages = Math.ceil(total_hits / records_per_page);
                console.log("current page#: " + current_page_number + ", total # of pages: " + total_number_of_pages);

                var html = "";
                var href = "";
                var entry = "";
                if (current_page_number != 1) {   //no "previous" link if first page.
                    start = (current_page_number - 2) * records_per_page;
                    href = hrefGenerator(query, start);
                    entry = "<li><a href= \"" + href + "\">Previous</a></li>";
                    html += entry;
                }
                //show current page number, no href.
                entry = "<li>" + current_page_number + "</li>";
                html += entry;

                var i = current_page_number + 1;
                while (i < current_page_number + 10 && i <= total_number_of_pages) {
                    start = (i - 1) * records_per_page;
                    href = hrefGenerator(query, start);
                    entry="<li><a href = \"" + href + "\">" + i + "</a></li>";
                    html += entry;
                    ++i;
//                    console.log(entry);
                }

                if (current_page_number < total_number_of_pages) {
                    start = current_page_number * records_per_page;
                    href = hrefGenerator(query, start );
                    entry = "<li><a href=\"" + href +"\">Next</a></li>";
                    html += entry;
                }
                return html;
            }

            function hrefGenerator (query, start) {
                var href = window.location.pathname + "?";
                href += "query=" + query;
                href += "&";
                href += "start=" + start;
                return href;
            }

            document.getElementById("nav").innerHTML = getPaginationHtml("rutgers", start, total_hits);

            var url = window.location.search.substring(1);
            console.log("Current url: " + url);

            function parseQueryString (queryString) {
                var params = {}, queries, temp, i, l;

                // Split into key/value pairs
                queries = queryString.split("&");
                console.log(queries);
                // Convert the array of strings into an object
                for ( i = 0, l = queries.length; i < l; i++ ) {
                    temp = queries[i].split('=');
                    params[temp[0]] = temp[1];
                }
                return params;
            }

            var parameters = parseQueryString(url);
            //-->
    </script>
</body>
</html>
