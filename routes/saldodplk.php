<?php

Route::group(['prefix' => 'hr'], function () {
    Route::get('saldo-DPLK/', [
        'as' => 'saldodplk.indexUpload',
        'uses' => 'SaldoDplkController@index'
    ]);
    Route::post('saldo-DPLK/upload', [
        'as' => 'saldodplk.upload',
        'uses' => 'SaldoDplkController@importFile'
    ]);
    Route::get('saldo-DPLK/insert', [
        'as' => 'saldodplk.insert',
        'uses' => 'SaldoDplkController@insertFile'
    ]);
    Route::get('saldo-DPLK/upload/check', [
        'as' => 'saldodplk.check',
        'uses' => 'SaldoDplkController@getDataTables'
    ]);
    Route::get('saldo-DPLK/delete/file', [
        'as' => 'saldodplk.deleteExcel',
        'uses' => 'SaldoDplkController@deleteFile'
    ]);
});
Route::get('saldo-DPLK/{npk}', [
    'as' => 'saldodplk.index',
    'uses' => 'SaldoDplkController@getSaldoDplk'
]);
