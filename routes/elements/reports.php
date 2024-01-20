<?php 


use Illuminate\Support\Facades\Route;

// -------------------------- Reports Routes  --------------------------
Route::middleware(['auth'])->prefix('reports')->group(function () {
    /* General Services Routes */
    Route::prefix('general-services')->group(function () {
        /* Item Canvass Routes */
       Route::prefix('item-canvass')->group(function () {
           Route::get('', 'ReportItemCanvassController@index')->name('reports.general-services.item-canvass.index');
           Route::get('lists', 'ReportItemCanvassController@lists')->name('reports.general-services.item-canvass.lists');
           Route::get('export', 'ReportItemCanvassController@export')->name('reports.general-services.item-canvass.export');
       });
    });

    /* Accounting Routes */
    Route::prefix('accounting')->group(function () {    
        /* Collections Routes */    
        Route::prefix('collections')->group(function () {

            /* Deposits Routes */  
            Route::prefix('deposits')->group(function () {
                Route::get('', 'AcctgCollectionReportController@index')->name('acctg.general-journal.index');
                Route::get('', 'AcctgCollectionReportController@export_to_pdf');
            });
            /* Remittance Routes */  
            Route::prefix('remittances')->group(function () {

            });
        }); 

        /* Trial Balance Routes */    
        Route::prefix('trial-balance')->group(function () {
            Route::get('', 'ReportAcctgTrialBalanceController@index')->name('reports.acctg.trial-balance.index');
            Route::get('reload', 'ReportAcctgTrialBalanceController@reload')->name('reports.acctg.trial-balance.reload');
            Route::get('reload-category-name', 'ReportAcctgTrialBalanceController@reload_category_name')->name('reports.acctg.trial-balance.reload-category-name');
            Route::get('export/excel', 'ReportAcctgTrialBalanceController@export_to_excel')->name('reports.acctg.trial-balance.export-to-excel');
            Route::get('export/pdf', 'ReportAcctgTrialBalanceController@export_to_pdf')->name('reports.acctg.trial-balance.export-to-pdf');
            Route::get('export/pageview', 'ReportAcctgTrialBalanceController@export_to_pageview')->name('reports.acctg.trial-balance.export-to-pageview');
        });

        /* Ledgers Routes */    
        Route::prefix('ledgers')->group(function () {
            Route::get('', 'ReportAcctgLedgerController@index')->name('reports.acctg.ledgers.index');
            Route::get('reload', 'ReportAcctgLedgerController@reload')->name('reports.acctg.ledgers.reload');
            Route::get('reload-category-name', 'ReportAcctgLedgerController@reload_category_name')->name('reports.acctg.ledgers.reload-category-name');
            Route::get('export/excel', 'ReportAcctgLedgerController@export_to_excel')->name('reports.acctg.ledgers.export-to-excel');
            Route::get('export/pdf', 'ReportAcctgLedgerController@export_to_pdf')->name('reports.acctg.ledgers.export-to-pdf');
            Route::get('export/pageview', 'ReportAcctgLedgerController@export_to_pageview')->name('reports.acctg.ledgers.export-to-pageview');
        });

        /* Fixed Asset Routes */    
        Route::prefix('fixed-assets')->group(function () {
            Route::get('', 'ReportAcctgFixedAssetController@index')->name('reports.acctg.fixed-asset.index');
            Route::get('reload', 'ReportAcctgFixedAssetController@reload')->name('reports.acctg.fixed-asset.reload');
            Route::get('export/excel', 'ReportAcctgFixedAssetController@export_to_excel')->name('reports.acctg.fixed-asset.export-to-excel');
            Route::get('export/pdf', 'ReportAcctgFixedAssetController@export_to_pdf')->name('reports.acctg.fixed-asset.export-to-pdf');
            Route::get('export/pageview', 'ReportAcctgFixedAssetController@export_to_pageview')->name('reports.acctg.fixed-asset.export-to-pageview');
        });

        /* Journal Routes */    
        Route::prefix('journals')->group(function () {
            Route::get('', 'ReportAcctgJournalController@index')->name('reports.acctg.journal.index');
            Route::get('export/excel', 'ReportAcctgJournalController@export_to_excel')->name('reports.acctg.journal.export-to-excel');
            Route::get('export/pdf', 'ReportAcctgJournalController@export_to_pdf')->name('reports.acctg.journal.export-to-pdf');
            Route::get('export/pageview', 'ReportAcctgJournalController@export_to_pageview')->name('reports.acctg.journal.export-to-pageview');
        });

        /* Journal Routes */    
        Route::prefix('recap')->group(function () {
            Route::get('', 'ReportAcctgRecapController@index')->name('reports.acctg.recap.index');
            Route::get('export/excel', 'ReportAcctgRecapController@export_to_excel')->name('reports.acctg.recap.export-to-excel');
            Route::get('export/pdf', 'ReportAcctgRecapController@export_to_pdf')->name('reports.acctg.recap.export-to-pdf');
            Route::get('export/pageview', 'ReportAcctgRecapController@export_to_pageview')->name('reports.acctg.recap.export-to-pageview');
        });
    });

    /* Treasury Routes */
    Route::prefix('treasury')->group(function () {    
        /* Collections & Deposits Routes */    
        Route::prefix('collections-and-deposits')->group(function () {
            Route::get('', 'ReportTreasuryCollectionController@index')->name('reports.treasury.collections.index');
            Route::get('export/pdf', 'ReportTreasuryCollectionController@export_to_pdf')->name('reports.treasury.collections.export-to-pdf');
        });

        Route::prefix('statement-of-reciepts-sources')->group(function () {
            Route::get('', 'ReportTreasurySRSController@index')->name('reports.treasury.srs.index');
            Route::get('export/pdf', 'ReportTreasurySRSController@export_to_pdf')->name('reports.treasury.srs.export-to-pdf');
        });
    });
    Route::prefix('finance')->group(function () {
        Route::prefix('budget-expense')->group(function () {
            Route::get('', 'ReportBudgetExpenseController@index')->name('reports.budget-expense.index');
            Route::get('export/pdf', 'ReportBudgetExpenseController@export_to_pdf')->name('reports.budget-expense.export-to-pdf');
        });
    });
});