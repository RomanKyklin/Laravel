<?php

namespace App\Services\Client;


class SimpleClient implements ClientDriverInterface
{

    public function load(string $url): string
    {
        $content = file_get_contents($url);

        if (strpos($content, 'html') === false) {
            throw new \Exception('html not found!');
        }
        return $content;
    }
}