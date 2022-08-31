<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Common Resource Routes:
| index - Show all listings
| show - Show single listing
| create - Show form to create new listing
| store - Store new listing
| edit - Show form to edit listing
| update - Update listing
| destroy - Delete listing
*/

// show all listing page
Route::get('/', [ListingController::class, 'index']);

// show create listing form
Route::get('/listings/create', [ListingController::class, 'create'])
  ->middleware('auth');

// store listing data
Route::post('/listings', [ListingController::class, 'store'])
  ->middleware('auth');

// show edit listing form
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])
  ->middleware('auth');

// update listing data
Route::put('/listings/{listing}', [ListingController::class, 'update'])
  ->middleware('auth');

// destroy listing data
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])
  ->middleware('auth');

// show single listing
Route::get('/listings/{listing}', [ListingController::class, 'show']);

// show register user form
Route::get('/register', [UserController::class, 'create'])
  ->middleware('guest');

// store user data
Route::post('/users', [UserController::class, 'store']);

// show user login form
Route::get('/login', [UserController::class, 'login']);

// user login
Route::post('/login', [UserController::class, 'authenticate'])
  ->name('login')
  ->middleware('guest');

// user logout
Route::post('/logout', [UserController::class, 'logout'])
  ->middleware('auth');
