<?php

use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\profiling\Employee;

Route::get('employees', [Employee::class, 'index'])->name('profiling.employees.index');

Route::middleware([(Admin::class)])->group(function () {
    Route::get('employees/create', [Employee::class, 'create'])->name('profiling.employees.create');
    Route::post('employees/create/submit', [Employee::class, 'submit'])->name('profiling.employees.create.submit');
});
Route::get('employees/{id}/edit', [Employee::class, 'edit'])->name('profiling.employees.edit');
Route::post('employees/edit/submit', [Employee::class, 'edit_submit'])->name('profiling.employees.edit.submit');

Route::get('employees/email', [Employee::class, 'email'])->name('profiling.employees.email');
