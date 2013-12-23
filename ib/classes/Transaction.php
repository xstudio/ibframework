<?php

/**
 * DB Transaction
 *
 * @version 1.0
 * @date 13/12/13
 * @author yueqian.sinaapp.com
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
