<?php

use App\Http\Controllers\Public\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('/categories')->group(function (){
   Route::get('' , [CategoryController::class , 'index']);
   Route::get('/{category:slug}' , [CategoryController::class , 'show']);
});
