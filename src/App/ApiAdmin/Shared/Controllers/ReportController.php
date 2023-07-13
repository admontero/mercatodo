<?php

namespace App\ApiAdmin\Shared\Controllers;

use App\ApiAdmin\Shared\Jobs\GenerateReportJob;
use App\ApiAdmin\Shared\Requests\ReportRequest;
use App\Controller;
use Domain\Order\Services\OrderService;
use Domain\OrderProduct\Services\OrderProductService;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function bestSellingProduct(ReportRequest $request, OrderProductService $orderProductService): JsonResponse
    {
        $data = $orderProductService->getBestSellingProduct($request);

        dispatch(new GenerateReportJob($request->user(), $data, 'pdf.reports.best-selling-product'));

        return response()->json(['message' => 'Report Completed'], 200);
    }

    public function BestBuyer(ReportRequest $request, OrderService $orderService): JsonResponse
    {
        $data = $orderService->getBestBuyer($request);

        dispatch(new GenerateReportJob($request->user(), $data, 'pdf.reports.best-buyer'));

        return response()->json(['message' => 'Report Completed'], 200);
    }

    public function CompletedOrdersAndUsersByState(ReportRequest $request, OrderService $orderService): JsonResponse
    {
        $data = $orderService->getCompletedOrdersAndUsersByState();

        dispatch(new GenerateReportJob($request->user(), $data, 'pdf.reports.completed-orders-and-users-by-state'));

        return response()->json(['message' => 'Report Completed'], 200);
    }

    public function CompletedOrdersByMonth(ReportRequest $request, OrderService $orderService): JsonResponse
    {
        $data = $orderService->getCompletedOrdersByMonth();

        dispatch(new GenerateReportJob($request->user(), $data, 'pdf.reports.completed-orders-by-month'));

        return response()->json(['message' => 'Report Completed'], 200);
    }
}
