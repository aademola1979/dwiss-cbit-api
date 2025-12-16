<?php

use Illuminate\Support\Facades\Route;
//use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/blog/{id}', function (Request $request) {
    dd($request);
    return Response('Hello, Ade!');
});