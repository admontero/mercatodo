<?php

namespace Domain\Shared\Contracts;

interface ReportInterface
{
    public function generate(): void;
}
