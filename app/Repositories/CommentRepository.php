<?php

namespace App\Repositories;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentRepository
{
    private $comment;

    function __construct(Comment $comment)
    {
        $this->comment =  $comment;
    }

    public function create($data)
    {
        return DB::transaction(function () use ($data) {
            return $this->comment->create($data);
        });
    }

    public function findAll($product_id)
    {
         return DB::transaction(function () use ($product_id) {
            return $this->comment->where('product_id' , $product_id)->get();
        }); 
    }

    public function delete($product_id)
    {
        return DB::transaction(function () use ($product_id){
            return $this->delete($product_id);
        });
    }
}
