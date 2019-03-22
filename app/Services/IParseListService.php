<?php

namespace App\Services;


interface IParseListService
{
    public function setHtml($url);

    public function getUrl(int $page = null);

    public function parsePageCount(string $startPageUrl);

    public function setPagesCount(int $pagesCount);

    public function collectData();

    public function parse(int $page);

    public function save();

}