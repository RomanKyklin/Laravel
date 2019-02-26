<?php

namespace App\Http\Controllers\Parsers;

use App\Services\ParseListService;
use App\Http\Controllers\Controller;
use Symfony\Component\DomCrawler\Crawler;

class ParserController extends Controller
{
    /**
     * avito item parser
     * @return \Illuminate\Http\JsonResponse
     */
    public function avitoItem()
    {
        $html = file_get_contents("https://www.avito.ru/ekaterinburg/noutbuki/macbook_pro_13_2015_i7_3.1ghz_16gb_256ssd_art078_973405235");

        $crawler = new Crawler($html);
        $title = trim($crawler->filter('.title-info-title')->getNode(0)->nodeValue);
        $price = $crawler->filter('.js-item-price')->getNode(0)->nodeValue;
        $description = trim($crawler->filter('.item-description-text')->getNode(0)->nodeValue);
        $views = $crawler->filter('.js-show-stat')->getNode(0)->nodeValue;
        $titleMetaData = trim($crawler->filter('.title-info-metadata-item')->getNode(0)->nodeValue);

        return response()->json([
            'title' => $title,
            'price' => $price,
            'description' => $description,
            'views' => $views,
            'titleMetaData' => $titleMetaData
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function avitoList()
    {
        $parseService = new ParseListService('https://www.avito.ru/perm/nastolnye_kompyutery');
        $parseService->collectData();
        $parseService->save();
    }
}
