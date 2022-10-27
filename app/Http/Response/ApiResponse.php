<?php

namespace App\Http\Response;
use \Illuminate\Validation\ValidationException;

class ApiResponse
{
    const ERROR_TYPE = 'error';
    const SUCCESS_TYPE = 'success';

    /*
     | By "a uniform JSON response" I mean frontend is going to get same response array structure for every request.
     | Then it is up to JS to determine if request succeeded or failed based on "response_type" value.
     | 
     | e.g. SUCCESS_TYPE is telling FE to show green display with 'check SVG' while
     | ERROR_TYPE red background with X svg
     */

    /**
     * Convert a successful response into a uniform JSON response with response type identifier.
     *
     * @param $messages
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    static function success($content)
    {
        return array_merge([
            'response_type' => self::SUCCESS_TYPE,
        ], $content);
    }

    /**
     * Convert a validation exception into a uniform JSON response with response type identifier.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    static function error($request, ValidationException $exception)
    {
        return response()->json([
            'messages' => $exception->errors(),
            'response_type' => self::ERROR_TYPE,
        ], $exception->status);
    }
}
