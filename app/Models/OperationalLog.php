<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function cadets()
    {
        return $this->embedsMany(Cadet::class);
    }

    public function lecturers()
    {
        return $this->embedsMany(Lecturer::class);
    }

    public function operationalSpecifications()
    {
        return $this->embedsMany(OperationalSpecification::class);
    }

    public function scopeFilters(Builder $query, array $filters = [])
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $query, $search) {
                return $query->where('cadet_on_duty', 'like', "%{$search}%");
            })
            ->when($filters['periodStart'] ?? null, function (Builder $query, $periodStart) {
                return $query->whereDate('date', '>=', $periodStart);
            })
            ->when($filters['periodEnd'] ?? null, function (Builder $query, $periodEnd) {
                return $query->whereDate('date', '<=', $periodEnd);
            })
            ->when($filters['session'] ?? null, function (Builder $query, $session) {
                return $query->where('session', $session);
            });
    }

    public static function countPerMonth($year): array {
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $start_date = Carbon::createFromDate($year, "$i")->startOfMonth();
            $end_date = Carbon::createFromDate($year, "$i")->endOfMonth();
            $data[] = self::query()
                ->whereDate('date', '>=', $start_date)
                ->whereDate('date', '<=', $end_date)
                ->count();
        }

        return $data;
    }

    public static function year() {
        $yearsRaw = [];
        $yearsRaw[] = self::query()
            ->select(['date'])
            ->get()
            ->groupBy(function ($oplog) {
                return Carbon::parse($oplog->date)->format('Y');
            });

        $years = [];

        foreach ($yearsRaw[0] as $key => $value) {
            $years[] = $key;
        }

        return $years;
    }
}
