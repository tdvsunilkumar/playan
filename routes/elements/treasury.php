<?php 


use Illuminate\Support\Facades\Route;

// ------------------------------------- Accounting ------------------------------
Route::middleware(['auth'])->prefix('treasury')->group(function () {
    /* Journal Entry Voucher Routes */
    Route::prefix('journal-entries')->group(function () {
        /* Payables Routes */
        Route::prefix('payables')->group(function () {
            Route::get('', 'TreasuryPayablesVoucherController@index')->name('treasury-payables-voucher.index');
            Route::get('lists', 'TreasuryPayablesVoucherController@lists')->name('treasury-payables-voucher.lists');
            Route::get('payables-lists/{id}', 'TreasuryPayablesVoucherController@payables_lists')->name('treasury-payables-voucher.payable-lists');
            Route::get('payments-lists/{id}', 'TreasuryPayablesVoucherController@payments_lists')->name('treasury-payables-voucher.payment-lists');
            Route::get('add', 'TreasuryPayablesVoucherController@create')->name('treasury-payables-voucher.create');
            Route::put('update/{id}', 'TreasuryPayablesVoucherController@update')->name('treasury-payables-voucher.update');
            Route::get('get-voucher', 'TreasuryPayablesVoucherController@get_voucher')->name('treasury-payables-voucher.get-voucher-id');
            Route::get('fetch-status/{id}', 'TreasuryPayablesVoucherController@fetch_status')->name('treasury-payables-voucher.fetch_status');
            Route::get('edit/{id}', 'TreasuryPayablesVoucherController@edit')->name('treasury-payables-voucher.edit');
            Route::get('view/{id}', 'TreasuryPayablesVoucherController@view')->name('treasury-payables-voucher.view');
            Route::get('view-available-payables/{id}', 'TreasuryPayablesVoucherController@view_available_payables')->name('treasury-payables-voucher.view-available-payables');
            Route::post('add-payables/{id}', 'TreasuryPayablesVoucherController@add_payables')->name('treasury-payables-voucher.add-payables');
            Route::post('add-disbursement/{id}', 'TreasuryPayablesVoucherController@add_disbursement')->name('treasury-payables-voucher.add-disbursement');
            Route::get('payables/edit/{id}', 'TreasuryPayablesVoucherController@edit_payables')->name('treasury-payables-voucher.edit-payables');
            Route::put('payables/update/{id}', 'TreasuryPayablesVoucherController@update_payables')->name('treasury-payables-voucher.update-payables');
            Route::put('payables/remove/{id}', 'TreasuryPayablesVoucherController@remove_payables')->name('treasury-payables-voucher.remove-payables');
            Route::put('payables/remove-all/{voucher}', 'TreasuryPayablesVoucherController@remove_all_payables')->name('treasury-payables-voucher.remove-all-payables');
            Route::put('payables/send-all/{voucher}', 'TreasuryPayablesVoucherController@send_all_payables')->name('treasury-payables-voucher.send-all-payables');
            Route::post('payments/store/{id}', 'TreasuryPayablesVoucherController@store_payments')->name('treasury-payables-voucher.store-payments');
            Route::get('payments/edit/{id}', 'TreasuryPayablesVoucherController@find_payments')->name('treasury-payables-voucher.remove-payments');
            Route::put('payments/update/{id}', 'TreasuryPayablesVoucherController@update_payments')->name('treasury-payables-voucher.update-payments');
            Route::put('payments/remove/{id}', 'TreasuryPayablesVoucherController@remove_payments')->name('treasury-payables-voucher.remove-payments');
            Route::put('payments/remove-all/{voucher}', 'TreasuryPayablesVoucherController@remove_all_payments')->name('treasury-payables-voucher.remove-all-payments');
            Route::put('payments/send-all/{voucher}', 'TreasuryPayablesVoucherController@send_all_payments')->name('treasury-payables-voucher.send-all-payments');
            Route::get('find-sl-bank/{id}', 'TreasuryPayablesVoucherController@find_sl_bank')->name('treasury-payables-voucher.find-sl-bank');
            Route::get('print/{voucher}', 'TreasuryPayablesVoucherController@print')->name('treasury-payables-voucher.print');  
            Route::get('preview/{voucher}', 'TreasuryPayablesVoucherController@preview')->name('treasury-payables-voucher.preview');  
            Route::get('print-cheque/{id}', 'TreasuryPayablesVoucherController@print_cheque')->name('treasury-payables-voucher.print-cheque'); 
            Route::get('payables/fetch-status/{id}', 'TreasuryPayablesVoucherController@fetch_payable_status')->name('treasury-payables-voucher.fetch-payable-status');
            Route::get('payables/validate-approver/{id}', 'TreasuryPayablesVoucherController@validate_payables_approver')->name('treasury-payables-voucher.validate-payables-approver');
            Route::put('payables/approve/{id}', 'TreasuryPayablesVoucherController@approve_payable')->name('treasury-payables-voucher.approve-payable');
            Route::put('payables/disapprove/{id}', 'TreasuryPayablesVoucherController@disapprove_payable')->name('treasury-payables-voucher.disapprove-payable');
            Route::get('payables/fetch-remarks/{id}', 'TreasuryPayablesVoucherController@fetch_payable_remarks')->name('treasury-payables-voucher.fetch-payable-rmarks');
            Route::get('payments/fetch-status/{id}', 'TreasuryPayablesVoucherController@fetch_payment_status')->name('treasury-payables-voucher.fetch_payment_status');
            Route::get('payments/validate-approver/{id}', 'TreasuryPayablesVoucherController@validate_payments_approver')->name('treasury-payables-voucher.validate-payments-approver');
            Route::put('payments/approve/{id}', 'TreasuryPayablesVoucherController@approve_payment')->name('treasury-payables-voucher.approve-payment');
            Route::put('payments/disapprove/{id}', 'TreasuryPayablesVoucherController@disapprove_payment')->name('treasury-payables-voucher.disapprove-payment');
            Route::get('payments/fetch-remarks/{id}', 'TreasuryPayablesVoucherController@fetch_payment_remarks')->name('treasury-payables-voucher.fetch-payment-rmarks');
            Route::get('fetch-voucher-print', 'TreasuryPayablesVoucherController@fetch_voucher_print')->name('treasury-payables-voucher.fetch-voucher-print');
            Route::put('update-voucher-date/{id}', 'TreasuryPayablesVoucherController@update_voucher_date')->name('treasury-payables-voucher.update-voucher-date');
            Route::get('fetch-document-status', 'TreasuryPayablesVoucherController@fetch_document_status')->name('treasury-payables-voucher.fetch-document-status');
            Route::get('fetch-document-remarks', 'TreasuryPayablesVoucherController@fetch_document_remarks')->name('treasury-payables-voucher.fetch-document-remarks');
            Route::put('payments/update-payment/{id}', 'TreasuryPayablesVoucherController@update_paymentx')->name('treasury-payables-voucher.update-paymentx');
            Route::post('payments/store-payment/{id}', 'TreasuryPayablesVoucherController@store_paymentx')->name('treasury-payables-voucher.store-paymentx');
            Route::put('update-vouchers/{voucher}', 'TreasuryPayablesVoucherController@vouchers_update')->name('treasury-payables-voucher.vouchers-update');
        });
        /* Payables Routes */
        Route::prefix('incomes')->group(function () {
            Route::get('', 'TreasuryIncomesVoucherController@index')->name('treasury-incomes-voucher.index');
            Route::get('lists', 'TreasuryIncomesVoucherController@lists')->name('treasury-incomes-voucher.lists');
            Route::get('payables-lists/{id}', 'TreasuryIncomesVoucherController@collections_lists')->name('treasury-incomes-voucher.payable-lists');
            Route::get('payments-lists/{id}', 'TreasuryIncomesVoucherController@payments_lists')->name('treasury-incomes-voucher.payment-lists');
            Route::get('deductions-lists/{id}', 'TreasuryIncomesVoucherController@deductions_lists')->name('treasury-voucher.deduction-lists');
            Route::get('add', 'TreasuryIncomesVoucherController@create')->name('treasury-incomes-voucher.create');
            Route::put('update/{id}', 'TreasuryIncomesVoucherController@update')->name('treasury-incomes-voucher.update');
            Route::get('get-voucher', 'TreasuryIncomesVoucherController@get_voucher')->name('treasury-incomes-voucher.get-voucher-id');
            Route::get('fetch-status/{id}', 'TreasuryIncomesVoucherController@fetch_status')->name('treasury-incomes-voucher.fetch_status');
            Route::get('edit/{id}', 'TreasuryIncomesVoucherController@edit')->name('treasury-incomes-voucher.edit');
            Route::get('view/{id}', 'TreasuryIncomesVoucherController@view')->name('treasury-incomes-voucher.view');
            Route::get('view-available-payables/{id}', 'TreasuryIncomesVoucherController@view_available_payables')->name('treasury-incomes-voucher.view-available-payables');
            Route::post('add-payables/{id}', 'TreasuryIncomesVoucherController@add_payables')->name('treasury-incomes-voucher.add-payables');
            Route::post('add-disbursement/{id}', 'TreasuryIncomesVoucherController@add_disbursement')->name('treasury-incomes-voucher.add-disbursement');
            Route::get('payables/edit/{id}', 'TreasuryIncomesVoucherController@edit_payables')->name('treasury-incomes-voucher.edit-payables');
            Route::put('payables/update/{id}', 'TreasuryIncomesVoucherController@update_payables')->name('treasury-incomes-voucher.update-payables');
            Route::put('payables/remove/{id}', 'TreasuryIncomesVoucherController@remove_collections')->name('treasury-incomes-voucher.remove-collections');
            Route::put('payables/remove-all/{voucher}', 'TreasuryIncomesVoucherController@remove_all_collections')->name('treasury-incomes-voucher.remove-all-collections');
            Route::put('payables/send-all/{voucher}', 'TreasuryIncomesVoucherController@send_all_collections')->name('treasury-incomes-voucher.send-all-collections');
            Route::post('payments/store/{id}', 'TreasuryIncomesVoucherController@store_payments')->name('treasury-incomes-voucher.store-payments');
            Route::get('payments/edit/{id}', 'TreasuryIncomesVoucherController@find_payments')->name('treasury-incomes-voucher.remove-payments');
            Route::put('payments/update/{id}', 'TreasuryIncomesVoucherController@update_payments')->name('treasury-incomes-voucher.update-payments');
            Route::put('payments/remove/{id}', 'TreasuryIncomesVoucherController@remove_payments')->name('treasury-incomes-voucher.remove-payments');
            Route::put('payments/remove-all/{voucher}', 'TreasuryIncomesVoucherController@remove_all_payments')->name('treasury-incomes-voucher.remove-all-payments');
            Route::put('payments/send-all/{voucher}', 'TreasuryIncomesVoucherController@send_all_payments')->name('treasury-incomes-voucher.send-all-payments');
            Route::get('find-sl-bank/{id}', 'TreasuryIncomesVoucherController@find_sl_bank')->name('treasury-incomes-voucher.find-sl-bank');
            Route::get('print/{voucher}', 'TreasuryIncomesVoucherController@print')->name('treasury-incomes-voucher.print');
            Route::get('preview/{voucher}', 'TreasuryIncomesVoucherController@preview')->name('treasury-incomes-voucher.preview');  
            Route::get('print-cheque/{id}', 'TreasuryIncomesVoucherController@print_cheque')->name('treasury-incomes-voucher.print-cheque'); 
            Route::get('payables/fetch-status/{id}', 'TreasuryIncomesVoucherController@fetch_payable_status')->name('treasury-incomes-voucher.fetch-payable-status');
            Route::get('payables/validate-approver/{id}', 'TreasuryIncomesVoucherController@validate_payables_approver')->name('treasury-incomes-voucher.validate-payables-approver');
            Route::put('payables/approve/{id}', 'TreasuryIncomesVoucherController@approve_payable')->name('treasury-incomes-voucher.approve-payable');
            Route::put('payables/disapprove/{id}', 'TreasuryIncomesVoucherController@disapprove_payable')->name('treasury-incomes-voucher.disapprove-payable');
            Route::get('payables/fetch-remarks/{id}', 'TreasuryIncomesVoucherController@fetch_payable_remarks')->name('treasury-incomes-voucher.fetch-payable-rmarks');
            Route::get('payments/fetch-status/{id}', 'TreasuryIncomesVoucherController@fetch_payment_status')->name('treasury-incomes-voucher.fetch_payment_status');
            Route::get('payments/validate-approver/{id}', 'TreasuryIncomesVoucherController@validate_payments_approver')->name('treasury-incomes-voucher.validate-payments-approver');
            Route::put('payments/approve/{id}', 'TreasuryIncomesVoucherController@approve_payment')->name('treasury-incomes-voucher.approve-payment');
            Route::put('payments/disapprove/{id}', 'TreasuryIncomesVoucherController@disapprove_payment')->name('treasury-incomes-voucher.disapprove-payment');
            Route::get('payments/fetch-remarks/{id}', 'TreasuryIncomesVoucherController@fetch_payment_remarks')->name('treasury-incomes-voucher.fetch-payment-rmarks');
            Route::get('fetch-voucher-print', 'TreasuryIncomesVoucherController@fetch_voucher_print')->name('treasury-incomes-voucher.fetch-voucher-print');
            Route::put('update-voucher-date/{id}', 'TreasuryIncomesVoucherController@update_voucher_date')->name('treasury-incomes-voucher.update-voucher-date');
            Route::get('deductions/view/{id}', 'TreasuryIncomesVoucherController@view_deduction')->name('treasury-incomes-voucher.view-deduction');
            Route::put('deductions/send-all/{voucher}', 'TreasuryIncomesVoucherController@send_all_deductions')->name('treasury-incomes-voucher.send-all-deductions');
            Route::get('deductions/fetch-status/{id}', 'TreasuryIncomesVoucherController@fetch_deduction_status')->name('treasury-incomes-voucher.fetch-deduction-status');
            Route::get('deductions/validate-approver/{id}', 'TreasuryIncomesVoucherController@validate_deductions_approver')->name('treasury-incomes-voucher.validate-deductions-approver');
            Route::put('deductions/approve/{id}', 'TreasuryIncomesVoucherController@approve_deduction')->name('treasury-incomes-voucher.approve-deduction');
            Route::put('deductions/disapprove/{id}', 'TreasuryIncomesVoucherController@disapprove_deduction')->name('treasury-incomes-voucher.disapprove-deduction');
            Route::get('deductions/fetch-remarks/{id}', 'TreasuryIncomesVoucherController@fetch_deduction_remarks')->name('treasury-incomes-voucher.fetch-deduction-rmarks');
            Route::get('fetch-document-status', 'TreasuryIncomesVoucherController@fetch_document_status')->name('treasury-incomes-voucher.fetch-document-status');
            Route::get('fetch-document-remarks', 'TreasuryIncomesVoucherController@fetch_document_remarks')->name('treasury-incomes-voucher.fetch-document-remarks');
            Route::put('payments/update-payment/{id}', 'TreasuryIncomesVoucherController@update_paymentx')->name('treasury-incomes-voucher.update-paymentx');
            Route::post('payments/store-payment/{id}', 'TreasuryIncomesVoucherController@store_paymentx')->name('treasury-incomes-voucher.store-paymentx');
            Route::put('update-vouchers/{voucher}', 'TreasuryIncomesVoucherController@vouchers_update')->name('treasury-incomes-voucher.vouchers-update');
        });

        /* Check Disbursement Routes */
        Route::prefix('check-disbursement')->group(function () {
            Route::get('', 'TreasuryCheckDisbursementVoucherController@index')->name('treasury.cash-receipt.index');
            Route::get('lists', 'TreasuryCheckDisbursementVoucherController@lists')->name('treasury.cash-receipt.lists');
            Route::match(array('GET', 'POST'),'store', 'TreasuryCheckDisbursementVoucherController@store')->name('treasury.cash-receipt.store');
            Route::post('store/formValidation', 'TreasuryCheckDisbursementVoucherController@validation')->name('treasury.cash-receipt.validation');
        });
    });

    /* Account Receivables Routes */
    Route::prefix('account-receivables')->group(function () {
        Route::get('', 'TreasuryReceivablesController@index')->name('treasury-receivables.index');
        Route::get('lists', 'TreasuryReceivablesController@lists')->name('treasury-receivables.lists');
        Route::get('export', 'TreasuryReceivablesController@export')->name('treasury-receivables.export');
    });

    Route::prefix('petty-cash')->group(function () {
        /* Disbursement Routes */
        Route::prefix('disbursement')->group(function () {
            Route::get('', 'TreasuryDisburseController@index')->name('treasury.petty-cash-disburse.index');
            Route::get('lists', 'TreasuryDisburseController@lists')->name('treasury.petty-cash-disburse.lists');
            Route::get('line-lists/{id}', 'TreasuryDisburseController@line_lists')->name('treasury.petty-cash-disburse.line-lists');
            Route::get('view-available-obligation-requests/{id}', 'TreasuryDisburseController@view_available_obligation_requests')->name('treasury.petty-cash-disburse.view-available-obligation-requests');
            Route::post('add-line/{id}', 'TreasuryDisburseController@add_line')->name('treasury.petty-cash-disburse.add-line');
            Route::put('remove-line/{id}', 'TreasuryDisburseController@remove_line')->name('treasury.petty-cash-disburse.remove-line');
            Route::get('fetch-status/{id}', 'TreasuryDisburseController@fetch_status')->name('treasury.petty-cash-disburse.fetch_status');
            Route::get('edit/{id}', 'TreasuryDisburseController@find')->name('treasury.petty-cash-disburse.edit');
            Route::put('update/{id}', 'TreasuryDisburseController@update')->name('treasury.petty-cash-disburse.update');
            Route::put('send/{detail}/{id}', 'TreasuryDisburseController@send')->name('treasury.petty-cash-disburse.send');
            Route::get('print/{voucher}', 'TreasuryDisburseController@print')->name('treasury.petty-cash-disburse.print');
        });

        Route::prefix('replenishment')->group(function () {
            Route::get('', 'TreasuryReplenishController@index')->name('treasury.petty-cash-replenish.index');
            Route::get('lists', 'TreasuryReplenishController@lists')->name('treasury.petty-cash-replenish.lists');
            Route::get('line-lists/{id}', 'TreasuryReplenishController@line_lists')->name('treasury.petty-cash-replenish.line-lists');
            Route::get('view-available-disbursements/{id}', 'TreasuryReplenishController@view_available_disbursements')->name('treasury.petty-cash-replenish.view-available-disbursements');
            Route::post('add-line/{id}', 'TreasuryReplenishController@add_line')->name('treasury.petty-cash-replenish.add-line');
            Route::put('remove-line/{id}', 'TreasuryReplenishController@remove_line')->name('treasury.petty-cash-replenish.remove-line');
            Route::get('fetch-status/{id}', 'TreasuryReplenishController@fetch_status')->name('treasury.petty-cash-replenish.fetch_status');
            Route::get('edit/{id}', 'TreasuryReplenishController@find')->name('treasury.petty-cash-replenish.edit');
            Route::put('update/{id}', 'TreasuryReplenishController@update')->name('treasury.petty-cash-replenish.update');
            Route::put('send/{detail}/{id}', 'TreasuryReplenishController@send')->name('treasury.petty-cash-replenish.send');
            Route::get('print/{controlNo}', 'TreasuryReplenishController@print')->name('treasury.petty-cash-replenish.print');
        });
    });

    Route::prefix('collections')->group(function () {
        Route::get('', 'TreasuryCollectionController@index')->name('treasury.collection.index');
        Route::get('lists', 'TreasuryCollectionController@lists')->name('treasury.collection.lists');
        Route::get('get-denominations/{id}', 'TreasuryCollectionController@get_denominations')->name('treasury.collection.get-denominations');
        Route::get('transaction-lists/{id}', 'TreasuryCollectionController@transaction_lists')->name('treasury.collection.transaction-lists');
        Route::get('receipt-lists/{id}', 'TreasuryCollectionController@receipt_lists')->name('treasury.collection.receipt-lists');
        Route::post('store', 'TreasuryCollectionController@store')->name('treasury.collection.store');
        Route::get('edit/{id}', 'TreasuryCollectionController@find')->name('treasury.collection.edit');
        Route::put('update/{id}', 'TreasuryCollectionController@update')->name('treasury.collection.update');
        Route::put('unset/{id}', 'TreasuryCollectionController@unset')->name('treasury.collection.unset');
        Route::get('validate-collection/{id}', 'TreasuryCollectionController@validate_collection')->name('treasury.collection.validate-collection');
        Route::put('send/{detail}/{id}', 'TreasuryCollectionController@send')->name('treasury.collection.send');
        Route::get('print/{transNo}', 'TreasuryCollectionController@print')->name('treasury.collection.print');
    });

    /* Cash Receipt Routes */
    Route::prefix('cash-receipt')->group(function () {
        Route::get('', 'TreasuryCashReceiptController@index')->name('treasury.cash-receipt.index');
        Route::get('lists', 'TreasuryCashReceiptController@lists')->name('treasury.cash-receipt.lists');
        Route::match(array('GET', 'POST'),'store', 'TreasuryCashReceiptController@store')->name('treasury.cash-receipt.store');
        Route::post('store/formValidation', 'TreasuryCashReceiptController@validation')->name('treasury.cash-receipt.validation');
    });
});
