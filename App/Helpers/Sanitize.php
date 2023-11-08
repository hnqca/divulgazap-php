<?php

    namespace App\Helpers;

    abstract class Sanitize {

        public static function doCleanParam($param)
        {
            return is_string($param) ? strip_tags(htmlspecialchars($param)) : $param;
        }

        public static function url(string $url) {

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException("informe uma URL válida!");
            }

            return $url;
        }

    }