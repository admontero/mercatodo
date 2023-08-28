<?php

namespace Services;

use Domain\Order\Models\Order;
use Domain\Shared\DTOs\ReportDTO;

class BestBuyerReport extends ReportBase
{
    public function __construct(
        public readonly string $view = 'pdf.reports.best-buyer'
    ) {}

    /** @return array<int, mixed> */
    public function generate(ReportDTO $dto): array
    {
        $data = Order::getBestBuyer($dto)
            ->orderBy('orders_completed', 'DESC')
            ->orderBy('total', 'DESC')
            ->get()
            ->toArray();

        return [$data, $this->view];
    }
}
