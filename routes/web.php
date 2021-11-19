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

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::resource('ship', 'ShipController');
Route::resource('sender', 'SenderController');
Route::resource('recipient', 'RecipientController');
Route::resource('bank', 'BankController');

Route::resource('deliveryorder', 'DeliveryOrderController');
Route::post('deliveryorder/search-list', 'DeliveryOrderController@searchList');
Route::post('deliveryorder/search-list-inv', 'DeliveryOrderController@searchListInv');
Route::post('deliveryorder/search-table-list', 'DeliveryOrderController@searchTableList');

Route::resource('shipschedule', 'ShipScheduleController');
Route::post('shipschedule/search-table-list', 'ShipScheduleController@searchTableList');

Route::resource('invoice', 'InvoiceController');
Route::post('invoice/getinvoiceno', 'InvoiceController@getInvoiceNo');
Route::post('invoice/get-unpaidlist', 'InvoiceController@getUnpaidList');
Route::post('invoice/search-table-list', 'InvoiceController@searchTableList');

Route::resource('cashierin', 'CashierinController');
Route::post('cashierin/search-table-list', 'CashierinController@searchTableList');

Route::get('report/loadinglist', 'ReportController@loadingList');
Route::post('report/loadinglist-excel', 'ReportController@loadingListExcel');
Route::get('report/deliveryorder', 'ReportController@deliveryOrder');
Route::post('report/deliveryorder-excel', 'ReportController@deliveryOrderExcel');
Route::get('report/invoice-excel', 'ReportController@invoiceExcel');
Route::get('report/invoice-jasper', 'ReportController@invoiceJasper');
Route::get('report/deliveryorder_report', 'ReportController@deliveryOrderReport');
Route::post('report/deliveryorder_report-excel', 'ReportController@deliveryOrderReportExcel');
Route::get('report/invoice_report', 'ReportController@invoiceReport');
Route::post('report/invoice_report-excel', 'ReportController@invoiceReportExcel');
Route::get('report/item_report', 'ReportController@itemReport');
Route::post('report/item_report-jasper', 'ReportController@itemReportJasper');
Route::get('report/container_report', 'ReportController@containerReport');
Route::post('report/container_report-excel', 'ReportController@containerReportExcel');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
