<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Osiset\BasicShopifyAPI;

class PostController extends Controller
{

    public function index()
    {

        $api = new BasicShopifyAPI();
        $api->setVersion('2020-01'); // "YYYY-MM" or "unstable"
        $api->setShop('koga-dev.myshopify.com');
        $api->setAccessToken('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');

        $API_KEY = '9238c156e8184a0af8f3dfb74c9f623b';
        $API_SECRET = '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';

        $url = "https://9238c156e8184a0af8f3dfb74c9f623b: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@koga-dev.myshopify.com/admin/api/2020-07/products.json";
        $method = "GET";

        // graphtQL endpoint_def POST https://{shop}.myshopify.com/admin/api/2020-07/graphql.json 
        $graPhUrl = "https://koga-dev.myshopify.com/admin/api/2020-07/graphql.json";
        $accessToken = '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';
        $access_token = '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';

        
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

          
          //$query = '{ shop { productz(first: 1) { edges { node { handle, id } } } } }';
        
        // Now run your requests...
        $result = $api->graph($query)->body;
        //$result = $api->graph('{ shop { productz(first: 1) { edges { node { handle, id } } } } }');

        //接続
        //$client = new Client();
        //$client ->setUrl($url);
        //$response = $client->request($method, $url);

       /*$response = $ $client->request(
        'POST',
        'https://koga-dev.myshopify.com/admin/api/2020-07/graphql.json',
        [
            'json' => [
            'query' => $query,
            'variables' => null,
            'operationName' => null
            ],
            'headers' => [
            'sm-api-key' => $API_KEY,
            'sm-api-secret' => $API_SECRET
            ]
        ]);
        */

        /*
        $response = $client->request(
            'GET', 
            "https://koga-dev.myshopify.com/admin/products.json",
            [
                'query' => [
                    'fields' => 'id,images,title,variants',
                    'access_token' => $access_token
                ]
            ]
        );
        */

        //$result = json_decode($response->getBody()->getContents(), true);

        //$posts = $result['products'][0]["id"];
        //$posts = var_dump($result);
        $posts = var_dump($result);
        //$posts = $response->getBody();
        //$posts = json_decode($posts, true);

        //$posts = json_decode( $response , true ) ;
        //$posts = $posts["products"][0]["id"];
        //$posts = $posts["shop"][0]["id"];
        //$response_body = json_decode((string)$response->getBody());

        return view('index', ['posts' => $posts]);
    }
    //
}
