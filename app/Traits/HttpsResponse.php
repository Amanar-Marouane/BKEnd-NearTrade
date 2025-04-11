<?php

namespace App\Traits;

trait HttpsResponse
{
    protected function success($message = null, $data = null, $cookies = [], $code = 200)
    {
        $response = response()->json(
            [
                'status' => 'Request has been sent successfully',
                'message' => $message,
                'data' => $data,
            ],
            $code
        );
        foreach ($cookies as $key => $value) {
            $response->withCookie(cookie($key, $value, 1440, null, null, true, true, false, 'None'));
        }
        return $response;
    }

    protected function error($message = null, $data = null, $cookies = [], $code)
    {
        $response = response()->json(
            [
                'status' => 'Error has occurred',
                'message' => $message,
                'data' => $data,
            ],
            $code
        );
        foreach ($cookies as $key => $value) {
            $response->withCookie(cookie($key, $value, 1440, null, null, true, true, false, 'None'));
        }
        return $response;
    }
}
