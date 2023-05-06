<?php

namespace App\DataTransferObjects;

use App\Http\Requests\UpdateCustomerProfileRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;

class CustomerProfileDTO
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly ?string $document_type,
        public readonly ?string $document,
        public readonly ?string $address,
        public readonly ?string $phone,
        public readonly ?string $cell_phone,
    ) {
    }

    public static function fromHttpRequest(Request $request): CustomerProfileDTO
    {
        return new self(
            first_name: $request->input('first_name'),
            last_name: $request->input('last_name'),
            document_type: $request->input('document_type', null),
            document: $request->input('document', null),
            address: $request->input('address', null),
            phone: $request->input('phone', null),
            cell_phone: $request->input('cell_phone', null),
        );
    }

    public static function fromUpdateCustomerRequest(UpdateCustomerRequest $request): CustomerProfileDTO
    {
        return new self(
            first_name: $request->validated('first_name'),
            last_name: $request->validated('last_name'),
            document_type: $request->validated('document_type', null),
            document: $request->validated('document', null),
            address: $request->validated('address', null),
            phone: $request->validated('phone', null),
            cell_phone: $request->validated('cell_phone', null),
        );
    }

    public static function fromUpdateProfileRequest(UpdateCustomerProfileRequest $request): CustomerProfileDTO
    {
        return new self(
            first_name: $request->validated('first_name'),
            last_name: $request->validated('last_name'),
            document_type: $request->validated('document_type', null),
            document: $request->validated('document', null),
            address: $request->validated('address', null),
            phone: $request->validated('phone', null),
            cell_phone: $request->validated('cell_phone', null),
        );
    }
}
