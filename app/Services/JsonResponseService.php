<?php

namespace App\Services;

use Exception;

class JsonResponseService
{
    /**
     * @param string $source source file eg home/path/template.docx
     * @param array $options array of placeholders to replace, key value pair eg
     * ['username' => 'sammy']
     * @param string $destination destination file path eg home/path/template.pdf, 
     */
    static function handler(array $data)
    {
        echo json_encode($data);
    }

    static function build($data){
        // Normal Customers
        if ($data['status'] == "success") {
            $response = array(
                'title' => 'COMPLETED',
                'icon' => 'success',
                'status' => 'success',
                'message' => $data['message'],
                'extra' => $data['extra']
            );
        }else{
            $response = array(
                'title' => 'FAILED',
                'icon' => 'error',
                'status' => 'fail',
                'message' => '<div class="alert alert-danger">' . $data['message'] . '</div>',
            );
        }

        return response()->json($response);
    }


}