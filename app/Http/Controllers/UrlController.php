<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortenUrlRequest;
use App\Http\Resources\UrlResource;
use App\Services\UrlServiceInterface;
use Illuminate\Http\RedirectResponse;

class UrlController extends Controller
{
    protected UrlServiceInterface $urlService;

    public function __construct(UrlServiceInterface $urlService)
    {
        $this->urlService = $urlService;
    }

    public function shorten(ShortenUrlRequest $request): UrlResource
    {
        $url = $this->urlService->getOrCreateShortUrl($request->validated('original_url'));
        return new UrlResource($url);
    }

    public function redirect($shortUrl): RedirectResponse
    {
        $url = $this->urlService->getOriginalUrl($shortUrl);
        return redirect()->to($url->original_url);
    }
}
