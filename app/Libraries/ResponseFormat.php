<?php

namespace App\Libraries;

class ResponseFormat
{
    public static function formatResponse(int $status, string $message, $data)
    {
        return json_encode([
            'cabecalho' => [
                'status' => $status,
                'mensagem' => $message
            ],
            'retorno' => $data
        ]);
    }
}