<?php

Route::group(['middleware' => ['auth']], function () {
    Route::get('home', function() {
        return view("index");
    })->name("dashboard");
    Route::get('logout', [
        'as' => 'logout',
        'uses' => 'Auth\LoginController@logout'
    ]);
});

Route::group(['middleware' => ['guest']], function () {
    Route::get('', function() {
        return view("auth.login");
    });
    Route::post('login', [
        'as' => 'login',
        'uses' => 'Auth\LoginController@login'
    ]);
    Route::get('register', function() {
        return view("auth.register");
    });
    Route::post('registercreate', [
        'as' => 'registercreate',
        'uses' => 'Auth\LoginController@createRegister'
    ]);
});



Route::group(['prefix' => 'portalgtk', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'pelanggan'], function () {
        Route::get('indexpelanggan', [
            'as' => 'portalgtk.indexpelanggan',
            'uses' => 'PortalGtkController@indexpelanggan'
        ]);
        Route::post('storepesanan/', [
            'as' => 'portalgtk.storepesanan',
            'uses' => 'PortalGtkController@storepesanan'
        ]);
        Route::get('dttablepelanggan', [
            'as' => 'portalgtk.dttablepelanggan',
            'uses' => 'PortalGtkController@dttablepelanggan'
        ]);
        Route::delete('deletepesanan/{id}', [
            'as' => 'portalgtk.deletepesanan',
            'uses' => 'PortalGtkController@deletepesanan'
        ]);
    });
    Route::group(['prefix' => 'admin'], function () {
        Route::get('approvepesanan', [
            'as' => 'portalgtk.approvepesanan',
            'uses' => 'PortalGtkController@approvepesanan'
        ]);
        Route::get('dttableapprovepesanan', [
            'as' => 'portalgtk.dttableapprovepesanan',
            'uses' => 'PortalGtkController@dttableapprovepesanan'
        ]);
        Route::post('submitapprovepesanan/', [
            'as' => 'portalgtk.submitapprovepesanan',
            'uses' => 'PortalGtkController@submitapprovepesanan'
        ]);
        Route::get('printapprovalpesanan/{thn}/{bln}', [
            'as' => 'portalgtk.printapprovalpesanan',
            'uses' => 'PortalGtkController@printapprovalpesanan'
        ]);
        Route::get('monitoringstockbarang', [
            'as' => 'portalgtk.monitoringstockbarang',
            'uses' => 'PortalGtkController@monitoringstockbarang'
        ]);
        Route::get('hakaksesuser', [
            'as' => 'portalgtk.hakaksesuser',
            'uses' => 'PortalGtkController@hakaksesuser'
        ]);
        Route::get('dttablehakakses', [
            'as' => 'portalgtk.dttablehakakses',
            'uses' => 'PortalGtkController@dttablehakakses'
        ]);
        Route::get('changehakakses/{id}/{val}', [
            'as' => 'portalgtk.changehakakses',
            'uses' => 'PortalGtkController@changehakakses'
        ]);
    });
    Route::group(['prefix' => 'produksi'], function () {
        Route::get('inputstockbarang', [
            'as' => 'portalgtk.inputstockbarang',
            'uses' => 'PortalGtkController@inputstockbarang'
        ]);
        Route::post('storestock/', [
            'as' => 'portalgtk.storestock',
            'uses' => 'PortalGtkController@storestock'
        ]);
        Route::get('dttablestock', [
            'as' => 'portalgtk.dttablestock',
            'uses' => 'PortalGtkController@dttablestock'
        ]);
    });
});
