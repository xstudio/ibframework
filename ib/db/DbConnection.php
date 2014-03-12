<?php

/**
 * Database connection 
 *
 * @filesource
 * @version 1.0
 * @date 13/12/13
 * @author yueqian.sinaapp.com
 */

/**
 * 数据库连接类，调用此连接通过IB::app()->db
 * 
 * <code>
 * <?php
 * var_dump(IB::app()->db->getConnection()); //调用生成的PDO链接，方便使用PDO的一些操作方法
 * var_dump(IB::app()->db->createCommand()); //生成一个DbCommand对象
 * var_dump(IB::app()->db->getLastInsertId()); //获取db的lastinserid
 * var_dump(IB::app()->db->beginTransaction()); //生成Transaction对象，开启一个事物
 * </code>
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
                IB::log('Db Connection failed : '.iconv('gb2312', 'utf-8', $e->getMessage()), false);
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
    public function getCommand()
    {
        return $this->_cmd;
    }
    public function getTransaction()
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
     * Transaction instance begin transaction turning off autocommit 
     */
    public function beginTransaction()
    {
        return $this->_transaction=new Transaction($this->_conn);
    }
}
