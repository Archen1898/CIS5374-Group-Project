<?php

use Dom\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::controller(CommentController::class)->group(function () {
    Route::post('/comment/create', 'createComment')->middleware('scan-for-xss');
    Route::get('/comment/index/{id}', 'viewPostComments')->middleware('scan-for-xss');
});