<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HistoricalInfo extends Model
{
    protected $table = 'historical_info';

    protected $fillable = [
        'taxonomy_id',
        'heading',
        'text',
        'type',
    ];

    /**
     * Get the owning model (either a Leader or a Civilization) for this historical info.
     *
     * Note: We use custom column names: 'type' for the model class and 'taxonomy_id' for the foreign key.
     *
     * @return MorphTo
     */
    public function historable(): MorphTo
    {
        return $this->morphTo(null, 'type', 'taxonomy_id');
    }
}
