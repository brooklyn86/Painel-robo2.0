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

Route::group(['prefix' => 'v1', 'namespace' => 'processo'], function () {
    Route::post('/cadastrar/processo', 'ProcessoController@store');
    Route::post('/upload/pdf', 'ProcessoController@upload');
    Route::post('/generate/processo/server', 'ProcessoController@extractPdfToBot');
    Route::post('/situacao/processo', 'ProcessoController@situacaoProcesso');

    
    Route::post('/submit/processo/domain', 'ProcessoController@submitProcessoDomain')->name('submit.processo.api');
});