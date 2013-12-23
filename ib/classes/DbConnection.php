<?php

/**
 * Database connection 
 *
 * @version 1.0
 * @date 13/12/13
 * @author yueqian.sinaapp.com
 */
class DbConnection
{
    /**
     * db
     */
    private $_conn=null;
    private $_cmd;
    private $_transaction;

    public function __construct($db_config=array())
    {
        if(!empty($db_config))
        {
            try
            {
                $this->_conn=@new PDO($db_config['connString'], $db_config['username'], $db_config['password']);
                $this->_conn->query("set names {$db_config['charset']}");
            }
            catch(PDOException $e)
            {
                die('Connection failed : '.$e->getMessage());
            }
        }
    }  
    public function __destruct()
    {
        $this->close();
    }
    /**
     * close connection
     */
    public function close()
    {
        $this->_conn=null;
    }
    /**
     * get
     */
    public function getConnection()
    {
        return $this->_conn;
    }
    private function getCommand()
    {
        return $this->_cmd;
    }
    private function getTransaction()
    {
        return $this->_transaction;
    }

    /**
     * Command instance
     */
    public function createCommand($queryString=null)
    {
        return $this->_cmd=new DbCommand($this->_conn, $queryString);
    }

    /**
     * Get last inserte id
     */
    public function getLastInsertId ()
    {
        return $this->_conn->lastInsertId;
    }

    /**
     * Transaction instance
     */
    public function beginTransaction()
    {
        return $this->_transaction=new Transaction($this->_conn);
    }
}
