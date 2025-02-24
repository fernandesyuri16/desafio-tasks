<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function createToken(array $data): string
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (blank($user)) {
			throw new NotFoundHttpException('User not found.');
        }

        $validatePassword = Hash::check($data['password'], $user->password);

        if (!$validatePassword) {
            throw new UnprocessableEntityHttpException('Invalid password.');
        }

		$user->tokens()->delete();

		$token = $user->createToken($data['email'])->plainTextToken;

        Log::info("Bearer token generated for user {$user->id}.");

        return $token;
    }

    public function logout(): void
    {
        $user = $this->userRepository->findById(auth()->user()->id);

		$user->tokens()->delete();

        Log::info('User logged out.', [
            'user_id' => $user->id
        ]);
    }
}
