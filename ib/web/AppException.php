<?php

/**
 *
 */
class AppException extends Exception
{
    public function __construct($msg='', $code=0)
    {
        parent::__construct($msg, $code);
        IB::log($msg);
    }
}
