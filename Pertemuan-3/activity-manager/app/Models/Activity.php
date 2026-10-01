<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'code',
        'title',
        'description',
        'activity_date',
        'category_id',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
        ];
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        $validStatuses = ['Planned', 'Ongoing', 'Done'];

        return $query->when(in_array($status, $validStatuses, true), function (Builder $q) use ($status) {
            $q->where('status', $status);
        });
    }
}
