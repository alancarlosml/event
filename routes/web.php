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

Route::get('/', 'App\Http\Controllers\HomeController@home')->name('home');
// Route::post ('/contato', 'App\Http\Controllers\HomeController@contact')->name('contact');
Route::get('/contato', 'App\Http\Controllers\HomeController@show_contact_form');
Route::post('/contato', 'App\Http\Controllers\HomeController@send')->name('contact');
Route::get('/eventos', 'App\Http\Controllers\EventHomeController@events');
Route::get('/eventos/get-more-events', 'App\Http\Controllers\EventHomeController@getMoreEvents')->name('event_home.get-more-events');

Route::post('painel/get-areas-by-category','App\Http\Controllers\HomeController@getAreas')->name('event_home.get_areas_by_category');
Route::get('painel/autocomplete_place', 'App\Http\Controllers\EventHomeController@autocomplete_place')->name('event_home.autocomplete_place');
Route::get('painel/check_slug', 'App\Http\Controllers\EventHomeController@check_slug')->name('event_home.check_slug');
Route::get('painel/create_slug', 'App\Http\Controllers\EventHomeController@create_slug')->name('event_home.create_slug');
Route::post('painel/store_file/{id}','App\Http\Controllers\EventHomeController@file_store')->middleware(['auth:participante', 'verified'])->name('event_home.store_file');
Route::get('painel/delete_file/{id}','App\Http\Controllers\EventHomeController@delete_file')->middleware(['auth:participante', 'verified'])->name('event_home.delete_file');
Route::post('painel/places/get-cities-by-state','App\Http\Controllers\EventHomeController@getCity')->middleware(['auth:participante', 'verified'])->name('event_home.get_city');

// PAINEL - CRIAR EVENTO
Route::get('painel/minhas-inscricoes', 'App\Http\Controllers\EventAdminController@myRegistrations')->middleware(['auth:participante', 'verified'])->name('event_home.my_registrations');
Route::get('painel/meus-eventos', 'App\Http\Controllers\EventAdminController@myEvents')->middleware(['auth:participante', 'verified'])->name('event_home.my_events');
Route::get('painel/meus-eventos/{hash}/detalhes', 'App\Http\Controllers\EventAdminController@myEventsShow')->middleware(['auth:participante', 'verified'])->name('event_home.my_events_show');
Route::get('painel/meus-eventos/{hash}/contatos', 'App\Http\Controllers\EventAdminController@contacts')->middleware(['auth:participante', 'verified'])->name('event_home.contacts');
Route::get('painel/meus-eventos/{hash}/editar', 'App\Http\Controllers\EventAdminController@myEventsEdit')->middleware(['auth:participante', 'verified'])->name('event_home.my_events_edit');
Route::get('painel/meus-eventos/{hash}/convidados', 'App\Http\Controllers\EventAdminController@guests')->middleware(['auth:participante', 'verified'])->name('event_home.guests');
Route::get('painel/meus-eventos/{hash}/convidados/adicionar', 'App\Http\Controllers\EventAdminController@addGuest')->middleware(['auth:participante', 'verified'])->name('event_home.guest_add');
Route::post('painel/meus-eventos/{hash}/convidados/adicionar', 'App\Http\Controllers\EventAdminController@storeGuest')->middleware(['auth:participante', 'verified'])->name('event_home.guest_store');
Route::get('painel/meus-eventos/{hash}/mensagens', 'App\Http\Controllers\EventAdminController@listMessages')->middleware(['auth:participante', 'verified'])->name('event_home.messages');
Route::get('painel/meus-eventos/mensagens/{id}/abrir', 'App\Http\Controllers\EventAdminController@showMessage')->middleware(['auth:participante', 'verified'])->name('event_home.show_message');
Route::delete('painel/meus-eventos/mensagens/{id}/excluir', 'App\Http\Controllers\EventAdminController@destroyMessage')->middleware(['auth:participante', 'verified'])->name('event_home.destroy_message');
Route::get('painel/meus-eventos/convidados/{id}/editar', 'App\Http\Controllers\EventAdminController@editGuest')->middleware(['auth:participante', 'verified'])->name('event_home.guest_edit');
Route::post('painel/meus-eventos/convidados/{id}/editar', 'App\Http\Controllers\EventAdminController@updateGuest')->middleware(['auth:participante', 'verified'])->name('event_home.guests_update');
Route::delete('painel/meus-eventos/convidados/{id}/excluir', 'App\Http\Controllers\EventAdminController@destroyGuest')->middleware(['auth:participante', 'verified'])->name('event_home.destroy_guest');
Route::get('painel/meus-eventos/{hash}/relatorios', 'App\Http\Controllers\EventAdminController@reports')->middleware(['auth:participante', 'verified'])->name('event_home.reports');

