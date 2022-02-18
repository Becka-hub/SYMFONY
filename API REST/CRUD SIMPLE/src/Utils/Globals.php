<?php

namespace App\Utils;

use App\Utils\ErrorHttp;
use Symfony\Component\HttpFoundation\JsonResponse;

class Globals
{
    public function success($data=[], $message ='success',int $codeHttp = 200) : JsonResponse
    {
        return new JsonResponse([
           'status' => 1,
           'message' => $message,
           'data' => $data
        ],$codeHttp);
    }

    public function error(array $error= ErrorHttp::ERROR) : JsonResponse
    {
        return new JsonResponse([
           'status' => 0,
           'message' => $error['message'] ?? 'error',
        ],$error['code'] ?? 500);
    }

}