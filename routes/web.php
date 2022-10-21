<?php

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

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->name('logout'); //view

Route::group(['middleware' => 'role:developer'], function () {

    Route::resource('/permissions', 'PermissionsController');
    Route::resource('/users_permissions', 'UsersPermissionsController');
});


Route::group(['middleware' => 'auth'], function () {
    

    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::get('/home', 'DashboardController@dashboard')->name('home');
    Route::get('/', 'DashboardController@dashboard')->name('home');



    Route::resource('/users', 'UsersController')->names([
        'index' => 'users.index',
        'show' => 'users.show'
    ]);

    Route::resource('/roles', 'RolesController')->names([
        'index' => 'roles.index',
        'show' => 'roles.show'
    ]);

    Route::resource('/roles', 'RolesController');
    Route::resource('/roles_permissions', 'RolesPermissionsController');

    Route::resource('/users_roles', 'UserRolesController');

    Route::get('/profile', 'Auth\ProfilesController@index')->name('profiles.index');
    Route::Post('/profile', 'Auth\ProfilesController@store')->name('profiles.store');
    Route::get('/change-password', 'Auth\ChangePasswordController@index')->name('changePasswords.index');
    Route::Post('/change-password', 'Auth\ChangePasswordController@store')->name('changePasswords.store');

    Route::get('/roles-permissions/{id}', 'RolesPermissionsController@listByRoles')->name('roles.update_permissions_roles');
    Route::Post('/store-roles', 'RolesPermissionsController@storeRoles')->name('rolesPermission.storeRoles');
    Route::get('/roles-users/{id}', 'UserRolesController@listByRoles')->name('roles.update_user_roles');
    Route::Post('/update-roles-users', 'UserRolesController@storeRoles')->name('userRoles.storeRoles');

    Route::get('/users-permissions/{id}', 'UsersPermissionsController@listByUser')->name('users.list_permissions');

    Route::resource('/url_shortener', 'UrlShortenerController');
Route::resource('/url_logs', 'UrlLogsController');


    Route::fallback(function () {
        return view('errors.404');
    });

});

Route::fallback(function () {
    return view('errors.404');
});