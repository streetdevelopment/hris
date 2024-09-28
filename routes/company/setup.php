<?php

use App\Http\Controllers\company\Setup;
use Illuminate\Support\Facades\Route;

Route::get('registration/admin/{user_id}/profiling', [Setup::class, 'initial_profiling'])->name('registration.admin.initial_profiling');
Route::get('registration/admin/profiling/validate_fields', [Setup::class, 'validate_fields'])->name('company.setup.validate_fields');
Route::get('registration/admin/profiling/submit', [Setup::class, 'submit'])->name('company.setup.submit');
