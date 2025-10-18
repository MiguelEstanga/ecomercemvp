<?php
namespace App\Repositories;
use App\Models\PinchupAgencies;
class AgenciesRepository{
  
  private $agencies;

  public function __construct(PinchupAgencies $agencies)
  {
    $this->agencies = $agencies;
  }

  public function get(){
    return $this->agencies->all();
  }

  public function findId($id){
    return $this->agencies->find($id);
  }
}