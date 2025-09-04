<?php

namespace Tests\Feature;

use App\Models\Decision;
use App\Models\Infraction;
use App\Models\Agent;
use App\Models\Categorie;
use App\Models\Commune;
use App\Models\Violant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecisionApiTest extends TestCase
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
        $this->infraction = Infraction::factory()->create([
            'commune_id' => $this->commune->id,
            'violant_id' => $this->violant->id,
            'agent_id' => $this->agent->id,
            'categorie_id' => $this->categorie->id,
        ]);
    }

    /** @test */
    public function it_can_list_all_decisions()
    {
        // Create some test decisions
        Decision::factory()->count(3)->create([
            'infraction_id' => $this->infraction->id,
        ]);

        $response = $this->getJson('/api/decision');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_new_decision()
    {
        $decisionData = [
            'infraction_id' => $this->infraction->id,
            'date' => now()->format('Y-m-d'),
            'decisionprise' => 'Fine imposed for traffic violation',
        ];

        $response = $this->postJson('/api/decision', $decisionData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Decision created successfully',
                'data' => [
                    'date' => now()->format('Y-m-d'),
                    'decisionprise' => 'Fine imposed for traffic violation',
                ]
            ]);

        $this->assertDatabaseHas('decision', [
            'infraction_id' => $this->infraction->id,
            'date' => now()->format('Y-m-d'),
            'decisionprise' => 'Fine imposed for traffic violation',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_decision()
    {
        $response = $this->postJson('/api/decision', []);

        $response->assertStatus(400)
            ->assertJsonValidationErrors([
                'infraction_id',
                'date',
                'decisionprise'
            ]);
    }

    /** @test */
    public function it_validates_infraction_exists()
    {
        $decisionData = [
            'infraction_id' => 999, // Non-existent infraction
            'date' => now()->format('Y-m-d'),
            'decisionprise' => 'Fine imposed for traffic violation',
        ];

        $response = $this->postJson('/api/decision', $decisionData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['infraction_id']);
    }



    /** @test */
    public function it_can_show_a_specific_decision()
    {
        $decision = Decision::factory()->create([
            'infraction_id' => $this->infraction->id,
        ]);

        $response = $this->getJson("/api/decision/{$decision->id}");

        $response->assertStatus(200)
            ->assertJson($decision->toArray());
    }

    /** @test */
    public function it_returns_404_for_nonexistent_decision()
    {
        $response = $this->getJson('/api/decision/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Decision not found']);
    }

    /** @test */
    public function it_can_update_a_decision()
    {
        $decision = Decision::factory()->create([
            'infraction_id' => $this->infraction->id,
        ]);

        $updateData = [
            'date' => now()->addDay()->format('Y-m-d'),
            'decisionprise' => 'Updated decision description',
        ];

        $response = $this->putJson("/api/decision/{$decision->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Decision updated successfully',
                'data' => [
                    'date' => now()->addDay()->format('Y-m-d'),
                    'decisionprise' => 'Updated decision description',
                ]
            ]);

        $this->assertDatabaseHas('decision', [
            'id' => $decision->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'decisionprise' => 'Updated decision description',
        ]);
    }

    /** @test */
    public function it_can_delete_a_decision()
    {
        $decision = Decision::factory()->create([
            'infraction_id' => $this->infraction->id,
        ]);

        $response = $this->deleteJson("/api/decision/{$decision->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Decision deleted successfully']);

        $this->assertDatabaseMissing('decision', ['id' => $decision->id]);
    }

    /** @test */
    public function it_validates_date_format()
    {
        $decisionData = [
            'infraction_id' => $this->infraction->id,
            'date' => 'invalid-date',
            'decisionprise' => 'Fine imposed for traffic violation',
        ];

        $response = $this->postJson('/api/decision', $decisionData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['date']);
    }
}
