<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware(['guest']);

Auth::routes([
    // 'verify' => true,
    // 'register' => false,
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('roles', App\Http\Controllers\RoleController::class);
Route::resource('users', App\Http\Controllers\UserController::class);
Route::prefix('user')->group(function () {
    Route::controller(App\Http\Controllers\UserController::class)->group(function () {
        Route::post('/check-email-exists', 'check_email_exists')->name('users.check.email.exists');
        Route::get('/datatables', 'datatables')->name('users.datatables');
    });
});

Route::resource('books', App\Http\Controllers\BookController::class);
Route::prefix('book')->group(function () {
    Route::controller(App\Http\Controllers\BookController::class)->group(function () {
        Route::get('/datatables', 'datatables')->name('books.datatables');
        Route::get('/cover/{filename}', 'show_image')->name('books.image.show');
        Route::post('/export/pdf/{id}', 'export_pdf')->name('books.export.pdf');
        Route::post('/export-all/pdf', 'export_all_pdf')->name('books.export.all.pdf');
        Route::get('/get/borrowers', 'get_valid_borrowers')->name('books.valid.borrowers');
        Route::post('/borrow/{book}', 'borrow')->name('books.borrow');
        Route::post('/return/{user}/{book}', 'return')->name('books.return');

        Route::get('/my-book-list', 'user_books_index')->name('user.books');
        Route::get('/my-book-list/datatables', 'user_books_datatables')->name('user.books.datatables');
    });
});


