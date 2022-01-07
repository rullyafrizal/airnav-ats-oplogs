<?php

namespace App\Http\Controllers;

use App\Models\OperationalLog;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index', [
            'oplogCount' => OperationalLog::query()->count()
        ]);
    }
}
