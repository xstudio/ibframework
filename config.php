<?php

/**
 *
 * Configuration page
 *
 * @version 1.0
 * @date 13/12/13
 * @author yueqian.sinaapp.com
 */

return array(
    //website title
    'title'=>'',
    'basePath'=>dirname(__FILE__).'/protected/',
    'defaultController'=>'site',

    //PDO database config
    'db_config'=>array(
        'connString'=>'mysql:host=localhost;dbname=sep',
        'username'=>'root',
        'password'=>'123456',
        'prefix'=>'bbs_',
        'charset'=>'utf8',
    ),

    //custom global variable
    'param'=>array(
        'defaultWebSite'=>'yueqian.sinaapp.com',
    )
);

