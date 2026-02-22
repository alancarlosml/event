<?php

declare(strict_types=1);

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

Route::post('painel/get-areas-by-category', 'App\Http\Controllers\HomeController@getAreas')->name('event_home.get_areas_by_category');
Route::get('painel/autocomplete_place', 'App\Http\Controllers\EventHomeController@autocomplete_place')->name('event_home.autocomplete_place');
Route::get('painel/check_slug', 'App\Http\Controllers\EventHomeController@check_slug')->name('event_home.check_slug');
Route::get('painel/create_slug', 'App\Http\Controllers\EventHomeController@create_slug')->name('event_home.create_slug');
Route::post('painel/store_file/{id}', 'App\Http\Controllers\EventHomeController@file_store')->middleware(['auth:participante', 'verified'])->name('event_home.store_file');
Route::get('painel/delete_file/{id}', 'App\Http\Controllers\EventHomeController@delete_file')->middleware(['auth:participante', 'verified'])->name('event_home.delete_file');
Route::post('painel/places/get-cities-by-state', 'App\Http\Controllers\EventHomeController@getCity')->middleware(['auth:participante', 'verified'])->name('event_home.get_city');

// PAINEL - ROTAS PROTEGIDAS (agrupadas)
Route::prefix('painel')
    ->middleware(['auth:participante', 'verified'])
    ->name('event_home.')
    ->group(function () {
        // Rotas principais
        Route::get('/minhas-inscricoes', 'App\Http\Controllers\EventAdminController@myRegistrations')->name('my_registrations');
        Route::get('/meus-eventos', 'App\Http\Controllers\EventAdminController@myEvents')->name('my_events');
        Route::get('/dashboard', 'App\Http\Controllers\EventAdminController@dashboard')->name('dashboard');
        
        // Perfil
        Route::get('/perfil', 'App\Http\Controllers\EventAdminController@profile')->name('profile');
        Route::put('/perfil', 'App\Http\Controllers\EventAdminController@updateProfile')->name('profile.update');
        
        // Eventos - CRUD
        Route::get('/meus-eventos/{hash}/detalhes', 'App\Http\Controllers\EventAdminController@myEventsShow')->name('my_events_show');
        Route::get('/meus-eventos/{hash}/editar', 'App\Http\Controllers\EventAdminController@myEventsEdit')->name('my_events_edit');
        Route::put('/meus-eventos/{hash}/editar', 'App\Http\Controllers\EventAdminController@updateEvent')->name('my_events_edit.update');
        Route::delete('/meus-eventos/{hash}/excluir', 'App\Http\Controllers\EventAdminController@destroy')->name('destroy');
        Route::get('/meus-eventos/{hash}/duplicar', 'App\Http\Controllers\EventAdminController@eventClone')->name('event_clone');
        
        // Mensagens/Contatos
        Route::get('/meus-eventos/{hash}/contatos', 'App\Http\Controllers\EventAdminController@contacts')->name('messages');
        Route::get('/meus-eventos/mensagens/{id}/abrir', 'App\Http\Controllers\EventAdminController@showMessage')->name('show_message');
        
        // Convidados
        Route::get('/meus-eventos/{hash}/convidados', 'App\Http\Controllers\EventAdminController@guests')->name('guests');
        Route::get('/meus-eventos/{hash}/convidados/adicionar', 'App\Http\Controllers\EventAdminController@addGuest')->name('guest_add');
        Route::post('/meus-eventos/{hash}/convidados/adicionar', 'App\Http\Controllers\EventAdminController@storeGuest')->name('guest_store');
        Route::get('/meus-eventos/convidados/{id}/editar', 'App\Http\Controllers\EventAdminController@editGuest')->name('guest_edit');
        Route::post('/meus-eventos/convidados/{id}/editar', 'App\Http\Controllers\EventAdminController@updateGuest')->name('guest_update');
        Route::delete('/meus-eventos/convidados/{id}/excluir', 'App\Http\Controllers\EventAdminController@destroyGuest')->name('destroy_guest');
        
        // Relatórios
        Route::get('/meus-eventos/{hash}/relatorios', 'App\Http\Controllers\EventAdminController@reports')->name('reports');
        
        // Criação de Eventos - Wizard
        Route::get('/eventos/criar-evento', 'App\Http\Controllers\EventAdminController@createEventLink')->name('create_event_link');
        Route::get('/eventos/primeiro-passo', 'App\Http\Controllers\EventAdminController@create_event')->name('create_event');
        Route::post('/eventos/primeiro-passo', 'App\Http\Controllers\EventAdminController@postCreateStepOne')->name('create.step.one');
        
        // Step 2 - Lotes
        Route::get('/eventos/segundo-passo', 'App\Http\Controllers\EventAdminController@createStepTwo')->name('create.step.two');
        Route::get('/eventos/segundo-passo/criar-lote', 'App\Http\Controllers\EventAdminController@createLote')->name('lote_create');
        Route::post('/eventos/segundo-passo/criar-lote', 'App\Http\Controllers\EventAdminController@storeLote')->name('create_lote_store');
        Route::get('/eventos/lote/{hash}/editar', 'App\Http\Controllers\EventAdminController@editLote')->name('lote_edit');
        Route::post('/eventos/lote/{hash}/editar', 'App\Http\Controllers\EventAdminController@updateLote')->name('lote_update');
        Route::delete('/eventos/lote/{hash}/excluir', 'App\Http\Controllers\EventAdminController@deleteLote')->name('lote_delete');
        Route::post('/eventos/{event_id}/save_lotes', 'App\Http\Controllers\EventAdminController@save_lotes')->name('save_lotes');
        
        // Step 3 - Cupons
        Route::get('/eventos/terceiro-passo', 'App\Http\Controllers\EventAdminController@createStepThree')->name('create.step.three');
        Route::get('/eventos/{hash}/criar-cupom', 'App\Http\Controllers\EventAdminController@createCoupon')->name('create_coupon');
        Route::post('/eventos/{hash}/criar-cupom', 'App\Http\Controllers\EventAdminController@storeCoupon')->name('store_coupon');
        Route::get('/eventos/cupom/{hash}/editar', 'App\Http\Controllers\EventAdminController@editCoupon')->name('coupon_edit');
        Route::post('/eventos/cupom/{hash}/editar', 'App\Http\Controllers\EventAdminController@updateCoupon')->name('update_coupon');
        Route::delete('/eventos/cupom/{hash}/excluir', 'App\Http\Controllers\EventAdminController@destroyCoupon')->name('destroy_coupon');
        
        // Step 4 - Publicar
        Route::get('/eventos/publicar', 'App\Http\Controllers\EventAdminController@createStepFour')->name('create.step.four');
        Route::post('/eventos/publicar/{hash}', 'App\Http\Controllers\EventAdminController@postCreateStepFour')->name('publish');
        
        // Vendas
        Route::get('/eventos/vendas/detalhes/{order_hash}', 'App\Http\Controllers\EventAdminController@order_details')->name('order.details');
        Route::get('/eventos/vendas/voucher/{order_hash}', 'App\Http\Controllers\EventAdminController@print_voucher')->name('order.print_voucher');
        
        // Order Management (Cancel/Refund for organizers)
        Route::post('/orders/{id}/cancel', 'App\Http\Controllers\OrderController@cancelOrganizer')->name('order.cancel');
        Route::post('/orders/{id}/refund', 'App\Http\Controllers\OrderController@refundOrganizer')->name('order.refund');
        
        // Arquivos
        Route::get('/eventos/{id}/delete_file', 'App\Http\Controllers\EventAdminController@deleteFileEvent')->name('delete_file_event');
        Route::get('/eventos/organizador/{id}/delete_file_icon', 'App\Http\Controllers\EventAdminController@deleteFileIcon')->name('delete_file_icon');
    });

