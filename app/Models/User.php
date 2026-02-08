<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Obtener el identificador que se almacenará en el claim subject del JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Devolver un array de clave-valor con claims personalizados para agregar al JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role
        ];
    }

    /**
     * Verificar si el usuario es administrador
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Relación: Usuario ha creado muchos threads
     */
    public function createdThreads()
    {
        return $this->hasMany(Thread::class, 'created_by');
    }

    /**
     * Relación: Usuario participa en muchos threads (many-to-many)
     */
    public function threads()
    {
        return $this->belongsToMany(Thread::class, 'thread_participants')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    /**
     * Relación: Usuario ha escrito muchos mensajes
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
