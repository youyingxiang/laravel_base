<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2020/4/26
 * Time: 3:05 PM
 */

namespace App\Services;

use Carbon\Carbon;
use DateInterval;
use DateTimeInterface;
use RedisClient;
use Illuminate\Support\Facades\Cache;

/**
 * Class CacheService
 * @method rememberForever($key, \Closure $param)
 * @method setDefaultCacheTime(\Illuminate\Config\Repository $config)
 * @method getDefaultCacheTime()
 * @method increment($key, int $value)
 * @method decrement($key, int $value)
 * @method remember($fmt, int $int, \Closure $param)
 */
class CacheService {
    const KEY_USER_SHOP_CART = 'user_shop_cart';

    public function __construct() {
        $this->setDefaultCacheTime(config('global.redis_key_expire'));
    }

    /**
     * @param      $key
     * @param      $field
     * @param      $increment
     * @param null $ttl
     */
    public function hIncrBy($key, $field, $increment, $ttl = null) {
        RedisClient::hIncrBy(Cache::getPrefix() . $key, $field, $increment);
        if ($ttl) {
            $seconds = $this->getSeconds($ttl);
            $this->redisExpire($key, $seconds);
        }
    }

    /**
     * @param $sKey
     * @param $field
     *
     * @return array|mixed|string
     */
    public function hGet($sKey, $field) {
        $sData = RedisClient::hGet(Cache::getPrefix() . $sKey, $field);
        if ($sData) {
            $aData = json_decode($sData, true);
            if ($aData && (is_object($aData)) || (is_array($aData) && !empty($aData))) {
                $result = $aData;
            } else {
                $result = $sData;
            }
            return $result;
        } else {
            return null;
        }
    }

    /**
     * @param      $sKey
     * @param      $field
     * @param      $content
     * @param null $time
     *
     * @return bool|int
     */
    public function hSet($sKey, $field, $content, $time = null) {
        if (is_array($content) || is_object($content)) {
            $bRes = RedisClient::hSet(Cache::getPrefix() . $sKey, $field, json_encode($content, true));
        } else {
            $bRes = RedisClient::hSet(Cache::getPrefix() . $sKey, $field, $content);
        }
        self::redisExpire($sKey, $time);
        return $bRes;
    }

    /**
     * @param $sKey
     * @param $field
     *
     * @return mixed
     */
    public function hDel($sKey, $field) {
        return RedisClient::hDel(Cache::getPrefix() . $sKey,$field);
    }

    /**
     * @param $key
     * @param $ttl
     */
    public function redisExpire($key, $ttl)
    {
        RedisClient::expire(\Cache::getPrefix() . $key, $ttl ?: $this->getDefaultCacheTime());
    }

    /**
     * @param $key
     * @return mixed
     */
    public function hvals($key)
    {
        return RedisClient::hvals(Cache::getPrefix() . $key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function hgetall($key)
    {
        $res =  RedisClient::hgetall(Cache::getPrefix() . $key);
        if ($res) {
            $res = array_map(function ($val){
                return json_decode($val,true);
            },$res);
        }
        return $res ?? [];
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments) {
        return Cache::$method(... $arguments);
    }
}
