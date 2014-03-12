<?php

/**
 * DB Transaction
 *
 * @filesource
 * @version 1.0
 * @date 13/12/13
 * @author yueqian.sinaapp.com
 */

/**
 * 事物处理类，只是封装了PDO的事物处理，也可以在获取PDO连接后，直接使用PDO进行事物操作
 * 
 * <code>
 * $db=IB::app()->db;
 * $transaction=$db->beginTransaction(); //开启事务
 * try
 * {
 *   //throw new AppException('sssssssss');
 *   $db->createCommand('delete from chat where id_chat=17')->execute();
 *   $db->createCommand('delete from chat where id_chat=4')->execute();
 *   $transaction->commit();
 * }
 * catch(Exception $e)
 * {
 *   $transaction->rollback(); //执行出错，事务回滚
 * }
 * </code>
 */
class Transaction
{
    /**
     * db connection
     */
    private $_conn;

    public function __construct($conn=null)
    {
        $this->_conn=$conn;
        $this->_conn->beginTransaction();
    }
    /**
     * commit a transaction
     */
    public function commit()
    {
        $this->_conn->commit();
    }
    /**
     * rollback operate
     */
    public function rollback()
    {
        $this->_conn->rollback();
    }
}
