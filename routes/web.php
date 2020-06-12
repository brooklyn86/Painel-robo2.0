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

Route::get('/', function () {
    return Redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('getUsuarios', ['as' => 'get.usuarios', 'uses' => 'UserController@returnUserPlataforma']);
	Route::get('getUsuario/{id}', ['as' => 'get.usuarios_atualiza', 'uses' => 'UserController@getUser']);
	Route::get('user/{id}/excluir', ['as' => 'get.delete_usuario', 'uses' => 'UserController@delete']);
	Route::post('atualiza/usuario', ['as' => 'atualiza.usuario', 'uses' => 'UserController@update']);
	Route::post('register/usuario', ['as' => 'register.usuario', 'uses' => 'UserController@register']);
	
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});
Route::group(['middleware' => 'auth', 'namespace' => 'Processo', 'prefix' => 'processos'], function () {
	Route::get('view/robo/{id}/processos', ['as' => 'process.view', 'uses' => 'ProcessoController@index']);
	Route::get('{id}/view', ['as' => 'process.view.view', 'uses' => 'ProcessoController@show']);
	Route::get('situacao/processual/{login}', ['as' => 'process.situacao', 'uses' => 'ProcessoController@situacaoProcessual']);
	Route::get('get/situacao/processual/{login}', ['as' => 'getprocess.situacao', 'uses' => 'ProcessoController@returnProcessSituacao']);
	Route::get('view/acordos/{id}/processo', ['as' => 'acordo.situacao', 'uses' => 'ProcessoController@acordoProcessual']);


	Route::post('update/processo', ['as' => 'atualiza.processo', 'uses' => 'ProcessoController@update']);
	Route::get('getprocessosDatatables/{id}', ['as' => 'get.process', 'uses' => 'ProcessoController@returnProcessDatatable']);

});
Route::group(['middleware' => 'auth', 'namespace' => 'Robo',  'prefix' => 'robo'], function () {
	Route::get('create/bot', ['as' => 'bot.create', 'uses' => 'RoboController@create']);
	Route::get('getbotdatatables', ['as' => 'get.bots', 'uses' => 'RoboController@returnRoboDatatable']);
	Route::post('create/bot', ['as' => 'create.bot.post', 'uses' => 'RoboController@store']);
	Route::get('disabled/bot', ['as' => 'disabled.bot.post', 'uses' => 'RoboController@disabledBot']);
	Route::get('enabled/bot', ['as' => 'enabled.bot.post', 'uses' => 'RoboController@activateBot']);
});

