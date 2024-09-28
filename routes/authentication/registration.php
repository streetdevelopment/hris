<?php

use App\Http\Controllers\authentication\Registration;
use Illuminate\Support\Facades\Route;

Route::get('registration/admin/{user_id}/verification', [Registration::class, 'verification'])->name('registration.admin.verification');
Route::get('auth/logout', [Registration::class, 'logout'])->name('auth.logout');
