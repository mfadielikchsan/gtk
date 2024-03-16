<?php

Route::group(['prefix' => 'hr'], function () {
    Route::group(['prefix' => 'mobile'], function () {
        Route::group(['prefix' => 'penilaian-kinerja'], function () {
            Route::get('index-input', [
                'as' => 'hripk.indexInputPkKaryawan',
                'uses' => 'HrIPKController@index_input'
            ]);
            Route::get('input/{npk}/{tahun}/{kategori_jab}', [
                'as' => 'hripk.inputIpkAtasan',
                'uses' => 'HrIPKController@input_atasan'
            ]);
            Route::get('printPK/{npk}/{tahun}/{kategori_jab}', [
                'as' => 'hripk.printPKKaryawan',
                'uses' => 'HrIPKController@print_penilaianKaryaKaryawan'
            ]);
            Route::get('periode-pk', [
                'as' => 'hripk.indexPeriodeHRD',
                'uses' => 'HrIPKController@indexPeriode_hrd'
            ]);
            Route::get('datatable/periode', [
                'as' => 'hripk.datatablePeriodeHRD',
                'uses' => 'HrIPKController@datatablePeriode'
            ]);
            Route::get('datatable/get-bawahan', [
                'as' => 'hripk.datatableGetListBawahan',
                'uses' => 'HrIPKController@datatableGetListBawahan'
            ]);
            Route::get('ajax/getdetailperiode', [
                'as' => 'hripk.ajaxgetdetailperiode',
                'uses' => 'HrIPKController@getDetailPeriode'
            ]);
        });
    });
});
