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

//catch notice and warning info 
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if($errno==1)
        $error_level='Error: ';
    elseif($errno==8)
        $error_level='Notice: ';
    elseif($errno==2)
        $error_level='Warning: ';
    else
        $error_level='Unknown error type: ';
    IB::log($error_level.$errstr.' in '.$errfile.' on line '.$errline, false);
});

//catch fatal error
register_shutdown_function(function() {
    if ($error = error_get_last()) 
        IB::log('Fatal error: '.$error['message'].' in '.$error['file'].' on line '.$error['line'], false);
});

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
        try
        {
            if(file_exists($config))
            {
                $config_arr=include($config);
                return self::$_app=new Application($config_arr);
            }
            else
                throw new AppException("Can't load config file, please check exists and readable.");
        }
        catch(AppException $e){}
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
    public static function log($msg='', $is_trace=true)
    {
        $tmp_msg='';
        if($is_trace)
        {
            $traces=debug_backtrace();
            for($i=0; $i<3; $i++)
            {
                if(isset($traces[$i]['file'], $traces[$i]['line']))
                    $tmp_msg.="\nin ".$traces[$i]['file'].' (Line:'.$traces[$i]['line'].")";
            }
        }
        if(isset(self::app()->basePath))
            $path=self::app()->basePath;
        else
        {
            global $config;
            $path=dirname($config).'/protected';
        }

        file_put_contents($path.'/runtime/application.log', date('Y/m/d H:i:s', time()).' '.$msg.$tmp_msg."\n", FILE_APPEND);
        if(DEBUG) 
            self::printError($msg.$tmp_msg);
    }
    public static function printError($msg)
    {
        echo '<meta charset="utf-8">';
        echo '<div style="padding:5px; margin:10px; background-color:#efefef; ">';
        echo '<pre style="white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;word-wrap:break-word;">'.$msg.'</pre>';
        echo '</div>';
        return;
    }
}
