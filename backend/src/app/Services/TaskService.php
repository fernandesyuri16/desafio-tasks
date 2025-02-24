<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository
    )
    {
    }

    public function list(): Collection
    {
        return $this->taskRepository->getAll();
    }

    public function create(array $data): Task
    {
        $this->dueDateIsValid($data['due_date']);

        $data['user_id'] = auth()->user()->id;

        $task = $this->taskRepository->create($data);

        Log::info('Task created.', [
            'task' => print_r($task->toArray(), true),
            'user_id' => $task->user_id
        ]);

        return $task;
    }

    public function update(int $id, array $data): Task
    {
        if (isset($data['due_date'])) {
            $this->dueDateIsValid($data['due_date']);
        }

        $task = $this->getById($id);

        $updatedTask = $this->taskRepository->update($task, $data);

        Log::info('Task updated.', [
            'task' => print_r($updatedTask->toArray(), true),
            'user_id' => $updatedTask->user_id
        ]);

        return $updatedTask;
    }

    public function delete(int $id): void
    {
        $task = $this->getById($id);

        $this->taskRepository->delete($task);

        Log::info('Task deleted.', [
            'task_id' => $id,
            'user_id' => $task->user_id
        ]);
    }

    public function getById(int $id): Task
    {
        $task = $this->taskRepository->getById($id);

        if (blank($task)) {
            throw new NotFoundHttpException('Task not found.');
        }

        return $task;
    }

    private function dueDateIsValid(string $dueDate): void
    {
        $carbonDueDate = Carbon::parse($dueDate);

        if ($carbonDueDate->isBefore(Carbon::today())) {
            throw new UnprocessableEntityHttpException('Due date must be after today.');
        }
    }
}
