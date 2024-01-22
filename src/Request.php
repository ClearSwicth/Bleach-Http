<?php
/**
 *
 * User: daikai
 * Date: 2023/3/13
 */

namespace ClearSwitch\BleachHttp;


use ClearSwitch\BleachHttp\Aisles\AisleInterface;
use ClearSwitch\BleachHttp\Container\Ioc;
use ClearSwitch\BleachHttp\Serializer\Body\FormDataBodySerializer;
use ClearSwitch\BleachHttp\Serializer\Body\JsonBodySerializer;
use ClearSwitch\BleachHttp\Serializer\Body\UrlencodedBodySerializer;
use ClearSwitch\BleachHttp\Serializer\Body\XmlBodeSerializer;

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
     * 消息体序列化器
     * @var string
     * @author ClearSwitch
     */
    protected $bodySerializer = JsonBodySerializer::class;


    /**
     * 序列化方式
     * @var string
     */
    protected $serializer = '';

    /**
     * 序列化的类型
     * @var string[]
     */
    protected $serializerType = ['json', 'xml', 'form_data', 'urlencoded'];

    /**
     * 注册请求通道
     * Request constructor
     */
    public function __construct()
    {
        Ioc::bind('curl', 'ClearSwitch\BleachHttp\Aisles\CurAisle');
    }

    /**
     * 设置服务通道
     * @param $requestAisle
     * @return $this
     * @author ClearSwitch
     */
    public function setRequestAisle($requestAisle): Request
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
            $newAisle = Ioc::make($aisleName, $classPath);
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
    public function setUrl($url): Request
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
    public function setMethod($method): Request
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * 获得请求方法
     * @return string
     * @author ClearSwitch
     */
    public function getMethod(): string
    {
        return $this->method;
    }


    /**
     * 设置请求数据
     * @param array $data
     * @return $this
     * @author SwitchSwitch
     */
    public function setContent(array $data, $serializer = 'json'): Request
    {
        if (!in_array($serializer, $this->serializerType)) {
            throw new \Exception("序列化类型错");
        }
        $this->serializer = $serializer;
        if ($serializer != 'json') {
            $this->getSerializerBody($serializer);
        }
        $this->content = $data;
        return $this;
    }

    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     *  获得序列器
     * @param $serializer
     * @return void
     * @throws \Exception
     * @author SwitchSwitch
     */
    protected function getSerializerBody($serializer)
    {
        if (!in_array($serializer, $this->serializerType)) {
            throw new \Exception("序列化类型错");
        }
        switch ($serializer) {
            case 'xml':
                $this->bodySerializer = XmlBodeSerializer::class;
                break;
            case 'form_data':
                $this->bodySerializer = FormDataBodySerializer::class;
                break;
            case 'urlencoded':
                $this->bodySerializer = UrlencodedBodySerializer::class;
                break;
            default:
        }
    }

    /**
     * 获得请求参数
     * @return mixed
     * @author ClearSwitch
     */
    public function getContent()
    {
        if (!empty($this->content)) {
            $class = $this->bodySerializer;
            return $class::serializer($this->content);
        } else {
            return null;
        }
    }


    /***
     * 设置请求参数
     * @param array $headers
     * @return $this
     * @author ClearSwitch
     */
    public function setHeader(array $headers): Request
    {
        $this->header = $headers;
        return $this;
    }

    /**
     * 获得请求参数
     * @return array
     * @author ClearSwitch
     */
    public function getHeader(): array
    {
        if (!in_array($this->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            return $this->header;
        }
        $class = $this->bodySerializer;
        $headers = array_merge($this->header, $class::headers());
        $headers['Content-Length'] = strlen($this->getContent());
        return $headers;
    }

    /**
     * 设置超时时间
     * @param int $timeOut
     * @return $this
     * @author ClearSwitch
     */
    public function setTimeOut(int $timeOut): Request
    {
        $this->timeOut = $timeOut;
        return $this;
    }

    /**
     * 获得超时时间
     * @return int
     * @author ClearSwitch
     */
    public function getTimeOut(): int
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
    public function setProxy($host, $port = null): Request
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
     * @return Response
     * @throws \Exception
     * @author SwitchSwitch
     */
    public function send(): Response
    {
        $aisle = Ioc::make($this->requestAisle);
        list($status, $headers, $content, $response) = $aisle->send($this);
        return new Response($status, $headers, $content, $response);
    }
}
