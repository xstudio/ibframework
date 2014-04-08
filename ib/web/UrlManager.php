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
            
            if(isset($pathInfo[0]) && !empty($pathInfo[0])) 
                $_GET['c']=$pathInfo[0];
            else $_GET['c']=IB::app()->defaultController;

            if(isset($pathInfo[1]) && !empty($pathInfo[1])) 
            {
                $tmp=explode('.', $pathInfo[1]);
                $_GET['a']=$tmp[0];
            }
            else $_GET['a']='index';

            for($i=2; $i<count($pathInfo); $i+=2)
                $_GET[$pathInfo[$i]]=$pathInfo[$i+1];
        }
        else
        {
            $_GET['c']=(isset($_GET['c']) && !empty($_GET['c'])) ? $_GET['c'] : IB::app()->defaultController;

            if(isset($_GET['a']) && !empty($_GET['a']))
            {
                $tmp=explode('.', $_GET['a']);
                $_GET['a']=$tmp[0];
            }
            else $_GET['a']='index';
        }
    }
}
