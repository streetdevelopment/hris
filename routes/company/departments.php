<?php

use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\company\Department;

Route::get('departments', [Department::class, 'index'])->name('company.departments.index');

Route::middleware([(Admin::class)])->group(function () {
    Route::get('departments/create', [Department::class, 'create'])->name('company.departments.create');
    Route::get('departments/new', [Department::class, 'new'])->name('company.departments.new');
    Route::get('departments/{id}/edit', [Department::class, 'edit'])->name('company.departments.edit');
    Route::get('departments/delete', [Department::class, 'delete'])->name('company.departments.delete');
    Route::get('departments/edit/submit', [Department::class, 'edit_submit'])->name('company.departments.edit.submit');


    Route::get('departments/employee/{id}/view', [Department::class, 'view_employee'])->name('company.departments.employee.view');
    Route::get('departments/employee/{id}/attendance', [Department::class, 'view_attendance'])->name('company.departments.employee.attendance');
});
Route::get('departments/all', [Department::class, 'all'])->name('company.departments.all');
Route::get('departments/{id}/view', [Department::class, 'view'])->name('company.departments.view');
