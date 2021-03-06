<?php

/**
 * memory cache 
 * set/get/delete/increment/decrement
 *
 * @filesource
 * @version 1.0
 * @date 14/01/08
 * @author yueqian.sinaapp.com
 */

/**
 * memcache缓存类，调用通过IB::app()->memcache， 通过IB::app()->memcache->getCache()即可操作原生memcache
 * 缓存的key值使用sql语句的md5值，具体的操作同memcache
 * 
 */
class IMemcache extends Cache
{
    public function __construct($mc_config=array())
    {
        $this->_cache=new Memcache();
        if(!empty($mc_config))    
        {
            foreach($mc_config as $server)
                $this->_cache->addServer($server[0], $server[1]);
        }
        $status=$this->_cache->getStats();
        if(empty($status))
            IB::log("Memcache connected error, please check it's config", false);
    }

    /**
     * add cache
     * @param string $key
     * @param mixed $value
     * @param int $expire if not set, it will be 0, express cache never expired
     * @param boolean $is_compress is compress cache 是否压缩存储
     * @return boolean
     */
    public function set($key, $value, $expire=0, $is_compress=true)
    {
        $key=md5($key);
        $expire=$expire ? $expire : 0;
        if($is_compress)
            return $this->_cache->set($key, $value, MEMCACHE_COMPRESSED, $expire);
        else
            return $this->_cache->set($key, $value, false, $expire);

    }
    /**
     * delete all cache
     * @return boolean
     */
    public function flush()
    {
        return $this->_cache->flush();
    }
    /**
     * decrement cache value
     * @return integer decremented value
     */
    public function decrement($key, $num=0)
    {
        return $this->_cache->decrement($this->getEncryKey($key), $num);
    }
    /**
     * increment cache value
     * @return integer incremented value
     */
    public function increment($key, $num=0)
    {
        return $this->_cache->increment($this->getEncryKey($key), $num);
    }
}
 
