<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'title',
        'description',
        'activity_date',
        'category',
        'status',
    ];

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
