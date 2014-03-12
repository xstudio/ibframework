<?php

/**
 * Timer calculate execute micro time
 * @version 1.0
 * @date 14/1/2
 * @filesource
 * @author yueqian.sinaapp.com
 */
/**
 * 计时类，用于统计运行时间，时间精确到微秒
 *
 * <code>
 * <?php
 * $timer=new Timer();
 * $timer->start(); //启动计时
 * sleep(2);
 * $timer->stop(); //停止计时
 * echo $timer->fetch(); //计时时间
 * $timer->start();
 * sleep(2);
 * $timer->pause(); //暂停计时
 * sleep(2);
 * $timer->unPause(); //解除暂停
 * sleep(1);
 * $timer->stop();
 * echo $timer->fetch();
 *
 * </code>
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
     * @param integer $decimalPlaces 保存的小数位数
     */
    public function fetch($decimalPlaces=4)
    {
        return round(($this->stopTime-$this->startTime), $decimalPlaces);
    }

}
