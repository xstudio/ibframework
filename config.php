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
    'publicPath'=>dirname(__FILE__).'/public/',
    'defaultController'=>'site',

    //convert url to path-formath and decide show index.php or not
    'urlManager'=>array(
        'urlRewrite'=>true,
        'showScriptName'=>false,
    ),

    //import class or director
    'import'=>array(
        'application.extension.*'
    ),

    //PDO database config
    'db_config'=>array(
        'connString'=>'mysql:host=localhost;dbname=sep',
        'username'=>'root',
        'password'=>'123456',
        'prefix'=>'',
        'charset'=>'utf8',
    ),

    //custom global variable
    'param'=>array(
        'defaultWebSite'=>'yueqian.sinaapp.com',
    )
);

