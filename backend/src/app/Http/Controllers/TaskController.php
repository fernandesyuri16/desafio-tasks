<?php

namespace App\Http\Controllers;

use App\Enum\HttpStatus;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Services\TaskService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $service
    )
    {
    }

    public function index(): JsonResponse
    {
        try {
            $tasks = $this->service->list();
        } catch (Exception $exception) {
            Log::error('Internal error while trying to list tasks.', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ]);

            return response()->json([
                'message' => 'Internal error while trying to list tasks.'
            ], HttpStatus::INTERNAL_SERVER_ERROR->value);
        }

        return response()->json($tasks);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $task = $this->service->getById($id);
        } catch (NotFoundHttpException $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], HttpStatus::NOT_FOUND->value);
        } catch (Exception $exception) {
            Log::error('Internal error while trying to get task.', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ]);

            return response()->json([
                'message' => 'Internal error while trying to get task.'
            ], HttpStatus::INTERNAL_SERVER_ERROR->value);
        }

        return response()->json($task);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $task = $this->service->create($data);
        } catch (UnprocessableEntityHttpException $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], HttpStatus::UNPROCESSABLE_ENTITY->value);
        } catch (Exception $exception) {
            Log::error('Internal error while trying to create task.', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ]);

            return response()->json([
                'message' => 'Internal error while trying to create task.'
            ], HttpStatus::INTERNAL_SERVER_ERROR->value);
        }

        return response()->json($task, HttpStatus::CREATED->value);
    }

    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();

            $task = $this->service->update($id, $data);
        } catch (NotFoundHttpException $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], HttpStatus::NOT_FOUND->value);
        } catch (UnprocessableEntityHttpException $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], HttpStatus::UNPROCESSABLE_ENTITY->value);
        }  catch (Exception $exception) {
            Log::error('Internal error while trying to update task.', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ]);

            return response()->json([
                'message' => 'Internal error while trying to update task.'
            ], HttpStatus::INTERNAL_SERVER_ERROR->value);
        }

        return response()->json($task);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);
        } catch (NotFoundHttpException $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], HttpStatus::NOT_FOUND->value);
        } catch (Exception $exception) {
            Log::error('Internal error while trying to delete task.', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ]);

            return response()->json([
                'message' => 'Internal error while trying to delete task.'
            ], HttpStatus::INTERNAL_SERVER_ERROR->value);
        }

        return response()->json([], HttpStatus::NO_CONTENT->value);
    }
}
