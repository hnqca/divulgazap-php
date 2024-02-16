<?php

    namespace App\Helpers;

    use App\Http\Response;

    abstract class Sanitize {

        public static function cleanParam($param)
        {
            return is_string($param) ? strip_tags(htmlspecialchars($param)) : $param;
        }

        public static function integer($integer)
        {
            if (!filter_var($integer, FILTER_VALIDATE_INT) OR $integer <= 0) {
                die ((new Response)->sendResponse(400, [
                    'status'  => "error",
                    'message' => "Informe um número inteiro válido."
                ]));
            }

            return $integer;
        }

        public static function url(string $url) {

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException("informe uma URL válida!");
            }

            return $url;
        }

        public static function kebabToPascalCase($input) 
        {
            // Converte a string para lowercase e explode usando o caractere "-"
            $words = explode('-', strtolower($input));
        
            // Capitaliza cada palavra
            $words = array_map('ucfirst', $words);
        
            // Junta as palavras de volta em uma única string
            $result = implode('', $words);
        
            return $result;
        }

    }