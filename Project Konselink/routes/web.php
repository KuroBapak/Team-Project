<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('pages.home');
});/*Home*/

Route::get('/register', function () {
    return view('auth.register');
});/*Register*/

Route::get('/login', function () {
    return view('auth.login');
});/*Login*/

Route::get('/menu1', function () {
    return view('pages.dashboardgbk');
});/*Dashboard Guru BK*/

Route::get('/menu2', function () {
    return view('pages.menuki');
});/*Menu Konseling Individu*/

Route::get('/menu3', function () {
    return view('pages.menuki1');
});/*Menu Konseling Kelompok*/

Route::get('/menu4', function () {
    return view('pages.menuki2');
});/*Menu Konseling Klasikal*/

Route::get('/form1', function () {
    return view('pages.form1');
});/*Form Konseling Individu*/

Route::get('/form2', function () {
    return view('pages.form3');
});/*Form Konseling kelompok*/

Route::get('/form3', function () {
    return view('pages.form2');
});/*Ilang dari Figma anjg udh di bikin jg*/

Route::get('/form4', function () {
    return view('pages.form4');
});/*Form Bimbingan Klasikal*/

Route::get('/setting', function () {
    return view('pages.psetting');
});/*Pengaturan Akun*/
