<?php
/**
 *
 * User: daikai
 * Date: 2021/5/24
 */

namespace Clearswitch\BleachHttp;

use Clearswitch\DoraemonIoc\Container;

/**
 * 批量请求
 * Class BatchRequest
 * @package ClearSwitch\Http
 */
class BatchRequest
{

    /**
     * 默认的批量一次是50
     * @var int
     */
    protected $batchNumber = 50;

    /**
     * 批量求的request
     * @var array
     */
    protected $requests = [];

    /**
     * request 服务
     * @var string
     */
    public $requestAisle = 'curl';


    /**
     * 服务容器
     * @var
     */
    protected $ioc;

    /**
     * BatchRequest constructor.
     */
    public function __construct()
    {
        $this->ioc = new Container();
        $this->ioc->bind('curl', 'BleachHttp\Aisle\CurAisle');
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
     * Date: 2023/3/14 下午3:28
     * @return mixed
     * @throws \Exception
     * @author clearSwitch
     */
    public function send()
    {
        $result = [];
        $responses = [];
        if (empty($this->requests)) {
            throw new \Exception('请求体不能为空');
        }
        foreach ($this->getRequests() as $v) {
            $response = $this->ioc->make($this->requestAisle)->batchSend($v);
            $result = array_merge($result, $response);
        };
        foreach ($result as $v) {
            $responses[] = new Response(... $v);
        }
        return $responses;
    }

    /**
     * 设置批量请求
     * @param $request
     * @param null $batchNumber
     * @return $this
     * @author clearSwitch
     */
    public function setRequests($request, $batchNumber = null)
    {
        if ($batchNumber) {
            $this->batchNumber = $batchNumber;
        }
        $this->requests = array_chunk($request, $this->batchNumber);
        return $this;
    }

    /**
     * 获得请求的内容
     * @return array
     * @author clearSwitch
     */
    public function getRequests()
    {
        return $this->requests;
    }
}
