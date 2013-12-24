<?php

/**
 * Parent class of single controller
 * All controller class extends it
 *
 * @date 13/12/13
 */

class Controller
{
    /**
     * run action of controller 
     */
    public function run()
    {
        //default action
        $action='index';
        if(isset($_GET['a']) && !empty($_GET['a']))
            $action=$_GET['a'];
        if(method_exists($this, $action))
            $this->$action();
        else
            echo 'Error Action:'.$action;
    
    }

    /**
     * URL redirect
     */
    public function redirect($path, $args='')
    {
        /*$path=trim($path, "/");
        
        if($args!="")
			$args="&".$args;
        if(strstr($path, "/"))
        {
            $pathArr=explode('/', $path);
            $url='?c='.$pathArr[0].'&a='.$pathArr[1].$args;
        }
        else
        {
            $c=isset($_GET['c'])?$_GET['c']:DEFAULT_C;
            $url='?c='.$c."&a=".$path.$args;
        }

        $url=$GLOBALS["app"].$url;
        header("Location:$url");
        return;*/

    }

    /**
     *
     */
    public function render($tpl=null, $assign=array())
    {
        $controller=isset($_GET['c']) ? $_GET['c'] : IB::app()->defaultController;
        if(file_exists(IB::app()->basePath.'views/'.$controller.'/'.$tpl.'.php'))
        {
            //make str to variable 
            if(!empty($assign))
                extract($assign, EXTR_PREFIX_SAME, 'assign');
            include($controller.'/'.$tpl.'.php');
        }
    }
}