Route::get('painel/eventos/criar-evento', 'App\Http\Controllers\EventAdminController@createEventLink')->middleware(['auth:participante', 'verified'])->name('event_home.create_event_link');
Route::get('painel/eventos/primeiro-passo', 'App\Http\Controllers\EventAdminController@create_event')->middleware(['auth:participante', 'verified'])->name('event_home.create_event');
Route::post('painel/eventos/primeiro-passo', 'App\Http\Controllers\EventAdminController@postCreateStepOne')->middleware(['auth:participante', 'verified'])->name('event_home.create.step.one');

Route::get('painel/eventos/segundo-passo', 'App\Http\Controllers\EventAdminController@createStepTwo')->middleware(['auth:participante', 'verified'])->name('event_home.create.step.two');
// Route::post('painel/eventos/segundo-passo', 'App\Http\Controllers\EventAdminController@postCreateStepTow')->middleware(['auth:participante', 'verified'])->name('event_home.create.step.two');
Route::get('painel/eventos/segundo-passo/criar-lote', 'App\Http\Controllers\EventAdminController@createLote')->middleware(['auth:participante', 'verified'])->name('event_home.lote_create');
Route::post('painel/eventos/segundo-passo/criar-lote', 'App\Http\Controllers\EventAdminController@storeLote')->middleware(['auth:participante', 'verified'])->name('event_home.create_lote_store');
Route::get('painel/eventos/lote/{hash}/editar', 'App\Http\Controllers\EventAdminController@editLote')->middleware(['auth:participante', 'verified'])->name('event_home.lote_edit');
Route::post('painel/eventos/lote/{hash}/editar', 'App\Http\Controllers\EventAdminController@updateLote')->middleware(['auth:participante', 'verified'])->name('event_home.lote_update');
Route::delete('painel/eventos/lote/{hash}/excluir', 'App\Http\Controllers\EventAdminController@deleteLote')->middleware(['auth:participante', 'verified'])->name('event_home.lote_delete');
Route::post('painel/eventos/{event_id}/save_lotes', 'App\Http\Controllers\EventAdminController@save_lotes')->middleware(['auth:participante', 'verified'])->name('event_home.save_lotes');
Route::get('painel/eventos/vendas/detalhes/{order_hash}', 'App\Http\Controllers\EventAdminController@order_details')->middleware(['auth:participante', 'verified'])->name('event_home.order.details');

Route::get('painel/eventos/terceiro-passo', 'App\Http\Controllers\EventAdminController@createStepThree')->middleware(['auth:participante', 'verified'])->name('event_home.create.step.three');
Route::post('painel/eventos/terceiro-passo', 'App\Http\Controllers\EventAdminController@postCreateStepThree')->middleware(['auth:participante', 'verified'])->name('event_home.create.step.three');
Route::get('painel/eventos/{hash}/criar-cupom', 'App\Http\Controllers\EventAdminController@createCoupon')->middleware(['auth:participante', 'verified'])->name('event_home.create_coupon');
Route::post('painel/eventos/{hash}/criar-cupom', 'App\Http\Controllers\EventAdminController@storeCoupon')->middleware(['auth:participante', 'verified'])->name('event_home.store_coupon');
Route::get('painel/eventos/cupom/{hash}/editar', 'App\Http\Controllers\EventAdminController@editCoupon')->middleware(['auth:participante', 'verified'])->name('event_home.coupon_edit');
Route::post('painel/eventos/cupom/{hash}/editar', 'App\Http\Controllers\EventAdminController@updateCoupon')->middleware(['auth:participante', 'verified'])->name('event_home.update_coupon');
Route::delete('painel/eventos/cupom/{hash}/excluir', 'App\Http\Controllers\EventAdminController@destroyCoupon')->middleware(['auth:participante', 'verified'])->name('event_home.destroy_coupon');

