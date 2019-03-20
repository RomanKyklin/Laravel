<?php

namespace App\Services;


interface IParseListService
{
    public function collectDataAndSave();

    public function setHtml($url);

    public function getUrl(int $page = null);

    public function parse(int $page);

    public function parsePageCount(string $startPageUrl);

    public function getPageCount();

    public function getParsePages();

    public function setPagesCount(int $pagesCount);

    public function collectData();

    public function getParseList();

    public function save();

}