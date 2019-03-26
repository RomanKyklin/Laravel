<?php

namespace Tests\Feature;


use App\Services\Client\GuzzleClient;
use App\Services\Client\SimpleClient;
use App\Services\ParseListService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Data\AvitoHelper;
use Tests\TestCase;

class CrawlerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group avito
     * @test
     */
    public function avito_test_pages()
    {
        $parseListService = new ParseListService();
        $url = $parseListService->getUrl(1);
        $html = (new SimpleClient())->load($url);
        $parseListService->setHtml($html);
        $parseListService->setPagesCount(2);
        $parseListService->collectData();
        $pages = $parseListService->getParsePages();

        foreach (AvitoHelper::$pages as $page) {
            $this->assertContains($page, $pages);
        }
    }

    /**
     * @group avito
     * @test
     */
    public function avito_test_items()
    {
        $parseListService = new ParseListService();
        $url = $parseListService->getUrl(1);
        $html = (new SimpleClient())->load($url);
        $parseListService->setHtml($html);
        $parseListService->setPagesCount(2);
        $parseListService->collectData();
        $parseData = $parseListService->getParseList();

        foreach (AvitoHelper::$listData as $key => $value) {
            $this->assertTrue(array_key_exists($key, $parseData));
            $this->assertEquals($value['adv_id'], $parseData[$key]['adv_id']);
            $this->assertEquals($value['title'], $parseData[$key]['title']);
            $this->assertEquals($value['href'], $parseData[$key]['href']);
            $this->assertEquals($value['price'], $parseData[$key]['price']);
            $this->assertEquals($value['description'], $parseData[$key]['description']);
            //TODO: проблема с залетающими символами в другой кодировке
//            $this->assertEquals($value['create_date'], $parseData[$key]['create_date']);
        }
    }

    /**
     * @group simple-client
     * @group client
     * @test
     */
    public function simple_client_test()
    {
        $client = new SimpleClient();
        $clientHtml = $client->load('https://www.avito.ru/perm');
        $this->assertTrue(strpos($clientHtml, 'html') !== false);
    }

    /**
     * @group guzzle-client
     * @group client
     * @test
     */
    public function guzzle_client_test()
    {
        $client = new GuzzleClient();
        $clientHtml = $client->load('https://www.avito.ru/perm');
        $this->assertTrue(strpos($clientHtml, 'html') !== false);
    }

    /**
     * @group phantome-client
     * @group client
     * @test
     */
    public function phantome_client_test()
    {
        $client = new GuzzleClient();
        $clientHtml = $client->load('https://www.avito.ru/perm');
        $this->assertTrue(strpos($clientHtml, 'html') !== false);
    }
}