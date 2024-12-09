<?php

namespace App\Actions;

use App\Models\Shop;
use App\Services\ShopUrlData;
use Illuminate\Database\Eloquent\Model;

class CreateShop
{
    private $shop_data;

    private $shopify_app;

    public function __construct($data, Model $shopify_app)
    {
        $this->shop_data = $data;
        $this->shopify_app = $shopify_app;
    }

    public function createShop()
    {

        $url_data = new ShopUrlData;
        $link_data = $url_data->getUrlData($this->shop_data['myshopifyDomain']);

        $shop = Shop::firstOrCreate([
            'shop_id' => $this->shop_data['id'],
        ],
            [
                'avatarUrl' => $this->shop_data['avatarUrl'],
                'myshopifyDomain' => $this->shop_data['myshopifyDomain'],
                'name' => $this->shop_data['name'],
                'partner_id' => $this->shopify_app->partner_id,
                'title' => $link_data['title'] ?? null,
                'image' => $link_data['image'] ?? null,
                'description' => $link_data['description'] ?? null,
            ]
        );

        return $shop;
    }
}
