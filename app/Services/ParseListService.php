<?php


namespace App\Services;

use App\Helpers\ParseListHelper;
use App\Services\Client\Client;
use App\Services\Client\SimpleClient;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ParseListService
 * @package App\Services
 */
class ParseListService extends BaseListService implements IParseListService
{

    /**
     * @param $url
     * @throws \Exception
     */
    public function setHtml($url)
    {
        $this->html = (new Client(new SimpleClient()))->get($url);

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

}