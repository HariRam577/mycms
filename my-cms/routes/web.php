<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ContentTypeController;
use App\Http\Controllers\FieldController;
use Illuminate\Support\Facades\Route;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Resource routes for content types and fields
Route::resource('content_types', ContentTypeController::class);
Route::resource('content_types.fields', FieldController::class)->shallow();
Route::resource('content_types.posts', PostController::class);
Route::resource('posts',PostController::class);

Route::patch('/posts/{postId}/toggle-publish', [PostController::class, 'togglePublish'])->name('posts.togglePublish');


Route::get('/content_types/fields/{field}/edit', [FieldController::class, 'edit'])->name('fields.edit');
Route::put('/content_types/fields/{field}', [FieldController::class, 'update'])->name('fields.update');
Route::delete('/content_types/fields/{field}', [FieldController::class, 'destroy'])->name('fields.destroy');
// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authentication and profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include authentication routes
require __DIR__.'/auth.php';
