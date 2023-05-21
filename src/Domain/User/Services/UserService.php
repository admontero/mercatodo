<?php

namespace Domain\User\Services;

use Domain\CustomerProfile\DTOs\CustomerProfileDTO;
use Domain\CustomerProfile\Models\CustomerProfile;
use Domain\User\DTOs\UserDTO;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(UserDTO $dto): User
    {
        return User::create([
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);
    }

    public function assignRole(User $user, string $role): User
    {
        return $user->assignRole($role);
    }

    public function createCustomerProfile(CustomerProfileDTO $dto, User $user): User
    {
        $profile = CustomerProfile::create([
            'first_name' => $dto->first_name,
            'last_name' => $dto->last_name,
        ]);

        $profile->user()->save($user);

        return $user;
    }

    public function updateCustomerProfile(CustomerProfileDTO $dto, User $user): User
    {
        $user->profileable()->update([
            'first_name' => $dto->first_name,
            'last_name' => $dto->last_name,
            'document_type' => $dto->document_type,
            'document' => $dto->document,
            'address' => $dto->address,
            'phone' => $dto->phone,
            'cell_phone' => $dto->cell_phone,
        ]);

        $user->refresh();

        return $user;
    }
}
