<?php

namespace App\Services;

use App\Models\Partner;
use Illuminate\Support\Facades\Http;

class UrLdata
{

    public function getUrlData($url){
        if(!$url){
            return null;
        }
        if(substr($url,0,25)!=='https://apps.shopify.com/'){
            return 'bad link';
        }else{
            $response = Http::get($url);
        }
        if ($response->failed()) {
            return 'bad link';
        }
        $body = $response->body();
        preg_match_all('/<title>(.*?)<\/title>|<script[^>]*type="application\/ld\+json"[^>]*>([\s\S]*?)<\/script>/', $body, $matches);
        $title = $matches[1][0] ?? 'No title found';
        $jsonLdData = array_filter(array_map('json_decode', $matches[2]));

        $image = null;
        $description = null;
        foreach ($jsonLdData as $data) {
            if (isset($data->image)) {
                $image = $data->image;
            }
            if (isset($data->description)) {
                $description = $data->description;
            }
        }
        $data = [
            'title' => $title,
            'image' => $image[0],
            'description' => $description,
        ];

        return $data;
    }
}
