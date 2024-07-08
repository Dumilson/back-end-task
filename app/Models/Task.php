<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = "tasks";
    protected $fillable = [
        'id',
        'title',
        'description',
        'status',
        'deadline',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tasks');
    }
    
}
