<?php


namespace App\Services\Client;


use Josh\Component\PhantomJs\Facade\PhantomJs;

class PhantomeClient implements ClientDriverInterface
{

    public function load(string $url): string
    {
        $request = PhantomJs::get($url);

        $response = PhantomJs::send($request);

        if($response->getStatus() !== 200) {
            throw new Exception('Bad response');
        }

        $content = $response->getContent();

        if (strpos($content, 'html') === false) {
            throw new \Exception('html not found!');
        }

        return $content;
    }
}