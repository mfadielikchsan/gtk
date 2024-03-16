<?php
Route::resource('pengajuandinasluar', 'KlaimDinasLuarController');
// Route::get('dashboard', )

Route::group(['prefix' => 'klaim-dinas-luar'], function () {
    Route::get('printdata/{id}', [
        'as' => 'pengajuandinasluar.printdata',
        'uses' => 'KlaimDinasLuarController@printData'
    ]);
    Route::get('get-jam-in/', [
        'as' => 'pengajuandinasluar.getJamInForValidation',
        'uses' => 'KlaimDinasLuarController@getJamInForValidation'
    ]);
    Route::get('index-ga/', [
        'as' => 'pengajuandinasluar.indexga',
        'uses' => 'KlaimDinasLuarController@index_ga'
    ]);
    // approval
    Route::get('approval/', [
        'as' => 'approvalpengajuandinasluar.indexapprovalpengajuandinasluar',
        'uses' => 'KlaimDinasLuarController@indexApprovalKlaimDinasLuar'
    ]);
    Route::get('approval/{id}', [
        'as' => 'approvalpengajuandinasluar.showapprovalpengajuandinasluar',
        'uses' => 'KlaimDinasLuarController@showApprovalKlaimDinasLuar'
    ]);
    Route::put('approve/', [
        'as' => 'klaimdinasluar.approveAtasan',
        'uses' => 'KlaimDinasLuarController@approvalAtasan'
    ]);
    Route::put('approve-ga/', [
        'as' => 'klaimdinasluar.approveGA',
        'uses' => 'KlaimDinasLuarController@approvalGA'
    ]);
    // rekap - ga
    Route::get('rekap-ga/', [
        'as' => 'klaimdinasluar.indexRekapGa',
        'uses' => 'KlaimDinasLuarController@index_rekap_ga'
    ]);
    // ajax : get no_batch berdasarkan kd_pt
    Route::get('rekap-ga/get-no-batch-kdpt', [
        'as' => 'klaimdinasluar.getNoBatchPt',
        'uses' => 'KlaimDinasLuarController@getBatchByPt'
    ]);
    // create batch rekap
    Route::get('rekap-ga/create-batch', [
        'as' => 'klaimdinasluar.createRekapGa',
        'uses' => 'KlaimDinasLuarController@createBatchRekap'
    ]);
    Route::post('rekap-ga/store-batch', [
        'as' => 'klaimdinasluar.storeBatchGa',
        'uses' => 'KlaimDinasLuarController@storeBatchGA'
    ]);
    // ajax : get no_batch and last data for create batch
    Route::get('rekap-ga/get-last-batch', [
        'as' => 'klaimdinasluar.getlastDataBatch',
        'uses' => 'KlaimDinasLuarController@getNoBatch'
    ]);
    Route::get('print-rekap-ga/{no_batch}', [
        'as' => 'klaimdinasluar.printRekapGa',
        'uses' => 'KlaimDinasLuarController@print_rekap_ga'
    ]);
    // index_finance
    Route::get('finance-cek-data/', [
        'as' => 'klaimdinasluar.indexFinance',
        'uses' => 'KlaimDinasLuarController@indexFinance'
    ]);
    // untuk datatable
    Route::group(['prefix' => 'list'], function () {
        Route::get('pengajuandinasluar/listpengajuandinasluar', [
            'as' => 'pengajuandinasluar.listpengajuandinasluar',
            'uses' => 'KlaimDinasLuarController@listpengajuandinasluar'
        ]);
        Route::get('pengajuandinasluar/listapprovalpengajuandinasluar', [
            'as' => 'approvalpengajuandinasluar.listapprovalpengajuandinasluar',
            'uses' => 'KlaimDinasLuarController@listKlaimDinasLuarByNpkAtasan'
        ]);
        Route::get('pengajuandinasluar/listapprovalpengajuandinasluarnotapproved', [
            'as' => 'approvalpengajuandinasluar.listapprovalpengajuandinasluarnotapproved',
            'uses' => 'KlaimDinasLuarController@listKlaimDinasLuarByNpkAtasanNotApproved'
        ]);
        Route::get('pengajuandinasluar/listapprovalpengajuandinasluarapproved', [
            'as' => 'approvalpengajuandinasluar.listapprovalpengajuandinasluarapproved',
            'uses' => 'KlaimDinasLuarController@listKlaimDinasLuarByNpkAtasanApproved'
        ]);
        Route::get('pengajuandinasluar/listallpengajuandinasluarapproved', [
            'as' => 'pengajuandinasluar.listallpengajuandinasluarapproved',
            'uses' => 'KlaimDinasLuarController@listKlaimDinasLuarApproveAtasan'
        ]);
        Route::get('pengajuandinasluar/listallpengajuandinasluarapprovedga', [
            'as' => 'pengajuandinasluar.listallpengajuandinasluarapprovedga',
            'uses' => 'KlaimDinasLuarController@listKlaimDinasLuarApprovedGA'
        ]);
        Route::get('pengajuandinasluar/listallpengajuandinasluarnotapproved', [
            'as' => 'pengajuandinasluar.listallpengajuandinasluarnotapproved',
            'uses' => 'KlaimDinasLuarController@listKlaimDinasLuarNotApproveAtasan'
        ]);
        Route::get('pengajuandinasluar/listallpengajuandinasluarapprovedbyga', [
            'as' => 'pengajuandinasluar.listallpengajuandinasluarapprovedbyga',
            'uses' => 'KlaimDinasLuarController@listKlaimDinasLuarApprovedByGA'
        ]);
        Route::get('pengajuandinasluar/listallpengajuandinasluarbynobatch', [
            'as' => 'pengajuandinasluar.listallpengajuandinasluarbynobatch',
            'uses' => 'KlaimDinasLuarController@listKlaimDinasLuarByBatch'
        ]);
    });
});
