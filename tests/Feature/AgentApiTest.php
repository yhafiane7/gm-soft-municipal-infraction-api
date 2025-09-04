<?php

namespace Tests\Feature;

use App\Models\Agent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_agents()
    {
        // Create some test agents
        Agent::factory()->count(3)->create();

        $response = $this->getJson('/api/agent');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_new_agent()
    {
        $agentData = [
            'nom' => 'Smith',
            'prenom' => 'John',
            'tel' => '1234567890',
            'cin' => 'AB123456',
        ];

        $response = $this->postJson('/api/agent', $agentData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Agent created successfully',
                'data' => [
                    'nom' => 'Smith',
                    'prenom' => 'John',
                    'tel' => '1234567890',
                    'cin' => 'AB123456',
                ]
            ]);

        $this->assertDatabaseHas('agent', [
            'nom' => 'Smith',
            'prenom' => 'John',
            'tel' => '1234567890',
            'cin' => 'AB123456',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_agent()
    {
        $response = $this->postJson('/api/agent', []);

        $response->assertStatus(400)
            ->assertJsonValidationErrors([
                'nom',
                'prenom',
                'tel',
                'cin'
            ]);
    }

    /** @test */
    public function it_validates_agent_name_length()
    {
        $agentData = [
            'nom' => 'A', // Too short
            'prenom' => 'B', // Too short
            'tel' => '1234567890',
            'cin' => 'AB123456',
        ];

        $response = $this->postJson('/api/agent', $agentData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['nom', 'prenom']);
    }

    /** @test */
    public function it_validates_phone_number_format()
    {
        $agentData = [
            'nom' => 'Smith',
            'prenom' => 'John',
            'tel' => '123456789', // Too short
            'cin' => 'AB123456',
        ];

        $response = $this->postJson('/api/agent', $agentData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['tel']);
    }

    /** @test */
    public function it_validates_phone_number_digits_only()
    {
        $agentData = [
            'nom' => 'Smith',
            'prenom' => 'John',
            'tel' => '123456789a', // Contains letter
            'cin' => 'AB123456',
        ];

        $response = $this->postJson('/api/agent', $agentData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['tel']);
    }

    /** @test */
    public function it_validates_cin_format()
    {
        $agentData = [
            'nom' => 'Smith',
            'prenom' => 'John',
            'tel' => '1234567890',
            'cin' => 'ab123456', // Lowercase not allowed
        ];

        $response = $this->postJson('/api/agent', $agentData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['cin']);
    }

    /** @test */
    public function it_validates_unique_phone_number()
    {
        // Create first agent
        Agent::factory()->create(['tel' => '1234567890']);

        $agentData = [
            'nom' => 'Smith',
            'prenom' => 'John',
            'tel' => '1234567890', // Duplicate phone
            'cin' => 'AB123456',
        ];

        $response = $this->postJson('/api/agent', $agentData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['tel']);
    }

    /** @test */
    public function it_validates_unique_cin()
    {
        // Create first agent
        Agent::factory()->create(['cin' => 'AB123456']);

        $agentData = [
            'nom' => 'Smith',
            'prenom' => 'John',
            'tel' => '1234567890',
            'cin' => 'AB123456', // Duplicate CIN
        ];

        $response = $this->postJson('/api/agent', $agentData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['cin']);
    }

    /** @test */
    public function it_can_show_a_specific_agent()
    {
        $agent = Agent::factory()->create();

        $response = $this->getJson("/api/agent/{$agent->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $agent->id,
                'nom' => $agent->nom,
                'prenom' => $agent->prenom,
                'tel' => $agent->tel,
                'cin' => $agent->cin,
            ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_agent()
    {
        $response = $this->getJson('/api/agent/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Agent not found']);
    }

    /** @test */
    public function it_can_update_an_agent()
    {
        $agent = Agent::factory()->create();

        $updateData = [
            'nom' => 'Updated',
            'prenom' => 'Name',
        ];

        $response = $this->putJson("/api/agent/{$agent->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Agent updated successfully',
                'data' => [
                    'nom' => 'Updated',
                    'prenom' => 'Name',
                ]
            ]);

        $this->assertDatabaseHas('agent', [
            'id' => $agent->id,
            'nom' => 'Updated',
            'prenom' => 'Name',
        ]);
    }

    /** @test */
    public function it_can_delete_an_agent()
    {
        $agent = Agent::factory()->create();

        $response = $this->deleteJson("/api/agent/{$agent->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Agent deleted successfully']);

        $this->assertDatabaseMissing('agent', ['id' => $agent->id]);
    }

    /** @test */
    public function it_returns_404_when_updating_nonexistent_agent()
    {
        $updateData = [
            'nom' => 'Updated',
            'prenom' => 'Name',
        ];

        $response = $this->putJson('/api/agent/999', $updateData);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Agent not found']);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_agent()
    {
        $response = $this->deleteJson('/api/agent/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Agent not found']);
    }
}
