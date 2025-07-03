<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('contacts.index');
});

Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('/', [ContactController::class, 'store'])->name('contacts.store');
    Route::put('/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/import', [ContactController::class, 'import'])->name('contacts.import');
});