Route::get('painel/eventos/publicar', 'App\Http\Controllers\EventAdminController@createStepFour')->middleware(['auth:participante', 'verified'])->name('event_home.create.step.four');
Route::post('painel/eventos/publicar/{hash}', 'App\Http\Controllers\EventAdminController@postCreateStepFour')->middleware(['auth:participante', 'verified'])->name('event_home.publish');
Route::get('painel/eventos/{id}/delete_file','App\Http\Controllers\EventAdminController@deleteFileEvent')->middleware(['auth:participante', 'verified'])->name('event_home.delete_file_event');
Route::get('painel/eventos/organizador/{id}/delete_file_icon','App\Http\Controllers\EventAdminController@deleteFileIcon')->middleware(['auth:participante', 'verified'])->name('event_home.delete_file_icon');

Route::get('/{slug}', 'App\Http\Controllers\ConferenceController@event')->name('conference.index');
Route::post('contato/{hash}', 'App\Http\Controllers\ConferenceController@send')->name('contact_event');
Route::get('{slug}/resumo', 'App\Http\Controllers\ConferenceController@resume')->middleware(['auth:participante', 'verified'])->name('conference.resume');
Route::get('{slug}/pagamento', 'App\Http\Controllers\ConferenceController@paymentView')->middleware(['auth:participante', 'verified'])->name('conference.payment');
Route::post('{slug}/pagamento', 'App\Http\Controllers\ConferenceController@payment')->middleware(['auth:participante', 'verified'])->name('conference.payment');
// Route::post('{slug}/obrigado', 'App\Http\Controllers\ConferenceController@thanks')->middleware(['auth:participante', 'verified'])->name('conference.thanks');
Route::post('getSubTotal', 'App\Http\Controllers\ConferenceController@getSubTotal')->name('conference.getSubTotal');
Route::post('/getCoupon', 'App\Http\Controllers\ConferenceController@getCoupon')->name('conference.getCoupon');
Route::delete('/{slug}/remover-cupom', 'App\Http\Controllers\ConferenceController@removeCoupon')->name('conference.removeCoupon');
Route::post('/setEventDate', 'App\Http\Controllers\ConferenceController@setEventDate')->name('conference.setEventDate');
// Route::post('painel/lotes/{id}/store', 'App\Http\Controllers\LoteController@store')->middleware(['auth:participante', 'verified'])->name('lote.store');
// Route::get('painel/lotes/{id}/edit', 'App\Http\Controllers\LoteController@edit')->middleware(['auth:participante', 'verified'])->name('lote.edit');
// Route::post('painel/lotes/{id}/update', 'App\Http\Controllers\LoteController@update')->middleware(['auth:participante', 'verified'])->name('lote.update');
// Route::delete('painel/lotes/{id}/destroy', 'App\Http\Controllers\LoteController@destroy')->middleware(['auth:participante', 'verified'])->name('lote.destroy');
// Route::post('painel/lotes/{id}/save_lotes', 'App\Http\Controllers\LoteController@save_lotes')->middleware(['auth:participante', 'verified'])->name('lote.save_lotes');



// Route::get('admin/register', [RegisteredUserController::class, 'create'])->name('register');

// Route::post('admin/register', [RegisteredUserController::class, 'store']);

// Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::get('admin/login', 'App\Http\Controllers\Admin\Auth\AuthenticatedSessionController@create')->name('admin.login');
Route::post('admin/login', 'App\Http\Controllers\Admin\Auth\AuthenticatedSessionController@store')->name('admin.login');
Route::post('admin/logout', 'App\Http\Controllers\Admin\Auth\AuthenticatedSessionController@destroy')->name('admin.logout');

