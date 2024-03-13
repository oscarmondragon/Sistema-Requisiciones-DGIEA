<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'apePaterno',
        'apeMaterno',
        'email',
        'password',
        'rol',
        'estatus',
        'id_usuario_sesion',
        'deleted_at'
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
        'password' => 'hashed',
    ];

    public function tipoRol()
    {
        return $this->belongsTo(RolUsuario::class, 'rol');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($usuario) {
            // Realizar la inserción en la otra tabla
            UserHistorial::create([
                'name' => $usuario->name,
                'apePaterno' => $usuario->apePaterno,
                'apeMaterno' => $usuario->apeMaterno,
                'email' => $usuario->email,
                'password' => $usuario->password,
                'estatus' => $usuario->estatus,
                'rol' => $usuario->rol,
                'id_usuario_sesion' => $usuario->id_usuario_sesion,
                'accion' => 'CREATE'
            ]);
        });

        static::updating(function ($usuario) {
            if ($usuario->original['deleted_at'] == null) {
                UserHistorial::create([
                    'name' => $usuario->name,
                    'apePaterno' => $usuario->apePaterno,
                    'apeMaterno' => $usuario->apeMaterno,
                    'email' => $usuario->email,
                    'password' => $usuario->password,
                    'estatus' => $usuario->estatus,
                    'rol' => $usuario->rol,
                    'id_usuario_sesion' => $usuario->id_usuario_sesion,
                    'accion' => 'UPDATE'
                ]);
            } else {
                UserHistorial::create([
                    'name' => $usuario->name,
                    'apePaterno' => $usuario->apePaterno,
                    'apeMaterno' => $usuario->apeMaterno,
                    'email' => $usuario->email,
                    'password' => $usuario->password,
                    'estatus' => $usuario->estatus,
                    'rol' => $usuario->rol,
                    'id_usuario_sesion' => $usuario->id_usuario_sesion,
                    'accion' => 'RESTORE'
                ]);
            }
        });

        static::deleted(function ($usuario) {
            // Realizar la inserción en la otra tabla
            UserHistorial::create([
                'name' => $usuario->name,
                'apePaterno' => $usuario->apePaterno,
                'apeMaterno' => $usuario->apeMaterno,
                'email' => $usuario->email,
                'password' => $usuario->password,
                'estatus' => $usuario->estatus,
                'rol' => $usuario->rol,
                'id_usuario_sesion' => $usuario->id_usuario_sesion,
                'deleted_at' => $usuario->deleted_at,
                'accion' => 'DELETE'
            ]);
        });
    }
}
