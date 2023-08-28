<?php

namespace Domain\Shared\Contracts;

use Services\ReportBase;

interface ReportFactoryInterface
{
    public function initializeReport(string $type): ReportBase;
}
