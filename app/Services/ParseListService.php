<?php


namespace App\Services;


use Illuminate\Support\Facades\DB;
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


    public function collectData()
    {
        $divS = $this->crawler->filter(".item_table");
        $advId = '';
        $title = '';
        $href = '';
        $price = '';

        foreach ($divS as $div) {
            $spanS = $div->getElementsByTagName('span');

            foreach ($spanS as $span) {
                if ($span->getAttribute('itemprop') === 'name') {
                    $href = $span->parentNode->getAttribute('href') ?? null;
                    $title = $span->parentNode->getAttribute('title') ?? null;
                    $advId = last(explode('_', $href)) ?? null;
                }
                if ($span->getAttribute('class') === 'price ' || $span->getAttribute('itemprop') === 'price') {
                    $price = (int)str_replace([" ", "₽", "\n"], "", $span->nodeValue) ?? null;
                }
            }

            $this->listData[] = [
                'href' => $href,
                'title' => $title,
                'adv_id' => $advId,
                'price' => $price
            ];
        }
    }

    /**
     * @return bool
     */
    public function save()
    {
        return DB::table('parse_list')->insert($this->listData);
    }
}