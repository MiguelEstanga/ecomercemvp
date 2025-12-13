<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    protected $table = 'contatos';

    protected $fillable = [
        "telefono_contacto",
        "telefono_banco",
        "codigo_banco",
        "nombre_banco",
        "codigo_banco",
        "cuenta_banco",
        "is_active",
    ];
}
