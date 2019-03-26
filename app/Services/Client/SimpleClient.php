<?php

namespace App\Services\Client;


use App\Exceptions\InvalidHtmlResponse;

class SimpleClient implements ClientDriverInterface
{

    /**
     * @param string $url
     * @return string
     * @throws \Exception
     */
    public function load(string $url): string
    {
        $content = file_get_contents($url);

        if (strpos($content, 'html') === false) {
            throw new InvalidHtmlResponse();
        }
        return $content;
    }
}