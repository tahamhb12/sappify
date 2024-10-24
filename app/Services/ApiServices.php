<?php

namespace App\Services;

use App\Models\Partner;
use Illuminate\Support\Facades\Http;

class ApiServices
{
    private $apiUrl;
    private $accessToken;

    public function __construct(Partner $partner,$version = "2024-10")
    {
        $this->apiUrl = 'https://partners.shopify.com/' . $partner->partner_id . '/api/' . $version . '/graphql.json';
        $this->accessToken = $partner->api_key;
    }
    public function getData($query)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $this->accessToken
                ])->post($this->apiUrl, [
            'query' => $query
        ]);
        return $response;
    }


    public function getApp($id){
        return $this->getData('
        {
                app(id: "gid://partners/App/'.$id.'") {
                    id
                    apiKey
                    name
                }
            }');
    }
    public function getEvents($id){
        return $this->getData('{
            app(id : "gid://partners/App/'.$id.'"){
                events (first: 100) {
                edges {
                    node {
                    occurredAt
                    type
                        shop {
                        avatarUrl
                        id
                        myshopifyDomain
                        name
                        }
                    }
                }
                }
            }
            }');
    }

    public function checkPartner(){
        return $this->getData(
            '{
                transactions(first: 20) {
                    edges {
                    node {
                        id
                        createdAt
                    }
                    }
                }
                }');
    }
}
