<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\RouteAttributes\RouteRegistrar;
use Spatie\RouteAttributes\RouteFileRegistrar;


Route::get('/tassili/router',[\Tassili\Tassili\Http\Controllers\TassiliRouter::class,'index'])->middleware('auth');
Route::get('/tassili/router/create',[\Tassili\Tassili\Http\Controllers\TassiliRouter::class,'create'])->middleware('auth');
Route::get('/tassili/router/update/{id}',[\Tassili\Tassili\Http\Controllers\TassiliRouter::class,'update'])->middleware('auth');
Route::post('/tassili/router/creator',[\Tassili\Tassili\Http\Controllers\TassiliRouter::class,'creator'])->middleware('auth');
Route::post('/tassili/router/updator',[\Tassili\Tassili\Http\Controllers\TassiliRouter::class,'updator'])->middleware('auth');
Route::post('/tassili/router/updateActive',[\Tassili\Tassili\Http\Controllers\TassiliRouter::class,'updateActive'])->middleware('auth');
Route::post('/tassili/router/delete',[\Tassili\Tassili\Http\Controllers\TassiliRouter::class,'delete'])->middleware('auth');
Route::post('/tassili/logout',[\Tassili\Tassili\Http\Controllers\TassiliRouter::class,'logout'])->middleware('auth');

(new RouteRegistrar(Route::getFacadeRoot()))
    ->useRootNamespace('App\\Http\\Controllers') // ✅ ligne importante
    ->useBasePath(app_path('Http/Controllers'))
    ->useMiddleware(['web'])
    ->registerDirectory(app_path('Http/Controllers'));
