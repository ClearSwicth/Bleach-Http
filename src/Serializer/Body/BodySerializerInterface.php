<?php
/**
 * BodySerializerInterface.php
 * 文件描述
 * Created on 2023/11/15 17:15
 * Creat by ClearSwitch
 */

namespace ClearSwitch\BleachHttp\Serializer\Body;

use ClearSwitch\BleachHttp\Serializer\SerializerInterface;

interface BodySerializerInterface extends SerializerInterface
{
    /**
     * 设置请求头
     * @return array
     * @author SwitchSwitch
     */
    public static function headers(): array;

}