<?php

namespace App\Traits;

trait JsonResponseTrait
{
    /**
     * Respond with success data.
     *
     * @param mixed $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $statusCode = 200)
    {
        return response()->json($data, $statusCode);
    }

    /**
     * Respond with an error message.
     *
     * @param string $errorMessage
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($errorMessage, $statusCode)
    {
        return response()->json(['err' => $errorMessage, 'status' => $statusCode], $statusCode);
    }


    public function NewResponse($success , $results, $error ,$code = 200)
    {
        return response()->json([
             'success' => $success,
             'results' => $results ?? "",
             'error' => $error ?? "",
        ], $code);
    }

    public function NewErrorResponseApi($error ,$code = 422)
    {
        return response()->json([
            'success' => false,
            'results' => "",
            'error' => $error ,
        ], $code);
    }


}
