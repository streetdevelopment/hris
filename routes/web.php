<?php

use App\Http\Controllers\Home;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentication\Login;
use App\Http\Controllers\authentication\Registration;
use App\Http\Middleware\Admin;

// Auth::routes([
//     'verify' => true
// ]);

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

Route::post('registration/admin/submit', [Registration::class, 'submit'])->name('registration.admin.submit');
Route::post('login/attempt', [Login::class, 'login_user'])->name('login.attempt');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [Home::class, 'index'])->name('dashboard');
    include __DIR__ . '/authentication/registration.php';

    include __DIR__ . '/company/departments.php';
    include __DIR__ . '/company/policies.php';
    include __DIR__ . '/company/deductions.php';

    Route::middleware([(Admin::class)])->group(function () {
        include __DIR__ . '/company/jobtypes.php';
        include __DIR__ . '/company/documents.php';
        include __DIR__ . '/company/leaves.php';
        include __DIR__ . '/company/setup.php';
    });

    include __DIR__ . '/profiling/profile.php';
    include __DIR__ . '/profiling/employees.php';

    include __DIR__ . '/attendance/attendance.php';
    include __DIR__ . '/attendance/leave.php';
    include __DIR__ . '/attendance/applications.php';

    include __DIR__ . '/payroll/processing.php';
});
