<?php

namespace Services;

use Domain\OrderProduct\Models\OrderProduct;
use Domain\Shared\DTOs\ReportDTO;

class BestSellingCategoryReport extends ReportBase
{
    public function __construct(
        public readonly string $view = 'pdf.reports.best-selling-category'
    ) {}

    /** @return array<int, mixed> */
    public function generate(ReportDTO $dto): array
    {
        $data = OrderProduct::getBestSellingCategory($dto)
            ->orderBy('sales', 'DESC')
            ->orderBy('products', 'DESC')
            ->orderBy('total', 'DESC')
            ->get()
            ->toArray();

        return [$data, $this->view];
    }
}
