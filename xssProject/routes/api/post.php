<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(PostController::class)->group(function () {
    Route::post('/post/create', 'createPost');
    Route::get('/post/index', 'indexPosts');
});