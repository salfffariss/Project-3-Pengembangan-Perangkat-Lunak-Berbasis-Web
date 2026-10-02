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
        'start_at',
        'end_at',
        'location',
        'capacity',
        'category_id',
        'status',
    ];

    protected $attributes = [
        'status' => 'draft',
        'capacity' => 100,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function casts(): array
    {
        return [
            'start_at' => 'date',
            'end_at' => 'date',
            'capacity' => 'integer',
        ];
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q, string $keyword) {
            $q->where(function (Builder $sub) use ($keyword) {
                $sub->where('code', 'like', "%{$keyword}%")
                    ->orWhere('title', 'like', "%{$keyword}%");
            });
        });
    }

    public function scopeFilterCategory(Builder $query, ?string $categoryId): Builder
    {
        return $query->when($categoryId, function (Builder $q, $id) {
            $q->where('category_id', $id);
        });
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        $validStatuses = ['draft', 'published', 'completed'];

        return $query->when(in_array($status, $validStatuses, true), function (Builder $q) use ($status) {
            $q->where('status', $status);
        });
    }

    public function scopeSortByDate(Builder $query, ?string $sort): Builder
    {
        $direction = strtolower($sort ?? '') === 'oldest' ? 'asc' : 'desc';

        return $query->orderBy('start_at', $direction);
    }
}
