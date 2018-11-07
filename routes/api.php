<?php

use App\Task;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});

Route::get('/v1/tasks','Api\TasksController@index'); //browser

Route::get('/v1/tasks/{task}','Api\TasksController@show'); //read
Route::delete('/v1/tasks/{task}','Api\TasksController@destroy'); //delete
Route::post('/v1/tasks','Api\TasksController@store'); //create
Route::put('/v1/tasks/{task}','Api\TasksController@edit'); //edit
Route::get('/v1/tags','Api\TagsController@show');
Route::post('/v1/tags','Api\TagsController@store');
Route::delete('/v1/tags/{tag}','Api\TagsController@destroy');
Route::put('/v1/tags/{tag}','Api\TagsController@update');

