<?php


namespace App\Services;


use App\Helpers\ParseListHelper;
use App\Repositories\ParseListRepository;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ParseListService
 * @package App\Services
 */
class ParseListService
{
    private $url;
    private $crawler;
    private $listData = [];
    private $html;

    public function __construct($url)
    {
        $this->url = $url;
        $this->html = file_get_contents($url);
        /** @var Crawler crawler */
        $this->crawler = new Crawler($this->html);
    }

    /**
     * @return void
     */
    private function collectData()
    {
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
                    $listHelper->price = (int)str_replace([" ", "₽", "\n"], "", $span->nodeValue) ?? null;
                }
            }

            $this->listData[] = $listHelper->toArray();
        }
    }

    /**
     * @return array
     */
    public function getParseList()
    {
        $this->collectData();

        return $this->listData;
    }
}