<?php


namespace App\Services;


use App\Entities\ParseList;
use App\Helpers\ParseListHelper;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ParseListService
 * @package App\Services
 */
class ParseListService implements IParseListService
{
    /** @var Crawler */
    private $crawler;
    private $listData = [];
    private $parsePages = [];
    private $html;
    private $pageCount = 0;


    public function collectDataAndSave()
    {
        $this->parsePageCount($this->getUrl(1));
        $this->setPagesCount(3);
        $this->collectData();
        $this->save();
    }

    /**
     * @param $url
     * @throws \Exception
     */
    public function setHtml($url)
    {
        $this->html = file_get_contents($url);

        if (strpos($this->html, 'html') === false) {
            throw new \Exception('html not found!');
        }

        /** @var Crawler crawler */
        $this->crawler = new Crawler($this->html);
    }

    /**
     * @param int|null $page
     * @return string
     */
    public function getUrl(int $page = null)
    {
        return "https://www.avito.ru/perm/nastolnye_kompyutery?p={$page}";
    }

    public function parse(int $page)
    {
        $this->setHtml($this->getUrl($page));

        $divS = $this->crawler->filter(".item_table");
        $listHelper = new ParseListHelper();

        foreach ($divS as $div) {
            $spanS = $div->getElementsByTagName('span');
            $divElements = $div->getElementsByTagName('div');

            foreach ($divElements as $key => $value) {
                if ($value->getAttribute('class') === 'js-item-date c-2') {
                    $listHelper->create_date = trim($value->getAttribute('data-absolute-date'));
                }
            }

            foreach ($spanS as $span) {
                if ($span->getAttribute('itemprop') === 'name') {
                    $listHelper->href = $span->parentNode->getAttribute('href') ?? null;
                    $listHelper->title = $span->parentNode->getAttribute('title') ?? null;
                    $listHelper->adv_id = last(explode('_', $listHelper->href)) ?? null;
                }
                if ($span->getAttribute('class') === 'price ' || $span->getAttribute('itemprop') === 'price') {
                    $price = preg_replace('/[^0-9]/', '', $span->nodeValue);
                    $listHelper->price = (int)$price ?? null;
                }
            }

            $this->listData[] = $listHelper->toArray();
            $this->parsePages[] = $page;
        }
    }

    /**
     * @param string $startPageUrl
     * @return string
     * @throws \Exception
     */
    public function parsePageCount(string $startPageUrl)
    {
        $this->setHtml($startPageUrl);
        $href = $this->crawler->filter('a.pagination-page')->last()->getNode(0)->getAttribute('href');
        preg_match('/p=(\d+)/', $href, $pageCount);

        if (!isset($pageCount[1])) {
            throw new \Exception('Pages count not found on page');
        }

        $this->setPagesCount((int)trim($pageCount[1]));
    }

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

    /**
     * @throws \Exception
     */
    public function save()
    {
        foreach ($this->getParseList() as $value) {
            ParseList::firstOrCreate($value);
        }
    }
}