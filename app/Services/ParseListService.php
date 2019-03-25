<?php


namespace App\Services;

use App\Helpers\ParseListHelper;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ParseListService
 * @package App\Services
 */
class ParseListService extends BaseListService implements IParseListService
{

    /**
     * @param $html
     */
    public function setHtml($html)
    {
        $this->html = $html;

        /** @var Crawler crawler */
        $this->crawler = new Crawler($html);
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
        $divS = $this->crawler->filter(".item_table");
        $listHelper = new ParseListHelper();

        foreach ($divS as $div) {
            $spanS = $div->getElementsByTagName('span');
            $divElements = $div->getElementsByTagName('div');

            foreach ($divElements as $key => $value) {
                if ($value->getAttribute('class') === 'js-item-date c-2') {
                    preg_match('/([а-яА-Я]+)\s*(\d+:\d+)/', $value->getAttribute('data-absolute-date'), $matches);
                    if($matches && $matches[0]) {
                        $listHelper->create_date = trim(str_replace(chr(194),"  ",$matches[0]));
                    }
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

            $this->listData[$listHelper->adv_id] = $listHelper->toArray();

            if(!in_array($page, $this->parsePages)) {
                $this->parsePages[] = $page;
            }
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function parsePageCount()
    {
        $href = $this->crawler->filter('a.pagination-page')->last()->getNode(0)->getAttribute('href');
        preg_match('/p=(\d+)/', $href, $pageCount);

        if (!isset($pageCount[1])) {
            throw new \Exception('Pages count not found on page');
        }

        $this->setPagesCount((int)trim($pageCount[1]));
    }
}