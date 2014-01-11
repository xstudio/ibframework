<?php

/**
 * validate data 
 * @version 1.0
 * @date 14/01/11
 * @author yueqian.sinaapp.com
 */
class Validator
{
    /**
     * is email format
     */
    public static function email($str)
    {
        $reg='/^\s*[a-zA-Z0-9]\w*[a-z0-9A-Z]@[a-zA-Z0-9]+(.[a-zA-Z]+)+\s*$/';
        return preg_match($reg, $str);
    }
    /**
     * is url format
     */
    public static function url($str)
    {
        $reg='/^(https?|ftp):\/\/([A-z0-9]+[_\-]?[A-z0-9]+\.)*[A-z0-9]+\-?[A-z0-9]+\.[A-z]{2,}(\/.*)*\/?$/';
        return preg_match($reg, $str);
    }
    /**
     * is username format
     * @param integer $case['min'] $str min length
     * @param integer $case['max'] $str max length
     * @param boolean $case['ch_char'] $str is contains chinese character
     */
    public static function username($str, $case=array('min'=>3, 'max'=>20, 'ch_char'=>true))
    {
        $len=mb_strlen($str, 'utf-8');
        if($len>=$case['min'] && $len<=$case['max'])
        {
            if($case['ch_char'])
                $reg="/^([0-9a-zA-Z\x7f-\xff])+(_)*$/";
            else
                $reg="/^([0-9a-zA-Z])+(_)*$/";
            return preg_match($reg, $str);
        }
        else
            return 0;
    }

}
