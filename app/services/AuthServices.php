<?php

namespace app\services;


use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;


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



  public function register($data) 
  {
    try {
      $user = $this->userRepository->create($data);

      return $user;
       
    } catch (\Exception $e) {
      Log::error('Error al crear el usuario: ' . $e->getMessage());
       return $e->getMessage() ;
      
    }
  }
}
