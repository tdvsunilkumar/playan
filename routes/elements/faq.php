<?php 


use Illuminate\Support\Facades\Route;

// --------------------------- Components Routes -----------------------------
Route::middleware(['auth'])->prefix('faq')->group(function () {
    Route::get('', 'FAQController@index')->name('faq.index');
    Route::get('lists', 'FAQController@lists')->name('faq.lists');
    Route::get('view/{id}', 'FAQController@find')->name('faq.view');
});
