<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/loadPost','AuthController@loadPost');
Route::get('/getAllPost','AuthController@getAllPost');
Route::get('/getLastID', 'AuthController@getLastID');
Route::get('/getImgUrl/{id}', 'AuthController@getImgUrl');
Route::delete('/delete/{id}','AuthController@deletePost');
Route::post('/createPost','AuthController@createPost');
Route::patch('/updatePost/{detail}','AuthController@updatePost');
Route::post('/comment','AuthController@comment');
Route::get('/detailID','AuthController@detailid');
Route::get('/welcome','AuthController@index');
Route::post('/update/{id}','AuthController@updateid');

Route::get('/detailComment','AuthController@detailcomment');

// Route::post('/login','AuthController@userLogin');
// Route::post('/signup','AuthController@signUp');

// Route::post('/admin','AuthController@adminLogin');