<?php

Route::group(['prefix' => 'hr'], function () {
    Route::group(['prefix' => 'mobile'], function(){
        Route::group(['prefix' => 'rekrutment'], function(){
            Route::get('form-lamaran/', [
                'as' => 'rekrutmentWeb.formLamaran',
                'uses' => 'rekrutmenHrController@lamaran',
                'id_programmer' => 'Y1A1NRiz51'
            ]);
            Route::get('form-application/', [
                'as' => 'rekrutmentWeb.formApplication',
                'uses' => 'rekrutmenHrController@form',
                'id_programmer' => 'Y1A1NRiz51'
            ]);

        });
    });
});
