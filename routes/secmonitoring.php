<?php
// Route::resource('secmonitoring', 'SecMonitoringController');

Route::group(['prefix' => 'secmonitoring'], function () {
    Route::get('', [
        'as' => 'secmonitoring.index',
        'uses' => 'SecMonitoringController@index'
    ]);
    Route::get('create/', [
        'as' => 'secmonitoring.create',
        'uses' => 'SecMonitoringController@create'
    ]);
    Route::post('store/', [
        'as' => 'secmonitoring.store',
        'uses' => 'SecMonitoringController@store'
    ]);
    Route::get('approval/', [
        'as' => 'secmonitoring.approval',
        'uses' => 'SecMonitoringController@approval'
    ]);
    Route::get('infosubmit/{id_monitoring}', [
        'as' => 'secmonitoring.infosubmit',
        'uses' => 'SecMonitoringController@infosubmit'
    ]);
    Route::get('dashboard/', [
        'as' => 'secmonitoring.dashboard',
        'uses' => 'SecMonitoringController@dashboard'
    ]);
    Route::get('dashboardapproval/', [
        'as' => 'secmonitoring.dashboardapproval',
        'uses' => 'SecMonitoringController@dashboardapproval'
    ]);
    Route::get('fetchbelumapprove/', [
        'as' => 'secmonitoring.fetchbelumaprove',
        'uses' => 'SecMonitoringController@fetchbelumapprove'
    ]);
    Route::get('fetchsudahapprove/', [
        'as' => 'secmonitoring.fetchsudahapprove',
        'uses' => 'SecMonitoringController@fetchsudahapprove'
    ]);
    Route::get('infodashboard/{id_monitoring}', [
        'as' => 'secmonitoring.infodashboard',
        'uses' => 'SecMonitoringController@infodashboard'
    ]);
    Route::get('approvesubmit/{id_monitoring}', [
        'as' => 'secmonitoring.approvesubmit',
        'uses' => 'SecMonitoringController@approvesubmit'
    ]);
    Route::get('destroy/{id_monitoring}', [
        'as' => 'secmonitoring.destroy',
        'uses' => 'SecMonitoringController@destroy'
    ]);
});
