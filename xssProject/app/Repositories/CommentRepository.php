<?php

namespace App\Repositories;

use Exception;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\Response;


class CommentRepository 
{
    public function create(array $data)
    {
        try{
            $comment = new Comment();
            $newComment = $this->dataFormat($data, $comment);
    
            return $newComment;
        }catch (Exception $exception) {
            throw new Exception($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function viewComments($id)
    {
        try {
            return Comment::where('post_id', $id)->orderBy('created_at', 'desc')->get();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


    public function dataFormat(array $request, Comment $newComment)
    {
        $newComment->post_id = $request['post_id'];
        $newComment->comment = $request['comment'];
        $newComment->user_id = $request['user_id'];

        return $newComment;
    }
}