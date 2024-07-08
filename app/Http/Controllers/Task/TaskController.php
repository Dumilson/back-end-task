<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreUpdateTaskRequest;
use App\Http\Response;
use App\Models\Task;
use App\Services\Task\TaskService;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks",
     *     summary="Lista todas as tarefas com os usuários associados",
     *     tags={"Tasks"},
     *     security={{ "sanctum":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas",
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
     *                 example="Lista de tarefas"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         example="Escrever testes automatizados puto"
     *                     ),
     *                     @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="Teste automatizado"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="deadline",
     *                         type="string",
     *                         format="date",
     *                         example="2024-07-08"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2024-07-07T15:32:33.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2024-07-07T22:38:54.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="users",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                                 example=1
     *                             ),
     *                             @OA\Property(
     *                                 property="name",
     *                                 type="string",
     *                                 example="Admin"
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sem tarefas disponiveis",
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
     *                 example="Sem tarefas disponiveis"
     *             )
     *         )
     *     )
     * )
     */


    public function index()
    {
        $stm = $this->taskService->getAllTasksUsers();
        if ($stm) {
            return Response::success("Lista de tarefas", 201, ["data" => $stm]);
        }
        return Response::error("Sem tarefas disponiveis", 404);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tasks/store",
     *     summary="Cria uma nova tarefa",
     *     tags={"Tasks"},
     *     security={{ "sanctum":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "status", "deadline"},
     *             @OA\Property(property="title", type="string", example="Título da tarefa"),
     *             @OA\Property(property="description", type="string", example="Descrição da tarefa"),
     *             @OA\Property(property="users_id", type="object", example="[1, 2]"),
     *             @OA\Property(property="deadline", type="string", format="date", example="2024-07-08")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa registrada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tarefa registrada com sucesso"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Título da tarefa"),
     *                 @OA\Property(property="description", type="string", example="Descrição da tarefa"),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="deadline", type="string", format="date", example="2024-07-08"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-08T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-08T12:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao cadastrar tarefa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao cadastrar tarefa")
     *         )
     *     )
     * )
     */

    public function store(StoreUpdateTaskRequest $storeUpdateTaskRequest)
    {
        try {
            DB::beginTransaction();
            $stm = $this->taskService->save($storeUpdateTaskRequest->all());
            DB::commit();
            return Response::success("Tarefa registrado com sucesso", 201, ["data" => $stm]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::error("Erro ao cadastrar tarefa", 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tasks/update/{task}",
     *     summary="Atualiza uma tarefa existente",
     *     tags={"Tasks"},
     *     security={{ "sanctum":{} }},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         description="ID da tarefa a ser atualizada"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "status", "deadline"},
     *             @OA\Property(property="title", type="string", example="Título atualizado da tarefa"),
     *             @OA\Property(property="description", type="string", example="Descrição atualizada da tarefa"),
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="deadline", type="string", format="date", example="2024-07-08"),
     *             @OA\Property(property="users_id", type="array", @OA\Items(type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa atualizada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tarefa atualizada com sucesso"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Título atualizado da tarefa"),
     *                 @OA\Property(property="description", type="string", example="Descrição atualizada da tarefa"),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="deadline", type="string", format="date", example="2024-07-08"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-08T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-08T12:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar tarefa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao atualizar tarefa")
     *         )
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="O recurso solicitado não foi encontrado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="O recurso solicitado não foi encontrado.")
     *         )
     *     )
     * )
     */

    public function update(StoreUpdateTaskRequest $storeUpdateTaskRequest, Task $task)
    {
        try {
            DB::beginTransaction();
            $stm = $this->taskService->update($task, $storeUpdateTaskRequest->all());
            DB::commit();
            return Response::success("Tarefa atualizada com sucesso", 201, ["data" => $stm]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::error("Erro ao atualizar tarefa", 500, $e->getMessage());
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/v1/tasks/delete/{task}",
     *     summary="Deleta uma tarefa existente",
     *     tags={"Tasks"},
     *     security={{ "sanctum":{} }},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         description="ID da tarefa a ser deletada"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa deletada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tarefa deletada"),
     *             @OA\Property(
     *                 property="data",
     *                 type="boolean",
     *                 example=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao deletar tarefa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao deletar tarefa")
     *         )
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="O recurso solicitado não foi encontrado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="O recurso solicitado não foi encontrado.")
     *         )
     *     )
     * )
     */

    public function destroy(Task $task)
    {
        $stm = $this->taskService->delete($task);
        if ($stm) {
            return Response::success("Tarefa deletada", 201, ["data" => $stm]);
        }
        return Response::error("Erro ao deletar tarefa", 500);
    }
}