Route::get('admin/dashboard', 'App\Http\Controllers\DashboardController@dashboard')->middleware(['auth:web'])->name('dashboard');

Route::get('admin/users/list', 'App\Http\Controllers\UserController@index')->middleware(['auth:web'])->name('user.index');
Route::get('admin/users/show/{id}', 'App\Http\Controllers\UserController@show')->middleware(['auth:web'])->name('user.show');
Route::get('admin/users/create', 'App\Http\Controllers\UserController@create')->middleware(['auth:web'])->name('user.create');
Route::post('admin/users/store', 'App\Http\Controllers\UserController@store')->middleware(['auth:web'])->name('user.store');
Route::get('admin/users/edit/{id}', 'App\Http\Controllers\UserController@edit')->middleware(['auth:web'])->name('user.edit');
Route::post('admin/users/update/{id}', 'App\Http\Controllers\UserController@update')->middleware(['auth:web'])->name('user.update');
Route::delete('admin/users/destroy/{id}', 'App\Http\Controllers\UserController@destroy')->middleware(['auth:web'])->name('user.destroy');

Route::get('admin/configurations/edit/', 'App\Http\Controllers\ConfigurationController@edit')->middleware(['auth:web'])->name('configuration.edit');
Route::post('admin/configurations/update', 'App\Http\Controllers\ConfigurationController@update')->middleware(['auth:web'])->name('configuration.update');

Route::get('admin/categories/list', 'App\Http\Controllers\CategoryController@index')->middleware(['auth:web'])->name('category.index');
Route::get('admin/categories/show/{id}', 'App\Http\Controllers\CategoryController@show')->middleware(['auth:web'])->name('category.show');
Route::get('admin/categories/create', 'App\Http\Controllers\CategoryController@create')->middleware(['auth:web'])->name('category.create');
Route::post('admin/categories/store', 'App\Http\Controllers\CategoryController@store')->middleware(['auth:web'])->name('category.store');
Route::get('admin/categories/edit/{id}', 'App\Http\Controllers\CategoryController@edit')->middleware(['auth:web'])->name('category.edit');
Route::post('admin/categories/update/{id}', 'App\Http\Controllers\CategoryController@update')->middleware(['auth:web'])->name('category.update');
Route::delete('admin/categories/destroy/{id}', 'App\Http\Controllers\CategoryController@destroy')->middleware(['auth:web'])->name('category.destroy');
Route::post('admin/categories/get-areas-by-category','App\Http\Controllers\CategoryController@getAreas')->middleware(['auth:web']);

Route::get('admin/categories/{category_id}/areas', 'App\Http\Controllers\AreaController@index')->middleware(['auth:web'])->name('area.index');
Route::get('admin/categories/{category_id}/areas/show/{id}', 'App\Http\Controllers\AreaController@show')->middleware(['auth:web'])->name('area.show');
Route::get('admin/categories/{category_id}/areas/create', 'App\Http\Controllers\AreaController@create')->middleware(['auth:web'])->name('area.create');
Route::post('admin/categories/{category_id}/areas/store', 'App\Http\Controllers\AreaController@store')->middleware(['auth:web'])->name('area.store');
Route::get('admin/categories/{category_id}/areas/edit/{id}', 'App\Http\Controllers\AreaController@edit')->middleware(['auth:web'])->name('area.edit');
Route::post('admin/categories/{category_id}/areas/update/{id}', 'App\Http\Controllers\AreaController@update')->middleware(['auth:web'])->name('area.update');
Route::delete('admin/categories/{category_id}/areas/destroy/{id}', 'App\Http\Controllers\AreaController@destroy')->middleware(['auth:web'])->name('area.destroy');

