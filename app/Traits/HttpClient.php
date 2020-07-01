<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2020/4/23
 * Time: 4:36 PM
 */

namespace App\Traits;

use GuzzleHttp\Client;

trait HttpClient {
    /**
     * HttpClient constructor.
     */
    public static function getClient(): Client {
        return new Client();
    }
}
