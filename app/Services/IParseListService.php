<?php

namespace App\Services;


interface IParseListService
{
    public function setHtml($html);

    public function getUrl(int $page = null);

    public function parsePageCount();

    public function setPagesCount(int $pagesCount);

    public function collectData();

    public function parse(int $page);

}