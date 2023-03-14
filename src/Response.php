<?php
/**
 *
 * User: daikai
 * Date: 2023/3/14
 */

namespace ClearSwitch\BleachHttp;

use ClearSwitch\DataConversion\DataConversion;

/**
 * 响应
 * @package BleachHttp
 */
class Response
{
    /**
     * 状态
     * @var
     */
    protected $status;

    /**
     *  Response header
     * @var array
     */
    protected $headers = [];

    /**
     * Response Body
     * @var
     */
    protected $content;

    /**
     * Response data
     * @var
     */
    protected $response;

    /**
     * Response constructor.
     * @param mixed ...$params
     */
    public function __construct(...$params)
    {
        $this->status = $params[0] ? $params[0] : null;
        $this->headers = $params[1] ? $params[1] : null;
        $this->content = $params[2] ? $params[2] : null;
        $this->response = $params[3] ? $params[3] : null;
    }

    /**
     * 获得Response header
     * @return array|mixed|string
     * @author ClearSwitch
     */
    public function getHeaders()
    {
        $headerData = array();
        if ($this->headers) {
            $headersData = explode("\r\n", $this->headers);
            foreach ($headersData as $key => $item) {
                $header = explode(':', $item);
                if (!empty($header[1])) {
                    if (isset($headerData[$header[0]])) {
                        if (!is_array($headerData[$header[0]])) {
                            $headerData[$header[0]] = [$headerData[$header[0]]];
                        }
                        $headerData[$header[0]][] = $header[1];
                    } else {
                        $headerData[$header[0]] = $header[1];
                    }
                }
            }
        }
        return $headerData;
    }

    /**
     * 获得原样的头部信息的输出
     * @author ClearSwitch
     */
    public function getRawHeaders()
    {
        return explode("\r\n", $this->headers);
    }

    /**
     * 获得 body
     * @return mixed|string
     * @author ClearSwitch
     */
    public function getRowContent()
    {
        return $this->content;
    }

    /**
     *  body 转换成数组
     * @return mixed|string
     * @author ClearSwitch
     */
    public function getBody()
    {
        if (is_array($body = (new DataConversion())->dataConversion($this->content, 'array'))) {
            return $body;
        } else {
            return $this->content;
        }
    }

    /**
     * 获得状态
     * @return mixed|string
     * @author ClearSwitch
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     * Date: 2023/3/14 上午11:08
     * @return mixed|null
     * @author ClearSwitch
     */
    public function getRowResponse()
    {
        return $this->response;
    }

    /**
     * 获得Cookies
     * @return array
     * @author ClearSwitch
     */
    public function getCookies()
    {
        $result = [];
        $headers = $this->getHeaders();
        if (isset($headers['Set-Cookie'])) {
            if ($cookies = $headers['Set-Cookie']) {
                if (!is_array($cookies)) {
                    $cookies = [$cookies];
                }
                foreach ($cookies as $cookie) {
                    $cookie = $this->parseCookie($cookie);
                    $result[$cookie['key']] = $cookie;
                }
            }
        }
        return $result;
    }

    /**
     * 解析Cookie
     * @param string $cookie cookie
     * @return array
     * @author Verdient。
     */
    public function parseCookie($cookie)
    {
        $cookie = explode('; ', $cookie);
        $keyValue = explode('=', $cookie[0]);
        unset($cookie[0]);
        $result['key'] = $keyValue[0];
        $result['value'] = urldecode($keyValue[1]);
        foreach ($cookie as $element) {
            $elements = explode('=', $element);
            $name = strtolower($elements[0]);
            if (count($elements) === 2) {
                $result[$name] = $elements[1];
            } else {
                $result[$name] = true;
            }
        }
        return $result;
    }
}
