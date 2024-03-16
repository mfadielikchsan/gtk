


<?php
Route::resource('coba1', 'CobaController');
// Route::get('dashboard', )

Route::group(['prefix' => 'awalan'], function () {
    Route::get('satu/', [
        'as' => 'coba.index',
        'uses' => 'CobaController@index'
    ]);
    Route::get('dua/', [
        'as' => 'coba.index2',
        'uses' => 'CobaController@index2'
    ]);
});