Route::get('admin/places/list', 'App\Http\Controllers\PlaceController@index')->middleware(['auth:web'])->name('place.index');
Route::get('admin/places/show/{id}', 'App\Http\Controllers\PlaceController@show')->middleware(['auth:web'])->name('place.show');
Route::get('admin/places/create', 'App\Http\Controllers\PlaceController@create')->middleware(['auth:web'])->name('place.create');
Route::post('admin/places/store', 'App\Http\Controllers\PlaceController@store')->middleware(['auth:web'])->name('place.store');
Route::get('admin/places/edit/{id}', 'App\Http\Controllers\PlaceController@edit')->middleware(['auth:web'])->name('place.edit');
Route::post('admin/places/update/{id}', 'App\Http\Controllers\PlaceController@update')->middleware(['auth:web'])->name('place.update');
Route::delete('admin/places/destroy/{id}', 'App\Http\Controllers\PlaceController@destroy')->middleware(['auth:web'])->name('place.destroy');
Route::post('admin/places/get-cities-by-state','App\Http\Controllers\PlaceController@getCity')->middleware(['auth:web']);

Route::get('admin/contacts/list', 'App\Http\Controllers\ContactController@index')->middleware(['auth:web'])->name('contact.index');
Route::get('admin/contacts/show/{id}', 'App\Http\Controllers\ContactController@show')->middleware(['auth:web'])->name('contact.show');

Route::get('admin/events/list', 'App\Http\Controllers\EventController@index')->middleware(['auth:web'])->name('event.index');
Route::get('admin/events/show/{id}', 'App\Http\Controllers\EventController@show')->middleware(['auth:web'])->name('event.show');
Route::get('admin/events/{id}/lotes', 'App\Http\Controllers\EventController@lotes')->middleware(['auth:web'])->name('event.lotes');
Route::get('admin/events/{id}/reports', 'App\Http\Controllers\EventController@reports')->middleware(['auth:web'])->name('event.reports');
// Route::get('admin/events/create', 'App\Http\Controllers\EventController@create')->middleware(['auth:web'])->name('event.create');
// Route::post('admin/events/store', 'App\Http\Controllers\EventController@store')->middleware(['auth:web'])->name('event.store');
Route::get('admin/events/edit/{id}', 'App\Http\Controllers\EventController@edit')->middleware(['auth:web'])->name('event.edit');
Route::post('admin/events/update/{id}', 'App\Http\Controllers\EventController@update')->middleware(['auth:web'])->name('event.update');
Route::delete('admin/events/destroy/{id}', 'App\Http\Controllers\EventController@destroy')->middleware(['auth:web'])->name('event.destroy');
Route::get('admin/events/autocomplete_place', 'App\Http\Controllers\EventController@autocomplete_place')->name('event.autocomplete_place');
Route::get('admin/events/check_slug', 'App\Http\Controllers\EventController@check_slug')->name('event.check_slug');
Route::get('admin/events/create_slug', 'App\Http\Controllers\EventController@create_slug')->name('event.create_slug');
Route::post('admin/events/store_file/{id}','App\Http\Controllers\EventController@file_store')->middleware(['auth:web'])->name('event.store_file');
Route::get('admin/events/delete_file/{id}','App\Http\Controllers\EventController@delete_file')->middleware(['auth:web'])->name('event.delete_file');
Route::get('admin/eventos/participantes/editar/{id}', 'App\Http\Controllers\EventController@participantes_edit')->middleware(['auth:web'])->name('event.participantes.edit');
Route::post('admin/eventos/participantes/update/{id}', 'App\Http\Controllers\EventController@participantes_update')->middleware(['auth:web'])->name('event.participantes.update');
Route::get('admin/eventos/vendas/detalhes/{id}', 'App\Http\Controllers\EventController@order_details')->middleware(['auth:web'])->name('event.orders.details');

Route::get('admin/events/{id}/coupons', 'App\Http\Controllers\CouponController@coupons')->middleware(['auth:web'])->name('event.coupons');
Route::get('admin/events/{id}/create_coupon', 'App\Http\Controllers\CouponController@create_coupon')->middleware(['auth:web'])->name('event.create_coupon');
Route::post('admin/events/{id}/store_coupon', 'App\Http\Controllers\CouponController@store_coupon')->middleware(['auth:web'])->name('event.store_coupon');
Route::get('admin/events/coupon_edit/{id}', 'App\Http\Controllers\CouponController@editCoupon')->middleware(['auth:web'])->name('event.coupon_edit');
Route::post('admin/events/update_coupon/{id}', 'App\Http\Controllers\CouponController@update_coupon')->middleware(['auth:web'])->name('event.update_coupon');
Route::delete('admin/events/destroy_coupon/{id}', 'App\Http\Controllers\CouponController@destroy_coupon')->middleware(['auth:web'])->name('event.destroy_coupon');

