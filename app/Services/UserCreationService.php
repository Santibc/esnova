<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserCreationService
{
    /**
     * Crea un nuevo usuario.
     */
    public function create(array $userData): User
    {
        return User::create([
            'name' => $userData['name'] ?? $userData['email'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password'] ?? '12345678')
        ]);
    }

    /**
     * Actualiza un usuario existente.
     */
    public function update(User $user, array $userData): User
    {
        $user->name = $userData['name'] ?? $user->name;
        $user->email = $userData['email'] ?? $user->email;

        if (!empty($userData['password'])) {
            $user->password = Hash::make($userData['password']);
        }
        $user->save();

        return $user;
    }
}
