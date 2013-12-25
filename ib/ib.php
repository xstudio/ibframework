<?php

/**
 * framework default index
 * 
 * @version 1.0
 * @date 13/12/13
 * @author yueqian.sinaapp.com
 */
//set include path
$include_path=get_include_path();                         
//framework class path
$include_path.=PATH_SEPARATOR.dirname(__FILE__)."/web/" ;    
$include_path.=PATH_SEPARATOR.dirname(__FILE__)."/db/" ;   
$include_path.=PATH_SEPARATOR.dirname(__FILE__)."/caching/" ;     
$include_path.=PATH_SEPARATOR.dirname($config)."/protected/models/" ; 
$include_path.=PATH_SEPARATOR.dirname($config)."/protected/controllers/" ;  
$include_path.=PATH_SEPARATOR.dirname($config)."/protected/views/" ;    
set_include_path($include_path);

//class autoload
function autoLoad($class_name)
{
    include($class_name.'.php');
}
spl_autoload_register('autoLoad');

class IB
{   
    /**
     * Application instance
     */
    private static $_app;
    /**
     * Get application instance
     */
    public static function app()
    {
        return self::$_app;
    }
    /**
     * new application and set $_app
     */
    public static function createApplication($config)
    {
        if(file_exists($config))
        {
            $config_arr=include($config);
            return self::$_app=new Application($config_arr);
        }
        return null;
    }
    /**
     * import file or directory
     */
    public static function import($aliass)
    {
        if(empty($aliass)) return;
        if(is_array($aliass))
        {
            foreach($aliass as $alias)
                self::loadFile($alias);
        }
        else
            self::loadFile($aliass);

    }
    private static function loadFile($alias='')
    {
        global $config;
        $ph=explode('.', $alias);
        $ph_path=implode('/', array_slice($ph, 1, count($ph)-2));

        if($ph[count($ph)-1]=='*') //directory
        {
            $include_path=get_include_path();    
            $include_path.=PATH_SEPARATOR.dirname($config).'/protected/'.$ph_path.'/';    
            set_include_path($include_path);
        }
        else if(file_exists($file=dirname($config).'/protected/'.$ph_path.'/'.ucfirst($ph[count($ph)-1]).'.php')) //file
            include($file);
    }
    /**
     * record runtime log
     * @param $msg log message
     */
    public static function log($msg='')
    {
        $traces=debug_backtrace();
        $tmp_msg='';
        for($i=0; $i<3; $i++)
        {
            if(isset($traces[$i]['file'], $traces[$i]['line']))
                $tmp_msg.="\nin ".$traces[$i]['file'].' (Line:'.$traces[$i]['line'].")";
        }
        file_put_contents(IB::app()->basePath.'/runtime/application.log', "\n".date('Y/m/d H:i:s', time()).' '.$msg.$tmp_msg, FILE_APPEND);
        if(DEBUG) 
        {
            echo '<meta charset="utf-8" />';
            echo '<div style="padding:5px; margin:10px; background-color:#efefef; ">';
            echo '<pre>'.$msg.$tmp_msg.'</pre>';
            echo '</div>';
        }
    }
}
