<?php

namespace App\Services\Client;

use GuzzleHttp\Client;

class GuzzleClient implements ClientDriverInterface
{
    public function load(string $url): string
    {
        $content = (new Client([
            'base_url' => $url,
        ]))->get($url)->getBody()->getContents();

        if (strpos($content, 'html') === false) {
            throw new \Exception('html not found!');
        }

        return $content;
    }
}