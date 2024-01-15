<?php

namespace Services\Reports;

use Domain\Shared\DTOs\ReportDTO;

abstract class ReportBase
{
    /** @return array<int, mixed> */
    abstract public function generate(ReportDTO $dto): array;
}
