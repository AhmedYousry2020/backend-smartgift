<?php

namespace App\Http\Traits;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait HttpResponsesTrait
{
    protected function success($message, $data = [], $another_data = [], $status = Response::HTTP_OK)
    {
        $return = [
            'success' => true,
            'message' => __($message),
            'data' => $data,
        ];
        if(is_array($another_data) && !empty($another_data)){
            foreach ($another_data as $key => $value){
                $return[$key] = $value;
            }
        }
        return response($return, $status);
    }

    protected function failure($message, $status = ResponseAlias::HTTP_BAD_REQUEST)
    {
        return response([
            'success' => false,
            'message' => __($message),
        ], $status);
    }

    protected function errors($message, $data = [], $status = Response::HTTP_UNPROCESSABLE_ENTITY){
        return response([
            'success' => false,
            'message' => __($message),
            'errors' => $data,
        ], $status);
    }

}
