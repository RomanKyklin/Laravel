<?php

namespace App\Services\Client;


interface ClientDriverInterface
{
    public function load(string $url): string;
}