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

Route::get('/editform1', function () {
    return view('pages.editform1');
});/*Form Edit Konseling Individu*/

Route::get('/detailform1', function () {
    return view('pages.detailform1');
});/*Form Detail Konseling Individu*/

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


/*Akun adminnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn*/

Route::get('/adashboard', function () {
    return view('admin.dashboard');
});/*dashboard admin*/

Route::get('/menu5', function () {
    return view('admin.menudata');
});/*menu data guru bk*/

Route::get('/form5', function () {
    return view('admin.formdata');
});/*form data guru bk*/

Route::get('/edit1', function () {
    return view('admin.editdata');
});/*edit data guru bk*/

Route::get('/menu6', function () {
    return view('admin.menu');
});/*menu admin*/

Route::get('/menu7', function () {
    return view('admin.menubkj');
});/*menu BK jurusan*/

Route::get('/form6', function () {
    return view('admin.formbkj');
});/*Form BK Jurusan*/

Route::get('/edit2', function () {
    return view('admin.editbkj');
});/*Edit bk jurusan*/

Route::get('/menu8', function () {
    return view('admin.menudatas');
});/*menu data siswa*/

Route::get('/form7', function () {
    return view('admin.formcreate');
});/*Form Create Data Siswa Tahun Ajaran*//////////////////////////////////anomaly//

Route::get('/menu9', function () {
    return view('admin.menudatast');
});/*Menu Data Siswa Tahun Ajaran 1*/

Route::get('/menu10', function () {
    return view('admin.menudatast1');
});/*Menu Data Siswa Tahun Ajaran 2*/

Route::get('/menu11', function () {
    return view('admin.menudatast2');
});/*Menu Data Siswa Tahun Ajaran 3*/

Route::get('/menu12', function () {
    return view('admin.menudatast3');
});/*Menu Data Siswa Tahun Ajaran 4*/

Route::get('/menu13', function () {
    return view('admin.menudating');
});/*Menu Data tingkat*/

Route::get('/menu14', function () {
    return view('admin.menudake');
});/*Menu Data Kelas*/

Route::get('/menu15', function () {
    return view('admin.menukidiva');
});/*Menu konseling individu admin*/

Route::get('/form8', function () {
    return view('admin.editdatasta');
});/*Edit Data Siswa Tahun Ajaran*/

Route::get('/form9', function () {
    return view('admin.showdatasta');
});/*show Data Siswa Tahun Ajaran*/

Route::get('/form10', function () {
    return view('admin.formdataj1');
});/*show Data Siswa Tahun Ajaran*/

Route::get('/form11', function () {
    return view('admin.formdataj2');
});/*show Data Siswa Tahun Ajaran*/

Route::get('/form12', function () {
    return view('admin.formdataj3');
});/*Form Data Jurusan*/

Route::get('/form13', function () {
    return view('admin.formdataj4');
});/*Form Data Jurusan*/

Route::get('/form14', function () {
    return view('admin.formdating1');
});/*Form Data tingkat*/

Route::get('/form15', function () {
    return view('admin.formdating2');
});/*Form Data tingkat*/

Route::get('/form16', function () {
    return view('admin.formdakel1');
});/*Form Data Kelas*/

Route::get('/form17', function () {
    return view('admin.formdakel2');
});/*Form Data Kelas*/

Route::get('/form18', function () {
    return view('admin.formksdu1');
});/*Form Konseling Individu*/

Route::get('/form19', function () {
    return view('admin.formksdu2');
});/*edit Konseling Individu*/

Route::get('/form20', function () {
    return view('admin.formksdu3');
});/*detail Konseling Individu*/


