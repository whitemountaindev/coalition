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

// Home Page
Route::get('/', 'App\Http\Controllers\PageController@index')->name('index');

// Task CRUD
Route::post('/createTask', 'App\Http\Controllers\PageController@createTask')->name('create_task');
Route::patch('/editTask', 'App\Http\Controllers\PageController@editTask')->name('edit_task');
Route::get('/deleteTask/{id}', 'App\Http\Controllers\PageController@deleteTask')->name('delete_task');

// Project CRUD
Route::post('/createProject', 'App\Http\Controllers\PageController@createProject')->name('create_project');


//Sortable Functionality
Route::post('sort', '\Rutorika\Sortable\SortableController@sort');