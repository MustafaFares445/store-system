<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index() {}

    public function create() {}

    public function store(Request $request) {}

    public function show(Category $productCategory) {}

    public function edit(Category $productCategory) {}

    public function update(Request $request, Category $productCategory) {}

    public function destroy(Category $productCategory) {}
}
