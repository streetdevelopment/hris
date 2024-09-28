<?php

use App\Http\Controllers\company\Document;
use Illuminate\Support\Facades\Route;

Route::get('documents', [Document::class, 'index'])->name('company.documents.index');
Route::post('documents/create/submit', [Document::class, 'submit'])->name('company.documents.submit');
Route::get('documents/reload', [Document::class, 'reload'])->name('company.documents.reload');
Route::get('documents/delete', [Document::class, 'delete'])->name('company.documents.delete');
Route::get('documents/find', [Document::class, 'find'])->name('company.documents.find');
Route::get('documents/edit', [Document::class, 'edit'])->name('company.documents.edit');
