<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'thread_id',
        'user_id',
        'body',
    ];

    /**
     * Relación: Mensaje pertenece a un thread
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Relación: Mensaje pertenece a un usuario (autor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
