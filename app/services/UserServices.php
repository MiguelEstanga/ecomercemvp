<?php

namespace App\services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\services\FileService;


class UserServices
{
    protected $userRepository;
    private $fileService;
    public function __construct(UserRepository $userRepository, FileService $fileService)
    {
        $this->userRepository = $userRepository;
        $this->fileService = $fileService;
    }

    public function updateUser($userID, array $data)
    {
        $user = User::find($userID);
        return $this->userRepository->updateUser($user, $data);
    }

    public function updateProfile(User $user, array $data)
    {
        $user = Auth::user();
        if (isset($data['avatar'])) {
            $data['avatar'] = $this->fileService->upload($data['avatar'], 'profile', 'public');
        }

        return $this->userRepository->updateProfile($user, $data);
    }

    public function getProfile(User $user)
    {
        return $this->userRepository->getProfile($user);
    }
}
