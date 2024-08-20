<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
    // return view('landing');
});

Route::get('login', 'App\Http\Controllers\AuthController@login')->name('login');
Route::post('login', 'App\Http\Controllers\AuthController@loginPost');
Route::get('logout', 'App\Http\Controllers\AuthController@logout')->middleware(['auth']);

Route::middleware(['auth'])->group(function () {
    Route::view('dasbor', 'dasbor');

    // Route::get('buat-permohonan', 'App\Http\Controllers\PermohonanController@index');
    // Route::post('buat-permohonan', 'App\Http\Controllers\PermohonanController@store');
    // Route::delete('buat-permohonan/{permohonan_kode}', 'App\Http\Controllers\PermohonanController@destroy');

    // Route::get('buat-permohonan/detail/{permohonan_kode}', 'App\Http\Controllers\PermohonanController@detail');
    // Route::post('buat-permohonan/detail/{permohonan_kode}', 'App\Http\Controllers\PermohonanController@storeDokumen');
    // Route::delete('buat-permohonan/detail/{dokumen_id}', 'App\Http\Controllers\PermohonanController@destroyDokumen');

    // Route::get('verifikasi', 'App\Http\Controllers\VerifikasiController@index');
    // Route::get('verifikasi/detail/{permohonan_kode}', 'App\Http\Controllers\VerifikasiController@detail');
    // Route::post('verifikasi/detail/{permohonan_kode}', 'App\Http\Controllers\VerifikasiController@store');

    // Route::get('laporan/rekap-per-item', 'App\Http\Controllers\LaporanController@index');

    Route::resource('master/kategori', 'App\Http\Controllers\MasterKategoriController');
    Route::resource('master/tld', 'App\Http\Controllers\MasterTLDController');
});