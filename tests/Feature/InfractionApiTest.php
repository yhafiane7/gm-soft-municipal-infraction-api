<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\Categorie;
use App\Models\Commune;
use App\Models\Infraction;
use App\Models\Violant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InfractionApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data for relationships
        $this->commune = Commune::factory()->create();
        $this->violant = Violant::factory()->create();
        $this->agent = Agent::factory()->create();
        $this->categorie = Categorie::factory()->create();
    }

    /** @test */
    public function it_can_list_all_infractions()
    {
        // Create some test infractions
        Infraction::factory()->count(3)->create([
            'commune_id' => $this->commune->id,
            'violant_id' => $this->violant->id,
            'agent_id' => $this->agent->id,
            'categorie_id' => $this->categorie->id,
        ]);

        $response = $this->getJson('/api/infraction');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_new_infraction()
    {
        $infractionData = [
            'nom' => 'Speeding Violation',
            'date' => now()->subDay()->format('Y-m-d'),
            'adresse' => '123 Main Street, City Center',
            'commune_id' => $this->commune->id,
            'violant_id' => $this->violant->id,
            'agent_id' => $this->agent->id,
            'categorie_id' => $this->categorie->id,
            'latitude' => 34.0522,
            'longitude' => -118.2437,
        ];

        $response = $this->postJson('/api/infraction', $infractionData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Infraction created successfully',
                'data' => [
                    'nom' => 'Speeding Violation',
                    'adresse' => '123 Main Street, City Center',
                ]
            ]);

        $this->assertDatabaseHas('infraction', [
            'nom' => 'Speeding Violation',
            'adresse' => '123 Main Street, City Center',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_infraction()
    {
        $response = $this->postJson('/api/infraction', []);

        $response->assertStatus(400)
            ->assertJsonValidationErrors([
                'nom',
                'date',
                'adresse',
                'commune_id',
                'violant_id',
                'agent_id',
                'categorie_id',
                'latitude',
                'longitude'
            ]);
    }

    /** @test */
    public function it_validates_infraction_name_length()
    {
        $infractionData = [
            'nom' => 'A', // Too short
            'date' => now()->subDay()->format('Y-m-d'),
            'adresse' => '123 Main Street, City Center',
            'commune_id' => $this->commune->id,
            'violant_id' => $this->violant->id,
            'agent_id' => $this->agent->id,
            'categorie_id' => $this->categorie->id,
            'latitude' => 34.0522,
            'longitude' => -118.2437,
        ];

        $response = $this->postJson('/api/infraction', $infractionData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['nom']);
    }

    /** @test */
    public function it_validates_future_dates_are_not_allowed()
    {
        $infractionData = [
            'nom' => 'Speeding Violation',
            'date' => now()->addDay()->format('Y-m-d'), // Future date
            'adresse' => '123 Main Street, City Center',
            'commune_id' => $this->commune->id,
            'violant_id' => $this->violant->id,
            'agent_id' => $this->agent->id,
            'categorie_id' => $this->categorie->id,
            'latitude' => 34.0522,
            'longitude' => -118.2437,
        ];

        $response = $this->postJson('/api/infraction', $infractionData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['date']);
    }

    /** @test */
    public function it_validates_coordinate_ranges()
    {
        $infractionData = [
            'nom' => 'Speeding Violation',
            'date' => now()->subDay()->format('Y-m-d'),
            'adresse' => '123 Main Street, City Center',
            'commune_id' => $this->commune->id,
            'violant_id' => $this->violant->id,
            'agent_id' => $this->agent->id,
            'categorie_id' => $this->categorie->id,
            'latitude' => 100, // Invalid latitude (> 90)
            'longitude' => -118.2437,
        ];

        $response = $this->postJson('/api/infraction', $infractionData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['latitude']);
    }

    /** @test */
    public function it_can_show_a_specific_infraction()
    {
        $infraction = Infraction::factory()->create([
            'commune_id' => $this->commune->id,
            'violant_id' => $this->violant->id,
            'agent_id' => $this->agent->id,
            'categorie_id' => $this->categorie->id,
        ]);

        $response = $this->getJson("/api/infraction/{$infraction->id}");

        $response->assertStatus(200)
            ->assertJson($infraction->toArray());
    }

    /** @test */
    public function it_returns_404_for_nonexistent_infraction()
    {
        $response = $this->getJson('/api/infraction/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Infraction not found']);
    }

    /** @test */
    public function it_can_update_an_infraction()
    {
        $infraction = Infraction::factory()->create([
            'commune_id' => $this->commune->id,
            'violant_id' => $this->violant->id,
            'agent_id' => $this->agent->id,
            'categorie_id' => $this->categorie->id,
        ]);

        $updateData = [
            'nom' => 'Updated Violation Name',
            'adresse' => '456 New Street, Downtown',
        ];

        $response = $this->putJson("/api/infraction/{$infraction->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Infraction updated successfully',
                'data' => [
                    'nom' => 'Updated Violation Name',
                    'adresse' => '456 New Street, Downtown',
                ]
            ]);

        $this->assertDatabaseHas('infraction', [
            'id' => $infraction->id,
            'nom' => 'Updated Violation Name',
            'adresse' => '456 New Street, Downtown',
        ]);
    }

    /** @test */
    public function it_can_delete_an_infraction()
    {
        $infraction = Infraction::factory()->create([
            'commune_id' => $this->commune->id,
            'violant_id' => $this->violant->id,
            'agent_id' => $this->agent->id,
            'categorie_id' => $this->categorie->id,
        ]);

        $response = $this->deleteJson("/api/infraction/{$infraction->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Infraction deleted successfully']);

        $this->assertDatabaseMissing('infraction', ['id' => $infraction->id]);
    }

    /** @test */
    public function it_validates_foreign_key_constraints()
    {
        $infractionData = [
            'nom' => 'Speeding Violation',
            'date' => now()->subDay()->format('Y-m-d'),
            'adresse' => '123 Main Street, City Center',
            'commune_id' => 999, // Non-existent commune
            'violant_id' => $this->violant->id,
            'agent_id' => $this->agent->id,
            'categorie_id' => $this->categorie->id,
            'latitude' => 34.0522,
            'longitude' => -118.2437,
        ];

        $response = $this->postJson('/api/infraction', $infractionData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['commune_id']);
    }
}
