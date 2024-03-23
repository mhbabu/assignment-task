<?php

use App\Http\Controllers\AuthOTPController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\VerifiedUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
   return redirect(auth()->id() ? route('dashboard') : route('login'));
});

Auth::routes();

Route::middleware('auth')->controller(AuthOTPController::class)->group(function(){
   Route::get('otp-verifications', 'otpVerification')->name('otp.verifications');
   Route::post('otp-login', 'loginWithOTP')->name('otp.login');
   Route::post('otp-regenerate', 'regenarateOTP')->name('otp.regenerate');
});

Route::middleware(['auth', VerifiedUser::class])->group(function () {
   Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});




