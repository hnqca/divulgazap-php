<?php

namespace App\Services;

use App\Helpers\Env;

class NotSafeForWorkService {

    const MINIMUM_SAFE_CONFIDENCE = 65;
    const IMAGGA_API_URL     = "https://api.imagga.com/v2/categories/nsfw_beta";
    const IMAGE_CONTENT_TYPE = 'image/jpeg';

    private $response;
    private $safeConfidence;

    public function checkImage(string $image)
    {
        $this->executeCurl($image);

        $reply = [
            'status'         => $this->response->status->type,
            'safeConfidence' => $this->getSafeConfidence(),
            'secureToSave'   => $this->isSecureToSave()
        ];

        return json_decode(json_encode($reply));
    }

    private function executeCurl(string $image)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => self::IMAGGA_API_URL,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_USERPWD        => Env::get('IMAGGA_API_KEY') . ':' . Env::get('IMAGGA_API_SECRET'),
            CURLOPT_HEADER         => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => [
                "image" => new \CurlFile($image, 'image/jpeg', 'image.jpg')
            ]
        ]);

        curl_close($curl);

        $this->response = json_decode(curl_exec($curl));
    }

    public function getSafeConfidence()
    {
        $categories = $this->response->result->categories;

        foreach ($categories as $category) {
            if ($category->name->en === "safe") {
                $this->safeConfidence = $category->confidence;
                break;
            }
        }

        return $this->safeConfidence;
    }

    public function isSecureToSave()
    {
        return ($this->safeConfidence >= self::MINIMUM_SAFE_CONFIDENCE);
    }
}
