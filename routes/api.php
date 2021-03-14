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
Route::group(['prefix' => 'auth'], function () {
    
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::get('signup/activate/{token}', 'AuthController@signupActivate'); // Not use
    
    // https://laravel.com/docs/8.x/passwords
    Route::post('forgot-password', 'AuthController@forgotPassword');
    Route::get('reset-password/{token}', 'AuthController@showResetForm')->name('password.reset'); // Esto solo es un arreglo. 
    // Route::get('email/verify/{id}/{hash}', 'AuthController@emailVerification')->middleware(['auth:api', 'signed'])->name('verification.verify'); // Esto solo es un arreglo. 
    Route::post('reset-password', 'AuthController@resetPassword');
    
    Route::get('email/verify/{id}/{hash}', 'AuthController@emailVerification')->middleware(['signed'])->name('verification.verify');
    Route::post('email/verification-notification', 'AuthController@verificationNotification')->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

    // https://laravel.com/docs/8.x/passwords
    // https://cvallejo.medium.com/sistema-de-autenticaci%C3%B3n-api-rest-con-laravel-5-6-parte-4-7365cc22d78b
    // https://stackoverflow.com/questions/52362927/laravel-email-verification-5-7-using-rest-api

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::get('persona', 'AuthController@persona');
        Route::put('user/{id}/passsword-request', 'AuthController@passwordRequest');
        // Route::post('change-password', 'AuthController@change_password');
    });
});

// Route::get('/reset-password/{token}', function ($token) {
//     return view('auth.reset-password', ['token' => $token]);
// })->middleware('guest')->name('password.reset');

// Route::group(['prefix' => 'password', 'namespace' => 'Auth', 'middleware' => 'auth:api'], function () {    
//     Route::post('create', 'PasswordResetController@create');
//     Route::get('find/{token}', 'PasswordResetController@find')->name('password.request');
//     Route::post('reset', 'PasswordResetController@reset');
// });


Route::group(['middleware' => ['auth:api', 'verified', 'checkstatus'], 'namespace' => 'Setup'], function () {

    // Personas
    Route::get('personas/in-session', 'PersonaController@index');
    Route::get('personas', 'PersonaController@index');
    Route::post('personas', 'PersonaController@store');
    Route::put('personas/{id}', 'PersonaController@update');
    Route::get('personas/{id}', 'PersonaController@show');
    Route::delete('personas/{id}', 'PersonaController@destroy');
    
    // Persona documentos
    Route::get('personas/{id}/documentos', 'PersonaDocumentoController@getByPersonaId');
    Route::post('persona-documentos', 'PersonaDocumentoController@store');
    Route::post('persona-documentos/{num_doc}', 'PersonaDocumentoController@update');
    Route::get('persona-documentos/{num_doc}', 'PersonaDocumentoController@show');
    Route::delete('persona-documentos/{num_doc}', 'PersonaDocumentoController@destroy');

    // Tipo documentos
    Route::get('tipo-documentos', 'TipoDocumentoController@index');
    // Tipo telefonos
    Route::get('tipo-telefonos', 'TipoTelefonoController@index');
    // Tipo virtuals
    Route::get('tipo-virtuals', 'TipoVirtualController@index');

    // Persona dirección virtuals
    Route::get('personas/{id}/virtuals', 'PersonaVirtualController@getByPersonaId');
    Route::post('persona-virtuals', 'PersonaVirtualController@store');
    Route::put('persona-virtuals/{id}', 'PersonaVirtualController@update');
    Route::get('persona-virtuals/{id}', 'PersonaVirtualController@show');
    Route::delete('persona-virtuals/{id}', 'PersonaVirtualController@destroy');

    // Persona teléfonos
    Route::get('personas/{id}/telefonos', 'PersonaTelefonoController@getByPersonaId');
    Route::post('persona-telefonos', 'PersonaTelefonoController@store');
    Route::put('persona-telefonos/{id}', 'PersonaTelefonoController@update');
    Route::get('persona-telefonos/{id}', 'PersonaTelefonoController@show');
    Route::delete('persona-telefonos/{id}', 'PersonaTelefonoController@destroy');

    // Persona dirección
    Route::get('personas/{id}/direccions', 'PersonaDireccionController@getByPersonaId');
    Route::post('persona-direccions', 'PersonaDireccionController@store');
    Route::put('persona-direccions/{id}', 'PersonaDireccionController@update');
    Route::get('persona-direccions/{id}', 'PersonaDireccionController@show');
    Route::delete('persona-direccions/{id}', 'PersonaDireccionController@destroy');
    
    // Ubigeos
    Route::get('ubigeos', 'UbigeosController@getUbigeosParents');
    Route::get('ubigeos/{parent_id}/childs', 'UbigeosController@getUbigeosByParentId');
    
});

Route::group(['middleware' => ['auth:api', 'verified', 'checkstatus'], 'namespace' => 'SetupSystem'], function () {

    // Modulos
    Route::get('modulos', 'ModuloController@index');
    Route::get('modulos/parents', 'ModuloController@getParents');
    Route::get('modulos/parents-childs', 'ModuloController@getParentAndChilds');
    Route::get('modulos/parents-childs-sidenav/{modulo_codigo}', 'ModuloController@getParentAndChildsToSidenav');
    Route::get('modulos/{modulo_codigo}/modulo-accions', 'ModuloController@getModuloAccions');
    Route::post('modulos', 'ModuloController@store');
    Route::put('modulos/{modulo_id}', 'ModuloController@update');
    Route::get('modulos/{modulo_id}', 'ModuloController@show');
    Route::delete('modulos/{modulo_id}', 'ModuloController@destroy');

    // Modulo accions
    Route::get('modulo-accions', 'ModuloAccionController@index');
    Route::get('modulo-accions/{modulo_accion_id}', 'ModuloAccionController@show');
    Route::post('modulo-accions', 'ModuloAccionController@store');
    Route::put('modulo-accions/{modulo_accion_id}', 'ModuloAccionController@update');
    Route::delete('modulo-accions/{modulo_accion_id}', 'ModuloAccionController@destroy');

    // Rols
    Route::get('rols', 'RolController@index');
    Route::get('rols/{id}', 'RolController@show');
    Route::put('rols/{id}', 'RolController@update');
    Route::get('rols/{id}/modulos', 'RolController@getModulos');
    Route::get('rols/{rol_id}/modulos/{modulo_id}/acciones', 'RolController@getAcciones');
    Route::post('rols/{rol_id}/modulos/{modulo_id}/acciones', 'RolController@storeAcciones');
    Route::delete('rols/{id}', 'RolController@destroy');
    Route::post('rols', 'RolController@store');
    Route::put('rols/{id}/activate', 'RolController@activate');
    Route::post('rols/{id}/modulos', 'RolController@storeModulos');

    // Rol Módulo
    //Route::post('rol-modulos', 'RolModuloController@store');
    //Route::delete('rol-modulos/{id}/by-rol-y-modulo', 'RolModuloController@destroyByRolIdAndModuloId');

    // Users
    Route::get('users', 'UserController@index');
    Route::get('users/{id}', 'UserController@show');
    Route::post('users', 'UserController@store');
    // Route::post('users/existe', 'UserController@storeExiste');
    Route::put('users/{id}/onlyemail', 'UserController@updateEmail');
    Route::post('users/{id}', 'UserController@update');
    Route::delete('users/{id}', 'UserController@destroy');
    Route::post('users/{id}/rols', 'UserController@storeRols');
    Route::get('users/{id}/rols', 'UserController@getRols');
    Route::post('users/{id}/verification-notification', 'UserController@verificationNotification');
    
});
