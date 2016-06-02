<?php header("Content-type: text/html; charset=UTF-8"); ?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/resultpage.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    </head>
    <body>

    <div class="container">
        <div class="search">
            <?php $search_str = isset($_REQUEST["q"])?  $_REQUEST["q"] : "";?>
            <form name="searchbox" action="search.php" method="get">
                <input type="text" name="q" value="<?php echo $search_str; ?>" >
                <input type="submit" value="Search"">
            </form>
        </div>
        <!-- block for debugging -->
        <div class="debug">
            <?php
                $query = $search_str;
                include "query.php";
//                echo "Debug: query string: ".$query."<br>";
//                echo "Debug: path: ".$path_src."<br>";
                $results=get_search_results($query, $path_src);
                //encode($array);
//                echo "Debug:  # of records: ".$results->size()."<br>";
                //print_results_test($array);
            ?>
        </div>
        <script>
            function getResultPage(page_num) {
                document.getElementById("jsdummy").innerHTML="you clicked ajax test";
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
//                        document.getElementById("results").innerHTML = xmlhttp.responseText;
//                        document.getElementById("results").innerHTML = "DUMMY CONETEN";
                    }
                };
                xmlhttp.open("GET", "query.php?page=" + str, true);
                xmlhttp.send();
            }
        </script>

        <div class="results" id="results">
            <script>
                window.onload = getResultsSlice(0, 10);

                function getResultsSlice(start, length) {
                    console.log("request result slice starting from: " + start + " length: " + length);
                    var slice = <?php echo get_results_slice_json($results, $start, 10); ?>;
                    document.getElementById("results").innerHTML = getResultsHtmlBlock(slice);
                }

                function getRecordHtmlBlock(record) {
                    var rechtml =
                        "<div class=\"result_block\"> " +
                            "<div class=\"result_title\">" +
                                "<a href=" + record.url + ">" + record.title + "</a>" +
                            "</div>" +
                            "<div class=\"result_url\">" + record.url + "</div>" +
                            "<div class=\"result_content\">"  + "dummy content" + "</div>" +
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

            </script>
        </div>

        <div class="navigation">
            <ul id="nav">

            </ul>
            <script>
//                this function is used to generate a navigation bar below results.
            function generatePageNavHtml (start, length) {
                console.log("Generate page navigation starting from " + start);
                var html = "";
                if (start != 1) {   //no "previous" link if first page.
                    var prev_link = "<li><a>previous</a></li>";
                    html += prev_link;
                }
                for (var i = start; i < start + length; ++i) {
                    var link="<li><a>" + i + "</a></li>";
                    html += link;
                }
                var next_link = "<li><a>next</a></li>";
                html += next_link;
                return html;
            }

            document.getElementById("nav").innerHTML = generatePageNavHtml(1, 10);


                function page_range(start, end) {
                    var nav = "";
                    if (start != 1) {
                        var prev_link= "<a href = \"http://www.google.com\">" + "prev" + "</a>";
                        nav += "<li> " + prev_link + " </li>";
                    }
                    for (var i = start; i <= end; i++) {
                        var link = "<a href = \"#\" style=\"font-size : 10px;\" onclick=\"getPageX('" + i + "')\">" + i + "</a>";
                        nav += "<li style=\"display: inline\"> " + link + " </li>";
                    }
                    var next_link = "<a href = \"http://www.google.com\">" + "next>>" + "</a>";
                    nav += "<li style=\"display: inline\"> " + next_link + " </li>";
                    document.getElementById("nav").innerHTML = nav;
                }
                //page_range (2, 10);

                function getPage(page) {
                    var str = page;
                    console.log("request result page #" + page);
                    //document.getElementById("jsdummy").innerHTML="you clicked ajax test";
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            document.getElementById("results").innerHTML = xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open("GET", "search.php?page=" + str, true);
                    xmlhttp.send();
                }

                function requestResultSlice(start, length) {
                    console.log("request " + length + " records starting from " + start);
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            document.getElementById("jsdummy").innerHTML = xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open("GET", "ajaxtest.php?page=" + start, true);
                    xmlhttp.send();
                }

                function ajaxCall(start, length) {
                    console.log("ajax call" + start);
                    $.ajax({
                        url: 'query.php',
                        type: 'get',
                        data: { "start": start},
                        success: function(response) {
                            console.log("success");
                            console.log(response); },
                        dataType: 'json'
                    });
                }

                function  getPageX(page) {
                    console.log("request page #" + page);
                    //document.getElementById("results").innerHTML = "get" + page;
                    var start = (page - 1) * 10;
                    ajaxCall(start, 10);
                }
            //-->
            </script>
        </div>
    <a href="#" onclick="">ajax test</a>
  <button type="button" onclick="dump()">Ajax Test</button>
  <div id="jsdummy"> </div>
</div>
</body>
</html>
