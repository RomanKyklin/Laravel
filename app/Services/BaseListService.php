<?php


namespace App\Services;


use Symfony\Component\DomCrawler\Crawler;

abstract class BaseListService
{
    /** @var Crawler */
    protected $crawler;
    protected $listData = [];
    protected $parsePages = [];
    protected $html;
    protected $pageCount = 0;

    /**
     * @return int
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    public function getParsePages()
    {
        return $this->parsePages;
    }

    public function setPagesCount(int $pagesCount)
    {
        $this->pageCount = $pagesCount;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function collectData()
    {
        for ($i = 1; $i <= $this->getPageCount(); $i++) {
            $this->parse($i);
        }
    }

    /**
     * @return array
     */
    public function getParseList()
    {
        return $this->listData;
    }

    abstract public function parsePageCount();
}