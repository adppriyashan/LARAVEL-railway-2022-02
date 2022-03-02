<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class APIStructure extends Model
{
    use HasFactory;

    protected static $responseCodes = [
        422 => 'Unauthorized Request',
        200 => 'Successfully'
    ];

    public static function getResponse($data = [], $errors = [], $statusCode = 200, $responseMessage = null)
    {
        $response = [
            'data' => $data,
            'errors' => $errors,
            'message' => ($responseMessage != null) ? $responseMessage : ((key_exists($statusCode, self::$responseCodes)) ? self::$responseCodes[$statusCode] : 'non'),
            'status' => $statusCode,
        ];

        return response($response, $statusCode);
    }
}
