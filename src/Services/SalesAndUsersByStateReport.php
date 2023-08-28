<?php

namespace Services;

use Domain\Order\Models\Order;
use Domain\Shared\DTOs\ReportDTO;

class SalesAndUsersByStateReport extends ReportBase
{
    public function __construct(
        public readonly string $view = 'pdf.reports.sales-and-users-by-state'
    ) {
    }

    /** @return array<int, mixed> */
    public function generate(ReportDTO $dto): array
    {
        $data = Order::getCompletedOrdersAndUsersByState()
            ->orderBy('orders_completed', 'DESC')
            ->orderBy('users_num', 'DESC')
            ->get()
            ->toArray();

        return [$data, $this->view];
    }
}
