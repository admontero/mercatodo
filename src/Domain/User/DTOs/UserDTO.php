<?php

namespace Domain\User\DTOs;

use Illuminate\Http\Request;

class UserDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }

    public static function fromHttpRequest(Request $request): self
    {
        return new self(
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }
}
