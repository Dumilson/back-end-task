<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\UserTask;
use PhpParser\Node\Expr\Cast\Object_;

class TaskService
{
  protected $repository;
  protected $repositoryUserService;

  public function __construct(Task $task, UserTask $userTask)
  {
    $this->repository = $task;
    $this->repositoryUserService = $userTask;
  }

  public function save(array  $data): Task
  {
    $stmt = $this->repository->create([
      'title' => $data['title'],
      'description' => $data['description'],
      'deadline'  => $data['deadline']
    ]);

    foreach ($data['users_id'] as $id_user) {
      $this->repositoryUserService->create([
        'user_id' => $id_user,
        'task_id' => $stmt->id,
      ]);
    }

    return $stmt;
  }

  public function update(Task $task, array $data): bool
  {       
    $stmt = $this->repository->find($task->id)->update($data);
    $this->repositoryUserService->where('task_id', $task->id)->delete();
    foreach ($data['users_id'] as $id_user) {
      $this->repositoryUserService->create([
        'user_id' => $id_user,
        'task_id' => $task->id,
      ]);
    }

    return $stmt;
  }

  public function updateStatusCompleted(Task $task): bool
  {
    $stmt = $this->repository->find($task->id);
    if ($stmt) {
      $stmt->status = false;
      $stmt->save();
      return true;
    }
    return false;
  }

  public function delete(Task $task): bool
  {
    $this->repositoryUserService->where('task_id', $task->id)->delete();
    return $this->repository->find($task->id)->delete();
  }

  public function getAllTasksUsers(): Object
  {
    return $this->repository->with(['users:id,name'])->get()->map(function ($task) {
      $task->users->makeHidden('pivot');
      return $task;
    });
  }
}
