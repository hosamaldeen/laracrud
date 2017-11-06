<?php


Route::group(['prefix' => 'laracrud','namespace'=>'Hosamaldeen\LaraCRUD\Http\Controllers' ], function () {
    Route::get('/', 'LaraCrudController@index');
    Route::get('/model', 'LaraCrudController@model');
    Route::get('/crud', 'LaraCrudController@crud');
	
    Route::get('/api/check-model/{table}', 'ApiController@checkModel');
    Route::get('/api/check-base-model', 'ApiController@checkBaseModel');
    Route::get('/api/generate-base-model', 'ApiController@generateBaseModel');
    Route::post('/api/generate', 'ApiController@generate');
});