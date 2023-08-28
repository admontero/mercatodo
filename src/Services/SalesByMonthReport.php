<?php

namespace Services;

use Domain\Order\Models\Order;
use Domain\Shared\DTOs\ReportDTO;

class SalesByMonthReport extends ReportBase
{
    public function __construct(
        public readonly string $view = 'pdf.reports.sales-by-month'
    ) {}

    /** @return array<int, mixed> */
    public function generate(ReportDTO $dto): array
    {
        $data = Order::getCompletedOrdersByMonth()
            ->get()
            ->toArray();

        return [$data, $this->view];
    }
}
