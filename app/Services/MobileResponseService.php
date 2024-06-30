<?php

namespace App\Services;

use Exception;

class MobileResponseService
{
    /**
     * @param string $source source file eg home/path/template.docx
     * @param array $options array of placeholders to replace, key value pair eg
     * ['username' => 'sammy']
     * @param string $destination destination file path eg home/path/template.pdf, 
     */

    static function json(
        $data = null,
        $message = null,
        $error = null,
        $status = 200,
        $headers = [],
        $options = 0
    ) {
        return response()->json([
            'data' => $data,
            'message' => $message ? $message : ($error ? 'There was an error with the request' : 'Request successful'),
            'error' => $error
        ], $status, $headers, $options);
    }

    /**
     * standardize service response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  array  $errors
     * @param  string  $status
     * @return array
     */
    public function formatServiceResponse(string $status, string $message, array $errors, mixed $data)
    {
        return [
            'status' => $status,
            'message' => $message,
            'errors' => $errors,
            'data' => $data
        ];
    }
    // static function handler(array $data)
    // {
    //     echo json_encode($data);
    // }

    // static function build($data){
    //     // Normal Customers
    //     if ($data['status'] == "success") {
    //         $response = array(
    //             'title' => 'COMPLETED',
    //             'icon' => 'success',
    //             'status' => 'success',
    //             'message' => $data['message'],
    //             'extra' => $data['extra']
    //         );
    //     }else{
    //         $response = array(
    //             'title' => 'FAILED',
    //             'icon' => 'error',
    //             'status' => 'fail',
    //             'message' => '<div class="alert alert-danger">' . $data['message'] . '</div>',
    //         );
    //     }

    //     return response()->json($response);
    // }
}