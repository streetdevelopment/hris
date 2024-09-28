<?php

use App\Http\Controllers\company\JobTypes;
use Illuminate\Support\Facades\Route;

Route::get('jobtypes', [JobTypes::class, 'index'])->name('company.jobtypes.index');
Route::get('jobtypes/create', [JobTypes::class, 'create'])->name('company.jobtypes.create');
Route::get('jobtypes/create/submit', [JobTypes::class, 'submit'])->name('company.jobtypes.submit');
Route::get('jobtypes/{id}/edit', [JobTypes::class, 'edit'])->name('company.jobtypes.edit');
Route::get('jobtypes/edit/submit', [JobTypes::class, 'edit_submit'])->name('company.jobtypes.edit.submit');
Route::get('jobtypes/{id}/delete', [JobTypes::class, 'delete'])->name('company.jobtypes.delete');
