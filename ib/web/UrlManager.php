<?php

/**
 * Url manager 
 *
 * @function parseUrl c=controller&a=action => controller/action
 * @filesurce
 * @version 1.0
 * @date 14/1/2
 * @author yueqian.sinaapp.com
 */

/**
 * 根据链接是否静态化设置要定用的controller和action
 */
class UrlManager
{
    public static function parseUrl()
    {
        if(isset($_SERVER['PATH_INFO']))
        {
            $pathInfo=explode('/', trim($_SERVER['PATH_INFO'], '/'));
            $_GET['c']=(isset($pathInfo[0]) && !empty($pathInfo[0])) ? $pathInfo[0] : IB::app()->defaultController;
            $_GET['a']=(isset($pathInfo[1]) && !empty($pathInfo[1])) ? explode('.', $pathInfo[1])[0] : 'index';

            for($i=2; $i<count($pathInfo); $i+=2)
                $_GET[$pathInfo[$i]]=$pathInfo[$i+1];
        }
        else
        {
            $_GET['c']=(isset($_GET['c']) && !empty($_GET['c'])) ? $_GET['c'] : IB::app()->defaultController;
            $_GET['a']=(isset($_GET['a']) && !empty($_GET['a'])) ? explode('.', $_GET['a'])[0] : 'index';
        }
    }
}
