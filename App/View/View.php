<?php
    namespace App\View;

    use App\Helpers\Env;
    use App\Helpers\Language;

    class View {
        
        const DIR_RESOURCES  = __DIR__ . '/../../resources/views';
        const DIR_PAGES      = __DIR__ . '/../../resources/views/pages';
        const DIR_LAYOUTS    = __DIR__ . '/../../resources/views/layouts';
        const DIR_COMPONENTS = __DIR__ . '/../../resources/views/components';

        private $loader;
        private $twig;
        private $theme;

        private function setGlobalVars()
        {
            $this->twig->addGlobal('APP_URL',  Env::get('APP_URL'));
            $this->twig->addGlobal('APP_NAME', Env::get('APP_NAME'));
            $this->twig->addGlobal('APP_LOGO', Env::get('APP_LOGO'));
            $this->twig->addGlobal('language', new Language);
        }

        public function renderHTML($fileName, $variables = [])
        {
            $this->loader = new \Twig\Loader\FilesystemLoader([self::DIR_RESOURCES]);
            $this->twig   = new \Twig\Environment($this->loader);

            $this->setGlobalVars();

            $this->theme = $this->twig->load( "{$fileName}.twig" );

            echo $this->theme->render($variables);
        }
    }