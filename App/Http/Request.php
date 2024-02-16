<?php

namespace App\Http;

class Request
{
    private $params;
    private $queryParams = null;
    private $body;
    private $headers;
    private $data;

    public function __construct($parameters = null)
    {
        if ($parameters) {
            $this->setParams($parameters);
        }

        $this->setQueryParams();
        $this->setBody();
        $this->setHeaders();
    }

    public function getBody()
    {
        return $this->body->data;
    }

    public function getParams()
    {
        return $this->params[0];
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function getHeaders()
    {
        return json_decode($this->headers);
    }

    public function setBody()
    {
        $this->body = json_decode(file_get_contents('php://input'));
    }

    private function setParams(array|object $parameters)
    {
        $current   = json_decode($this->params, true);
        $current[] = $parameters;

        $this->params = json_decode(json_encode($current));
    }

    private function setQueryParams()
    {
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);

        if(!isset($parsedUrl['query'])) {
            return;
        }

        // Divide a string de consulta em pares chave/valor
        parse_str($parsedUrl['query'], $queryParams);

        $this->queryParams = json_decode(json_encode($queryParams));
    }

    private function setHeaders()
    {
        $this->headers = json_encode(getallheaders());
    }

    public function checkDataRequest(array $requiredFields)
    {
        $body = $this->getBody();

        if (!$body) {
            return (new Response)->sendResponse(400, ['message' => "nenhum dado enviado na requisição."]);
        }

        if ($requiredFields) {
            foreach ($requiredFields as $key) {
                if (!isset($body->$key)) {
                    return (new Response)->sendResponse(400, ['message' => "Envie todos os dados necessários", 'values' => $requiredFields,]);
                }
            }
        }
        
        $this->data = $body;

        return $this->data;
    }

    public function setFilters(array $filters)
    {
        foreach ($filters as $key => $value) {

            $status = isset($value['status']) ? $value['status'] : 400;

            if (is_callable($value['filter']) and !call_user_func($value['filter'], $this->data->$key)) {
                return (new Response)->json($status, ['message' => $value['invalid']]);
            }

            if (!is_callable($value['filter']) and !filter_var($this->data->$key, $value['filter'])) {
                return (new Response)->json($status, ['message' => $value['invalid']]);
            }
        }

        return $this->data;
    }
}
