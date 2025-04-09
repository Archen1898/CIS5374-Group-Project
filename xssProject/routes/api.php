<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api'); */


require __DIR__.'/api/user.php';
require __DIR__.'/api/post.php';
require __DIR__.'/api/comment.php';


    

    
Route::post('/add_post', function(){
    return view('UserPost');
});
Route::get('/view_posts', function(){
    return view('ViewPosts');
});


Route::get('/login', function(){
    return view('UserLogin');
});
Route::controller(AuthController::class)->group(function () {
    Route::post('/login-user', 'login');
});