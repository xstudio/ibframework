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
$include_path.=PATH_SEPARATOR.dirname(__FILE__)."/classes/" ;    
$include_path.=PATH_SEPARATOR.dirname($config)."/protected/controllers/" ;   
$include_path.=PATH_SEPARATOR.dirname($config)."/protected/models/" ;     
$include_path.=PATH_SEPARATOR.dirname($config)."/protected/views/" ;    
set_include_path($include_path);

//class autoload
function __autoload($class_name)
{
    include($class_name.'.php');
}

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


}
