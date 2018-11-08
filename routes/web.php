<?php

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

Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware'=>['auth']],function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' => ['Administrador']], function () {

        Route::get('/clientes','ClienteController@index');
        Route::post('/clientes/registrar','ClienteController@store');
        Route::put('/clientes/actualizar','ClienteController@update');
        Route::put('/clientes/borrar','ClienteController@destroy');
        Route::get('/clientes/selectCliente','ClienteController@selectCliente');

        Route::get('/pedidos','PedidoController@index');
        Route::post('/pedidos/registrar','PedidoController@store');
        Route::put('/pedidos/actualizar','PedidoController@update');
        Route::put('/pedidos/borrar','PedidoController@destroy');

        Route::get('/usuarios','UserController@index');
        Route::post('/usuarios/registrar','UserController@store');
        Route::put('/usuarios/actualizar','UserController@update');
        Route::put('/usuarios/borrar','UserController@destroy');

        Route::get('/rol/selectRol','RolController@selectRol');

    });

    Route::group(['middleware' => ['Usuario']], function () {

        Route::get('/clientes','ClienteController@index');
        Route::post('/clientes/registrar','ClienteController@store');
        Route::put('/clientes/actualizar','ClienteController@update');
        Route::put('/clientes/borrar','ClienteController@destroy');
        Route::get('/clientes/selectCliente','ClienteController@selectCliente');

    });

});