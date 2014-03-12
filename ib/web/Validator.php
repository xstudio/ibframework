<?php

/**
 * validate data 
 * @version 1.0
 * @date 14/01/11
 * @filesource
 * @author yueqian.sinaapp.com
 */
/**
 * 数据合法性校验
 *
 * <code>
 * <?php
 * var_dump(Validator::url('http://yueqian.sinaapp.com'));
 * var_dump(Validator::email('hucsecurity@163.com'));
 * var_dump(Validator::username('小笙_'));
 * </code>
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
     * @param integer $case['min'] $str min length utf8编码 中文单词长度为1
     * @param integer $case['max'] $str max length
     * @param boolean $case['ch_char'] $str is contains chinese character 是否能包含中文
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
