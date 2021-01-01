<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinkResource;
use App\Link;

class LinkController extends Controller
{
    public function index()
    {
        return LinkResource::collection(Link::orderBy('title')->get());
    }

    public function byCategory($category_id)
    {
        return LinkResource::collection(Link::where('category_id', $category_id)->get());
    }
    
}