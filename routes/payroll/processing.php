<?php

use App\Http\Middleware\Admin;
use App\Http\Controllers\payroll\Run;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\payroll\Slip;
use App\Http\Controllers\payroll\Payroll;

Route::middleware([(Admin::class)])->group(function () {
    Route::get('payroll', [Payroll::class, 'index'])->name('payroll.index');
    Route::get('payroll/create', [Payroll::class, 'create'])->name('payroll.create');
    Route::post('payroll/create/submit', [Run::class, 'submit'])->name('payroll.submit');
    Route::get('payroll/run/process', [Run::class, 'process'])->name('payroll.run.process');
    Route::get('payroll/{id}/view', [Run::class, 'view'])->name('payroll.run.view');
    Route::get('payroll/slips', [Slip::class, 'index'])->name('payroll.slips');
});

Route::get('payroll/{id}/slips', [Slip::class, 'employee_payslips'])->name('payroll.slips.employee');
Route::get('payroll/slips/{id}/view', [Slip::class, 'view'])->name('payroll.slips.view');
