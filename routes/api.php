<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/mypost', function(Request $request){
    return Response::json([
        'post'=>[
        'id' => '1234' ,
        'first_name'=> 'John',
        'middle_name' => 'Ryan',
        'last_name' => 'ogbe'
        ]
    ]);
});

Route::get('/blog', function (Request $request) {
    return Response::json([
        'post'=>[
            'id'=> '67',
            'title' => 'first blog',
            'description' => '##description',
            'content' => 'lorem oposum'

        ]]);
});
