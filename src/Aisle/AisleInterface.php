<?php
/**
 *
 * User: daikai
 * Date: 2023/3/13
 */

namespace BleachHttp\Aisle;


use BleachHttp\Request;

interface AisleInterface
{
    /**
     * 单个请求
     * @param Request $request
     * @return mixed
     * @author clearSwitch
     */
    public function send(Request $request);

    /**
     * 批量并发的请求
     * @param $requests
     * @return mixed
     * @author clearSwitch
     */
    public function batchSend($requests);
}
