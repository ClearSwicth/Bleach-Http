<?php
/**
 *
 * User: daikai
 * Date: 2023/3/13
 */

namespace Clearswitch\BleachHttp;


use ClearsWitch\DataConversion\DataConversion;
use Clearswitch\DoraemonIoc\Container;

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
     * 服务容器
     * @var
     */
    protected $ioc;

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
        $this->ioc = new Container();
        $this->ioc->bind('curl', 'ClearsWitch\BleachHttp\Aisle\CurAisle');
    }

    /**
     * 设置服务通道
     * @param $requestAisle
     * @return $this
     * @author clearSwitch
     */
    public function setRequestAisle($requestAisle)
    {
        $this->requestAisle = $requestAisle;
        return $this;
    }

    /**
     * 获得请求地址
     * @param $url
     * @return $this
     * @author clearSwitch
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 获得请求的地址
     * @return mixed
     * @author clearSwitch
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * 设置请求方式
     * @param $method
     * @return $this
     * @author clearSwitch
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * 获得请求方法
     * @return string
     * @author clearSwitch
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
     * @author clearSwitch
     */
    public function setContent($content, $type = "json")
    {
        $data = (new DataConversion())->dataConversion($content, $type);
        $this->content = $data;
        return $this;
    }

    /**
     * 获得请求参数
     * @return mixed
     * @author clearSwitch
     */
    public function getContent()
    {
        return $this->content;
    }

    /***
     * 设置请求参数
     * @param array $headers
     * @return $this
     * @author clearSwitch
     */
    public function setHeader(array $headers)
    {
        $this->header = $headers;
        return $this;
    }

    /**
     * 获得请求参数
     * @return array
     * @author clearSwitch
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * 设置超时时间
     * @param int $timeOut
     * @return $this
     * @author clearSwitch
     */
    public function setTimeOut(int $timeOut)
    {
        $this->timeOut = $timeOut;
        return $this;
    }

    /**
     * 获得超时时间
     * @return int
     * @author clearSwitch
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
     * @author clearSwitch
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
     * @author clearSwitch。
     */
    public function getProxyHost()
    {
        return $this->proxyHost;
    }

    /**
     * 获取代理端口
     * @return int
     * @author clearSwitch。
     */
    public function getProxyPort()
    {
        return $this->proxyPort;
    }

    /**
     * 发送请求
     * @author clearSwitch
     */
    public function send()
    {
        $aisle = $this->ioc->make($this->requestAisle);
        list($status, $headers, $content, $response) = $aisle->send($this);
        return new Response($status, $headers, $content, $response);
    }
}
