<?php

use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\company\Policy;

Route::get('policies', [Policy::class, 'index'])->name('company.policies.index');

Route::middleware([(Admin::class)])->group(function () {
    Route::get('policies/submit', [Policy::class, 'submit'])->name('company.policies.submit');
});
