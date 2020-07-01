<?php

namespace App\Services;

class BaseService
{
    /**
     * @param array $aParams
     * $aParams['body'] = "xx充值"
     * $aParams['trade_type'] = "JSAPI"
     * $aParams['out_trade_no'] = "2019101255102485"
     * $aParams['openid'] = "o6ZHPvgOGw-fE9s_LdpD76u8ACPo"
     * $aParams['notify_url'] = "http://api.jukejia.com/api/staff/v1/recharge_notify"
     * $aParams['total_fee'] = "15"
     *
     * @return array
     * @throws ApiParamsException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see 统一下单接口
     */
}
