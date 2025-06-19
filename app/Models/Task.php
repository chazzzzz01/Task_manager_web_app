<?php

// Declare the namespace of the model
namespace App\Models;

// Import traits and base Model class
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Task model, it represents the "tasks" table in the database
//define the eloquent model for the tasks table
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_done',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
