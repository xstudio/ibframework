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
        if(method_exists($this, $_GET['a']))
            $this->$_GET['a']();
        else
            IB::printError('Undefined Action: '.$_GET['a']);
    
    }

    /**
     * URL redirect relate urlmanager configuration
     * @param string $url controller/action or action
     * @param array $params param=>value
     */
    public function redirect($url, $params=array())
    {
        $urls='';
        if(!IB::app()->urlManager['urlRewrite'])
        {
            if(strpos($url, '/')!==false)
                $url=explode('/', $url);
            if(is_array($url))
                $urls='?c='.$url[0].'&a='.$url[1];
            else
                $urls='?c='.$_GET['c'].'&a='.$url;
            if(!empty($params))
                $urls.='&'.http_build_query($params);
        }
        else
        {
            if(strpos($url, '/')===false)
                $urls=$_GET['c'].'/'.$url;
            if(!empty($params))
                foreach($params as $key=>$value)
                    $urls.='/'.$key.'/'.$value;
            
        }
        if(IB::app()->urlManager['showScriptName'])
        {
            if(IB::app()->urlManager['urlRewrite'])
                $urls=$_SERVER['SCRIPT_NAME'].'/'.$urls;
            else
                $urls=$_SERVER['SCRIPT_NAME'].$urls;
        }
        else
            $urls=dirname($_SERVER['SCRIPT_NAME']).'/'.$urls;
        header('Location:'.$urls);
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
        else
            IB::printError("Unknown view file: ".$tpl);
    }
}
