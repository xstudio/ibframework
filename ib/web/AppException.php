<?php

/**
 * exception class
 *
 * @version 1.0
 * @filesource
 * @author yueqian.sinaapp.com
 */

/**
 * 抛出异常并且记录异常信息日志
 *
 * <code>
 * <?php
 * try
 * {
 *      throw new AppExpection('exception');    //抛出异常
 * }
 * catch(AppException $e)       //捕获异常
 * {
 *      echo($e->getMessage());
 * }
 *
 * </code>
 */
class AppException extends Exception
{
    public function __construct($msg='', $code=0)
    {
        parent::__construct($msg, $code);
        IB::log($msg);
    }
}