// Check-in Routes
Route::get('checkin/{purchase_hash}', 'App\Http\Controllers\CheckInController@viewTicket')->name('checkin.view');
Route::post('api/checkin/{purchase_hash}', 'App\Http\Controllers\CheckInController@validateCheckIn')->name('checkin.validate');

// Rota para corrigir hashes de lotes (antes do /{slug} para não ser capturada)
Route::get('/fix-lote-hashes', 'App\Http\Controllers\LoteController@fixLoteHashes')->middleware(['auth:web'])->name('lote.fix_hashes');

Route::get('/politica', 'App\Http\Controllers\HomeController@politica')->name('politica');
Route::get('/termos', 'App\Http\Controllers\HomeController@termos')->name('termos');

Route::get('/{slug}', 'App\Http\Controllers\ConferenceController@event')->where('slug', '^(?!admin|api|checkin|webhooks|fix-lote-hashes|painel|getSubTotal|getCoupon|setEventDate|getLotesPorData|contato|politica|termos).*$')->name('conference.index');
Route::post('contato/{hash}', 'App\Http\Controllers\ConferenceController@send')->name('contact_event');
Route::match(['GET', 'POST'], '{slug}/resumo', 'App\Http\Controllers\ConferenceController@resume')->middleware(['auth:participante', 'verified'])->name('conference.resume');
Route::get('{slug}/pagamento-view', 'App\Http\Controllers\ConferenceController@paymentView')->middleware(['auth:participante', 'verified'])->name('conference.paymentView');
Route::post('{slug}/pagamento', 'App\Http\Controllers\ConferenceController@payment')->middleware(['auth:participante', 'verified'])->name('conference.payment');
Route::post('{slug}/obrigado', 'App\Http\Controllers\ConferenceController@thanks')->middleware(['auth:participante', 'verified'])->name('conference.thanks');
Route::get('check-payment-status/{order_id}', 'App\Http\Controllers\ConferenceController@checkPaymentStatus')->middleware(['auth:participante', 'verified'])->name('conference.checkPaymentStatus');
Route::post('getSubTotal', 'App\Http\Controllers\ConferenceController@getSubTotal')->name('conference.getSubTotal');
Route::post('/getCoupon', 'App\Http\Controllers\ConferenceController@getCoupon')->name('conference.getCoupon');
Route::delete('/{slug}/remover-cupom', 'App\Http\Controllers\ConferenceController@removeCoupon')->name('conference.removeCoupon');
Route::post('/setEventDate', 'App\Http\Controllers\ConferenceController@setEventDate')->name('conference.setEventDate');
Route::post('/getLotesPorData', 'App\Http\Controllers\ConferenceController@getLotesPorData')->name('conference.getLotesPorData');

