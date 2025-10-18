<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Busca un usuario por su ID.
     */
    public function findById(int $id): ?User;

    /**
     * Busca un usuario por un campo específico (ej: email).
     */
    public function findBy(string $field, $value): ?User;
    
    /**
     * Guarda un nuevo usuario o actualiza uno existente.
     */
    public function save(array $data): User;

    /**
     * Intenta autenticar a un usuario con credenciales, devolviendo el objeto User si tiene éxito.
     */
    public function attemptLogin(string $email, string $password): ?User;
}