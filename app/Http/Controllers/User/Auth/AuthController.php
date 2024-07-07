<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Response;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;


/**
 * @OA\Info(
 *     title="TODO ENDPOINTS",
 *     version="1.0.0",
 *     description="EndPoitnts da API"
 * )
 */

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth",
     *     summary="Login do usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@admin.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sessão iniciada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="1|abcdefg123456"),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="user@example.com")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="E-mail ou senha incorreto",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="E-mail ou senha incorreto")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();
            $token = $user->createToken("APP_TODO_DO");

            return Response::success('Sessão iniciada com sucesso', 200, [
                "token" => $token->plainTextToken,
                "user" => $user
            ]);
        }
        return Response::error('E-mail ou senha incorreto', 401);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Logout do usuário",
     *     tags={"Auth"},
     *     security={{ "sanctum":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Sessão Terminada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Sessão Terminada")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return Response::success("Sessão Terminada");
    }
}
