<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Civilization;
use App\Models\Leader;
use App\Models\HistoricalInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_leaders(): void
    {
        // Create a civilization and several leaders for it.
        $civ = Civilization::factory()->create();
        Leader::factory()->count(3)->create(['civilization_id' => $civ->id]);

        $response = $this->getJson('/api/leaders');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_it_can_create_a_leader(): void
    {
        $civ = Civilization::factory()->create();

        $payload = [
            'name'             => 'Test Leader',
            'civilization_id'  => $civ->id,
            'icon'             => 'https://example.com/leader.png',
            'subtitle'         => 'Test Subtitle',
            'lifespan'         => '200-300 AD',
        ];

        $response = $this->postJson('/api/leaders', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('leaders', $payload);
    }

    public function test_it_can_update_a_leader(): void
    {
        $civ = Civilization::factory()->create();
        $leader = Leader::factory()->create(['civilization_id' => $civ->id]);

        $payload = [
            'name'             => 'Updated Leader',
            'civilization_id'  => $civ->id,
            'icon'             => 'https://example.com/updated.png',
            'subtitle'         => 'Updated Subtitle',
            'lifespan'         => '300-400 AD',
        ];

        $response = $this->putJson("/api/leaders/{$leader->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('leaders', $payload);
    }

    public function test_it_can_delete_a_leader(): void
    {
        $civ = Civilization::factory()->create();
        $leader = Leader::factory()->create(['civilization_id' => $civ->id]);

        $response = $this->deleteJson("/api/leaders/{$leader->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('leaders', ['id' => $leader->id]);
    }

    public function test_leader_belongs_to_civilization(): void
    {
        $civ = Civilization::factory()->create();
        $leader = Leader::factory()->create(['civilization_id' => $civ->id]);

        $leader->load('civilization');

        $this->assertInstanceOf(Civilization::class, $leader->civilization);
        $this->assertEquals($civ->id, $leader->civilization->id);
    }

    public function test_leader_can_have_many_historical_info_entries(): void
    {
        $civ = Civilization::factory()->create();
        $leader = Leader::factory()->create(['civilization_id' => $civ->id]);

        // Create 2 historical info records associated with this leader.
        HistoricalInfo::factory()->count(2)->create([
            'taxonomy_id' => $leader->id,
            'type' => Leader::class,
        ]);

        $leader->load('historicalInfo');

        $this->assertCount(2, $leader->historicalInfo);
    }
}
