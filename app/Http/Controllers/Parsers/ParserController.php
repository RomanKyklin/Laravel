<?php

namespace App\Http\Controllers\Parsers;

use App\Entities\ParseList;
use App\Repositories\ParseListRepository;
use App\Services\Client\SimpleClient;
use App\Services\ParseListService;
use App\Http\Controllers\Controller;


/**
 * Class ParserController
 * @package App\Http\Controllers\Parsers
 */
class ParserController extends Controller
{
    /** @var ParseListRepository */
    private $parseListRepository;

    public function __construct(ParseListRepository $parseListRepository)
    {
        $this->parseListRepository = $parseListRepository;
    }

    /**
     * Parsing avito list
     * @param ParseListService $parseListService
     * @throws \Exception
     */
    public function avitoList(ParseListService $parseListService)
    {
        $url = $parseListService->getUrl(1);
        $html = (new SimpleClient())->load($url);
        $parseListService->setHtml($html);
        $data = $parseListService->getData();

        foreach ($data as $value) {
            ParseList::firstOrCreate($value);
        }
    }

//    /**
//     * @param GuzzleParseListService $parseListService
//     */
//    public function avitoListGuzzle(GuzzleParseListService $parseListService)
//    {
//        $parseListService->collectDataAndSave();
//    }
//
//    /**
//     * @param PhantomParseListService $parseListService
//     */
//    public function avitoListPhantomejs(PhantomParseListService $parseListService)
//    {
//        $parseListService->collectDataAndSave();
//    }
}
