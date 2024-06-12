<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_short_url_and_returns_it()
    {
        Http::fake([
            'https://safebrowsing.googleapis.com/*' => Http::response([], 200),
        ]);

        $response = $this->postJson('/shorten', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['short_url']]);
    }

    #[Test]
    public function it_returns_existing_short_url_for_duplicate_url()
    {
        Http::fake([
            'https://safebrowsing.googleapis.com/*' => Http::response([], 200),
        ]);

        $existingUrl = Url::create([
            'original_url' => 'https://example.com',
            'short_url' => 'abcdef',
        ]);

        $response = $this->postJson('/shorten', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['short_url' => url($existingUrl->short_url)]]);
    }

    #[Test]
    public function it_returns_error_for_invalid_url()
    {
        $response = $this->postJson('/shorten', [
            'original_url' => 'invalid-url',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('original_url');
    }

    #[Test]
    public function it_redirects_to_original_url()
    {
        $url = Url::create([
            'original_url' => 'https://example.com',
            'short_url' => 'abcdef',
        ]);

        $response = $this->get('/' . $url->short_url);
        $response->assertRedirect($url->original_url);
    }
}
