<?php

namespace App\Http\Controllers\User\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Manager\StoreUserRequest;
use App\Http\Response;
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
     *             @OA\Property(property="message", type="string", example="Usuario registrado com sucesso"),
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
     *             @OA\Property(property="message", type="string", example="Erro ao cadastrar usuario")
     *         )
     *     )
     * )
     */
    public function store(StoreUserRequest $storeUserRequest)
    {
        $stm = $this->userService->save($storeUserRequest->all());
        if ($stm) {
            return Response::success("Usuario registrado com sucesso", 201, ["data" => $stm]);
        }
        return Response::error("Erro ao cadastrar usuario", 500);
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
     *             @OA\Property(property="message", type="string", example="Lista de usuarios"),
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
     *             @OA\Property(property="message", type="string", example="Sem usuarios")
     *         )
     *     )
     * )
     */
    public function getAllUsersPaginate()
    {
        $stm = $this->userService->getAllUsersPaginate();
        if ($stm) {
            return Response::success("Lista de usuarios ", 200, ["data" => $stm]);
        }
        return Response::success("Sem usuarios", 404);
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
     *             @OA\Property(property="message", type="string", example="Lista de usuarios"),
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
     *             @OA\Property(property="message", type="string", example="Sem usuarios")
     *         )
     *     )
     * )
     */
    public function getAllUsers()
    {
        $stm = $this->userService->getAllUsers();
        if ($stm) {
            return Response::success("Lista de usuarios ", 200, ["data" => $stm]);
        }
        return Response::success("Sem usuarios", 404);
    }
}
