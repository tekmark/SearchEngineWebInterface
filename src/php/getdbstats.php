<?php
/**
 * Created by PhpStorm.
 * User: chaohan
 * Date: 6/6/16
 * Time: 12:59 PM
 */
include ('../log4php/Logger.php');
include ('../settings.php');

$log = Logger::getRootLogger();


$cmd = "export JAVA_HOME=".JAVA_HOME." && cd ".NUTCH_BASE." && bin/nutch readdb crawl/crawldb/ -stats";
$output = shell_exec($cmd);
//echo "<p>".$output."</p>";
echo nl2br($output);