// Route::post('painel/lotes/{id}/store', 'App\Http\Controllers\LoteController@store')->middleware(['auth:participante', 'verified'])->name('lote.store');
// Route::get('painel/lotes/{id}/edit', 'App\Http\Controllers\LoteController@edit')->middleware(['auth:participante', 'verified'])->name('lote.edit');
// Route::post('painel/lotes/{id}/update', 'App\Http\Controllers\LoteController@update')->middleware(['auth:participante', 'verified'])->name('lote.update');
// Route::delete('painel/lotes/{id}/destroy', 'App\Http\Controllers\LoteController@destroy')->middleware(['auth:participante', 'verified'])->name('lote.destroy');
// Route::post('painel/lotes/{id}/save_lotes', 'App\Http\Controllers\LoteController@save_lotes')->middleware(['auth:participante', 'verified'])->name('lote.save_lotes');

// Route::get('admin/register', [RegisteredUserController::class, 'create'])->name('register');

// Route::post('admin/register', [RegisteredUserController::class, 'store']);

// Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::get('admin/login', 'App\Http\Controllers\Admin\Auth\AuthenticatedSessionController@create')->name('admin.login');
Route::post('admin/login', 'App\Http\Controllers\Admin\Auth\AuthenticatedSessionController@store')->name('admin.login.store');
Route::post('admin/logout', 'App\Http\Controllers\Admin\Auth\AuthenticatedSessionController@destroy')->name('admin.logout');

Route::get('admin/dashboard', 'App\Http\Controllers\DashboardController@dashboard')->middleware(['auth:web'])->name('dashboard');
Route::get('admin/dashboard/chart-data', 'App\Http\Controllers\DashboardController@getChartData')->middleware(['auth:web'])->name('dashboard.chart-data');

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
Route::post('admin/categories/get-areas-by-category', 'App\Http\Controllers\CategoryController@getAreas')->middleware(['auth:web'])->name('category.getAreas');

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
Route::post('admin/places/get-cities-by-state', 'App\Http\Controllers\PlaceController@getCity')->middleware(['auth:web'])->name('place.get_city');

Route::get('admin/contacts/list', 'App\Http\Controllers\ContactController@index')->middleware(['auth:web'])->name('contact.index');
Route::get('admin/contacts/show/{id}', 'App\Http\Controllers\ContactController@show')->middleware(['auth:web'])->name('contact.show');

