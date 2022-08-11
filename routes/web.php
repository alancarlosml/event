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

Route::get('/', 'App\Http\Controllers\HomeController@home');
Route::get('/eventos', 'App\Http\Controllers\EventHomeController@events');
Route::get('/{slug}', 'App\Http\Controllers\EventHomeController@event');
Route::post ('/contato', 'App\Http\Controllers\HomeController@contact')->name('contact');
Route::post('/get-areas','App\Http\Controllers\HomeController@getAreas');
// Route::get('/contato', 'App\Http\Controllers\HomeController@contact');



// Route::get('admin/register', [RegisteredUserController::class, 'create'])->name('register');

// Route::post('admin/register', [RegisteredUserController::class, 'store']);

// Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::get('admin/login', 'App\Http\Controllers\Admin\Auth\AuthenticatedSessionController@create')->name('admin.login');
Route::post('admin/login', 'App\Http\Controllers\Admin\Auth\AuthenticatedSessionController@store')->name('admin.login');
Route::post('admin/logout', 'App\Http\Controllers\Admin\Auth\AuthenticatedSessionController@destroy')->name('admin.logout');

Route::get('admin/dashboard', 'App\Http\Controllers\DashboardController@dashboard')->middleware(['auth'])->name('dashboard');

Route::get('admin/users/list', 'App\Http\Controllers\UserController@index')->middleware(['auth'])->name('user.index');
Route::get('admin/users/show/{id}', 'App\Http\Controllers\UserController@show')->middleware(['auth'])->name('user.show');
Route::get('admin/users/create', 'App\Http\Controllers\UserController@create')->middleware(['auth'])->name('user.create');
Route::post('admin/users/store', 'App\Http\Controllers\UserController@store')->middleware(['auth'])->name('user.store');
Route::get('admin/users/edit/{id}', 'App\Http\Controllers\UserController@edit')->middleware(['auth'])->name('user.edit');
Route::post('admin/users/update/{id}', 'App\Http\Controllers\UserController@update')->middleware(['auth'])->name('user.update');
Route::delete('admin/users/destroy/{id}', 'App\Http\Controllers\UserController@destroy')->middleware(['auth'])->name('user.destroy');

Route::get('admin/configurations/edit/', 'App\Http\Controllers\ConfigurationController@edit')->middleware(['auth'])->name('configuration.edit');
Route::post('admin/configurations/update', 'App\Http\Controllers\ConfigurationController@update')->middleware(['auth'])->name('configuration.update');

Route::get('admin/categories/list', 'App\Http\Controllers\CategoryController@index')->middleware(['auth'])->name('category.index');
Route::get('admin/categories/show/{id}', 'App\Http\Controllers\CategoryController@show')->middleware(['auth'])->name('category.show');
Route::get('admin/categories/create', 'App\Http\Controllers\CategoryController@create')->middleware(['auth'])->name('category.create');
Route::post('admin/categories/store', 'App\Http\Controllers\CategoryController@store')->middleware(['auth'])->name('category.store');
Route::get('admin/categories/edit/{id}', 'App\Http\Controllers\CategoryController@edit')->middleware(['auth'])->name('category.edit');
Route::post('admin/categories/update/{id}', 'App\Http\Controllers\CategoryController@update')->middleware(['auth'])->name('category.update');
Route::delete('admin/categories/destroy/{id}', 'App\Http\Controllers\CategoryController@destroy')->middleware(['auth'])->name('category.destroy');
Route::post('admin/categories/get-areas-by-category','App\Http\Controllers\CategoryController@getAreas')->middleware(['auth']);

Route::get('admin/categories/{category_id}/areas', 'App\Http\Controllers\AreaController@index')->middleware(['auth'])->name('area.index');
Route::get('admin/categories/{category_id}/areas/show/{id}', 'App\Http\Controllers\AreaController@show')->middleware(['auth'])->name('area.show');
Route::get('admin/categories/{category_id}/areas/create', 'App\Http\Controllers\AreaController@create')->middleware(['auth'])->name('area.create');
Route::post('admin/categories/{category_id}/areas/store', 'App\Http\Controllers\AreaController@store')->middleware(['auth'])->name('area.store');
Route::get('admin/categories/{category_id}/areas/edit/{id}', 'App\Http\Controllers\AreaController@edit')->middleware(['auth'])->name('area.edit');
Route::post('admin/categories/{category_id}/areas/update/{id}', 'App\Http\Controllers\AreaController@update')->middleware(['auth'])->name('area.update');
Route::delete('admin/categories/{category_id}/areas/destroy/{id}', 'App\Http\Controllers\AreaController@destroy')->middleware(['auth'])->name('area.destroy');

