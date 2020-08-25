<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Osiset\BasicShopifyAPI;

class GrapshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:graph';

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
     */
    public function handle()
    {
        $api = new BasicShopifyAPI();
        $api->setVersion('2020-01'); // "YYYY-MM" or "unstable"
        $api->setShop('koga-dev.myshopify.com');
        $api->setAccessToken('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');

        $query = ' query {
            shop {
              id
              name
              email
            }
            customers(first:1) {
              edges {
                node {
                  id
                  displayName
                  phone
                }
              }
            }
          }';

        $result = $api->graph($query)->body;

        $str = var_dump($result);
        $this->info($str);
    }
}
