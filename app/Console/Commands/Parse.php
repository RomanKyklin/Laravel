<?php

namespace App\Console\Commands;

use App\Entities\ParseList;
use App\Services\Client\SimpleClient;
use App\Services\ParseListService;
use Illuminate\Console\Command;

class Parse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $parseListService = new ParseListService();
        $url = $parseListService->getUrl(1);
        $html = (new SimpleClient())->load($url);
        $parseListService->setHtml($html);
        $data = $parseListService->getData();

        foreach ($data as $value) {
            ParseList::firstOrCreate($value);
        }
    }
}
