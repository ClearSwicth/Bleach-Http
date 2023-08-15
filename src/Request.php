<?php
/**
 *
 * User: daikai
 * Date: 2023/3/13
 */

namespace ClearSwitch\BleachHttp;


use ClearSwitch\BleachHttp\Aisle\AisleInterface;
use ClearSwitch\DataConversion\DataConversion;
use ClearSwitch\DoraemonIoc\Container;

class Request
{

    /**
     * 默认的请求通道
     * @var string
     */
    protected $aisleType = "curl";

    /**
     * 请求地址
     * @var
     */
    protected $url;

    /**
     * 请求方法
     * @var string
     */
    protected $method = "GET";

    /**
     * 请求头
     */
    protected $header = [];

    /**
     * 请求参数
     * @var
     */
    protected $content;

    /**
     * 超时时间
     * @var int
     */
    protected $timeOut = 10;

    /**
     * 代理地址
     * @var
     */
    protected $proxyHost;

    /**
     * 代理端口
     * @var
     */
    protected $proxyPort;

    /**
     * 请求通道
     * @var
     */
    protected $requestAisle = 'curl';

    /**
     * 注册请求通道
     * Request constructor
     */
    public function __construct()
    {
        Container::bind('curl', 'ClearSwitch\BleachHttp\Aisle\CurAisle');
    }

    /**
     * 设置服务通道
     * @param $requestAisle
     * @return $this
     * @author ClearSwitch
     */
    public function setRequestAisle($requestAisle)
    {
        $this->requestAisle = $requestAisle;
        return $this;
    }

    /**
     * 增加自己的请求通道
     * @param $aisleName
     * @param $classPath
     * @throws \Exception
     * @author clearSwitch
     */
    public function addAisle($aisleName, $classPath)
    {
        if (is_callable($classPath)) {
            $newAisle = Container::make($aisleName, $classPath);
            if (!$newAisle instanceof AisleInterface) {
                throw new \Exception("新增请求通道必须继承 AisleInterface");
            }
        }
    }

    /**
     * 获得请求地址
     * @param $url
     * @return $this
     * @author ClearSwitch
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 获得请求的地址
     * @return mixed
     * @author ClearSwitch
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * 设置请求方式
     * @param $method
     * @return $this
     * @author ClearSwitch
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * 获得请求方法
     * @return string
     * @author ClearSwitch
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * 设置请求参数
     * @param $content
     * @param string $type
     * @return $this
     * @author ClearSwitch
     */
    public function setContent($content, $type = "json")
    {
        if ($type == "urlencoded") {
            $data = http_build_query($content, '', "&", PHP_QUERY_RFC1738);
            $this->addHeader(['Content-Type' => 'application/x-www-form-urlencoded']);
        } else {
            $data = (new DataConversion())->dataConversion($content, $type);
        }
        $this->content = $data;
        return $this;
    }

    /**
     * 获得请求参数
     * @return mixed
     * @author ClearSwitch
     */
    public function getContent()
    {
        return $this->content;
    }

    /***
     * 设置请求参数
     * @param array $headers
     * @return $this
     * @author ClearSwitch
     */
    public function setHeader(array $headers)
    {
        $this->header = $headers;
        return $this;
    }

    /**
     * 获得请求参数
     * @return array
     * @author ClearSwitch
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * 设置超时时间
     * @param int $timeOut
     * @return $this
     * @author ClearSwitch
     */
    public function setTimeOut(int $timeOut)
    {
        $this->timeOut = $timeOut;
        return $this;
    }

    /**
     * 获得超时时间
     * @return int
     * @author ClearSwitch
     */
    public function getTimeOut()
    {
        return $this->timeOut;
    }

    /**
     * 设置代理
     * @param $host
     * @param null $port
     * @return $this
     * @author ClearSwitch
     */
    public function setProxy($host, $port = null)
    {
        $this->proxyHost = $host;
        $this->proxyPort = $port;
        return $this;
    }

    /**
     * 获取代理地址
     * @return string
     * @author ClearSwitch。
     */
    public function getProxyHost()
    {
        return $this->proxyHost;
    }

    /**
     * 获取代理端口
     * @return int
     * @author ClearSwitch。
     */
    public function getProxyPort()
    {
        return $this->proxyPort;
    }

    /**
     * 添加请求头
     * @param array $headers
     * @author clearSwitch
     */
    public function addHeader(array $headers)
    {
        $this->header = array_unique(array_merge($this->header, $headers));
    }

    /**
     * 发送请求
     * @author ClearSwitch
     */
    public function send()
    {
        $aisle = Container::make($this->requestAisle);
        list($status, $headers, $content, $response) = $aisle->send($this);
        return new Response($status, $headers, $content, $response);
    }
}
