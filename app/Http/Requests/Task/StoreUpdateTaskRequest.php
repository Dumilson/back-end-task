<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'users_id' => 'required|array',
            'users_id.*' => 'exists:users,id',
            'deadline' => 'required|date|after:today',
        ];
    }

    public function messages()
    {
        return [
            'deadline.after' => 'O campo Data final deve ser uma data posterior a hoje'
        ];
    }
    public function attributes()
    {
        return [
            "deadline" => "Data final",
            "users_id" => "Usuario(s)"
        ];
    }
}
