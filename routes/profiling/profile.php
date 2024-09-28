<?php

use App\Http\Controllers\profiling\Profile;
use Illuminate\Support\Facades\Route;

Route::get('profile', [Profile::class, 'index'])->name('profiling.profile.index');
Route::post('profile/pd/create', [Profile::class, 'pd_create'])->name('profiling.pd.create');
