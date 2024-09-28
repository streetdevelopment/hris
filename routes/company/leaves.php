<?php

use App\Http\Controllers\company\Leave;
use Illuminate\Support\Facades\Route;

Route::get('leavetypes', [Leave::class, 'index'])->name('company.leavetypes.index');
Route::get('leavetypes/create', [Leave::class, 'create'])->name('company.leavetypes.create');
Route::get('leavetypes/submit', [Leave::class, 'submit'])->name('company.leavetypes.submit');
Route::get('leavetypes/{id}/edit', [Leave::class, 'edit'])->name('company.leavetypes.edit');
Route::get('leavetypes/edit/submit', [Leave::class, 'edit_submit'])->name('company.leavetypes.edit.submit');
Route::get('leavetypes/{id}/delete', [Leave::class, 'delete'])->name('company.leavetypes.delete');
