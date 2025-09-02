<?php

namespace Tests\Feature;

use App\Models\Categorie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategorieApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_categories()
    {
        // Create some test categories
        Categorie::factory()->count(3)->create();

        $response = $this->getJson('/api/categorie');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_new_category()
    {
        $categoryData = [
            'nom' => 'Traffic Violation',
            'degre' => 3,
        ];

        $response = $this->postJson('/api/categorie', $categoryData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Category created successfully',
                'data' => [
                    'nom' => 'Traffic Violation',
                    'degre' => 3,
                ]
            ]);

        $this->assertDatabaseHas('categorie', [
            'nom' => 'Traffic Violation',
            'degre' => 3,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_category()
    {
        $response = $this->postJson('/api/categorie', []);

        $response->assertStatus(400)
            ->assertJsonValidationErrors([
                'nom',
                'degre'
            ]);
    }

    /** @test */
    public function it_validates_category_name_length()
    {
        $categoryData = [
            'nom' => 'A', // Too short
            'degre' => 3,
        ];

        $response = $this->postJson('/api/categorie', $categoryData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['nom']);
    }

    /** @test */
    public function it_validates_degree_range()
    {
        $categoryData = [
            'nom' => 'Traffic Violation',
            'degre' => 6, // Too high
        ];

        $response = $this->postJson('/api/categorie', $categoryData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['degre']);
    }

    /** @test */
    public function it_validates_degree_minimum()
    {
        $categoryData = [
            'nom' => 'Traffic Violation',
            'degre' => 0, // Too low
        ];

        $response = $this->postJson('/api/categorie', $categoryData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['degre']);
    }

    /** @test */
    public function it_validates_degree_is_integer()
    {
        $categoryData = [
            'nom' => 'Traffic Violation',
            'degre' => 'three', // Not integer
        ];

        $response = $this->postJson('/api/categorie', $categoryData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['degre']);
    }

    /** @test */
    public function it_can_show_a_specific_category()
    {
        $category = Categorie::factory()->create();

        $response = $this->getJson("/api/categorie/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $category->id,
                'nom' => $category->nom,
                'degre' => $category->degre,
            ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_category()
    {
        $response = $this->getJson('/api/categorie/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Category not found']);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $category = Categorie::factory()->create();

        $updateData = [
            'nom' => 'Updated Category',
            'degre' => 4,
        ];

        $response = $this->putJson("/api/categorie/{$category->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Category updated successfully',
                'data' => [
                    'nom' => 'Updated Category',
                    'degre' => 4,
                ]
            ]);

        $this->assertDatabaseHas('categorie', [
            'id' => $category->id,
            'nom' => 'Updated Category',
            'degre' => 4,
        ]);
    }

    /** @test */
    public function it_can_update_category_partially()
    {
        $category = Categorie::factory()->create();

        $updateData = [
            'nom' => 'Updated Category',
        ];

        $response = $this->putJson("/api/categorie/{$category->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Category updated successfully',
                'data' => [
                    'nom' => 'Updated Category',
                    'degre' => $category->degre, // Should remain unchanged
                ]
            ]);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $category = Categorie::factory()->create();

        $response = $this->deleteJson("/api/categorie/{$category->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Category deleted successfully']);

        $this->assertDatabaseMissing('categorie', ['id' => $category->id]);
    }

    /** @test */
    public function it_returns_404_when_updating_nonexistent_category()
    {
        $updateData = [
            'nom' => 'Updated Category',
            'degre' => 4,
        ];

        $response = $this->putJson('/api/categorie/999', $updateData);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Category not found']);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_category()
    {
        $response = $this->deleteJson('/api/categorie/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Category not found']);
    }

    /** @test */
    public function it_validates_degree_range_on_update()
    {
        $category = Categorie::factory()->create();

        $updateData = [
            'degre' => 6, // Too high
        ];

        $response = $this->putJson("/api/categorie/{$category->id}", $updateData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['degre']);
    }
}
