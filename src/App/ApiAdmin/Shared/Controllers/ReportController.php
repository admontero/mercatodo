<?php

namespace App\ApiAdmin\Shared\Controllers;

use App\ApiAdmin\Shared\Jobs\GenerateReportJob;
use App\ApiAdmin\Shared\Requests\ReportRequest;
use App\Controller;
use Domain\Shared\Contracts\ReportFactoryInterface;
use Domain\Shared\DTOs\ReportDTO;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function store(ReportRequest $request, ReportFactoryInterface $reportFactory): JsonResponse
    {
        $this->authorize('generateReport', new User());

        dispatch(new GenerateReportJob($request->user(), ReportDTO::fromStoreRequest($request), $reportFactory));

        return response()->json(['message' => 'Report Completed'], 200);
    }
}
