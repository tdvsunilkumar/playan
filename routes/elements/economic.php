<?php 


use Illuminate\Support\Facades\Route;

// ------------------------------------- Accounting ------------------------------
Route::middleware(['auth'])->prefix('economic-and-investment')->group(function () {
    /* Cemetery Application Routes */
    Route::prefix('cemetery-application')->group(function () {
        Route::get('', 'EconCemeteryApplicationController@index')->name('cemetery.index');
        Route::get('lists', 'EconCemeteryApplicationController@lists')->name('cemetery.lists');
        Route::get('payment-lists/{id}', 'EconCemeteryApplicationController@payment_lists')->name('cemetery.payment_lists');
        Route::get('fetch-data/{id}', 'EconCemeteryApplicationController@fetch_data')->name('cemetery.fetch_data');
        Route::get('reload-cemetery-lot/{id}', 'EconCemeteryApplicationController@reload_cemetery_lot')->name('cemetery.reload-cemetery-lot');
        Route::get('reload-cemetery-name', 'EconCemeteryApplicationController@reload_cemetery_name')->name('cemetery.reload-cemetery-name');
        Route::post('store', 'EconCemeteryApplicationController@store')->name('cemetery.store');
        Route::put('update/{id}', 'EconCemeteryApplicationController@update')->name('cemetery.update');
        Route::put('update-terms/{id}', 'EconCemeteryApplicationController@update_terms')->name('cemetery.update.terms');
        Route::put('send/{detail}/{id}', 'EconCemeteryApplicationController@send')->name('cemetery.send');
        Route::get('edit/{id}', 'EconCemeteryApplicationController@find')->name('cemetery.edit');
        Route::get('print/{trans}', 'EconCemeteryApplicationController@print')->name('cemetery.print');
        Route::get('fetch-status/{id}', 'EconCemeteryApplicationController@fetch_status')->name('rental.fetch-status');
        Route::get('fetch-remarks/{id}', 'EconCemeteryApplicationController@fetch_remarks')->name('rental.fetch-remarks');
    });

    /* Cemetery Application Routes */
    Route::prefix('rental-application')->group(function () {
        Route::get('', 'EconRentalApplicationController@index')->name('rental.index');
        Route::get('lists', 'EconRentalApplicationController@lists')->name('rental.lists');
        Route::get('fetch-data/{id}', 'EconRentalApplicationController@fetch_data')->name('rental.fetch_data');
        Route::get('reload-reception-name', 'EconRentalApplicationController@reload_reception_name')->name('rental.reload-reception-name');
        Route::get('reload-reception-class/{id}', 'EconRentalApplicationController@reload_reception_class')->name('rental.reload-reception-class');
        Route::get('fetch-multiplier-amount', 'EconRentalApplicationController@fetch_multiplier_amount')->name('rental.fetch-multiplier-amount');
        Route::post('store', 'EconRentalApplicationController@store')->name('rental.store');
        Route::get('edit/{id}', 'EconRentalApplicationController@find')->name('rental.edit');
        Route::put('update/{id}', 'EconRentalApplicationController@update')->name('rental.update');
        Route::put('send/{detail}/{id}', 'EconRentalApplicationController@send')->name('rental.send');
        Route::get('print/{trans}', 'EconRentalApplicationController@print')->name('rental.print');
        Route::get('fetch-status/{id}', 'EconRentalApplicationController@fetch_status')->name('rental.fetch-status');
        Route::get('fetch-remarks/{id}', 'EconRentalApplicationController@fetch_remarks')->name('rental.fetch-remarks');
        Route::get('fetch-discount/{id}', 'EconRentalApplicationController@fetch_discount')->name('rental.fetch-discount');
        Route::get('print/{trans}', 'EconRentalApplicationController@print')->name('rental.print');
    });

    /* Calendar Application Routes */
    Route::prefix('calendar')->group(function () {
        Route::get('', 'EconRentalApplicationController@calendar')->name('rental.calendar.index');
        Route::get('lists', 'EconRentalApplicationController@calendar_lists')->name('rental.calendar.lists');
    });

    /* Calendar Application Routes */
    Route::prefix('setup-data')->group(function () {
        Route::prefix('housing-penalties')->group(function () {
            Route::get('', 'EconHousingPenaltyController@index')->name('setup-data.housing-penalty.index');
            Route::get('lists', 'EconHousingPenaltyController@lists')->name('setup-data.housing-penalty.lists');
            Route::post('store', 'EconHousingPenaltyController@store')->name('setup-data.housing-penalty.store');
            Route::get('edit/{id}', 'EconHousingPenaltyController@find')->name('setup-data.housing-penalty.find');
            Route::put('update/{id}', 'EconHousingPenaltyController@update')->name('setup-data.housing-penalty.update');
            Route::put('remove/{id}', 'EconHousingPenaltyController@remove')->name('setup-data.housing-penalty.remove');
            Route::put('restore/{id}', 'EconHousingPenaltyController@restore')->name('setup-data.housing-penalty.restore');
        });
    });
    
    Route::get('citizens', 'SocialWelfare\CitizenController@index')->name('eco.citizens');
});
