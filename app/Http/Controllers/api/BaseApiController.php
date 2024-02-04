<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{
    public function successResponse($response, $code = 200)
    {
        return response()->json($response, $code);
    }

    public function errorResponse($error, $code = 404)
    {
    	$response = [
            'success' => false,
            'results' => $error,
        ];

        return response()->json($response, $code);
    }

    public function NewResponse($success , $results, $error ,$code = 200)
    {
        return response()->json([
             'success' => $success,
             'results' => $results ?? "",
             'error' => $error ?? "",
        ], $code);
    }

    public function NewErrorResponse($error ,$code = 422)
    {
        return response()->json([
            'success' => false,
            'results' => "",
            'error' => $error ,
        ], $code);
    }
}
