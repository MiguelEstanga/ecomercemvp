<?php 

namespace App\services;
use App\Repositories\UserRepository;
use App\Models\User;
 

class UserServices
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateUser( $userID , array $data)
    {
        $user = User::find($userID);
        return $this->userRepository->updateUser($user , $data);
    }
}