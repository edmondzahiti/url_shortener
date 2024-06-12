<?php

namespace App\Services;

use App\Models\Url;
use App\Repositories\UrlRepositoryInterface;
use Illuminate\Support\Str;

class UrlService implements UrlServiceInterface
{
    protected UrlRepositoryInterface $urlRepository;

    public function __construct(UrlRepositoryInterface $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    public function getOrCreateShortUrl(string $originalUrl): Url
    {
        $url = $this->urlRepository->findByOriginalUrl($originalUrl);
        if ($url) {
            return $url;
        }

        $shortUrl = Str::random(6);
        return $this->urlRepository->create($originalUrl, $shortUrl);
    }

    public function getOriginalUrl(string $shortUrl): Url
    {
        return $this->urlRepository->findByShortUrl($shortUrl);
    }
}
