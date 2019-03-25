<?php

namespace Tests\Feature;


use App\Entities\ParseList;
use App\Services\Client\SimpleClient;
use App\Services\GuzzleParseListService;
use App\Services\ParseListService;
use App\Services\PhantomParseListService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Normalizer;
use Tests\Data\AvitoHelper;
use Tests\TestCase;

class CrawlerTest extends TestCase
{
    use RefreshDatabase;

//    /**
//     * @group avito
//     * @test
//     */
//    public function avito_test_phantomejs_database_has()
//    {
//        $parser = new PhantomParseListService();
//        $parser->setPagesCount(2);
//        $parser->collectData();
//        $parser->save();
//
//        foreach ($parser->getParseList() as $value) {
//            $this->assertDatabaseHas('parse_list', $value);
//        }
//    }
//
//    /**
//     * @group avito
//     * @test
//     */
//    public function avito_test_phantomejs_database_missing()
//    {
//        $parser = new PhantomParseListService();
//        $parser->setPagesCount(2);
//        $parser->save();
//        $parser->parse(4);
//
//        foreach ($parser->getParseList() as $value) {
//            $this->assertDatabaseMissing('parse_list', $value);
//        }
//    }
//
//    /**
//     * @group avito
//     * @test
//     */
//    public function avito_test_guzzle_database_has()
//    {
//        $parser = new GuzzleParseListService();
//        $parser->setPagesCount(2);
//        $parser->collectData();
//        $parser->save();
//
//        foreach ($parser->getParseList() as $value) {
//            $this->assertDatabaseHas('parse_list', $value);
//        }
//    }
//
//    /**
//     * @group avito
//     * @test
//     */
//    public function avito_test_guzzle_database_missing()
//    {
//        $parser = new GuzzleParseListService();
//        $parser->setPagesCount(2);
//        $parser->save();
//        $parser->parse(4);
//
//        foreach ($parser->getParseList() as $value) {
//            $this->assertDatabaseMissing('parse_list', $value);
//        }
//    }

    /**
     * @group avito
     * @test
     */
    public function avito_test_file_get_contents_database_has()
    {
        $parseListService = new ParseListService();
        $url = $parseListService->getUrl(1);
        $html = (new SimpleClient())->load($url);
        $parseListService->setHtml($html);
        $parseListService->setPagesCount(2);
        $parseListService->collectData();
        $pages = $parseListService->getParsePages();
        $parseData = $parseListService->getParseList();

        foreach (AvitoHelper::$pages as $page) {
            $this->assertContains($page, $pages);
        }

        foreach (AvitoHelper::$listData as $key => $value) {
            $this->assertTrue(array_key_exists($key,  $parseData));
            $this->assertEquals($value['adv_id'], $parseData[$key]['adv_id']);
            $this->assertEquals($value['title'], $parseData[$key]['title']);
            $this->assertEquals($value['href'], $parseData[$key]['href']);
            $this->assertEquals($value['price'], $parseData[$key]['price']);
            $this->assertEquals($value['description'], $parseData[$key]['description']);
            //TODO: проблема с залетающими символами в другой кодировке
//            $this->assertEquals($value['create_date'], $parseData[$key]['create_date']);
        }
    }

//    /**
//     * @group avito
//     * @test
//     */
//    public function avito_test_file_get_contents_database_missing()
//    {
//        $parser = new ParseListService();
//        $parser->setPagesCount(2);
//        $parser->save();
//        $parser->parse(4);
//
//        foreach ($parser->getParseList() as $value) {
//            $this->assertDatabaseMissing('parse_list', $value);
//        }
//    }

}