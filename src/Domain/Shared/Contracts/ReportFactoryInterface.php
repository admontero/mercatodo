<?php

namespace Domain\Shared\Contracts;

use Services\Reports\ReportBase;

interface ReportFactoryInterface
{
    public function initializeReport(string $type): ReportBase;
}
