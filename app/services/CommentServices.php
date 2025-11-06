<?php

namespace App\services;

use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class CommentServices
{

    private $commentRepository;

    function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function create($data)
    {
        try {
            // Llama al repositorio para guardar en la base de datos
            $comment = $this->commentRepository->create($data);

            // DEVUELVE UN ARRAY DE PHP en caso de éxito
            return [
                'success' => true,
                'data' => $comment
            ];
        } catch (Exception $e) {
            // Registra el error
            Log::error('Error in comment create: ' . $e->getMessage());

            // DEVUELVE UN ARRAY DE PHP en caso de error
            return [
                'success' => false,
                'error' => 'Failed to create comment.' // Mensaje de error simple
            ];
        }
    }

    public function findAll($comment_id)
    {
        return  $this->commentRepository->findAll($comment_id);
    }
}
