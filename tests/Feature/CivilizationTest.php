<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Civilization;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CivilizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_civilizations(): void
    {
        // Given there are 3 civilizations in the database.
        Civilization::factory()->count(3)->create();

        // When we hit the API endpoint.
        $response = $this->getJson('/api/civilizations');

        // Then we expect a 200 status code and 3 items in the JSON.
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_it_can_create_a_civilization(): void
    {
        $payload = [
            'name' => 'Test Civilization',
            'icon' => 'https://example.com/icon.png',
        ];

        $response = $this->postJson('/api/civilizations', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('civilizations', $payload);
    }

    public function test_it_can_update_a_civilization(): void
    {
        $civilization = Civilization::factory()->create();

        $payload = [
            'name' => 'Updated Civilization',
            'icon' => 'https://example.com/updated.png',
        ];

        $response = $this->putJson("/api/civilizations/{$civilization->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('civilizations', $payload);
    }

    public function test_it_can_delete_a_civilization(): void
    {
        $civilization = Civilization::factory()->create();

        $response = $this->deleteJson("/api/civilizations/{$civilization->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('civilizations', ['id' => $civilization->id]);
    }
}
