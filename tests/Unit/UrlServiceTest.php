<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Url;
use App\Services\UrlService;
use App\Repositories\UrlRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class UrlServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $urlService;
    protected $urlRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlRepository = $this->app->make(UrlRepositoryInterface::class);
        $this->urlService = new UrlService($this->urlRepository);
    }

    #[Test]
    public function it_can_create_a_short_url()
    {
        Http::fake([
            'https://safebrowsing.googleapis.com/*' => Http::response([], 200),
        ]);

        $originalUrl = 'https://example.com';
        $url = $this->urlService->getOrCreateShortUrl($originalUrl);

        $this->assertDatabaseHas('urls', [
            'original_url' => $originalUrl,
            'short_url' => $url->short_url,
        ]);
        $this->assertEquals($originalUrl, $url->original_url);
        $this->assertEquals(6, strlen($url->short_url));
    }

    #[Test]
    public function it_can_return_existing_short_url_for_duplicate_url()
    {
        Http::fake([
            'https://safebrowsing.googleapis.com/*' => Http::response([], 200),
        ]);

        $originalUrl = 'https://example.com';
        $existingUrl = Url::create([
            'original_url' => $originalUrl,
            'short_url' => Str::random(6),
        ]);

        $url = $this->urlService->getOrCreateShortUrl($originalUrl);

        $this->assertEquals($existingUrl->short_url, $url->short_url);
    }

    #[Test]
    public function it_handles_safe_urls_correctly()
    {
        Http::fake([
            'https://safebrowsing.googleapis.com/*' => Http::response([], 200),
        ]);

        $originalUrl = 'https://safe-example.com';
        $url = $this->urlService->getOrCreateShortUrl($originalUrl);

        $this->assertDatabaseHas('urls', [
            'original_url' => $originalUrl,
            'short_url' => $url->short_url,
        ]);
    }

    #[Test]
    public function it_redirects_to_original_url()
    {
        $url = Url::create([
            'original_url' => 'https://example.com',
            'short_url' => Str::random(6),
        ]);

        $response = $this->get('/' . $url->short_url);
        $response->assertRedirect($url->original_url);
    }
}
