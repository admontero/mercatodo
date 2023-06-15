<?php

namespace Domain\CustomerProfile\DTOs;

use App\ApiAdmin\User\Requests\UpdateCustomerRequest;
use App\ApiCustomer\User\Requests\UpdateCustomerProfileRequest;
use Illuminate\Http\Request;

class CustomerProfileDTO
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly ?string $document_type,
        public readonly ?string $document,
        public readonly ?string $address,
        public readonly ?int $country_id,
        public readonly ?int $state_id,
        public readonly ?int $city_id,
        public readonly ?string $phone,
        public readonly ?string $cell_phone,
    ) {
    }

    public static function fromHttpRequest(Request $request): self
    {
        return new self(
            first_name: $request->input('first_name'),
            last_name: $request->input('last_name'),
            document_type: $request->input('document_type', null),
            document: $request->input('document', null),
            country_id: $request->input('country_id', null),
            state_id: $request->input('state_id', null),
            city_id: $request->input('city_id', null),
            address: $request->input('address', null),
            phone: $request->input('phone', null),
            cell_phone: $request->input('cell_phone', null),
        );
    }

    public static function fromUpdateCustomerRequest(UpdateCustomerRequest $request): self
    {
        return new self(
            first_name: $request->validated('first_name'),
            last_name: $request->validated('last_name'),
            document_type: $request->validated('document_type', null),
            country_id: $request->input('country_id', null),
            state_id: $request->input('state_id', null),
            city_id: $request->input('city_id', null),
            document: $request->validated('document', null),
            address: $request->validated('address', null),
            phone: $request->validated('phone', null),
            cell_phone: $request->validated('cell_phone', null),
        );
    }

    public static function fromUpdateProfileRequest(UpdateCustomerProfileRequest $request): self
    {
        return new self(
            first_name: $request->validated('first_name'),
            last_name: $request->validated('last_name'),
            document_type: $request->validated('document_type', null),
            document: $request->validated('document', null),
            country_id: $request->input('country_id', null),
            state_id: $request->input('state_id', null),
            city_id: $request->input('city_id', null),
            address: $request->validated('address', null),
            phone: $request->validated('phone', null),
            cell_phone: $request->validated('cell_phone', null),
        );
    }
}
