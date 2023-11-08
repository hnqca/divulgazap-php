<?php


    namespace App\View;

    use App\Helpers\Env;

    class View {
        
        const DIR_PAGES   = __DIR__ . '/../../public/views/pages';
        const DIR_LAYOUTS = __DIR__ . '/../../public/views/layouts';

        private $loader;
        private $twig;
        private $theme;

        private function setGlobalVars()
        {
            $this->twig->addGlobal('APP_URL',  Env::get('APP_URL'));
            $this->twig->addGlobal('APP_NAME', Env::get('APP_NAME'));
            $this->twig->addGlobal('APP_LOGO', Env::get('APP_LOGO'));
            $this->twig->addGlobal('reCAPTCHA_public_key', Env::get('reCAPTCHA_public_key'));
        }

        public function renderPage(string $filePage, array $variables = [])
        {
            $this->loader = new \Twig\Loader\FilesystemLoader([self::DIR_PAGES, self::DIR_LAYOUTS]);
            $this->twig   = new \Twig\Environment($this->loader);

            $this->setGlobalVars();

            $this->theme = $this->twig->load( $filePage );

            echo $this->theme->render( $variables );
        }


        public static function renderJson($data, string $title = "data")
        {
            $data = json_decode(json_encode($data), true);
        
            header("Content-Type: application/json");
            die ( json_encode([$title => $data], JSON_PRETTY_PRINT) );
        }


        public static function renderError($message, $context = "unknow")
        {
            die (self::renderJson([
                "status"  => "error",
                "context" => $context,
                "message" => $message
            ]));
        }


    }