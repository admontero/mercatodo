<?php

namespace Services;

use Domain\OrderProduct\Models\OrderProduct;
use Domain\Shared\DTOs\ReportDTO;

class BestSellingProductReport extends ReportBase
{
    public function __construct(
        public readonly string $view = 'pdf.reports.best-selling-product'
    ) {
    }

    /** @return array<int, mixed> */
    public function generate(ReportDTO $dto): array
    {
        $data = OrderProduct::getBestSellingProduct($dto)
            ->orderBy('orders_completed', 'DESC')
            ->orderBy('units_purchased', 'DESC')
            ->get()
            ->toArray();

        return [$data, $this->view];
    }
}
