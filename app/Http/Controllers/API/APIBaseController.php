<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }

    public function sendError($errorMessages = '', $code = 406)
    {
        $response = [
            'success' => false,
            'message' => $errorMessages,
        ];

        return response()->json($response, $code);
    }

    /**
     * 说明: 手抛出validate验证错误信息
     *
     * @param $message
     * @param array $errors
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     * @author 郭庆
     */
    public function sendValidateErrors($message, $errors = [], $code = 422)
    {
        $response = [
            'errors' => $errors,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }
}
