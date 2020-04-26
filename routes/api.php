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

Route::apiResource('/site', 'SiteController');
Route::post('/site/{site}/git', 'SiteGitController@store')->name('git.store');
Route::put('/site/{site}/git', 'SiteGitController@update')->name('git.update');
Route::delete('/site/{site}/git', 'SiteGitController@destroy')->name('git.destroy');
Route::get('/site/{site}/deployment/script', 'SiteDeploymentScriptController@show')->name('script.show');
Route::put('/site/{site}/deployment/script', 'SiteDeploymentScriptController@store')->name('script.store');
