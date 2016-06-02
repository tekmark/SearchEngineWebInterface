/**
 * Created by chaohan on 5/17/16.
 */


function generateNavHtml(start, length) {
    var html = "";

    //check if starting from page 1; if true, no prev page;
    if (start != 1) {
        var prev_link= "<a href = \"#\">" + "prev" + "</a>";
        nav += "<li style=\"display: inline\"> " + prev_link + " </li>";
    }

    for (var i = start; i <= end; i++) {
        var link = "<a href = \"#\" style=\"font-size : 10px;\" onclick=\"getPageX('" + i + "')\">" + i + "</a>";
        nav += "<li style=\"display: inline\"> " + link + " </li>";
    }
    var next_link = "<a href = \"http://www.google.com\">" + "next>>" + "</a>";
    nav += "<li style=\"display: inline\"> " + next_link + " </li>";
    document.getElementById("nav").innerHTML = nav;
}