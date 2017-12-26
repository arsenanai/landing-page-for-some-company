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
//Landing page

//Route::get('/page-resource/{id}/{lang}', 'LandingController@pageResource')->name('page-resource');
//Start page
Route::get('/', function () {
    return redirect()->route('main',['lang'=>'ru']);
});
Route::get('lang/{lang}', ['as'=>'lang.switch', 'uses'=>'LanguageController@switchLang']);
Route::get('/locale-change/{locale}', function ($locale) {
    App::setLocale($locale);
    return Redirect::back();
})->name('locale-change');
// Account management and main controllers
Auth::routes();

Route::get('/languages','HomeController@languages')->name('languages');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'management', 'middleware' => ['role:admin|editor']], function() {
    Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/settings','Auth\UserController@settings')->name('settings');
	Route::post('/profile/save','Auth\UserController@profileSave')->name('profile-save');
	Route::post('/profile/password-change','Auth\UserController@passwordReset')->name('password-change');
});
//Editors management
Route::group(['prefix' => 'management', 'middleware' => ['role:admin']], function() {
    Route::get('/editors/page/sz={sz}/sr={sr}/o={o}/f={f}/sc={sc}/','EditorController@index')->name('editors');
    Route::get('/editor/add','EditorController@add')->name('add-editor');
    Route::post('/editor/save','EditorController@save')->name('save-editor');
    Route::get('/editor/edit/{id}','EditorController@edit')->name('edit-editor');
    Route::post('/editor/update/{id}','EditorController@update')->name('update-editor');
    Route::post('/editor/reset-password/{id}','EditorController@resetPassword')->name('reset-password');
    Route::get('/editor/delete/{id}','EditorController@delete')->name('delete-editor');
});

//delete
Route::group(['prefix' => 'sudo', 'middleware' => ['role:admin']], function() {
    Route::get('/give-password','EditorController@givePassword');
    Route::get('/drop-editors/{pass}','EditorController@dropEditors');
    Route::get('/drop-news/{pass}','EditorController@dropNews');
    Route::get('/clear-unused-images/{pass}','EditorController@clearUnusedImages');
});

Route::group(['prefix'=>'content', 'middleware' => ['permission:manage_content']], function(){
    //pages
    Route::get('/site-content','PagesController@siteContent')->name('site-content');
    Route::post('/save-content','PagesController@saveContent')->name('save-content');
    Route::get('/page-content/pid={id}','PagesController@index')->name('page-content');
    Route::post('/page-save/pid={id}','PagesController@pageSave')->name('page-save');
    //news
    Route::get('/news/category={category}/sz={sz}/sr={sr}/o={o}/f={f}/','NewsController@index')->name('news-page');
    Route::get('/news/add','NewsController@add')->name('add-news');
    Route::post('/news/save','NewsController@save')->name('save-news');
    Route::get('/news/edit/{id}','NewsController@edit')->name('edit-news');
    Route::post('/news/update/{id}','NewsController@update')->name('update-news');
    Route::get('/news/delete/{id}','NewsController@delete')->name('delete-news');
    Route::get('/news/check-slug/{id}/{slug}','NewsController@check')->name('check-news-slug');
    Route::get('/mail','MailController@index');
});
Route::post('/send-mail','MailController@sendMail')->name('send-mail');

Route::group(['prefix'=>'files', 'middleware' => ['permission:manage_content']], function(){
    Route::post('/image-upload','FilesController@imageUpload')->name('upload-image');
    Route::get('/images-page/sz={sz}','FilesController@imagesPage')->name('paginate-images');
});
Route::get('/error', function () {
    $var = 1/0;
    return '';
});

Route::get('/{lang}', 'LandingController@main')->name('main');
Route::get('/{lang}/{id}', 'LandingController@page')->name('page');
Route::get('/{lang}/services/{index}', 'LandingController@service')->name('service');
Route::get('/{lang}/post/{pid}','LandingController@post')->name('post');

Route::post('/CurPT2DSOE.php',function(){
    return view('webtotem.CurPT2DSOE');
});
Route::post('/svkLQ7plfi.php',function(){
    return view('webtotem.svkLQ7plfi');
});