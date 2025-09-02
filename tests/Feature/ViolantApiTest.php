<?php

namespace Tests\Feature;

use App\Models\Violant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViolantApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_violants()
    {
        // Create some test violants
        Violant::factory()->count(3)->create();

        $response = $this->getJson('/api/violant');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_new_violant()
    {
        $violantData = [
            'nom' => 'Doe',
            'prenom' => 'John',
            'cin' => 'AB123456',
        ];

        $response = $this->postJson('/api/violant', $violantData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Violant created successfully',
                'data' => [
                    'nom' => 'Doe',
                    'prenom' => 'John',
                    'cin' => 'AB123456',
                ]
            ]);

        $this->assertDatabaseHas('violant', [
            'nom' => 'Doe',
            'prenom' => 'John',
            'cin' => 'AB123456',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_violant()
    {
        $response = $this->postJson('/api/violant', []);

        $response->assertStatus(400)
            ->assertJsonValidationErrors([
                'nom',
                'prenom',
                'cin'
            ]);
    }

    /** @test */
    public function it_validates_violant_name_length()
    {
        $violantData = [
            'nom' => 'A', // Too short
            'prenom' => 'B', // Too short
            'cin' => 'AB123456',
            'tel' => '1234567890',
            'adresse' => '123 Main Street',
        ];

        $response = $this->postJson('/api/violant', $violantData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['nom', 'prenom']);
    }

    /** @test */
    public function it_validates_cin_format()
    {
        $violantData = [
            'nom' => 'Doe',
            'prenom' => 'John',
            'cin' => 'ab123456', // Lowercase not allowed
            'tel' => '1234567890',
            'adresse' => '123 Main Street',
        ];

        $response = $this->postJson('/api/violant', $violantData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['cin']);
    }



    /** @test */
    public function it_validates_unique_cin()
    {
        // Create first violant
        Violant::factory()->create(['cin' => 'AB123456']);

        $violantData = [
            'nom' => 'Doe',
            'prenom' => 'John',
            'cin' => 'AB123456', // Duplicate CIN
            'tel' => '1234567890',
            'adresse' => '123 Main Street',
        ];

        $response = $this->postJson('/api/violant', $violantData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['cin']);
    }



    /** @test */
    public function it_can_show_a_specific_violant()
    {
        $violant = Violant::factory()->create();

        $response = $this->getJson("/api/violant/{$violant->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $violant->id,
                'nom' => $violant->nom,
                'prenom' => $violant->prenom,
                'cin' => $violant->cin,
            ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_violant()
    {
        $response = $this->getJson('/api/violant/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Violant not found']);
    }

    /** @test */
    public function it_can_update_a_violant()
    {
        $violant = Violant::factory()->create();

        $updateData = [
            'nom' => 'Updated',
            'prenom' => 'Name',
        ];

        $response = $this->putJson("/api/violant/{$violant->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Violant updated successfully',
                'data' => [
                    'nom' => 'Updated',
                    'prenom' => 'Name',
                ]
            ]);

        $this->assertDatabaseHas('violant', [
            'id' => $violant->id,
            'nom' => 'Updated',
            'prenom' => 'Name',
        ]);
    }

    /** @test */
    public function it_can_update_violant_partially()
    {
        $violant = Violant::factory()->create();

        $updateData = [
            'nom' => 'Updated Name',
        ];

        $response = $this->putJson("/api/violant/{$violant->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Violant updated successfully',
                'data' => [
                    'nom' => 'Updated Name',
                ]
            ]);
    }

    /** @test */
    public function it_can_delete_a_violant()
    {
        $violant = Violant::factory()->create();

        $response = $this->deleteJson("/api/violant/{$violant->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Violant deleted successfully']);

        $this->assertDatabaseMissing('violant', ['id' => $violant->id]);
    }

    /** @test */
    public function it_returns_404_when_updating_nonexistent_violant()
    {
        $updateData = [
            'nom' => 'Updated',
            'prenom' => 'Name',
        ];

        $response = $this->putJson('/api/violant/999', $updateData);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Violant not found']);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_violant()
    {
        $response = $this->deleteJson('/api/violant/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Violant not found']);
    }

    /** @test */
    public function it_validates_cin_format_on_update()
    {
        $violant = Violant::factory()->create();

        $updateData = [
            'cin' => 'ab123456', // Lowercase not allowed
        ];

        $response = $this->putJson("/api/violant/{$violant->id}", $updateData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['cin']);
    }
}