Route::get('admin/events/list', 'App\Http\Controllers\EventController@index')->middleware(['auth:web'])->name('event.index');
Route::get('admin/events/show/{id}', 'App\Http\Controllers\EventController@show')->middleware(['auth:web'])->name('event.show');
Route::get('admin/events/{id}/lotes', 'App\Http\Controllers\EventController@lotes')->middleware(['auth:web'])->name('event.lotes');
Route::get('admin/events/{id}/reports', 'App\Http\Controllers\EventController@reports')->middleware(['auth:web'])->name('event.reports');
Route::get('admin/events/create', 'App\Http\Controllers\EventController@create')->middleware(['auth:web'])->name('event.create');
Route::post('admin/events/store', 'App\Http\Controllers\EventController@store')->middleware(['auth:web'])->name('event.store');
Route::get('admin/events/edit/{id}', 'App\Http\Controllers\EventController@edit')->middleware(['auth:web'])->name('event.edit');
Route::post('admin/events/update/{id}', 'App\Http\Controllers\EventController@update')->middleware(['auth:web'])->name('event.update');
Route::delete('admin/events/destroy/{id}', 'App\Http\Controllers\EventController@destroy')->middleware(['auth:web'])->name('event.destroy');
Route::get('admin/events/autocomplete_place', 'App\Http\Controllers\EventController@autocomplete_place')->name('event.autocomplete_place');
Route::get('admin/events/check_slug', 'App\Http\Controllers\EventController@check_slug')->name('event.check_slug');
Route::get('admin/events/create_slug', 'App\Http\Controllers\EventController@create_slug')->name('event.create_slug');
Route::post('admin/events/store_file/{id}', 'App\Http\Controllers\EventController@file_store')->middleware(['auth:web'])->name('event.store_file');
Route::get('admin/events/delete_file/{id}', 'App\Http\Controllers\EventController@delete_file')->middleware(['auth:web'])->name('event.delete_file');
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
Route::post('admin/owners/store_file/{id}', 'App\Http\Controllers\OwnerController@file_store')->middleware(['auth:web'])->name('owner.store_file');
Route::get('admin/owners/delete_file/{id}', 'App\Http\Controllers\OwnerController@delete_file')->middleware(['auth:web'])->name('owner.delete_file');

Route::get('admin/participantes/list', 'App\Http\Controllers\ParticipanteController@index')->middleware(['auth:web'])->name('participante.index');
Route::get('admin/participantes/show/{id}', 'App\Http\Controllers\ParticipanteController@show')->middleware(['auth:web'])->name('participante.show');
Route::get('admin/participantes/create', 'App\Http\Controllers\ParticipanteController@create')->middleware(['auth:web'])->name('participante.create');
Route::post('admin/participantes/store', 'App\Http\Controllers\ParticipanteController@store')->middleware(['auth:web'])->name('participante.store');
Route::get('admin/participantes/edit/{id}', 'App\Http\Controllers\ParticipanteController@edit')->middleware(['auth:web'])->name('participante.edit');
Route::post('admin/participantes/update/{id}', 'App\Http\Controllers\ParticipanteController@update')->middleware(['auth:web'])->name('participante.update');
Route::get('admin/participantes/destroy/{id}', 'App\Http\Controllers\ParticipanteController@destroy')->middleware(['auth:web'])->name('participante.destroy');
Route::get('admin/participantes/{id}/eventos', 'App\Http\Controllers\ParticipanteController@listEvents')->middleware(['auth:web'])->name('participante.list_events');

Route::post('admin/participantes/{id}/nova-cobranca', 'App\Http\Controllers\MercadoPagoController@novaCobranca')->middleware(['auth:web'])->name('participante.nova_cobranca');

// Admin Order Management (Cancel/Refund)
Route::post('admin/orders/{id}/cancel', 'App\Http\Controllers\OrderController@cancel')->middleware(['auth:web'])->name('admin.order.cancel');
Route::post('admin/orders/{id}/refund', 'App\Http\Controllers\OrderController@refund')->middleware(['auth:web'])->name('admin.order.refund');
Route::get('admin/orders/{id}', 'App\Http\Controllers\OrderController@show')->middleware(['auth:web'])->name('admin.order.show');

Route::get('admin/events/{id}/questions', 'App\Http\Controllers\EventController@questions')->middleware(['auth:web'])->name('event.questions');
Route::post('admin/events/{id}/questions/create', 'App\Http\Controllers\EventController@create_questions')->middleware(['auth:web'])->name('event.questions.create');

// Mercado Pago Webhooks
Route::post('/webhooks/mercado-pago/notification', 'App\Http\Controllers\MercadoPagoController@notification');
Route::get('/webhooks/mercado-pago/check-linked-account', 'App\Http\Controllers\MercadoPagoController@checkLinkedAccount')->middleware(['auth:participante', 'verified']);

// Mercado Pago OAuth Callback
Route::get('/mercado-pago/link-account', 'App\Http\Controllers\MercadoPagoController@linkAccount')->middleware(['auth:participante', 'verified'])->name('mercado-pago.link-account');

require __DIR__.'/auth.php';
