<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function create(array $data): User
    {
        $user = $this->userRepository->create($data);

        Log::info('User created.', [
            'user' => print_r($user->toArray(), true)
        ]);

        return $user;
    }
}