Route::get('admin/lotes/list', 'App\Http\Controllers\LoteController@index')->middleware(['auth:web'])->name('lote.index');
Route::get('admin/lotes/show/{id}', 'App\Http\Controllers\LoteController@show')->middleware(['auth:web'])->name('lote.show');
Route::get('admin/lotes/{id}/create', 'App\Http\Controllers\LoteController@create')->middleware(['auth:web'])->name('lote.create');
Route::post('admin/lotes/{id}/store', 'App\Http\Controllers\LoteController@store')->middleware(['auth:web'])->name('lote.store');
Route::get('admin/lotes/{id}/edit', 'App\Http\Controllers\LoteController@edit')->middleware(['auth:web'])->name('lote.edit');
Route::post('admin/lotes/{id}/update', 'App\Http\Controllers\LoteController@update')->middleware(['auth:web'])->name('lote.update');
Route::delete('admin/lotes/{id}/destroy', 'App\Http\Controllers\LoteController@destroy')->middleware(['auth:web'])->name('lote.destroy');
Route::post('admin/lotes/{id}/save_lotes', 'App\Http\Controllers\LoteController@save_lotes')->middleware(['auth:web'])->name('lote.save_lotes');

Route::get('admin/owners/list', 'App\Http\Controllers\OwnerController@index')->middleware(['auth:web'])->name('owner.index');
Route::get('admin/owners/show/{id}', 'App\Http\Controllers\OwnerController@show')->middleware(['auth:web'])->name('owner.show');
Route::get('admin/owners/create', 'App\Http\Controllers\OwnerController@create')->middleware(['auth:web'])->name('owner.create');
Route::post('admin/owners/store', 'App\Http\Controllers\OwnerController@store')->middleware(['auth:web'])->name('owner.store');
Route::get('admin/owners/edit/{id}', 'App\Http\Controllers\OwnerController@edit')->middleware(['auth:web'])->name('owner.edit');
Route::post('admin/owners/update/{id}', 'App\Http\Controllers\OwnerController@update')->middleware(['auth:web'])->name('owner.update');
Route::delete('admin/owners/destroy/{id}', 'App\Http\Controllers\OwnerController@destroy')->middleware(['auth:web'])->name('owner.destroy');
Route::post('admin/owners/store_file/{id}','App\Http\Controllers\OwnerController@file_store')->middleware(['auth:web'])->name('owner.store_file');
Route::get('admin/owners/delete_file/{id}','App\Http\Controllers\OwnerController@delete_file')->middleware(['auth:web'])->name('owner.delete_file');

Route::get('admin/participantes/list', 'App\Http\Controllers\ParticipanteController@index')->middleware(['auth:web'])->name('participante.index');
Route::get('admin/participantes/show/{id}', 'App\Http\Controllers\ParticipanteController@show')->middleware(['auth:web'])->name('participante.show');
Route::get('admin/participantes/create', 'App\Http\Controllers\ParticipanteController@create')->middleware(['auth:web'])->name('participante.create');
Route::post('admin/participantes/store', 'App\Http\Controllers\ParticipanteController@store')->middleware(['auth:web'])->name('participante.store');
Route::get('admin/participantes/edit/{id}', 'App\Http\Controllers\ParticipanteController@edit')->middleware(['auth:web'])->name('participante.edit');
Route::post('admin/participantes/update/{id}', 'App\Http\Controllers\ParticipanteController@update')->middleware(['auth:web'])->name('participante.update');
Route::delete('admin/participantes/destroy/{id}', 'App\Http\Controllers\ParticipanteController@destroy')->middleware(['auth:web'])->name('participante.destroy');

require __DIR__.'/auth.php';
