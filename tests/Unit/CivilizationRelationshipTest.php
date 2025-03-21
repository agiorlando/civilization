<?php
declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Civilization;
use App\Models\Leader;
use App\Models\HistoricalInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CivilizationRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_civilization_has_a_leader(): void
    {
        $civilization = Civilization::factory()->create();
        $leader = Leader::factory()->create([
            'civilization_id' => $civilization->id,
        ]);

        // Refresh the model to load the relationship.
        $civilization->load('leader');

        $this->assertInstanceOf(Leader::class, $civilization->leader);
        $this->assertEquals($leader->id, $civilization->leader->id);
    }

    public function test_civilization_can_have_many_historical_info_entries(): void
    {
        $civilization = Civilization::factory()->create();

        HistoricalInfo::factory()->count(3)->create([
            'taxonomy_id' => $civilization->id,
            'type' => Civilization::class,
        ]);

        // Refresh to ensure relationships are loaded.
        $civilization->load('historicalInfo');

        $this->assertCount(3, $civilization->historicalInfo);
    }
}
