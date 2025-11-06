<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\services\UserServices;
class UserController extends Controller
{
    private $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }
    public function update(Request $request  , $userId)
    {
       try{
           
           $data = $request->only('name', 'email');
           $user = $this->userServices->updateUser( $userId , $data);
           return response()->json(['success' => true]);
       }catch(\Exception $e){
           return response()->json(['error' => $e->getMessage()], 401);
       }
    }
}
