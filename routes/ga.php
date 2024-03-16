<?php
Route::group(['prefix' => 'ga'], function () {
    // uniform
    Route::resource('puniform', 'GaPengajuanUniController');
    Route::get('mobile/puniform/hapus/{param}', [
        'as' => 'mobiles.hapusuniform',
        'uses' => 'GaPengajuanUniController@hapus'
    ]);
    Route::post('mobile/gettanggal-uniform', [
        'as' => 'mobiles.gettanggal',
        'uses' => 'GaPengajuanUniController@get_tanggal'
    ]);
    Route::get('mobile/getmasteruniform', [
        'as' => 'mobiles.getmasteruniform',
        'uses' => 'GaPengajuanUniController@getmasteruniform'
    ]);
    Route::get('mobile/index_user/puniform', [
        'as' => 'mobiles.useruni',
        'uses' => 'GaPengajuanUniController@index_user'
    ]);
    Route::get('mobile/index_karyawan/puniform', [
        'as' => 'mobiles.karyawanuni',
        'uses' => 'GaPengajuanUniController@index_karyawan'
    ]);
    Route::get('mobile/dashboarduser/puniform', [
        'as' => 'mobiles.dashboardpuniformuser',
        'uses' => 'GaPengajuanUniController@dashboardpuniformuser'
    ]);
    Route::get('mobile/index_user/puniform/{param1}', [
        'as' => 'mobiles.cekterakhiruni',
        'uses' => 'GaPengajuanUniController@getTerakhirMinta'
    ]);
    Route::post('mobile/user/puniform/submit', [
        'as' => 'mobiles.usrsubmituni',
        'uses' => 'GaPengajuanUniController@user_submit'
    ]);
    Route::get('mobile/approval/atasan/puniform', [
        'as' => 'mobiles.atasanuni',
        'uses' => 'GaPengajuanUniController@index_atasan'
    ]);
    Route::get('mobile/approval/atasan/puniform/approve/{param1}', [
        'as' => 'mobiles.uniatasanapprove',
        'uses' => 'GaPengajuanUniController@atasan_approve'
    ]);
    Route::get('mobile/approval/atasan/puniform/reject/{param1}', [
        'as' => 'mobiles.uniatasanreject',
        'uses' => 'GaPengajuanUniController@atasan_reject'
    ]);
    Route::get('mobile/approval/atasan/puniform/data', [
        'as' => 'mobiles.dashboardpuniformatasan',
        'uses' => 'GaPengajuanUniController@dashboardpuniformatasan'
    ]);
    Route::get('mobile/approval/ga/puniform', [
        'as' => 'mobiles.uniga',
        'uses' => 'GaPengajuanUniController@index_ga'
    ]);
    Route::get('mobile/approval/ga/puniform/data', [
        'as' => 'mobiles.dashboardpuniformga',
        'uses' => 'GaPengajuanUniController@dashboardpuniformga'
    ]);
    Route::post('mobile/approval/ga/puniform/approve', [
        'as' => 'mobiles.unigaapprove',
        'uses' => 'GaPengajuanUniController@ga_approve'
    ]);
    Route::get('mobile/dashboard/puniform', [
        'as' => 'mobiles.dashboardpuniform',
        'uses' => 'GaPengajuanUniController@dashboardpuniform'
    ]);
    Route::get('mobile/approval/pengga/puniform', [
        'as' => 'mobiles.unipengga',
        'uses' => 'GaPengajuanUniController@index_pengga'
    ]);
    Route::get('mobile/approval/pengga/puniform/data', [
        'as' => 'mobiles.dashboardpuniformpengga',
        'uses' => 'GaPengajuanUniController@dashboardpuniformpengga'
    ]);
    Route::post('mobile/approval/pengga/puniform/approve', [
        'as' => 'mobiles.unipenggaapprove',
        'uses' => 'GaPengajuanUniController@pengga_approve'
    ]);
    Route::get('mobile/approval/pengga/puniform/{param1}', [
        'as' => 'mobiles.getstok',
        'uses' => 'GaPengajuanUniController@getStok'
    ]);
    Route::get('mobile/index_stok/puniform', [
        'as' => 'mobiles.stokuni',
        'uses' => 'GaPengajuanUniController@index_stok'
    ]);
    Route::get('mobile/dashboardstok/puniform', [
        'as' => 'mobiles.dashboardpuniformstok',
        'uses' => 'GaPengajuanUniController@dashboardpuniformstok'
    ]);
    Route::post('mobile/puniformstok/store', [
        'as' => 'mobiles.stokstore',
        'uses' => 'GaPengajuanUniController@stok_store'
    ]);
    Route::get('mobile/puniformstok/hapus/{param1}/{param2}', [
        'as' => 'mobiles.hapusstok',
        'uses' => 'GaPengajuanUniController@stok_hapus'
    ]);
    Route::put('mobile/puniformstok/update/{param1}/{param2}', [
        'as' => 'mobiles.updatestok',
        'uses' => 'GaPengajuanUniController@stok_update'
    ]);
    Route::get('mobile/index_karyawan/jatuh_tempo_karyawan', [
        'as' => 'mobiles.jatuh_tempo_karyawan',
        'uses' => 'GaPengajuanUniController@index_karyawan_tempo'
    ]);
    Route::get('mobile/index_karyawan/dashboard/jatuh_tempo_karyawan', [
        'as' => 'mobiles.dasboard_tempo_karyawan',
        'uses' => 'GaPengajuanUniController@dsahboardtempokaryawan'
    ]);
    Route::get('uniform/userguide', [
        'as' => 'uniform.userguide',
        'uses' => 'GaPengajuanUniController@userguide'
    ]);
    // Route::get('mobile/lpbuniform/', [
    //     'as' => 'mobiles.indexlpbuni',
    //     'uses' => 'GaLpbUniformsController@indexlpbuni'
    // ]);
    // Route::get('mobile/dashboard/lpbuniform', [
    //     'as' => 'mobiles.dashboardlpbuni',
    //     'uses' => 'GaLpbUniformsController@dashboardlpbuni'
    // ]);
    // Route::get('mobile/lpbuniform/create', [
    //     'as' => 'mobiles.createlpbuni',
    //     'uses' => 'GaLpbUniformsController@createlpbuni'
    // ]);
    // Route::post('mobile/lpbuniform/store', [
    //     'as' => 'mobiles.storelpbuni',
    //     'uses' => 'GaLpbUniformsController@storelpbuni'
    // ]);
    // Route::get('mobile/lpbuniform/{param}', [
    //     'as' => 'mobiles.showlpbuni',
    //     'uses' => 'GaLpbUniformsController@showlpbuni'
    // ]);
    // Route::get('mobile/lpbuniform/{param}/print', [
    //     'as' => 'mobiles.printlpbuni',
    //     'uses' => 'GaLpbUniformsController@printlpbuni'
    // ]);
    // Route::get('mobile/mutasiuniform/', [
    //     'as' => 'mobiles.indexmutasiuni',
    //     'uses' => 'GaLpbUniformsController@indexmutasiuni'
    // ]);
    // Route::get('mobile/dashboard/mutasiuniform', [
    //     'as' => 'mobiles.dashboardmutasiuni',
    //     'uses' => 'GaLpbUniformsController@dashboardmutasiuni'
    // ]);
    // Route::post('mobile/mutasiuniform/print', [
    //     'as' => 'mobiles.printmutasiuni',
    //     'uses' => 'GaLpbUniformsController@printmutasiuni'
    // ]);
    // Route::get('mobile/mutasiuniform/createmutasi', [
    //     'as' => 'mobiles.createmutasi',
    //     'uses' => 'GaLpbUniformsController@createmutasi'
    // ]);
    // Route::post('mobile/mutasiuniform/storemutasi', [
    //     'as' => 'mobiles.storemutasi',
    //     'uses' => 'GaLpbUniformsController@storemutasi'
    // ]);
    // Route::get('mobile/mutasiuniform/createsto/{param}/{param2}/{param3}', [
    //     'as' => 'mobiles.createsto',
    //     'uses' => 'GaLpbUniformsController@createsto'
    // ]);
    // Route::post('mobile/mutasiuniform/storesto', [
    //     'as' => 'mobiles.storesto',
    //     'uses' => 'GaLpbUniformsController@storesto'
    // ]);
    // master uniform
    Route::get('mobile/uniform/ga/master', [
        'as' => 'mobiles.uniformappr_ga_master',
        'uses' => 'UniformGAController@get_master_uniform'
    ]);
});
