<?php

namespace Domain\Shared\DTOs;

use App\ApiAdmin\Shared\Requests\ReportRequest;

class ReportDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int|null $records,
    ) {
    }

    public static function fromStoreRequest(ReportRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            records: $request->validated('records'),
        );
    }
}
