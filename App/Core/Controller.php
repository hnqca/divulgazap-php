<?php

    namespace App\Core;

    abstract class Controller {

        protected $endpointAccess;
        protected $dataRequest;

        protected function checkDataRequest(array $requiredFields = null)
        {
            $data = json_decode(file_get_contents('php://input'))->data ?? false;

            if (!$data) {
                throw new \InvalidArgumentException("informe todos os dados necessários.");
            }

            if ($requiredFields) {
                foreach ($requiredFields as $key) {
                    if (!isset($data->$key)) {
                        throw new \InvalidArgumentException(" \"{$key}\" não foi enviado na requisição.");
                    }
                }
            }

            return $this->dataRequest = $data;
        }

        public function redirect(string $endpoint)
        {
            global $router;
            die ($router->redirect($endpoint));
        }

    }
