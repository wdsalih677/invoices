<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('invoices', InvoiceController::class);

Route::resource('section' , SectionController::class);

Route::resource('products' , ProductController::class);

Route::get('sections/{id}', [InvoiceController::class ,'getproducts']);

Route::resource('invoicesDetails', InvoiceDetailController::class );

Route::resource('invoicesAttachments',InvoiceAttachmentController::class);

Route::get('getDetails/{id}' ,[InvoiceDetailController::class , 'getDetails']);

Route::get('openFile/{invoice_number}/{file_name}',[InvoiceDetailController::class , 'openFile'])->name('openFile');

Route::get('downloadFile/{invoice_number}/{file_name}',[InvoiceDetailController::class , 'downloadFile']);

Route::post('delete_file',[InvoiceDetailController::class , 'destroy'])->name('delete_file');

Route::get('show_status/{id}',[InvoiceController::class ,'show_status'])->name('show_status');

Route::get('update_status/{id}',[InvoiceController::class ,'update_status'])->name('update_status');

Route::get('invoicesPaid',[InvoiceController::class ,'invoicesPaid'])->name('invoicesPaid');

Route::get('invoicesUnpaid',[InvoiceController::class ,'invoicesUnpaid'])->name('invoicesUnpaid');

Route::get('invoicesPartiallyPaid',[InvoiceController::class ,'invoicesPartiallyPaid'])->name('invoicesPartiallyPaid');

Route::get('/{page}',[AdminController::class,'index']);


