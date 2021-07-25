<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dir', function () {
$a=Storage::disk('public')->makeDirectory(('dir1'));print_r($a);
});
Route::get('/path', function () {
print_r(public_path());
// print_r(public_url());
print_r("\n".storage_path());
});

Route::get('/dir2', function () {
if(is_dir(public_path('storage/'.'60d34cecbe3dc'))) echo 1;
});
