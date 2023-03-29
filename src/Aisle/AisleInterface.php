<?php
/**
 *
 * User: daikai
 * Date: 2023/3/13
 */

namespace ClearSwitch\BleachHttp\Aisle;


use ClearSwitch\BleachHttp\Request;

interface AisleInterface
{
    /**
     * 单个请求
     * @param Request $request
     * @return mixed
     * @author ClearSwitch
     */
    public function send(Request $request);

    /**
     * 批量并发的请求
     * @param $requests
     * @return mixed
     * @author ClearSwitch
     */
    public function batchSend($requests);
}
