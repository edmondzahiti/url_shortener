<?php

namespace App\Repositories;

use App\Models\Url;

interface UrlRepositoryInterface
{
    public function findByOriginalUrl(string $originalUrl): ?Url;
    public function findByShortUrl(string $shortUrl): ?Url;
    public function create(string $originalUrl, string $shortUrl): Url;
}
