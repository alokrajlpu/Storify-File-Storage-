<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use Admin\RolesController;
use Admin\UsersController;
use Admin\FoldersController;  
use Admin\FilesController;
use App\Http\Controllers\Admin\DownloadsController;
use App\Http\Controllers\Admin\SpatieMediaController;

// Redirect Route
Route::get('/', function() { return redirect('/admin/home'); });

// Authentication Routes...
Route::get('login', [LoginController::class, 'showLoginForm'])->name('auth.login');
Route::post('login', [LoginController::class, 'login'])->name('auth.login');
Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');

// Change Password Routes...
Route::get('change_password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('auth.change_password');
Route::patch('change_password', [ChangePasswordController::class, 'changePassword'])->name('auth.change_password');

// Password Reset Routes...
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('auth.password.reset');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('auth.password.reset');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('auth.password.reset');

// Registration Routes...
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('auth.register');
Route::post('register', [RegisterController::class, 'register'])->name('auth.register');

// Grouped Routes for Admin
Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', [HomeController::class, 'index']);

    Route::resource('roles', RolesController::class);
    Route::post('roles_mass_destroy', [RolesController::class, 'massDestroy'])->name('roles.mass_destroy');

    Route::resource('users', UsersController::class);
    Route::post('users_mass_destroy', [UsersController::class, 'massDestroy'])->name('users.mass_destroy');

    // Folders Routes
    Route::resource('folders', FoldersController::class);
    Route::post('folders_mass_destroy', [FoldersController::class, 'massDestroy'])->name('folders.mass_destroy');
    Route::post('folders_restore/{id}', [FoldersController::class, 'restore'])->name('folders.restore');
    Route::delete('folders_perma_del/{id}', [FoldersController::class, 'perma_del'])->name('folders.perma_del');

    Route::resource('files', FilesController::class);
    Route::get('/{uuid}/download', [DownloadsController::class, 'download']);
    Route::post('files_mass_destroy', [FilesController::class, 'massDestroy'])->name('files.mass_destroy');
    Route::post('files_restore/{id}', [FilesController::class, 'restore'])->name('files.restore');
    Route::delete('files_perma_del/{id}', [FilesController::class, 'perma_del'])->name('files.perma_del');

    Route::post('/spatie/media/upload', [SpatieMediaController::class, 'create'])->name('media.upload');
    Route::post('/spatie/media/remove', [SpatieMediaController::class, 'destroy'])->name('media.remove');
});
