<?php

/**
 *
 * default index page
 *
 * @version 1.0
 * @date 13/12/13
 *
 */
//you can change it if necessary
$ib=dirname(__FILE__).'/ib/ib.php';
$config=dirname(__FILE__).'/config.php';

require_once($ib);
IB::createApplication($config)->run();
//var_dump(IB::app());
