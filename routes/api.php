<?php

use App\Http\Controllers\HomePageController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/categories')->group(function (){
   Route::get('' , [CategoryController::class , 'index']);
   Route::get('/{category:slug}' , [CategoryController::class , 'show']);
});

Route::prefix('/home')->group(function (){
   Route::get('/popular/categories/products' , [HomePageController::class , 'popularCategoriesProducts']);
   Route::get('/popular/categories/{category:slug}/products' , [HomePageController::class , 'popularCategoriesChildrenProducts']);
});

Route::prefix('/products')->group(function (){
    Route::get('', [ProductController::class , 'index']);
    Route::get('/{product:slug}', [ProductController::class , 'show']);
});