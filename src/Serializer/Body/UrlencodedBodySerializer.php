<?php
/**
 * UrlencodedBodySerializer.php
 * 文件描述
 * Created on 2023/11/16 11:07
 * Creat by ClearSwitch
 */

namespace ClearSwitch\BleachHttp\Serializer\Body;

class UrlencodedBodySerializer implements BodySerializerInterface
{

    public static function headers(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
    }

    public static function serializer($data)
    {
        return http_build_query($data);
    }
}