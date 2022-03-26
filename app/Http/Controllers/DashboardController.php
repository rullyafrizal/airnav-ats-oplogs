<?php

namespace App\Http\Controllers;

use App\Models\OperationalLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['year']);

        $year = now()->format("Y");

        if (array_key_exists('year', $filters)) {
            $year = (int) $filters['year'];
        }

        return Inertia::render('Dashboard/Index', [
            'oplogCount' => OperationalLog::countPerMonth($year),
            'years' => OperationalLog::year(),
            'filters' => $filters
        ]);
    }
}
