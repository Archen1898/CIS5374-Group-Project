<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api'); */

Route::middleware('auth:api')->group(function () { 
    require __DIR__.'/api/user.php';
    require __DIR__.'/api/post.php';
    require __DIR__.'/api/comment.php';

    Route::get('/login', function(){
        return view('UserLogin');
    });
    Route::get('/add_post', function(){
        return view('UserPost');
    });
    Route::get('/view_posts', function(){
        return view('ViewPosts');
    });
});
