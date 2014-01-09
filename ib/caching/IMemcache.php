<?php

/**
 * memory cache set/get/delte
 * @version 1.0
 * @date 14/01/08
 * @author yueqian.sinaapp.com
 */
class IMemcach
{
    /**
     * memcache
     */
    private $_cache;

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
     * get
     */
    public function getCache()
    {
        return $this->_cache;
    }
    /**
     * add cache
     * @param string $key
     * @param mixed $value
     * @param int $expire if not set, it will be 0, express cache never expired
     */
    public function set($key, $value, $expire=0)
    {
        $key=md5($key);
        $expire=$expire ? $expire : 0;
        //MEMCACHE_COMPRESSED zlib compress cache
        $this->_cache->set($key, $value, MEMCACHE_COMPRESSED, $expire);
    }
    /**
     * replace cache
     * @param string $key
     * @param mixed $value
     * @param int $expire if not set, it will be 0, express cache never expired
     */
    public function replace($key, $value, $expire=0)
    {
        $key=md5($key);
        $expire=$expire ? $expire : 0;
        //MEMCACHE_COMPRESSED zlib compress cache
        $this->_cache->set($key, $value, MEMCACHE_COMPRESSED, $expire);
    }
    /**
     * get cache
     * @param string $key
     */
    public function get($key)
    {
        $key=md5($key);
        return $this->_cache->get($key);
    }
    /**
     * delete cache
     */
    public function delete($key)
    {
        $key=md5($key);
        $this->_cache->delete($key);
    }
    /**
     * delete all cache
     */
    public function flush()
    {
        $this->_cache->flush();
    }
}
 
