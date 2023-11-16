<?php
/**
 * JsonBodySerializer.php
 * 文件描述
 * Created on 2023/11/15 17:20
 * Creat by ClearSwitch
 */

namespace ClearSwitch\BleachHttp\Serializer\Body;

class JsonBodySerializer implements BodySerializerInterface
{

    /**
     * 设置价json的请求头
     * @return string[]
     * @author SwitchSwitch
     */
    public static function headers(): array
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }

    public static function serializer($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        } else {
            throw new \Exception("使用json序列化时候数据必须是数组");
        }
    }
}