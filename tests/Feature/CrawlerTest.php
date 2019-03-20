<?php

namespace Tests\Feature;


use App\Services\ParseListService;
use Tests\TestCase;

class CrawlerTest extends TestCase
{
    /**
     * @group avito
     * @test
     */
    public function avito_test_database_has()
    {
        $parser = new ParseListService();
        $parser->setPagesCount(2);
        $parser->collectData();

        foreach ($parser->getParseList() as $value) {
            $this->assertDatabaseHas('parse_list', $value);
        }
    }

    /**
     * @group avito
     * @test
     */
    public function avito_test_database_missing()
    {
        $parser = new ParseListService();
        $parser->parse(4);

        foreach ($parser->getParseList() as $value) {
            $this->assertDatabaseMissing('parse_list', $value);
        }
    }
}