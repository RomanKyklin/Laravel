<?php

namespace Tests\Feature;


use App\Services\GuzzleParseListService;
use App\Services\ParseListService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrawlerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group avito
     * @test
     */
    public function avito_test_guzzle_database_has()
    {
        $parser = new GuzzleParseListService();
        $parser->setPagesCount(2);
        $parser->collectData();
        $parser->save();

        foreach ($parser->getParseList() as $value) {
            $this->assertDatabaseHas('parse_list', $value);
        }
    }

    /**
     * @group avito
     * @test
     */
    public function avito_test_guzzle_database_missing()
    {
        $parser = new GuzzleParseListService();
        $parser->setPagesCount(2);
        $parser->save();
        $parser->parse(4);

        foreach ($parser->getParseList() as $value) {
            $this->assertDatabaseMissing('parse_list', $value);
        }
    }

    /**
     * @group avito
     * @test
     */
    public function avito_test_file_get_contents_database_has()
    {
        $parser = new ParseListService();
        $parser->setPagesCount(2);
        $parser->collectData();
        $parser->save();

        foreach ($parser->getParseList() as $value) {
            $this->assertDatabaseHas('parse_list', $value);
        }
    }

    /**
     * @group avito
     * @test
     */
    public function avito_test_file_get_contents_database_missing()
    {
        $parser = new ParseListService();
        $parser->setPagesCount(2);
        $parser->save();
        $parser->parse(4);

        foreach ($parser->getParseList() as $value) {
            $this->assertDatabaseMissing('parse_list', $value);
        }
    }

}