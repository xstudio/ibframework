<?php

/**
 * redis cache string data type set/get/delete
 * @version 1.0
 * @date 14/01/08
 * @author yueqian.sinaapp.com
 */
class IRedis extends Cache
{
    public function __construct($rs_config=array())
    {
        $this->_cache=new Redis();
        if(!empty($rs_config))
        {
            try
            {
                $this->_cache->connect($rs_config[0], $rs_config[1]);
            }
            catch(RedisException $e)
            {
                IB::log("Redis connected error: ".$e->getMessage(), false);
            }
        }
    }
    /**
     * add cache
     * @param string $key
     * @param mixed $value
     * @param int $expire if not set, it will be 0, express cache never expired
     * @param boolean $is_compress is compress cache
     * @return boolean
     */
    public function setex($key, $expire, $value)
    {
        $expire=$expire ? $expire : 0;
        return $this->_cache->setex($this->getEncryKey($key), $expire, $value);
    }
    /**
     * delete all cache
     * @return boolean
     */
    public function flushAll()
    {
        return $this->_cache->flushAll();
    }
    /**
     * decrement cache value
     * @return integer decremented value
     */
    public function decrby($key, $num=0)
    {
        return $this->_cache->decrby($this->getEncryKey($key), $num);
    }
    /**
     * increment cache value
     * @return integer incremented value
     */
    public function incrby($key, $num=0)
    {
        return $this->_cache->incrby($this->getEncryKey($key), $num);
    }
}
 
