<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThreadParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'thread_id',
        'user_id',
        'last_read_at',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
    ];

    /**
     * Relación: Participante pertenece a un thread
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Relación: Participante pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
