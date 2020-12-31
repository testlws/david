<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Category;
use App\Link;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::withCount('links')->orderBy('title')->get());
    }
}
