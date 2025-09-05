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
            ->assertJson([
                'status' => 'success',
                'message' => 'API is working',
                'version' => '1.0.0'
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'timestamp',
                'version'
            ]);
    }

    /** @test */
    public function it_returns_json_response()
    {
        $response = $this->get('/api/test');

        $response->assertHeader('Content-Type', 'application/json');
    }
}
