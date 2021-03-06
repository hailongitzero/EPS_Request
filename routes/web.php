<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::middleware(['middleware' => 'auth'])->group(function () {
    
    //Route::get('/logout', function (){
    //    return redirect('login')->with(Auth::logout());
    //});
    Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index'])->name('home');

    Route::get('/request', ['as' => 'request', 'uses' => 'HomeController@Request']);

    Route::any('/get-user-phong-ban', 'HomeController@getUserList');

    //tạo mới yêu cầu
    Route::post('/request-receipt', 'HomeController@receiptRequest');
    Route::get('/request-receipt', 'HomeController@receiptRequest');

    //tiếp nhận yêu cầu mới và phân công xử lý
    Route::get('/request-manage', 'RequestController@RequestManagement')->middleware('checkAdmin');
    Route::any('/get-request', 'RequestController@getRequest');
    Route::get('/request-assign/{id}', 'RequestController@requestAssign')->name('assignRequest'); //middleware('checkAdmin')

    //Danh sách các yêu cầu đã giao
    Route::get('/request-pending-manage', 'RequestController@RequestPendingManagement')->middleware('checkAdmin');

    //Danh sách các yêu cầu đã hoàn thành
    Route::get('/request-complete-manage', 'RequestController@RequestCompleteManagement')->middleware('checkAdmin');

    Route::get('/request-assign-set', 'RequestController@requestAssignSet')->middleware('checkAdmin');
    Route::post('/request-assign-set', 'RequestController@requestAssignSet')->middleware('checkAdmin');

    //danh sách các yêu cầu cần xử lý
    Route::get('/request-handle', 'RequestController@requestHandle');

    //danh sách các yêu cầu cần xử lý đã hoàn thành
    Route::get('/request-handle-complete', 'RequestController@requestHandleComplete');

    //chi tiết yêu cầu cần xử lý
    Route::get('/request-update/{id}', 'RequestController@requestUpdate')->name('requestUpdate');

    //cập nhật trạng thái yêu cầu
    Route::post('/request-update-status', 'RequestController@requestUpdateStatus');
    Route::get('/request-update-status', 'RequestController@requestUpdateStatus');

    //danh sách yêu cầu của người dùng
    Route::get('/my-request', 'RequestController@myRequest');
    Route::get('/request-detail/{id}', 'RequestController@myRequestDetail');

    Route::get('/get-request-data-by-month', 'HomeController@getRequestAnalysisByMonth');
    Route::post('/get-request-data-by-month', 'HomeController@getRequestAnalysisByMonth');

    Route::get('/get-total-req-by-dept-and-date', 'HomeController@getTotalRequestByDepartmentAndDate');
    Route::post('/get-total-req-by-dept-and-date', 'HomeController@getTotalRequestByDepartmentAndDate');

    Route::get('/get-total-req-by-type-and-date', 'HomeController@getTotalRequestByRequestTypeAndDate');
    Route::post('/get-total-req-by-type-and-date', 'HomeController@getTotalRequestByRequestTypeAndDate');

    Route::get('/get-user-info', 'HomeController@getUserInfo');
    Route::post('/get-user-info', 'HomeController@getUserInfo');

    Route::get('/mail', 'HomeController@sendTestMail');

    Route::get('/fileDownload/{id}', 'RequestController@fileDownload');

    Route::post('/ckupload', 'FileController@ckUploadImage');

    Route::get('/manual-document', 'FileController@fileManualManage');

    Route::get('/approval-request', 'RequestController@approvalRequest');

    Route::get('/request-extend-set', 'RequestController@requestExtendSet')->middleware('checkAdmin');
    Route::post('/request-extend-set', 'RequestController@requestExtendSet')->middleware('checkAdmin');
    
});
