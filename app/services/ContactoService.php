<?php

namespace App\services;

use App\Models\Contato;
use Illuminate\Support\Facades\Log;

class ContactoService
{
    public function getAllContatos()
    {
        return Contato::where('is_active', true)->get();
    }

    public function getContatoById($id)
    {
        return Contato::findOrFail($id);
    }

    public function createContato($data)
    {
        return Contato::create($data);
    }

    public function updateContato($id, $data)
    {
        return Contato::findOrFail($id)->update($data);
    }

    public function toggleStatus($id)
    {
        return Contato::findOrFail($id)->is_active = !Contato::findOrFail($id)->is_active;
    }

    public function deleteContato($id)
    {
        return Contato::findOrFail($id)->delete();
    }
}