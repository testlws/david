<?php

namespace App\Http\Controllers\Api;

use App\Todo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $todos = Todo::all();

        $coll = TodoResource::collection($todos);
        return $coll;
    }

    public function create(Request $request)
    {
        $todo = new Todo();
        $todo->name = $request->input('name');
        $todo->done = false;
        $todo->save();
    
        return response($todo, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);
        $todo->name = $request->input('name');
        $todo->done = $request->input('done');       
        $todo->end_date = empty($todo->done) ? null : date("Y-m-d H:i:s");
        $todo->save();

        return response($todo, Response::HTTP_OK);
    }

    public function delete($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return response(null, Response::HTTP_OK);
    }

}