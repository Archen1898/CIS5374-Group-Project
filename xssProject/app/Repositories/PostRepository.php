<?php

namespace App\Repositories;

use Exception;
use App\Models\Post;
use App\Http\Requests\PostRequest;
use App\Traits\ResponseTrait;
use Symfony\Component\HttpFoundation\Response;


class PostRepository 
{
    use ResponseTrait;

    public function create(PostRequest $postRequest)
    {
        try{
            $post = new Post();
            $newPost = $this->dataFormat($postRequest, $post);
            $newPost->save();
            return $newPost;
        }catch (Exception $exception) {
            throw new Exception($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function viewAll()
    {
        try{
            return Post::all();
        }catch (Exception $exception) {
            throw new Exception($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function dataFormat($request, Post $newPost)
    {
        $newPost->title = $request['title'];
        $newPost->content = $request['content'];
        $newPost->user_id = $request['user_id'];

        return $newPost;
    }
}