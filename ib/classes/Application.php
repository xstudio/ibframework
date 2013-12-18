<?php

/**
 * Web site application base class 
 *
 * default index file will create this object for running
 * @variable string $title
 * @variable string basePath
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
                $this->$key=$value;
        }
    }

    public function __get($var)
    {
        if(!isset($this->$var))
        {
            if($var=='db')
                return $this->db=new DbConnection();
        }

        return $this->$var;
    }
    /**
     * execute action of action
     */
    public function run()
    {
        $c=(isset($_GET['c']) && !empty($_GET['c'])) ? $_GET['c']:$this->defaultController;
        $controllerFile=ucfirst(strtolower($c)).'Controller';
        if(file_exists($this->basePath.'controllers/'.$controllerFile.'.php'))
        {
            $controller=new $controllerFile;
            $controller->run();
        }
        else
            echo 'Error Controller';
    }
    /**
     * get db instance
     */
    public function getDb()
    {
        return $this->db;
    }
    
}
