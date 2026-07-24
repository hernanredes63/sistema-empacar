<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'id_user',
        'nombre',
        'documento',
        'telefono',
        'email',
        'direccion',
        'ciudad',
        'state',
    ];

    // Relación: Un usuario puede estar relacionado con un cliente comercial[cite: 3]
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}