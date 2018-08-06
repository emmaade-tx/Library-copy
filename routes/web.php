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
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/books', 'BookController@book')->name('book');
Route::get('/book/{id}', 'BookController@view')->name('view-book');
Route::get('/edit/book/{id}', 'BookController@edit')->name('edit-book');

Route::post('/update/book/{id}', 'BookController@update')->name('update-book');

Route::get('/scrape', 'BookController@scrape');