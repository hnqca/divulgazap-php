<?php

    namespace App\Services;

    use App\Helpers\Sanitize;

    class WebScrapingService {
    
        private $streamContext;
        private $url;
        private $html;

        public function __construct(string $url)
        {
            // Configuração das opções de contexto para a solicitação HTTP
            $this->streamContext = stream_context_create([
                'http' => [
                    // Configura um agente que se parece com o Google Chrome
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'
                ]
            ]);

            $this->url = Sanitize::url($url);
            $this->captureHTML();
        }

        private function captureHTML()
        {
            $this->html = file_get_contents($this->url, false, $this->streamContext);
        }

        public function getMatches(string $pattern)
        {
            preg_match_all($pattern, $this->html, $matches);
            return $matches;
        }


    }