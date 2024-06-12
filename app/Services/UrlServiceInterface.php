<?php

namespace App\Services;

use App\Models\Url;

interface UrlServiceInterface
{
    public function getOrCreateShortUrl(string $originalUrl): Url;

    public function getOriginalUrl(string $shortUrl): Url;
}
