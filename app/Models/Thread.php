<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject',
        'created_by',
    ];

    /**
     * Relación: Thread pertenece a un usuario (creador)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relación: Thread tiene muchos mensajes
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Relación: Thread tiene muchos participantes (many-to-many)
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'thread_participants')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    /**
     * Obtener el último mensaje del thread
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
