<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;

class OperationalLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function controllerInitials()
    {
        return $this->embedsMany(ControllerInitial::class);
    }

    public function operationalSpecifications()
    {
        return $this->embedsMany(OperationalSpecification::class);
    }

    public function scopeFilters(Builder $query, array $filters = [])
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $query, $search) {
                return $query->where('atc_on_duty', 'like', "%{$search}%");
            })
            ->when($filters['periodStart'] ?? null, function (Builder $query, $periodStart) {
                return $query->whereDate('date', '>=', $periodStart);
            })
            ->when($filters['periodEnd'] ?? null, function (Builder $query, $periodEnd) {
                return $query->whereDate('date', '<=', $periodEnd);
            })
            ->when($filters['shift'] ?? null, function (Builder $query, $shift) {
                return $query->where('shift', $shift);
            });
    }
}
