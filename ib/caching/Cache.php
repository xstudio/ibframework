<?php

/**
 * cache parent class 
 * set/get/delete/increment/decrement
 * @version 1.0
 * @date 14/01/08
 * @author yueqian.sinaapp.com
 */
class Cache
{
    /**
     * cache instance
     */
    protected $_cache;

    /**
     * @return key's md5 number
     */
    public function getEncryKey($key)
    {
        return md5($key);
    }
    /**
     * get
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     * get cache by key
     */
    public function get($key)
    {
        return $this->_cache->get($this->getEncryKey($key));
    }
    /**
     * delete cache by key
     */
    public function delete($key)
    {
        return $this->_cache->delete($this->getEncryKey($key));
    }
}
 
