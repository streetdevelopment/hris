<?php

use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\company\Deductions;

Route::get('deductions', [Deductions::class, 'index'])->name('company.deductions.index');

Route::middleware([(Admin::class)])->group(function () {
    Route::get('deductions/add', [Deductions::class, 'add'])->name('company.deductions.add');
    Route::get('deductions/reload', [Deductions::class, 'reload'])->name('company.deductions.reload');
    Route::get('deductions/delete', [Deductions::class, 'delete'])->name('company.deductions.delete');
    Route::get('deductions/edit', [Deductions::class, 'edit'])->name('company.deductions.edit');
});
Route::get('deductions/find', [Deductions::class, 'find'])->name('company.deductions.find');
