<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Task 1: Route::view() for Homepage
Route::view('/', 'home');

// Task 2: Blog Post Controller (List All Posts)
Route::get('/posts', [PostController::class, 'index']);

// Task 3: Route Parameters (View Single Post)
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

// Task 5: Route Group for Admin
Route::prefix('admin')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create']);
});

// PostController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = [
            ['id' => 1, 'title' => 'First Post'],
            ['id' => 2, 'title' => 'Second Post'],
            ['id' => 3, 'title' => 'Third Post']
        ];
        return view('posts.index', compact('posts'));
    }

    public function show($id)
    {
        return view('posts.show', ['id' => $id]);
    }

    public function create()
    {
        return view('admin.create');
    }
}

// Blade Views
// resources/views/home.blade.php
// "Welcome to My Blog"

// resources/views/posts/index.blade.php
@foreach ($posts as $post)
    <a href="{{ route('posts.show', ['id' => $post['id']]) }}">{{ $post['title'] }}</a><br>
@endforeach

// resources/views/posts/show.blade.php
// "You are viewing post ID: {{ $id }}"

// resources/views/admin/create.blade.php
<form>
    <input type="text" placeholder="Title"><br>
    <textarea placeholder="Content"></textarea><br>
    <button type="submit">Submit</button>
</form>
