<?php

Route::resource('joborder', 'JobOrderController');

Route::group(['prefix' => 'joborderlist'], function () {
    Route::get('listjoborder', [
        'as' => 'joborder.listjoborder',
        'uses' => 'JobOrderController@listJobOrderByNpkAtasan'
    ]);
    Route::get('listjoborderCancel', [
        'as' => 'joborder.listjoborderCancel',
        'uses' => 'JobOrderController@listJobOrderCancelByNpkAtasan'
    ]);
    Route::get('listjoborderDone', [
        'as' => 'joborder.listjoborderDone',
        'uses' => 'JobOrderController@listJobOrderDoneByNpkAtasan'
    ]);
    Route::put('cancelJobOrder/{id}', [
        'as' => 'joborder.canceljoborder',
        'uses' => 'JobOrderController@cancelJobOrder'
    ]);
    Route::post('updateDataJobOrder', [
        'as' => 'joborder.updatejoborder',
        'uses' => 'JobOrderController@updateDataJO'
    ]);
    Route::get('staff', [
        'as' => 'joborder.indexstaff',
        'uses' => 'JobOrderController@indexStaff'
    ]);
    Route::get('staff/{id}', [
        'as' => 'joborder.showstaff',
        'uses' => 'JobOrderController@showStaff'
    ]);
    Route::put('staff/progress/{id}', [
        'as' => 'joborder.updateprogressstaff',
        'uses' => 'JobOrderController@updateProgressStaff'
    ]);
    Route::put('staff/done/{id}', [
        'as' => 'joborder.donestaff',
        'uses' => 'JobOrderController@updateStatusDoneJobStaff'
    ]);
    Route::get('listJoborderStaff', [
        'as' => 'joborder.listjoborderstaff',
        'uses' => 'JobOrderController@listJobOrderStaffOnProgress'
    ]);
    Route::get('listJoborderStaffDone', [
        'as' => 'joborder.listjoborderstaffdone',
        'uses' => 'JobOrderController@listJobOrderStaffDone'
    ]);
});
