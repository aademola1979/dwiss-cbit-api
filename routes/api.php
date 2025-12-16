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
        'id' => 'John' 
        ]
    ]);
});
