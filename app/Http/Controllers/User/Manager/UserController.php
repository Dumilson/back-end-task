<?php

namespace App\Http\Controllers\User\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Manager\StoreUserRequest;
use App\Http\Response;
use App\Models\User;
use App\Services\User\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users/store",
     *     summary="Cadastrar novo usuário",
     *     tags={"User"},
     *     security={{ "sanctum":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "isAdmin"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="isAdmin", type="boolean", example=false),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="usuário registrado com sucesso"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="isAdmin", type="boolean", example=false),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao cadastrar usuário",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Erro ao cadastrar usuário")
     *         )
     *     )
     * )
     */
    public function store(StoreUserRequest $storeUserRequest)
    {
        $stm = $this->userService->save($storeUserRequest->all());
        if ($stm) {
            return Response::success("usuário registrado com sucesso", 201, ["data" => $stm]);
        }
        return Response::error("Erro ao cadastrar usuário", 500);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/get_all_users_paginate",
     *     summary="Obter lista de usuários com paginação",
     *     tags={"User"},
     *     security={{ "sanctum":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Lista de usuários"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                     @OA\Property(property="isAdmin", type="boolean", example=false),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sem usuários",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Sem usuários")
     *         )
     *     )
     * )
     */
    public function getAllUsersPaginate()
    {
        $stm = $this->userService->getAllUsersPaginate();
        if ($stm) {
            return Response::success("Lista de usuários ", 200, ["data" => $stm]);
        }
        return Response::success("Sem usuários", 404);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/get_all_users",
     *     summary="Obter lista de todos os usuários",
     *     tags={"User"},
     *     security={{ "sanctum":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Lista de usuários"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sem usuários",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Sem usuários")
     *         )
     *     )
     * )
     */
    public function getAllUsers()
    {
        $stm = $this->userService->getAllUsers();
        if ($stm) {
            return Response::success("Lista de usuários ", 200, ["data" => $stm]);
        }
        return Response::success("Sem usuários", 404);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/get_tasks_user/{id}",
     *     summary="Listar tarefas do usuário",
     *     tags={"User"},
     *     security={{ "sanctum":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="Id do usuário"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas do usuário",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Lista de tarefas do usuário"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Admin"),
     *                     @OA\Property(
     *                         property="tasks",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="Escrever testes automatizados"),
     *                             @OA\Property(property="description", type="string", example="Teste automatizado"),
     *                             @OA\Property(property="status", type="integer", example=1),
     *                             @OA\Property(property="deadline", type="string", format="date", example="2024-07-08"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-07T15:32:33.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-07T22:38:54.000000Z")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sem tarefas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Sem tarefas"
     *             )
     *         )
     *     )
     * )
     */

    public function getAllTaskUser(int $id)
    {
        $stm = $this->userService->getAllTasksUser($id);
        if ($stm) {
            return Response::success("Lista de tarefas do usuário ", 200, ["data" => $stm]);
        }
        return Response::success("Sem tarefas", 404);
    }
}
