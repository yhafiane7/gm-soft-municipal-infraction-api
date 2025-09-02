<?php

namespace Tests\Feature;

use App\Models\Commune;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommuneApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_communes()
    {
        // Create some test communes
        Commune::factory()->count(3)->create();

        $response = $this->getJson('/api/commune');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_new_commune()
    {
        $communeData = [
            'pachalik-circon' => 'District A',
            'caidat' => 'Caidat B',
            'nom' => 'Commune Name',
            'latitude' => 34.0522,
            'longitude' => -118.2437,
        ];

        $response = $this->postJson('/api/commune', $communeData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Commune created successfully',
                'data' => [
                    'pachalik-circon' => 'District A',
                    'nom' => 'Commune Name',
                ]
            ]);

        $this->assertDatabaseHas('commune', [
            'pachalik-circon' => 'District A',
            'nom' => 'Commune Name',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_commune()
    {
        $response = $this->postJson('/api/commune', []);

        $response->assertStatus(400)
            ->assertJsonValidationErrors([
                'pachalik-circon',
                'caidat',
                'nom',
                'latitude',
                'longitude'
            ]);
    }

    /** @test */
    public function it_validates_coordinate_ranges_for_commune()
    {
        $communeData = [
            'pachalik-circon' => 'District A',
            'caidat' => 'Caidat B',
            'nom' => 'Commune Name',
            'latitude' => 100, // Invalid latitude (> 90)
            'longitude' => -118.2437,
        ];

        $response = $this->postJson('/api/commune', $communeData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['latitude']);
    }

    /** @test */
    public function it_can_show_a_specific_commune()
    {
        $commune = Commune::factory()->create();

        $response = $this->getJson("/api/commune/{$commune->id}");

        $response->assertStatus(200)
            ->assertJson($commune->toArray());
    }

    /** @test */
    public function it_returns_404_for_nonexistent_commune()
    {
        $response = $this->getJson('/api/commune/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Commune not found']);
    }

    /** @test */
    public function it_can_update_a_commune()
    {
        $commune = Commune::factory()->create();

        $updateData = [
            'nom' => 'Updated Commune Name',
            'pachalik-circon' => 'Updated District',
        ];

        $response = $this->putJson("/api/commune/{$commune->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Commune updated successfully',
                'data' => [
                    'nom' => 'Updated Commune Name',
                    'pachalik-circon' => 'Updated District',
                ]
            ]);

        $this->assertDatabaseHas('commune', [
            'id' => $commune->id,
            'nom' => 'Updated Commune Name',
            'pachalik-circon' => 'Updated District',
        ]);
    }

    /** @test */
    public function it_can_delete_a_commune()
    {
        $commune = Commune::factory()->create();

        $response = $this->deleteJson("/api/commune/{$commune->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Commune deleted successfully']);

        $this->assertDatabaseMissing('commune', ['id' => $commune->id]);
    }
}
