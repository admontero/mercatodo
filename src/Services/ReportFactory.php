<?php

namespace Services;

use Domain\Shared\Contracts\ReportFactoryInterface;
use Exception;

class ReportFactory implements ReportFactoryInterface
{
    public function initializeReport(string $type): ReportBase
    {
        return match ($type) {
            'BestSellingProduct' => new BestSellingProductReport(),
            'BestSellingCategory' => new BestSellingCategoryReport(),
            'BestBuyer' => new BestBuyerReport(),
            'SalesAndUsersByState' => new SalesAndUsersByStateReport(),
            'SalesByMonth' => new SalesByMonthReport(),
            default => throw new Exception('Tipo de reporte no existente'),
        };
    }
}
