<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/9/29
 * Time: 11:19 AM
 */

namespace App\Exceptions\Api;

use Exception;
use App\Traits\JsonResponseTrait;

class ApiUnAuthException extends Exception
{
    use JsonResponseTrait;

    /**
     *
     */
    public function report()
    {
        //
    }

    public function render($request)
    {
        return $this->unAuthError($this->getMessage());
    }
}
