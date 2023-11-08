<?php

    namespace App\Services;

    use App\Helpers\Env;
    use App\View\View;

    class RecaptchaService {

        public static function validate(string $recaptchaResponse)
        {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL            => "https://www.google.com/recaptcha/api/siteverify",
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS     => [
                    "secret"   => Env::get('reCAPTCHA_secret_key'),
                    "response" => $recaptchaResponse
                ]
            ]);

            $response = json_decode(curl_exec($curl));
            curl_close($curl);

            if (!$response->success) {
                return View::renderError("reCAPTCHA não é válido!");
            }

            return $response;
        }

    }