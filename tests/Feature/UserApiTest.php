<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_users()
    {
        // Create some test users
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_new_user()
    {
        $userData = [
            'nom' => 'John',
            'prenom' => 'Doe',
            'Tel' => '+1234567890',
            'role' => 'user',
            'login' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/user', $userData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User created successfully',
                'data' => [
                    'nom' => 'John',
                    'prenom' => 'Doe',
                    'email' => 'john@example.com',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'nom' => 'John',
            'prenom' => 'Doe',
            'email' => 'john@example.com',
        ]);

        // Check that user was created
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_user()
    {
        $response = $this->postJson('/api/user', []);

        $response->assertStatus(400)
            ->assertJsonValidationErrors([
                'nom',
                'prenom',
                'Tel',
                'role',
                'login',
                'email',
                'password'
            ]);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $userData = [
            'nom' => 'John',
            'prenom' => 'Doe',
            'Tel' => '+1234567890',
            'role' => 'user',
            'login' => 'johndoe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/user', $userData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_validates_password_confirmation()
    {
        $userData = [
            'nom' => 'John',
            'prenom' => 'Doe',
            'Tel' => '+1234567890',
            'role' => 'user',
            'login' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ];

        $response = $this->postJson('/api/user', $userData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function it_validates_unique_email()
    {
        // Create a user first
        User::factory()->create(['email' => 'john@example.com']);

        $userData = [
            'nom' => 'Jane',
            'prenom' => 'Doe',
            'Tel' => '+1234567890',
            'role' => 'user',
            'login' => 'janedoe',
            'email' => 'john@example.com', // Same email
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/user', $userData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_show_a_specific_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/user/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
            ]);

        // Password should not be visible
        $response->assertJsonMissing(['password']);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_user()
    {
        $response = $this->getJson('/api/user/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'User not found']);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = User::factory()->create();

        $updateData = [
            'nom' => 'Updated',
            'prenom' => 'Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->putJson("/api/user/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User updated successfully',
                'data' => [
                    'nom' => 'Updated',
                    'prenom' => 'Name',
                    'email' => 'updated@example.com',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nom' => 'Updated',
            'prenom' => 'Name',
            'email' => 'updated@example.com',
        ]);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/user/{$user->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'User deleted successfully']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
