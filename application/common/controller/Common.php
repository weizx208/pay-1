<?php

/**
 *  +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2018 http://www.iredcap.cn All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 *  +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 *  +----------------------------------------------------------------------
 */

namespace app\common\controller;

use think\Controller;
use think\exception\HttpResponseException;
use think\Request;
use think\Response;

class Common extends Controller
{

    /**
     * 数据返回
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param mixed $data 返回数据
     * @param int $code 状态码
     * @param string $msg 消息
     * @param string $type 数据格式
     * @param array $header
     */
    final protected function result($code = 0, $msg = '', $data ='', $type = 'json', array $header = [])
    {

        $result = is_array($code) ? $this->parseJumpArray($code) : $this->parseJumpArray([$code, $msg, $data]);

        $response = Response::create($result, $type)->header($header);

        throw new HttpResponseException($response);
    }

    /**
     * 解析数组
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $data
     * @return array
     */
    final protected function parseJumpArray($data = [])
    {
        !isset($data[2]) && $data[2] = [];

        return ['code' => $data[0], 'msg' => $data[1] ,'count' => count($data[2]),'data' => $data[2]];
    }

    /**
     * 获取逻辑层实例  --魔术方法
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $logicName
     * @return \think\Model|\think\Validate
     */
    public function __get($logicName)
    {
        $layer = $this->getLayerPre($logicName);

        $model = sr($logicName, $layer);

        return VALIDATE_LAYER_NAME == $layer ? validate($model) : model($model, $layer);
    }

    /**
     * 获取层前缀
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $name
     * @return bool|mixed
     */
    public function getLayerPre($name)
    {

        $layer = false;

        $layer_array = [MODEL_LAYER_NAME, LOGIC_LAYER_NAME, VALIDATE_LAYER_NAME, SERVICE_LAYER_NAME];

        foreach ($layer_array as $v)
        {
            if (str_prefix($name, $v)) {

                $layer = $v;

                break;
            }
        }

        return $layer;
    }
}