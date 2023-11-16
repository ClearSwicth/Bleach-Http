<?php
/**
 * SerializerInterface.php
 * 文件描述
 * Created on 2023/11/15 17:12
 * Creat by ClearSwitch
 */

namespace ClearSwitch\BleachHttp\Serializer;

interface SerializerInterface
{
    /**
     * 序列化数据
     * @return mixed
     * @author SwitchSwitch
     */
    public static function serializer($data);
}