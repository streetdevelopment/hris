<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\company\Department;
use App\Http\Controllers\attendance\Attendance;

Route::get('attendance', [Attendance::class, 'index'])->name('attendance.attendance.index');
Route::get('attendance/upload', [Attendance::class, 'upload'])->name('attendance.attendance.upload');
Route::get('attendance/time', [Attendance::class, 'time'])->name('attendance.attendance.time');
Route::get('attendance/time/in', [Attendance::class, 'in'])->name('attendance.attendance.time.in');
Route::get('attendance/time/out', [Attendance::class, 'out'])->name('attendance.attendance.time.out');
Route::get('attendance/history', [Attendance::class, 'history'])->name('attendance.attendance.history');
