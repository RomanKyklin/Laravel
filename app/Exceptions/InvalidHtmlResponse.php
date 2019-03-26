<?php

namespace App\Exceptions;


class InvalidHtmlResponse extends \Exception
{
    protected $code = 1101;
    protected $message = 'Html not found!';
}