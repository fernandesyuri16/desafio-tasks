<?php

namespace App\Http\Controllers;

use App\Enum\HttpStatus;
use App\Http\Requests\User\StoreUserRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(
        private UserService $service
    )
    {
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $user = $this->service->create($data);
        } catch (Exception $exception) {
            Log::error('Internal error while creating user', [
                'error' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
            ]);

            return response()->json([
                'error' => 'Internal Server Error'
            ], HttpStatus::INTERNAL_SERVER_ERROR->value);
        }

        return response()->json($user, HttpStatus::CREATED->value);
    }
}
