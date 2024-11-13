<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware

{
    public static function middleware(){
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::with('user')->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
                    'title'=>'required',
                    'body'=>'required',
                ]);
        $post = $request->user()->posts()->create($field);

        return ['post' => $post, 'user' => $post->user];
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return ['post' => $post, 'user' => $post->user->name];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify', $post);

        $field = $request->validate([
                    'title'=>'required',
                    'body'=>'required',
                ]);

        $post->update($field);

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);

        $post->delete();
        return ['message' => 'the post was deleted'];
    }

    
}
