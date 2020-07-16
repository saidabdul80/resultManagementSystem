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

//actual to use
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@Registeration')->name('register');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/login/{role_ids}/{user_id}', 'UserController@RoleCheck');
Route::get('/admin/assign-role', 'AssignroleController@show');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');


/*Route::get('/login', 'AutoController@login');
Route::post('/login','UserController@Validation');
*/
Route::get('/admin', 'AdminController@Index');



Route::get('/lecturer', 'LecturerController@index');
Route::get('/home', function (){
/*$as = array('a'=>1212,'b'=>'1we12' );

//return $as;
if (!array_key_exists($a, $as)) {
	abort(404);
}*/
$asd= 'my name';
 return view('welcome', compact('asd'));
}); 

Route::get('/roles','PermissionController@Permission');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
