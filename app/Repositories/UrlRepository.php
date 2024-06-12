<?php

namespace App\Repositories;

use App\Models\Url;

class UrlRepository implements UrlRepositoryInterface
{
    public function findByOriginalUrl(string $originalUrl): ?Url
    {
        return Url::where('original_url', $originalUrl)->first();
    }

    public function findByShortUrl(string $shortUrl): ?Url
    {
        return Url::where('short_url', $shortUrl)->first();
    }

    public function create(string $originalUrl, string $shortUrl): Url
    {
        return Url::create([
            'original_url' => $originalUrl,
            'short_url' => $shortUrl
        ]);
    }
}