Route::get('admin/places/list', 'App\Http\Controllers\PlaceController@index')->middleware(['auth'])->name('place.index');
Route::get('admin/places/show/{id}', 'App\Http\Controllers\PlaceController@show')->middleware(['auth'])->name('place.show');
Route::get('admin/places/create', 'App\Http\Controllers\PlaceController@create')->middleware(['auth'])->name('place.create');
Route::post('admin/places/store', 'App\Http\Controllers\PlaceController@store')->middleware(['auth'])->name('place.store');
Route::get('admin/places/edit/{id}', 'App\Http\Controllers\PlaceController@edit')->middleware(['auth'])->name('place.edit');
Route::post('admin/places/update/{id}', 'App\Http\Controllers\PlaceController@update')->middleware(['auth'])->name('place.update');
Route::delete('admin/places/destroy/{id}', 'App\Http\Controllers\PlaceController@destroy')->middleware(['auth'])->name('place.destroy');
Route::post('admin/places/get-cities-by-state','App\Http\Controllers\PlaceController@getCity')->middleware(['auth']);

Route::get('admin/contacts/list', 'App\Http\Controllers\ContactController@index')->middleware(['auth'])->name('contact.index');
Route::get('admin/contacts/show/{id}', 'App\Http\Controllers\ContactController@show')->middleware(['auth'])->name('contact.show');

Route::get('admin/events/list', 'App\Http\Controllers\EventController@index')->middleware(['auth'])->name('event.index');
Route::get('admin/events/show/{id}', 'App\Http\Controllers\EventController@show')->middleware(['auth'])->name('event.show');
Route::get('admin/events/{id}/lotes', 'App\Http\Controllers\EventController@lotes')->middleware(['auth'])->name('event.lotes');
Route::get('admin/events/{id}/reports', 'App\Http\Controllers\EventController@reports')->middleware(['auth'])->name('event.reports');
// Route::get('admin/events/create', 'App\Http\Controllers\EventController@create')->middleware(['auth'])->name('event.create');
// Route::post('admin/events/store', 'App\Http\Controllers\EventController@store')->middleware(['auth'])->name('event.store');
Route::get('admin/events/edit/{id}', 'App\Http\Controllers\EventController@edit')->middleware(['auth'])->name('event.edit');
Route::post('admin/events/update/{id}', 'App\Http\Controllers\EventController@update')->middleware(['auth'])->name('event.update');
Route::delete('admin/events/destroy/{id}', 'App\Http\Controllers\EventController@destroy')->middleware(['auth'])->name('event.destroy');
Route::get('admin/events/autocomplete_place', 'App\Http\Controllers\EventController@autocomplete_place')->name('event.autocomplete_place');
Route::get('admin/events/check_slug', 'App\Http\Controllers\EventController@check_slug')->name('event.check_slug');
Route::get('admin/events/create_slug', 'App\Http\Controllers\EventController@create_slug')->name('event.create_slug');
Route::post('admin/events/store_file/{id}','App\Http\Controllers\EventController@file_store')->middleware(['auth'])->name('event.store_file');
Route::get('admin/events/delete_file/{id}','App\Http\Controllers\EventController@delete_file')->middleware(['auth'])->name('event.delete_file');
Route::get('admin/eventos/participantes/editar/{id}', 'App\Http\Controllers\EventController@participantes_edit')->middleware(['auth'])->name('event.participantes.edit');
Route::post('admin/eventos/participantes/update/{id}', 'App\Http\Controllers\EventController@participantes_update')->middleware(['auth'])->name('event.participantes.update');

Route::get('admin/events/{id}/coupons', 'App\Http\Controllers\CouponController@coupons')->middleware(['auth'])->name('event.coupons');
Route::get('admin/events/{id}/create_coupon', 'App\Http\Controllers\CouponController@create_coupon')->middleware(['auth'])->name('event.create_coupon');
Route::post('admin/events/{id}/store_coupon', 'App\Http\Controllers\CouponController@store_coupon')->middleware(['auth'])->name('event.store_coupon');
Route::get('admin/events/edit_coupon/{id}', 'App\Http\Controllers\CouponController@edit_coupon')->middleware(['auth'])->name('event.edit_coupon');
Route::post('admin/events/update_coupon/{id}', 'App\Http\Controllers\CouponController@update_coupon')->middleware(['auth'])->name('event.update_coupon');
Route::delete('admin/events/destroy_coupon/{id}', 'App\Http\Controllers\CouponController@destroy_coupon')->middleware(['auth'])->name('event.destroy_coupon');

