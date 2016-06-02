<?php
/**
 * Created by PhpStorm.
 * User: chaohan
 * Date: 5/16/16
 * Time: 3:28 PM
 */
require_once("http://localhost:8080/JavaBridge/java/Java.inc");
$System = java("java.lang.System");
echo $System->getProperties();