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

// Rota SEM ->name() para não conflitar com conference.thanks do web.php.
// Necessária porque o fetch() do JS pode chegar via /api/ (cache de browser).
Route::post('{slug}/obrigado', 'App\Http\Controllers\ConferenceController@thanks')->middleware(['web', 'auth:participante', 'verified']);
Route::get('oauth', 'App\Http\Controllers\ConferenceController@oauth')->name('conference.oauth');

Route::post('painel/meus-eventos/mensagens/marcar-como-lida', 'App\Http\Controllers\EventAdminController@marcarComoLida')->middleware(['auth:participante', 'verified'])->name('event_home.marcar_como_lida');
Route::post('painel/meus-eventos/mensagens/marcar-como-nao-lida', 'App\Http\Controllers\EventAdminController@marcarComoNaoLida')->middleware(['auth:participante', 'verified'])->name('event_home.marcar_como_nao_lida');
Route::post('painel/meus-eventos/mensagens/deletar', 'App\Http\Controllers\EventAdminController@deletarMensagens')->middleware(['auth:participante', 'verified'])->name('event_home.deletar_mensagens');
