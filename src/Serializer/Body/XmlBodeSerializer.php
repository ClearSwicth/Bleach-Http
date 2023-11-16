<?php
/**
 * XmlBodeSerializer.php
 * 文件描述
 * Created on 2023/11/16 11:09
 * Creat by ClearSwitch
 */

namespace ClearSwitch\BleachHttp\Serializer\Body;

class XmlBodeSerializer implements BodySerializerInterface
{

    /**
     * xml的编码方式
     * @var
     */
    public static $charset = "UTF-8";

    public static function headers(): array
    {
        return [
            'Content-Type' => 'application/xml'
        ];
    }

    public static function serializer($data)
    {
        return self::arrayToXml($data);
    }

    /**
     * 数组转成xml
     * @param $arr
     * @param $dom
     * @param $item
     * @return false|string
     * @throws \DOMException
     * @author SwitchSwitch
     */
    public static function arrayToXml($arr, $dom = 0, $item = 0)
    {
        if (!$dom) {
            $dom = new \DOMDocument("1.0", self::$charset);
        }
        if (!$item) {
            $item = $dom->createElement("request");
            $dom->appendChild($item);
        }
        foreach ($arr as $key => $val) {
            $itemx = $dom->createElement(is_string($key) ? $key : "item");
            $item->appendChild($itemx);
            if (!is_array($val)) {
                $text = $dom->createTextNode($val);
                $itemx->appendChild($text);
            } else {
                self::arrayToXml($val, $dom, $itemx);
            }
        }
        return $dom->saveXML();
    }

}