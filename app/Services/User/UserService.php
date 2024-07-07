<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
  protected $repository;

  public function __construct(User $user)
  {
    $this->repository = $user;
  }

  public function save(array $data): User
  {
    $data['password'] = Hash::make($data['password']);
    return $this->repository->create($data);
  }

  public function getAllUsersPaginate(): Object
  {
    return $this->repository->paginate(20);
  }

  public function getAllUsers(): Object
  {
    return $this->repository->select('id','name')->get();
  }

  public function delete(int $id): bool
  {
    return $this->repository->findOrFail($id)->delete( );
  }
}
