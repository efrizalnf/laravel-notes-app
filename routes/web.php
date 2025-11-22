<?php

use App\Http\Controllers\Notes;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::resource('notes', Notes::class);
Route::controller(Notes::class)->group(function() {
    Route::get('/', 'index')->name('note-lists');
    Route::get('notes/addnotes', 'addnotes')->name('add-note');
    Route::post('notes/savenote', 'save_note')->name('save-note');
    Route::get('notes/edit/{id}', 'editnotes')->name('edit-note');
    Route::put('notes/saveedit/{id}', 'save_edit_notes')->name('save-edit-note');
    Route::delete('notes/delete/{id}', 'delete_note')->name('delete-note');
    Route::get('notes/recyclebin', 'recyclebin')->name('recycle_bin');
    Route::delete('notes/delete_forever/{id}', 'delete_forever')->name('delete-forever');
    Route::get('notes/delete_all', 'delete_all')->name('delete-all');
    Route::get('notes/restore_all', 'restore_all')->name('restore-all');
});

