<?php
/**
 * FormDataBodySerializer.php
 * 文件描述
 * Created on 2023/11/15 17:30
 * Creat by ClearSwitch
 */

namespace ClearSwitch\BleachHttp\Serializer\Body;

/**
 * FormData的序列化
 * Created on 2023/11/16 10:37
 * Creat by ClearSwitch
 */
class FormDataBodySerializer implements BodySerializerInterface
{

    public static function headers(): array
    {
        //boundary=<calculated when request is sent> 边界待定
        return [
            'Content-Type' => 'multipart/form-data'
        ];
    }

    /**
     * 我使用的数组，因此不需要边界
     * @param $data
     * @return array
     * @author SwitchSwitch
     */
    public static function serializer($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        } else {
            throw new \Exception("使用json序列化时候数据必须是数组");
        }
    }

}