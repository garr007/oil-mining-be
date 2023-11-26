<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

// structured json responser
trait ApiResponser
{
    /**
     * Pakai ini untuk 2xx dan 4xx doang, jangan lupa Log
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    static protected function response($data, $message = null, $code = 200)
    {
        switch (intval($code / 100)) {
            case 4: //4xx
                throw new HttpResponseException(
                    response()->json([
                        'status' => 'fail',
                        'message' => $message,
                        'data' => $data
                    ], $code)
                );
            case 5: //5xx
                return self::errResponse(null, $message);
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Pakai ini untuk 5xx, tinggal pass request dan errmsg (errmsg is not shown to client)
     * 
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    static protected function errResponse(Request $request, string $errmsg, $code = 500) // 5xx
    {
        Log::emergency($errmsg . ". Request: " . json_encode($request));

        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => Response::$statusTexts[$code]
            ], $code)
        );
    }

}