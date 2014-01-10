<?php

/**
 * Web site application base class 
 *
 * default index file will create this object for running
 * @variable string title
 * @variable string basePath
 * @variable string publicPath
 * @variable string defaultController
 * @variable array db_config
 * @variable array param
 *
 * @version 1.0
 * @date 13/12/13
 * @author yueqian.sinaapp.com
 */
class Application
{
    /**
     * assign config array paramter to class member
     * @param array $config included config file
     */
    public function __construct($config=array())
    {
        if(!empty($config))
        {
            foreach($config as $key=>$value)
            {
                $this->$key=$value;
                if($key=='db_config')   //set application db 
                    $this->db=new DbConnection($value);
                elseif($key=='import')  //import file or directory
                    IB::import($value);
                elseif($key=='caching') //set memcache/redis
                {
                    if(isset($value['memcache']))
                        $this->memcache=new IMemcache($value['memcache']);
                    if(isset($value['redis']))
                        $this->redis=new IRedis($value['redis']);
                    
                }
            }
        }
    }
    /**
     * execute action of action
     */
    public function run()
    {
        UrlManager::parseUrl();
        $controllerFile=ucfirst(strtolower($_GET['c'])).'Controller';
        if(file_exists($this->basePath.'controllers/'.$controllerFile.'.php'))
        {
            $controller=new $controllerFile;
            $controller->run();
        }
        else
            IB::printError('Unknown Controller: '.$_GET['c']);
    }
    /**
     * get db instance
     */
    public function getDb()
    {
        return $this->db;
    }

}
