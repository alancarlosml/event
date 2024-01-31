<?php

declare(strict_types=1);

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('{slug}/obrigado', 'App\Http\Controllers\ConferenceController@thanks')->middleware(['auth:participante', 'verified'])->name('conference.thanks');
Route::post('{slug}/obrigado', 'App\Http\Controllers\ConferenceController@thanks')->name('conference.thanks');
Route::post('oauth', 'App\Http\Controllers\ConferenceController@oauth')->name('conference.oauth');
