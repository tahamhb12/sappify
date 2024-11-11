<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShopUrlData
{
    public function getUrlData($url)
    {

        if (! $url) {
            return null;
        }

        // check if is a valid url

        try {
            $response = Http::get($url);
        } catch (\Throwable $th) {
            Log::error('error', [$th]);

            return 'bad link';
        }

        if ($response->failed()) {
            return 'bad link';
        }

        $body = $response->body();

        $metadata = [];

        preg_match_all('/<meta (?:property|name)="(og|twitter):(title|description|image|image:secure_url)" content="([^"]*)"/', $body, $metaMatches, PREG_SET_ORDER);

        foreach ($metaMatches as $match) {
            $property = $match[2];
            $content = $match[3];

            if ($property === 'image:secure_url' && ! isset($metadata['image:secure_url'])) {
                $metadata['image:secure_url'] = $content;
            } elseif (! isset($metadata[$property])) {
                $metadata[$property] = $content;
            }
        }

        $image = $metadata['image:secure_url'] ?? $metadata['image'] ?? null;

        $data = [
            'title' => $metadata['title'] ?? null,
            'image' => $image ?? null,
            'description' => isset($metadata['description']) ? Str::limit($metadata['description'], 200) : null,
        ];

        return $data;
    }
}
