<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\company\Department;
use App\Http\Controllers\attendance\Applications;

Route::get('applications/leave', [Applications::class, 'leave'])->name('attendance.applications.leave');
Route::get('applications/leave/{id}/view', [Applications::class, 'view_leave'])->name('attendance.applications.view_leave');
Route::get('applications/leave/approve', [Applications::class, 'approve'])->name('attendance.applications.approve');
Route::get('applications/leave/reject', [Applications::class, 'reject'])->name('attendance.applications.reject');
Route::get('applications/time_correction', [Applications::class, 'time_correction'])->name('attendance.applications.time_correction');
Route::get('applications/leave/view/download', [Applications::class, 'download'])->name('attendance.applications.view.download');
Route::post('applications/leave/view', [Applications::class, 'view'])->name('attendance.applications.view.document');

Route::get('applications/overtime', [Applications::class, 'overtime'])->name('attendance.applications.overtime');
Route::get('applications/overtime/{id}/view', [Applications::class, 'view_overtime'])->name('attendance.applications.view_overtime');

Route::get('applications/leave_credits', [Applications::class, 'leave_credits'])->name('attendance.leave_credits');
Route::get('applications/leave_credits/edit', [Applications::class, 'edit_leave_credits'])->name('attendance.leave_credits.edit');
