<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

// Implementa la interfaz que definimos
class UserRepository implements UserRepositoryInterface
{
    protected $model;

    // Inyección del modelo User en el constructor
    public function __construct(User $model)
    {
        $this->model = $model;
    }
    public function create($data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();
        return $user;
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

    public function updateProfile(User $user, array $data): Profile
    {
        Log::info('Update profile', ['data' => $data, 'user_id' => $user->id]);
        $profile = Profile::where('user_id', $user->id)->first();
        // Si no tiene perfil, crearlo
        if (!isset($profile)) {
            $profile = Profile::create([
                'user_id' => $user->id,
                'phone' => $data['phone'] ?? null,
                'country' => $data['country'] ?? null,
                'city' => $data['city'] ?? null,
                'address' => $data['address'] ?? null,
                'dni' => $data['dni'] ?? null,
                'avatar' => $data['avatar'] ?? null,
            ]);

            return $profile;
        }

        // Si ya tiene perfil, actualizarlo

        $profile->phone = $data['phone'] ?? null;
        $profile->country = $data['country'] ?? null;
        $profile->city = $data['city'] ?? null;
        $profile->address = $data['address'] ?? null;
        $profile->dni = $data['dni'] ?? null;
        if (isset($data['avatar'])) {
            $profile->avatar = $data['avatar'];
        }
        $profile->save();

        return $profile->fresh(); // Refrescar desde la BD
    }

    public function getProfile(User $user): ?Profile
    {
        $profile = Profile::where('user_id', $user->id)->first();
        return  $profile;
    }
}
