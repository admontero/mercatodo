<?php

namespace App\Services;

use App\DataTransferObjects\CustomerProfileDTO;
use App\DataTransferObjects\UserDTO;
use App\Models\CustomerProfile;
use App\Models\User;
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
