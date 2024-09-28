<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\company\Department;
use App\Http\Controllers\attendance\Leave;

Route::get('leave', [Leave::class, 'index'])->name('attendance.leave.index');
Route::get('leave/requirements', [Leave::class, 'requirements'])->name('attendance.leave.requirements');
Route::post('leave/submit', [Leave::class, 'submit'])->name('attendance.leave.submit');

Route::get('leave/request/overtime', [Leave::class, 'overtime'])->name('attendance.request.overtime');
Route::get('leave/request/overtime/submit', [Leave::class, 'submit_ot'])->name('attendance.request.overtime.submit');

Route::get('leave/request/tc', [Leave::class, 'time_correction'])->name('attendance.request.tc');
Route::get('leave/request/tc/submit', [Leave::class, 'submit_tc'])->name('attendance.request.tc.submit');
