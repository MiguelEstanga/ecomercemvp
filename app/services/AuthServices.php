<?php

namespace app\services;


use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;

class AuthServices
{
  protected $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function login($email, $password)
  {
    $user = $this->userRepository->attemptLogin($email, $password);
    return $user;
  }
}
