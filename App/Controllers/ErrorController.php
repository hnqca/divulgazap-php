<?php

    namespace App\Controllers;

    use App\View\View;

    class ErrorController {

        private static function getExceptions()
        {
            return [
                'PDOException'                   => "database",
                'InvalidArgumentException'       => "invalid_argument",
                'App\Exceptions\RouterException' => "router"
            ];
        }

        public static function getErrorMessage(\Exception $e)
        {
            $message   = $e->getMessage();
            $typeError = self::getExceptions()[get_class($e)] ?? "unknown";

            return View::renderJSON([
                'status'     => "error",
                'type_error' => $typeError,
                'message'    => $message
            ]);
        }
    }
