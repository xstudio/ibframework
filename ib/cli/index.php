<?php

//you can change it if necessary     
error_reporting(0);
$ib=dirname(__FILE__).'/ib/ib.php';
$config=dirname(__FILE__).'/config.php';
//change it false when in production mode
define('DEBUG', true);

require_once($ib);
IB::createApplication($config)->run();


