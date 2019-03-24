<?php

namespace App\Services\Client;


class Client
{
    protected $driver;

    public function __construct(ClientDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function get($url)
    {
        $content = $this->driver->load($url);

        return $content;
    }
}