Route::get('admin/lotes/list', 'App\Http\Controllers\LoteController@index')->middleware(['auth'])->name('lote.index');
Route::get('admin/lotes/show/{id}', 'App\Http\Controllers\LoteController@show')->middleware(['auth'])->name('lote.show');
Route::get('admin/lotes/{id}/create', 'App\Http\Controllers\LoteController@create')->middleware(['auth'])->name('lote.create');
Route::post('admin/lotes/{id}/store', 'App\Http\Controllers\LoteController@store')->middleware(['auth'])->name('lote.store');
Route::get('admin/lotes/{id}/edit', 'App\Http\Controllers\LoteController@edit')->middleware(['auth'])->name('lote.edit');
Route::post('admin/lotes/{id}/update', 'App\Http\Controllers\LoteController@update')->middleware(['auth'])->name('lote.update');
Route::delete('admin/lotes/{id}/destroy', 'App\Http\Controllers\LoteController@destroy')->middleware(['auth'])->name('lote.destroy');
Route::post('admin/lotes/{id}/save_lotes', 'App\Http\Controllers\LoteController@save_lotes')->middleware(['auth'])->name('lote.save_lotes');

Route::get('admin/owners/list', 'App\Http\Controllers\OwnerController@index')->middleware(['auth'])->name('owner.index');
Route::get('admin/owners/show/{id}', 'App\Http\Controllers\OwnerController@show')->middleware(['auth'])->name('owner.show');
Route::get('admin/owners/create', 'App\Http\Controllers\OwnerController@create')->middleware(['auth'])->name('owner.create');
Route::post('admin/owners/store', 'App\Http\Controllers\OwnerController@store')->middleware(['auth'])->name('owner.store');
Route::get('admin/owners/edit/{id}', 'App\Http\Controllers\OwnerController@edit')->middleware(['auth'])->name('owner.edit');
Route::post('admin/owners/update/{id}', 'App\Http\Controllers\OwnerController@update')->middleware(['auth'])->name('owner.update');
Route::delete('admin/owners/destroy/{id}', 'App\Http\Controllers\OwnerController@destroy')->middleware(['auth'])->name('owner.destroy');
Route::post('admin/owners/store_file/{id}','App\Http\Controllers\OwnerController@file_store')->middleware(['auth'])->name('owner.store_file');
Route::get('admin/owners/delete_file/{id}','App\Http\Controllers\OwnerController@delete_file')->middleware(['auth'])->name('owner.delete_file');

Route::get('admin/participantes/list', 'App\Http\Controllers\ParticipanteController@index')->middleware(['auth'])->name('participante.index');
Route::get('admin/participantes/show/{id}', 'App\Http\Controllers\ParticipanteController@show')->middleware(['auth'])->name('participante.show');
Route::get('admin/participantes/create', 'App\Http\Controllers\ParticipanteController@create')->middleware(['auth'])->name('participante.create');
Route::post('admin/participantes/store', 'App\Http\Controllers\ParticipanteController@store')->middleware(['auth'])->name('participante.store');
Route::get('admin/participantes/edit/{id}', 'App\Http\Controllers\ParticipanteController@edit')->middleware(['auth'])->name('participante.edit');
Route::post('admin/participantes/update/{id}', 'App\Http\Controllers\ParticipanteController@update')->middleware(['auth'])->name('participante.update');
Route::delete('admin/participantes/destroy/{id}', 'App\Http\Controllers\ParticipanteController@destroy')->middleware(['auth'])->name('participante.destroy');

Route::get('painel/criar-evento', 'App\Http\Controllers\EventHomeController@create_event')->middleware(['auth'])->name('event_home.create_event');
Route::get('painel/autocomplete_place', 'App\Http\Controllers\EventHomeController@autocomplete_place')->name('event_home.autocomplete_place');
Route::get('painel/check_slug', 'App\Http\Controllers\EventHomeController@check_slug')->name('event_home.check_slug');
Route::get('painel/create_slug', 'App\Http\Controllers\EventHomeController@create_slug')->name('event_home.create_slug');
Route::post('painel/store_file/{id}','App\Http\Controllers\EventHomeController@file_store')->middleware(['auth'])->name('event_home.store_file');
Route::get('painel/delete_file/{id}','App\Http\Controllers\EventHomeController@delete_file')->middleware(['auth'])->name('event_home.delete_file');
Route::post('painel/get-areas-by-category','App\Http\Controllers\HomeController@getAreas')->middleware(['auth'])->name('event_home.get_areas_by_category');

require __DIR__.'/auth.php';
