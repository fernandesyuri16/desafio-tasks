<?php

namespace App\Http\Controllers;

use App\Enum\HttpStatus;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $service
    )
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $token = $this->service->createToken($data);
        } catch (NotFoundHttpException $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], HttpStatus::NOT_FOUND->value);
        } catch (UnprocessableEntityHttpException $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], HttpStatus::UNPROCESSABLE_ENTITY->value);
        } catch (Exception $exception) {
            Log::error('Internal error while creating token', [
                'error' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
            ]);

            return response()->json([
                'error' => 'An internal error occurred while logging in.'
            ], HttpStatus::INTERNAL_SERVER_ERROR->value);
        }

        return response()->json([
            'token' => $token
        ], HttpStatus::CREATED->value);
    }

    public function logout(): JsonResponse
    {
        try {
            $this->service->logout();
        } catch (Exception $exception) {
            Log::error('Internal error while logging out', [
                'error' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
            ]);

            return response()->json([
                'error' => 'An internal error occurred while logging out.'
            ], HttpStatus::INTERNAL_SERVER_ERROR->value);
        }

        return response()->json([], HttpStatus::NO_CONTENT->value);
    }
}
