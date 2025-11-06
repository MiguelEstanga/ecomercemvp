<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\CommentServices;
use Illuminate\Support\Facades\Log;
use Exception;
class CommentController extends Controller
{
    private $commentServices;
    public function __construct(CommentServices $commentServices)
    {
        $this->commentServices = $commentServices;
    }

    public function create(Request $request)
    {
      
    }
}
