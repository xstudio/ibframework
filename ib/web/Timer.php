<?php

/**
 * Timer calculate execute micro time
 * @version 1.0
 * @date 14/1/2
 * @author yueqian.sinaapp.com
 */
class Timer
{
    /**
     * store current time
     */ 
    private $startTime;
    private $pauseTime;
    private $stopTime;
    
    /**
     * start this timer
     */
    public function start()
    {
        $this->startTime=microtime(true);
    }
    /**
     * timer pause
     */
    public function pause()
    {
        $this->pauseTime=microtime(true);
    }
    /**
     * timer unpause time between pause and unpause doesn't calculate
     */
    public function unPause()
    {
        $this->startTime+=microtime(true)-$this->pauseTime;
        $this->pauseTime=0;
    }
    /**
     * stop this timer
     */
    public function stop()
    {
        $this->stopTime=microtime(true);
    }
    /**
     * get total micro time
     * @param integer $decimalPlaces fetched decimal places
     */
    public function fetch($decimalPlaces=4)
    {
        return round(($this->stopTime-$this->startTime), $decimalPlaces);
    }

}
