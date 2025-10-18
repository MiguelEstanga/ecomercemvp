<?php

namespace app\services;
use App\Repositories\AgenciesRepository;
class AgenciesServices{
  private $agenciesRepository;
  public function __construct(AgenciesRepository $agenciesRepository){
    $this->agenciesRepository = $agenciesRepository;
  }

  public function all()
  {
    return $this->agenciesRepository->get();
  }

  public function findId($id)
  {
    $agency = $this->agenciesRepository->findId($id);
    if(!$agency){
      return null;
    }
    return $agency;
  }
}