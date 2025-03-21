<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Civilization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
    ];

    /**
     * Get the single leader associated with the civilization.
     *
     * @return HasOne
     */
    public function leader(): HasOne
    {
        return $this->hasOne(Leader::class);
    }

    /**
     * Get the historical information for the civilization.
     *
     * Note: We're using custom morph columns 'type' and 'taxonomy_id'.
     *
     * @return MorphMany
     */
    public function historicalInfo(): MorphMany
    {
        return $this->morphMany(HistoricalInfo::class, 'historable', 'type', 'taxonomy_id');
    }
}
