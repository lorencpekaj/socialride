<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const RESPONSE_SUCCESS = 200;
    const RESPONSE_UNAUTHORIZED = 401;
    const RESPONSE_BAD_REQUEST = 400;
    const RESPONSE_NOT_FOUND = 404;

    /**
     * Returns a 200 API JSON Response using data provided
     *
     * @param array $data
     * @return JsonResponse
     */
    protected function success(array $data): JsonResponse
    {
        return $this->responseTemplate(self::RESPONSE_SUCCESS, $data);
    }

    /**
     * Returns a 401 API JSON Response using data provided
     *
     * @param array $data
     * @return JsonResponse
     */
    protected function unauthorized(
        string $message,
        int $responseCode = self::RESPONSE_UNAUTHORIZED
    ): JsonResponse {
        return $this->responseTemplate($responseCode, [], $message, false);
    }

    /**
     * Returns a Custom API JSON Response using data provided
     *
     * @param array $data
     * @return JsonResponse
     */
    protected function error(
        string $message,
        int $responseCode = self::RESPONSE_BAD_REQUEST
    ): JsonResponse {
        return $this->responseTemplate($responseCode, [], $message, false);
    }

    /**
     * Returns a Custom API JSON Response using data provided
     *
     * @param array $data
     * @return JsonResponse
     */
    protected function notFound(
        string $message,
        int $responseCode = self::RESPONSE_NOT_FOUND
    ): JsonResponse {
        return $this->responseTemplate($responseCode, [], $message, false);
    }

    /**
     * Returns a 500 API JSON Response using data provided
     *
     * @param array $data
     * @return JsonResponse
     */
    protected function critical(
        string $message,
        int $responseCode = 500
    ): JsonResponse {
        return $this->responseTemplate($responseCode, [], $message, false);
    }

    /**
     * Returns a API JSON Response template
     *
     * @param integer $responseCode
     * @param array $data
     * @param string $message
     * @param boolean $success
     * @return JsonResponse
     */
    private function responseTemplate(
        int $responseCode,
        array $data,
        string $message = '',
        bool $success = true
    ): JsonResponse {
        return response()->json([
            'data'    => $data,
            'errors'  => $message,
            'success' => $success
        ], $responseCode);
    }
}
