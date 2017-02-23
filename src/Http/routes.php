<?php


Route::group(['prefix' => 'laracrud','namespace'=>'HossamAhmed\LaraCRUD\Http\Controllers' ], function () {
    Route::get('/', 'LaraCrudController@index');
    Route::get('/model', 'LaraCrudController@model');
    Route::get('/crud', 'LaraCrudController@crud');
});