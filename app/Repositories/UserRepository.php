<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

// Implementa la interfaz que definimos
class UserRepository implements UserRepositoryInterface
{
    protected $model;

    // Inyección del modelo User en el constructor
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    // ------------------------------------------------------------------
    // Métodos CRUD básicos
    // ------------------------------------------------------------------

    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    public function findBy(string $field, $value): ?User
    {
        return $this->model->where($field, $value)->first();
    }

    public function save(array $data): User
    {
        // En una aplicación real, probablemente usarías $this->model->updateOrCreate(...)
        // pero para el ejemplo simple:
        return $this->model->create($data);
    }

    // ------------------------------------------------------------------
    // Lógica específica de Dominio (Autenticación)
    // ------------------------------------------------------------------

    public function attemptLogin(string $email, string $password): ?User
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Devuelve el objeto User si la autenticación fue exitosa
            return Auth::user();
        }

        return null;
    }

    public function updatePassword(User $user, string $password): bool
    {
        // En una aplicación real, probablemente usarías $user->update(...)
        // pero para el ejemplo simple:
        $user->password = bcrypt($password);
        return $user->save();
    }

    public function updateUser(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh(); // Refresca desde la BD
    }
}
