<?php

namespace App\Services;

use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'buyer',
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        if ($user->role === 'seller') {
            Store::create([
                'user_id' => $user->id,
                'name' => 'Toko ' . $user->name,
                'description' => 'Toko milik ' . $user->name,
            ]);
        }

        return $user;
    }

    public function authenticate(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kombinasi email dan password tidak cocok.'],
            ]);
        }

        return $user;
    }
}
