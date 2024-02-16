<?php
    
namespace App\Helpers;

class Language
{
    const DIR_LANGUAGES = __DIR__ . '/../../resources/languages/';

    public function get()
    {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return Env::get('APP_LANGUAGE_DEFAULT');
        }

        // Obtém a string do cabeçalho "Accept-Language"
        $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

        // Divide a string em partes usando ',' como delimitador
        $languages = explode(',', $language);

        // Pega apenas a primeira parte, que geralmente é o idioma principal
        $user_language = $languages[0];

        // Retorna o idioma do usuário
        return $user_language;
    }

    private static function dotToArrow(string $key)
    {
        // Substituir o ponto por "->"
        return str_replace(".", "->", $key);
    }
    
    private static function getValueFromKey($json, $key)
    {
        // Dividir a chave em partes usando "->" como delimitador
        $parts = explode("->", $key);
    
        // Iterar sobre as partes para acessar a estrutura do JSON
        foreach ($parts as $part) {
            // Verificar se a parte existe no JSON
            if (is_object($json) && property_exists($json, $part)) {
                $json = $json->$part;
            } else {
                // Lida com o caso em que a parte não existe
                return null;
            }
        }
    
        // Retorna o valor final
        return $json;
    }
    
    public function getTranslation(string $key)
    {
        $userLanguage = self::get();
    
        $filePath = self::DIR_LANGUAGES . "{$userLanguage}.json";
    
        // Verifica se o arquivo existe
        if (!file_exists($filePath)) {
            return 'Erro: Arquivo de idioma não encontrado.';
        }
    
        // Lê o conteúdo do arquivo
        $fileContent = file_get_contents($filePath);
    
        // Verifica se a leitura foi bem-sucedida
        if ($fileContent === false) {
            return 'Erro: Falha ao ler o arquivo de idioma.';
        }
    
        // Decodifica o JSON
        $json = json_decode($fileContent);
    
        // Verifica se a decodificação foi bem-sucedida
        if ($json === null) {
            return 'Erro: Falha ao decodificar o JSON.';
        }
        
        // Obtém o valor da chave
        $value = self::getValueFromKey($json, self::dotToArrow($key));
    
        // Verifica se o valor foi encontrado
        if ($value !== null) {
            return $value;
        } else {
            return 'Erro: Chave de tradução não encontrada.';
        }
    }
    
}