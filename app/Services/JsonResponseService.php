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
    static function handler(string $status, string $message, string $code)
    {
        $response = [
            'status_code' => $code,
            'status' => $status,
            'message' => $message,
        ];
       
        echo json_encode($response);
    }
}