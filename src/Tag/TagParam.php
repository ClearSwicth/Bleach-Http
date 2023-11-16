<?php
/**
 * TagParam.php
 * 文件描述
 * Created on 2023/11/16 11:25
 * Creat by ClearSwitch
 */

namespace ClearSwitch\BleachHttp\Tag;

class TagParam
{
    /**
     * 标记这是一个文件
     * @param $value
     * @return \CURLFile
     * @author SwitchSwitch
     */
    public static function file($value)
    {
        return new \CURLFile($value);
    }
}