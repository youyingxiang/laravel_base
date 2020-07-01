<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait JsonResponseTrait {
    public function restfulResult(int $code, string $message, array $data = null): \Illuminate\Http\JsonResponse {
        if ($data === null) {
            $data = new \ArrayObject();
        }
        if ($code == FoundationResponse::HTTP_OK) {
            $result = $data;
        } else {
            $result = [
                'code'    => $code,
                'message' => $message,
            ];
            $this->errorLog($code, $message);
        }

        return response()->json($result)->setStatusCode($code);
    }

    public function success(array $data = [], string $message = ''): \Illuminate\Http\JsonResponse {
        return $this->restfulResult(FoundationResponse::HTTP_OK, $message ?: "请求成功！", $data);
    }

    public function unAuthError(string $message = ''): \Illuminate\Http\JsonResponse {
        return $this->restfulResult(FoundationResponse::HTTP_UNAUTHORIZED, $message ?: "没有权限！");
    }

    public function paramsError(string $message = ''): \Illuminate\Http\JsonResponse {
        return $this->restfulResult(FoundationResponse::HTTP_BAD_REQUEST, $message ?: "输入参数错误！");
    }

    public function serverError(string $message = ''): \Illuminate\Http\JsonResponse {
        return $this->restfulResult(FoundationResponse::HTTP_INTERNAL_SERVER_ERROR, $message ?: "服务器内部错误！");
    }

    public function notFoundError(string $message = ''): \Illuminate\Http\JsonResponse {
        return $this->restfulResult(FoundationResponse::HTTP_NOT_FOUND, $message ?: "未找到查询结果！");
    }

    /**
     * @see 错误日志 非200 正常请求 日志记录
     */
    public function errorLog(int $iCode, string $sMessage){
        \Log::error('接口异常', ['code' => $iCode, 'message' => $sMessage, 'param' => request()->input()]);
    }



    public function abort($msg) {
        throw new HttpResponseException($this->serverError($msg));
    }
}
