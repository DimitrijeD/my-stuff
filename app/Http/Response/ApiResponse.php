<?php

namespace App\Http\Response;

class ApiResponse
{
    const ERROR_TYPE = 'error';
    const SUCCESS_TYPE = 'success';
    const INFO_TYPE = 'info';
    
    const TYPE = 'response_type';
    const MESSAGES = 'messages';
    const DATA = 'data';

    /**
     * Convert a successful response into a uniform JSON response with response type identifier.
     *
     * @param $content
     */
    static function success($content)
    {
        return array_merge([
            self::TYPE => self::SUCCESS_TYPE,
        ], $content);
    }

    /**
     * Convert a validation exception into a uniform JSON response with response type identifier.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception 
     * @return \Illuminate\Http\JsonResponse
     */
    static function error($request, $e)
    {
        return response()->json([
            self::MESSAGES => $e->errors(),
            self::TYPE => self::ERROR_TYPE,
        ], $e->status);
    }

    static function info($content)
    {
        return array_merge([
            self::TYPE => self::INFO_TYPE,
        ], $content);
    }
}
