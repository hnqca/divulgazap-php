<?php

namespace App\Http;

use App\Helpers\Env;

class Response
{
    public function redirect(string $endpoint, string|null $domain = null)
    {
        http_response_code(302);
        $domain = $domain ?: Env::get('APP_URL');

        header("location: {$domain}{$endpoint}");
        die;
    }

    public function sendResponse(int $status, mixed $data)
    {
        http_response_code($status);

        if (!is_array($data)) {
            die($data);
        }

        return $this->json($status, $data);
    }

    public function json(int $status, array $data)
    {
        http_response_code($status);
        header("Content-Type: application/json");

        $data = json_encode(['data' => $data], JSON_PRETTY_PRINT);
        die($data);
    }
}
