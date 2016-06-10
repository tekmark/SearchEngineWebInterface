<?php
/**
 * Created by PhpStorm.
 * User: chaohan
 * Date: 6/6/16
 * Time: 12:59 PM
 */
include ('../log4php/Logger.php');

$log = Logger::getRootLogger();

$nutch_base = "/Volumes/MySeagateDisk/nutch/";
$env_java_home = "/Library/Java/JavaVirtualMachines/jdk1.8.0_05.jdk/Contents/Home";

$cmd = "export JAVA_HOME=".$env_java_home." && cd ".$nutch_base." && bin/nutch readdb crawl/crawldb/ -stats";
$output = shell_exec($cmd);
//echo "<p>".$output."</p>";
echo nl2br($output);
