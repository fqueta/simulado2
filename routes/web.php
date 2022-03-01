<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GerenciarGrupo;
use App\Http\Controllers\GerenciarUsuarios;
use App\Http\Controllers\GerenciarRelatorios;
use App\Http\Controllers\FamiliaController;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Auth;

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

Route::prefix('users')->group(function(){
    Route::get('/',[UserController::class,'index'])->name('users.index');

    Route::get('/ajax',[UserController::class,'paginacaoAjax'])->name('users.ajax');
    Route::get('/lista.ajax',function(){
        return view('users.index_ajax');
    });

    Route::get('/create',[UserController::class,'create'])->name('users.create');
    Route::post('/',[UserController::class,'store'])->name('users.store');
    Route::get('/{id}/show',[UserController::class,'show'])->where('id', '[0-9]+')->name('users.show');
    Route::get('/{id}/edit',[UserController::class,'edit'])->where('id', '[0-9]+')->name('users.edit');
    Route::put('/{id}',[UserController::class,'update'])->where('id', '[0-9]+')->name('users.update');
    Route::delete('/{id}',[UserController::class,'destroy'])->where('id', '[0-9]+')->name('users.destroy');
});
Route::prefix('familias')->group(function(){
    Route::get('/',[FamiliaController::class,'index'])->name('familias.index');

    Route::get('/ajax',[FamiliaController::class,'paginacaoAjax'])->name('familias.ajax');
    Route::get('/lista.ajax',function(){
        return view('users.index_ajax');
    });

    Route::get('/create',[FamiliaController::class,'create'])->name('familias.create');
    Route::post('/',[FamiliaController::class,'store'])->name('familias.store');
    Route::get('/{id}/show',[FamiliaController::class,'show'])->name('familias.show');
    Route::get('/{id}/edit',[FamiliaController::class,'edit'])->name('familias.edit');
    Route::put('/{id}',[FamiliaController::class,'update'])->where('id', '[0-9]+')->name('familias.update');
    Route::post('/{id}',[FamiliaController::class,'update'])->where('id', '[0-9]+')->name('familias.update-ajax');
    Route::delete('/{id}',[FamiliaController::class,'destroy'])->where('id', '[0-9]+')->name('familias.destroy');
    Route::get('export/all', [FamiliaController::class, 'exportAll'])->name('familias.export_all');
    Route::get('export/filter', [FamiliaController::class, 'exportFilter'])->name('familias.export_filter');
});

Route::prefix('uploads')->group(function(){
    Route::get('/',[uploadController::class,'index'])->name('uploads.index');

    Route::get('/ajax',[uploadController::class,'paginacaoAjax'])->name('uploads.ajax');
    Route::get('/lista.ajax',function(){
        return view('users.index_ajax');
    });

    Route::get('/create',[UploadController::class,'create'])->name('uploads.create');
    Route::post('/',[UploadController::class,'store'])->name('uploads.store');
    Route::get('/{id}/show',[UploadController::class,'show'])->name('uploads.show');
    Route::get('/{id}/edit',[UploadController::class,'edit'])->name('uploads.edit');
    Route::put('/{id}',[UploadController::class,'update'])->where('id', '[0-9]+')->name('uploads.update');
    Route::post('/{id}',[UploadController::class,'update'])->where('id', '[0-9]+')->name('uploads.update-ajax');
    Route::post('/{id}',[UploadController::class,'destroy'])->where('id', '[0-9]+')->name('uploads.destroy');
    Route::get('export/all', [UploadController::class, 'exportAll'])->name('uploads.export_all');
    Route::get('export/filter', [UploadController::class, 'exportFilter'])->name('uploads.export_filter');
});

Route::fallback(function () {
    return view('erro404');
});
Route::get('/teste',[App\Http\Controllers\TesteController::class,'index'])->name('teste');
Route::post('/upload',[App\Http\Controllers\UploadFile::class,'upload'])->name('teste.upload');

Auth::routes();

Route::get('/',function(){
  return redirect()->route('login');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
