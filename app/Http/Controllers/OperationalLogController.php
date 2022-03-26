<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\OperationalLog\CreateOperationalLogRequest;
use App\Http\Requests\OperationalLog\UpdateOperationalLogRequest;
use App\Http\Resources\OperationalLog\OperationalLogCollection;
use App\Http\Resources\OperationalLog\OperationalLogResource;
use App\Models\OperationalLog;
use App\Services\Api\OplogService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OperationalLogController extends Controller
{
    private $oplogService;

    public function __construct(OplogService $oplogService)
    {
        $this->oplogService = $oplogService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['periodStart', 'periodEnd', 'session', 'search']);

        $oplogs = new OperationalLogCollection(
            OperationalLog::query()
                ->orderByDesc('date')
                ->filters($filters)
                ->paginate(10)
        );

        return Inertia::render('OperationalLogs/Index', [
            'oplogs' => $oplogs,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return Inertia::render('OperationalLogs/Create');
    }

    public function edit(OperationalLog $operationalLog)
    {
        return Inertia::render('OperationalLogs/Edit', [
            'oplog' => new OperationalLogResource($operationalLog),
        ]);
    }

    public function store(CreateOperationalLogRequest $request)
    {
        $this->oplogService->store($request->validated());

        return redirect()
            ->route('operational-logs.index')
            ->with('success', 'Operational log successfully created');
    }

    public function update(UpdateOperationalLogRequest $request,OperationalLog $operationalLog)
    {
        $update = $this->oplogService->update($operationalLog, $request->validated());

        return redirect()
            ->route('operational-logs.index')
            ->with($update['status'], $update['message']);
    }

    public function destroy(OperationalLog $operationalLog)
    {
        $this->oplogService->destroy($operationalLog);

        return redirect()
            ->route('operational-logs.index')
            ->with('success', 'Operational log successfully deleted');
    }

    public function export(OperationalLog $operationalLog)
    {
        return $this->oplogService->export($operationalLog);
    }


}
