<?php

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
*/

// all listing
Route::get('/', function () {
  return view('listings', [
    'heading' => 'Lastest Listing',
    'listings' => Listing::all(),
  ]);
});

// single listing
Route::get('/listing/{listing}', function (Listing $listing) {
  return view('listing', [
    'heading' => 'Selected Listing',
    'listing' => $listing,
  ]);
});
