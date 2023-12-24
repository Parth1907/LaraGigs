<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
    Common Resource Routes 

    index - show all listings
    show- show single listing
    create- show form to create new listing
    store- store new listing
    edit- show form to edit listing
    update- update listing
    destroy- delete listing
*/

// All listings
Route::get('/', [ListingController::class, 'index']);

// Show create form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

// Store Listing Data
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

// Show Edit Form
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

// Update listing
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

// Delete Listing
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');
;

// Manage Listings
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

// Get datatable info
Route::post('/listings/datatable', [ListingController::class, 'datatable'])->middleware('auth');

// Get datatable view
Route::get('/listings/listData', [ListingController::class, 'list'])->middleware('auth');

// Single listing
// by route model binding
Route::get('/listings/{listing}', [ListingController::class, 'show']);

// Show Register/Create Form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// Create new user
Route::post('/users', [UserController::class, 'store']);

// Log User Out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// Show Login Form
Route::get('/login', [UserController::class, 'login'])
    ->name('login')
    ->middleware('guest');

// Log In user
Route::post('/users/authenticate', [UserController::class, 'authenticate']);






// the ususal way
// Route::get('/listings/{id}', function ($id) {
//     $listing = Listing::find($id);
//     if ($listing) {
//         return view('listing', [
//             'listing' => $listing
//         ]);
//     } else {
//         abort('404');
//     }
// });






// //Practise routes
// Route::get('/hello', function () {
//     // return response('Hello World');
//     return ('<h1>Hello World</h1>');
//     /*
//     return response('<h1>Hello World</h1>', 200)
//         ->header('Content-Type', 'text/plain');
//     */
// });

// Route::get('/post/{id}', function ($id) {
//     return response('Post ' . $id);
// })->where('id', '[0-9]+');

// //for debugging we can use the function dd() which shows us the properties of the obj for debugging

// Route::get('/search', function (Request $request) {
//     // dd($request);
//     return 'User name: ' . $request->name . ' City:' . $request->city;
// });