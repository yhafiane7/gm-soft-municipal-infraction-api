<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_test_response()
    {
        $response = $this->getJson('/api/test');

        $response->assertStatus(200)
            ->assertSee('test');
    }

    /** @test */
    public function it_returns_text_response()
    {
        $response = $this->get('/api/test');

        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    }
}
