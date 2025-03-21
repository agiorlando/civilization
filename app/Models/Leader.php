<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leader extends Model
{
    use HasFactory;

    protected $fillable = [
        'civilization_id',
        'name',
        'icon',
        'subtitle',
        'lifespan',
    ];

    /**
     * Get the civilization that this leader belongs to.
     *
     * @return BelongsTo
     */
    public function civilization(): BelongsTo
    {
        return $this->belongsTo(Civilization::class);
    }

    /**
     * Get the historical information for the leader.
     *
     * Note: Using custom morph columns 'type' and 'taxonomy_id'.
     *
     * @return MorphMany
     */
    public function historicalInfo(): MorphMany
    {
        return $this->morphMany(HistoricalInfo::class, 'historable', 'type', 'taxonomy_id');
    }
}
