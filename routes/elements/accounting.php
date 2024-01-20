<?php 


use Illuminate\Support\Facades\Route;

// -------------------------- Accounting Routes  --------------------------
Route::middleware(['auth'])->prefix('accounting')->group(function () {
    /* Fund Code Routes */
    Route::prefix('fund-codes')->group(function () {
        Route::get('', 'AcctgFundCodeController@index')->name('fund-codes.index');
        Route::get('lists', 'AcctgFundCodeController@lists')->name('fund-codes.lists');
        Route::post('store', 'AcctgFundCodeController@store')->name('fund-codes.store');
        Route::get('edit/{id}', 'AcctgFundCodeController@find')->name('fund-codes.find');
        Route::put('update/{id}', 'AcctgFundCodeController@update')->name('fund-codes.update');
        Route::put('remove/{id}', 'AcctgFundCodeController@remove')->name('fund-codes.remove');
        Route::put('restore/{id}', 'AcctgFundCodeController@restore')->name('fund-codes.restore');
    });

    /* Chart of Accounts Routes */
    Route::prefix('chart-of-accounts')->group(function () {
        /* Account Groups Routes */
        Route::prefix('account-groups')->group(function () {
            Route::get('', 'AcctgAccountGroupController@index')->name('account-groups.index');
            Route::get('lists', 'AcctgAccountGroupController@lists')->name('account-groups.lists');
            Route::post('store', 'AcctgAccountGroupController@store')->name('account-groups.store');
            Route::get('edit/{id}', 'AcctgAccountGroupController@find')->name('account-groups.find');
            Route::put('update/{id}', 'AcctgAccountGroupController@update')->name('account-groups.update');
            Route::put('remove/{id}', 'AcctgAccountGroupController@remove')->name('account-groups.remove');
            Route::put('restore/{id}', 'AcctgAccountGroupController@restore')->name('account-groups.restore');
        });

        /* Major Account Groups Routes */
        Route::prefix('major-account-groups')->group(function () {
            Route::get('', 'AcctgAccountGroupMajorController@index')->name('major-account-groups.index');
            Route::get('lists', 'AcctgAccountGroupMajorController@lists')->name('major--major-account-groups.lists');
            Route::post('store', 'AcctgAccountGroupMajorController@store')->name('major-account-groups.store');
            Route::get('edit/{id}', 'AcctgAccountGroupMajorController@find')->name('major-account-groups.find');
            Route::put('update/{id}', 'AcctgAccountGroupMajorController@update')->name('major-account-groups.update');
            Route::put('remove/{id}', 'AcctgAccountGroupMajorController@remove')->name('-major-account-groups.remove');
            Route::put('restore/{id}', 'AcctgAccountGroupMajorController@restore')->name('major-account-groups.restore');
        });

        /* Submajor Account Groups Routes */
        Route::prefix('submajor-account-groups')->group(function () {
            Route::get('', 'AcctgAccountGroupSubmajorController@index')->name('submajor-account-groups.index');
            Route::get('lists', 'AcctgAccountGroupSubmajorController@lists')->name('submajor-account-groups.lists');
            Route::post('store', 'AcctgAccountGroupSubmajorController@store')->name('submajor-account-groups.store');
            Route::get('edit/{id}', 'AcctgAccountGroupSubmajorController@find')->name('submajor-account-groups.find');
            Route::put('update/{id}', 'AcctgAccountGroupSubmajorController@update')->name('submajor-account-groups.update');
            Route::put('remove/{id}', 'AcctgAccountGroupSubmajorController@remove')->name('submajor-account-groups.remove');
            Route::put('restore/{id}', 'AcctgAccountGroupSubmajorController@restore')->name('submajor-account-groups.restore');
            Route::get('fetch-group-code', 'AcctgAccountGroupSubmajorController@fetch_group_code')->name('submajor-account-groups.fetch');
            Route::get('reload-major-account/{account}', 'AcctgAccountGroupSubmajorController@reload_major_account')->name('submajor-account-groups.reload_major');
        });


        /* General Ledger Accounts Routes */
        Route::prefix('general-ledgers')->group(function () {
            Route::get('', 'AcctgAccountGeneralLedgerController@index')->name('general-ledgers.index');
            Route::get('lists', 'AcctgAccountGeneralLedgerController@lists')->name('general-ledger.lists');
            Route::get('subsidiary-lists/{id}', 'AcctgAccountGeneralLedgerController@subsidiary_lists')->name('general-ledger.subsidiary-lists');
            Route::get('current-lists/{id}', 'AcctgAccountGeneralLedgerController@current_lists')->name('general-ledger.current-lists');
            Route::post('store', 'AcctgAccountGeneralLedgerController@store')->name('general-ledger.store');
            Route::get('edit/{id}', 'AcctgAccountGeneralLedgerController@find')->name('general-ledger.find');
            Route::get('edit-sl/{id}', 'AcctgAccountGeneralLedgerController@find_sl')->name('general-ledger.find-sl');
            Route::put('update/{id}', 'AcctgAccountGeneralLedgerController@update')->name('general-ledger.update');
            Route::put('remove/{id}', 'AcctgAccountGeneralLedgerController@remove')->name('general-ledger.remove');
            Route::put('restore/{id}', 'AcctgAccountGeneralLedgerController@restore')->name('general-ledger.restore');
            Route::get('fetch-group-code', 'AcctgAccountGeneralLedgerController@fetch_group_code')->name('general-ledger.fetch');
            Route::get('reload-major-account/{account}', 'AcctgAccountGeneralLedgerController@reload_major_account')->name('general-ledger.reload_major');
            Route::get('reload-submajor-account', 'AcctgAccountGeneralLedgerController@reload_submajor_account')->name('general-ledger.reload_submajor');
            Route::get('reload-parent/{id}/{sl}', 'AcctgAccountGeneralLedgerController@reload_parent')->name('general-ledger.reload-parent');
            Route::post('sl/store/{gl_account}', 'AcctgAccountGeneralLedgerController@store_sl')->name('general-ledger.store-sl');
            Route::put('sl/update/{gl_account}/{id}', 'AcctgAccountGeneralLedgerController@update_sl')->name('general-ledger.update-sl');       
            Route::put('remove-sl/{id}', 'AcctgAccountGeneralLedgerController@remove_sl')->name('general-ledger.remove-sl');
            Route::put('restore-sl/{id}', 'AcctgAccountGeneralLedgerController@restore_sl')->name('general-ledger.restore-sl');
            Route::put('hide-sl/{id}', 'AcctgAccountGeneralLedgerController@hide_sl')->name('general-ledger.hide-sl');
            Route::put('show-sl/{id}', 'AcctgAccountGeneralLedgerController@show_sl')->name('general-ledger.show-sl');
            Route::get('current/edit/{sl}', 'AcctgAccountGeneralLedgerController@find_current')->name('general-ledger.find-current');  
            Route::post('current/store/{sl}', 'AcctgAccountGeneralLedgerController@store_current')->name('general-ledger.store-current');            
            Route::put('current/update/{sl}/{id}', 'AcctgAccountGeneralLedgerController@update_current')->name('general-ledger.update-current');            
        });

        /* Subsidiary Ledger Accounts Routes */
        Route::prefix('subsidiary-ledgers')->group(function () {
            Route::get('', 'AcctgAccountSubsidiaryLedgerController@index')->name('subsidiary-ledgers.index');
            Route::get('lists', 'AcctgAccountSubsidiaryLedgerController@lists')->name('subsidiary-ledger.lists');
            Route::post('store/{gl_account}', 'AcctgAccountSubsidiaryLedgerController@store')->name('subsidiary-ledger.store');
            Route::put('update/{gl_account}/{id}', 'AcctgAccountSubsidiaryLedgerController@update')->name('subsidiary-ledger.update');
            Route::get('edit/{id}', 'AcctgAccountSubsidiaryLedgerController@find')->name('subsidiary-ledger.find');
            Route::put('remove/{id}', 'AcctgAccountSubsidiaryLedgerController@remove')->name('subsidiary-ledger.remove');
            Route::put('restore/{id}', 'AcctgAccountSubsidiaryLedgerController@restore')->name('subsidiary-ledger.restore');           
        });
    });

    /* Department Routes */
    Route::prefix('departments')->group(function () {
        Route::get('', 'AcctgDepartmentController@index')->name('departments.index');
        Route::get('lists', 'AcctgDepartmentController@lists')->name('departments.lists');
        Route::post('store', 'AcctgDepartmentController@store')->name('departments.store');
        Route::get('edit/{id}', 'AcctgDepartmentController@find')->name('departments.find');
        Route::put('update/{id}', 'AcctgDepartmentController@update')->name('departments.update');
        Route::put('remove/{id}', 'AcctgDepartmentController@remove')->name('departments.remove');
        Route::put('restore/{id}', 'AcctgDepartmentController@restore')->name('departments.restore');
        Route::get('fetch-designation/{id}', 'AcctgDepartmentController@fetch_designation')->name('departments.fetch.designation');

        /* Department Division Routes */
        Route::prefix('divisions')->group(function () {
            Route::get('lists/{department}', 'AcctgDepartmentController@line_lists')->name('departments.division.lists');            
            Route::post('store/{department}', 'AcctgDepartmentController@storeLineItem')->name('departments.division.store');
            Route::get('edit/{id}', 'AcctgDepartmentController@findLineItem')->name('departments.division.find');
            Route::put('update/{id}', 'AcctgDepartmentController@updateLineItem')->name('departments.division.update');
            Route::put('remove/{id}', 'AcctgDepartmentController@removeLineItem')->name('departments.division.remove');
            Route::put('restore/{id}', 'AcctgDepartmentController@restoreLineItem')->name('departments.division.restore');
        });
    });

    /* Account Payables Routes */
    Route::prefix('account-payables')->group(function () {
        Route::get('', 'AcctgPayablesController@index')->name('acctg-payables.index');
        Route::get('lists', 'AcctgPayablesController@lists')->name('acctg-payables.lists');
        Route::get('edit/{id}', 'AcctgPayablesController@find')->name('acctg-payables.find');
        Route::post('store', 'AcctgPayablesController@store')->name('acctg-payables.store');
        Route::put('update/{id}', 'AcctgPayablesController@update')->name('acctg-payables.update');
    });

    /* Account Receivables Routes */
    Route::prefix('account-receivables')->group(function () {
        Route::get('', 'AcctgReceivablesController@index')->name('acctg-receivables.index');
        Route::get('lists', 'AcctgReceivablesController@lists')->name('acctg-receivables.lists');
        Route::get('export', 'AcctgReceivablesController@export')->name('acctg-receivables.export');
    });

    /* Journal Entry Voucher Routes */
    Route::prefix('journal-entries')->group(function () {
        /* Payables Routes */
        Route::prefix('payables')->group(function () {
            Route::get('', 'AcctgPayablesVouchersController@index')->name('acctg-voucher.index');
            Route::get('lists', 'AcctgPayablesVouchersController@lists')->name('acctg-voucher.lists');
            Route::get('payables-lists/{id}', 'AcctgPayablesVouchersController@payables_lists')->name('acctg-voucher.payable-lists');
            Route::get('payments-lists/{id}', 'AcctgPayablesVouchersController@payments_lists')->name('acctg-voucher.payment-lists');
            Route::get('add', 'AcctgPayablesVouchersController@create')->name('acctg-voucher.create');
            Route::get('validate-voucher/{id}', 'AcctgPayablesVouchersController@validate_voucher')->name('acctg-voucher.validate-voucher');
            Route::put('update/{id}', 'AcctgPayablesVouchersController@update')->name('acctg-voucher.update');
            Route::get('get-voucher', 'AcctgPayablesVouchersController@get_voucher')->name('acctg-voucher.get-voucher-id');
            Route::get('fetch-status/{id}', 'AcctgPayablesVouchersController@fetch_status')->name('acctg-voucher.fetch_status');
            Route::get('edit/{id}', 'AcctgPayablesVouchersController@edit')->name('acctg-voucher.edit');
            Route::get('view/{id}', 'AcctgPayablesVouchersController@view')->name('acctg-voucher.view');
            Route::get('view-available-payables/{id}', 'AcctgPayablesVouchersController@view_available_payables')->name('acctg-voucher.view-available-payables');
            Route::post('add-payables/{id}', 'AcctgPayablesVouchersController@add_payables')->name('acctg-voucher.add-payables');
            Route::post('add-disbursement/{id}', 'AcctgPayablesVouchersController@add_disbursement')->name('acctg-voucher.add-disbursement');
            Route::get('payables/edit/{id}', 'AcctgPayablesVouchersController@edit_payables')->name('acctg-voucher.edit-payables');
            Route::put('payables/update/{id}', 'AcctgPayablesVouchersController@update_payables')->name('acctg-voucher.update-payables');
            Route::put('payables/remove/{id}', 'AcctgPayablesVouchersController@remove_payables')->name('acctg-voucher.remove-payables');
            Route::put('payables/remove-all/{voucher}', 'AcctgPayablesVouchersController@remove_all_payables')->name('acctg-voucher.remove-all-payables');
            Route::put('payables/send-all/{voucher}', 'AcctgPayablesVouchersController@send_all_payables')->name('acctg-voucher.send-all-payables');
            Route::post('payments/store/{id}', 'AcctgPayablesVouchersController@store_payments')->name('acctg-voucher.store-payments');
            Route::get('payments/edit/{id}', 'AcctgPayablesVouchersController@find_payments')->name('acctg-voucher.remove-payments');
            Route::put('payments/update/{id}', 'AcctgPayablesVouchersController@update_payments')->name('acctg-voucher.update-payments');
            Route::put('payments/remove/{id}', 'AcctgPayablesVouchersController@remove_payments')->name('acctg-voucher.remove-payments');
            Route::put('payments/remove-all/{voucher}', 'AcctgPayablesVouchersController@remove_all_payments')->name('acctg-voucher.remove-all-payments');
            Route::put('payments/send-all/{voucher}', 'AcctgPayablesVouchersController@send_all_payments')->name('acctg-voucher.send-all-payments');
            Route::get('find-sl-bank/{id}', 'AcctgPayablesVouchersController@find_sl_bank')->name('acctg-voucher.find-sl-bank');
            Route::get('print/{voucher}', 'AcctgPayablesVouchersController@print')->name('acctg-voucher.print');
            Route::get('preview/{voucher}', 'AcctgPayablesVouchersController@preview')->name('acctg-voucher.preview');
            Route::get('print-cheque/{id}', 'AcctgPayablesVouchersController@print_cheque')->name('acctg-voucher.print-cheque');
            Route::get('fetch-disbursement-type/{id}', 'AcctgPayablesVouchersController@fetch_disbursement_type')->name('acctg-voucher.fetch_disbursement_type');
            Route::get('fetch-disbursement-reference/{id}', 'AcctgPayablesVouchersController@fetch_disbursement_reference')->name('acctg-voucher.fetch_disbursement_reference');
            Route::get('payables/fetch-status/{id}', 'AcctgPayablesVouchersController@fetch_payable_status')->name('acctg-voucher.fetch-payable-status');
            Route::get('payables/validate-approver/{id}', 'AcctgPayablesVouchersController@validate_payables_approver')->name('acctg-voucher.validate-payables-approver');
            Route::put('payables/approve/{id}', 'AcctgPayablesVouchersController@approve_payable')->name('acctg-voucher.approve-payable');
            Route::put('payables/disapprove/{id}', 'AcctgPayablesVouchersController@disapprove_payable')->name('acctg-voucher.disapprove-payable');
            Route::get('payables/fetch-remarks/{id}', 'AcctgPayablesVouchersController@fetch_payable_remarks')->name('acctg-voucher.fetch-payable-rmarks');
            Route::get('payments/fetch-status/{id}', 'AcctgPayablesVouchersController@fetch_payment_status')->name('acctg-voucher.fetch_payment_status');
            Route::get('payments/validate-approver/{id}', 'AcctgPayablesVouchersController@validate_payments_approver')->name('acctg-voucher.validate-payments-approver');
            Route::put('payments/approve/{id}', 'AcctgPayablesVouchersController@approve_payment')->name('acctg-voucher.approve-payment');
            Route::put('payments/disapprove/{id}', 'AcctgPayablesVouchersController@disapprove_payment')->name('acctg-voucher.disapprove-payment');
            Route::get('payments/fetch-remarks/{id}', 'AcctgPayablesVouchersController@fetch_payment_remarks')->name('acctg-voucher.fetch-payment-rmarks');        
            Route::put('complete/{id}', 'AcctgPayablesVouchersController@complete')->name('acctg-voucher.complete');
            Route::get('fetch-voucher-print', 'AcctgPayablesVouchersController@fetch_voucher_print')->name('acctg-voucher.fetch-voucher-print');
            Route::get('fetch-document-status', 'AcctgPayablesVouchersController@fetch_document_status')->name('acctg-voucher.fetch-document-status');
            Route::get('fetch-document-remarks', 'AcctgPayablesVouchersController@fetch_document_remarks')->name('acctg-voucher.fetch-document-remarks');
            Route::put('update-voucher-date/{id}', 'AcctgPayablesVouchersController@update_voucher_date')->name('acctg-voucher.update-voucher-date');
            Route::put('payments/update-payment/{id}', 'AcctgPayablesVouchersController@update_paymentx')->name('acctg-voucher.update-paymentx');
            Route::post('payments/store-payment/{id}', 'AcctgPayablesVouchersController@store_paymentx')->name('acctg-voucher.store-paymentx');
            Route::put('update-vouchers/{voucher}', 'AcctgPayablesVouchersController@vouchers_update')->name('acctg-voucher.vouchers-update');
        });

        /* Incomes Routes */
        Route::prefix('incomes')->group(function () {
            Route::get('', 'AcctgIncomeVouchersController@index')->name('acctg-voucher.index');
            Route::get('lists', 'AcctgIncomeVouchersController@lists')->name('acctg-voucher.lists');
            Route::get('payables-lists/{id}', 'AcctgIncomeVouchersController@collections_lists')->name('acctg-voucher.collection-lists');
            Route::get('payments-lists/{id}', 'AcctgIncomeVouchersController@payments_lists')->name('acctg-voucher.payment-lists');
            Route::get('deductions-lists/{id}', 'AcctgIncomeVouchersController@deductions_lists')->name('acctg-voucher.deduction-lists');
            Route::get('add', 'AcctgIncomeVouchersController@create')->name('acctg-voucher.create');
            Route::get('validate-voucher/{id}', 'AcctgIncomeVouchersController@validate_voucher')->name('acctg-voucher.validate-voucher');
            Route::put('update/{id}', 'AcctgIncomeVouchersController@update')->name('acctg-voucher.update');
            Route::get('get-voucher', 'AcctgIncomeVouchersController@get_voucher')->name('acctg-voucher.get-voucher-id');
            Route::get('fetch-status/{id}', 'AcctgIncomeVouchersController@fetch_status')->name('acctg-voucher.fetch_status');
            Route::get('edit/{id}', 'AcctgIncomeVouchersController@edit')->name('acctg-voucher.edit');
            Route::get('view/{id}', 'AcctgIncomeVouchersController@view')->name('acctg-voucher.view');
            Route::get('view-available-payables/{id}', 'AcctgIncomeVouchersController@view_available_payables')->name('acctg-voucher.view-available-payables');
            Route::post('add-payables/{id}', 'AcctgIncomeVouchersController@add_payables')->name('acctg-voucher.add-payables');
            Route::post('add-disbursement/{id}', 'AcctgIncomeVouchersController@add_disbursement')->name('acctg-voucher.add-disbursement');            
            Route::get('payables/edit/{id}', 'AcctgIncomeVouchersController@edit_payables')->name('acctg-voucher.edit-payables');
            Route::put('payables/update/{id}', 'AcctgIncomeVouchersController@update_payables')->name('acctg-voucher.update-payables');
            Route::put('payables/remove/{id}', 'AcctgIncomeVouchersController@remove_collections')->name('acctg-voucher.remove-collections');
            Route::put('payables/remove-all/{voucher}', 'AcctgIncomeVouchersController@remove_all_collections')->name('acctg-voucher.remove-all-collections');
            Route::put('payables/send-all/{voucher}', 'AcctgIncomeVouchersController@send_all_collections')->name('acctg-voucher.send-all-collections');
            Route::post('payments/store/{id}', 'AcctgIncomeVouchersController@store_payments')->name('acctg-voucher.store-payments');
            Route::get('payments/edit/{id}', 'AcctgIncomeVouchersController@find_payments')->name('acctg-voucher.remove-payments');
            Route::put('payments/update/{id}', 'AcctgIncomeVouchersController@update_payments')->name('acctg-voucher.update-payments');
            Route::put('payments/remove/{id}', 'AcctgIncomeVouchersController@remove_payments')->name('acctg-voucher.remove-payments');
            Route::put('payments/remove-all/{voucher}', 'AcctgIncomeVouchersController@remove_all_payments')->name('acctg-voucher.remove-all-payments');
            Route::put('payments/send-all/{voucher}', 'AcctgIncomeVouchersController@send_all_payments')->name('acctg-voucher.send-all-payments');
            Route::get('find-sl-bank/{id}', 'AcctgIncomeVouchersController@find_sl_bank')->name('acctg-voucher.find-sl-bank');
            Route::get('print/{voucher}', 'AcctgIncomeVouchersController@print')->name('acctg-voucher.print');
            Route::get('preview/{voucher}', 'AcctgIncomeVouchersController@preview')->name('acctg-voucher.preview');
            Route::get('print-cheque/{id}', 'AcctgIncomeVouchersController@print_cheque')->name('acctg-voucher.print-cheque');
            Route::get('fetch-disbursement-type/{id}', 'AcctgIncomeVouchersController@fetch_disbursement_type')->name('acctg-voucher.fetch_disbursement_type');
            Route::get('fetch-disbursement-reference/{id}', 'AcctgIncomeVouchersController@fetch_disbursement_reference')->name('acctg-voucher.fetch_disbursement_reference');
            Route::get('payables/fetch-status/{id}', 'AcctgIncomeVouchersController@fetch_payable_status')->name('acctg-voucher.fetch-payable-status');
            Route::get('payables/validate-approver/{id}', 'AcctgIncomeVouchersController@validate_payables_approver')->name('acctg-voucher.validate-payables-approver');
            Route::put('payables/approve/{id}', 'AcctgIncomeVouchersController@approve_payable')->name('acctg-voucher.approve-payable');
            Route::put('payables/disapprove/{id}', 'AcctgIncomeVouchersController@disapprove_payable')->name('acctg-voucher.disapprove-payable');
            Route::get('payables/fetch-remarks/{id}', 'AcctgIncomeVouchersController@fetch_payable_remarks')->name('acctg-voucher.fetch-payable-rmarks');
            Route::get('payments/fetch-status/{id}', 'AcctgIncomeVouchersController@fetch_payment_status')->name('acctg-voucher.fetch_payment_status');
            Route::get('payments/validate-approver/{id}', 'AcctgIncomeVouchersController@validate_payments_approver')->name('acctg-voucher.validate-payments-approver');
            Route::put('payments/approve/{id}', 'AcctgIncomeVouchersController@approve_payment')->name('acctg-voucher.approve-payment');
            Route::put('payments/disapprove/{id}', 'AcctgIncomeVouchersController@disapprove_payment')->name('acctg-voucher.disapprove-payment');
            Route::get('payments/fetch-remarks/{id}', 'AcctgIncomeVouchersController@fetch_payment_remarks')->name('acctg-voucher.fetch-payment-rmarks');        
            Route::put('complete/{id}', 'AcctgIncomeVouchersController@complete')->name('acctg-voucher.complete');
            Route::get('fetch-voucher-print', 'AcctgIncomeVouchersController@fetch_voucher_print')->name('acctg-voucher.fetch-voucher-print');
            Route::put('update-voucher-date/{id}', 'AcctgIncomeVouchersController@update_voucher_date')->name('acctg-voucher.update-voucher-date');
            Route::get('deductions/view/{id}', 'AcctgIncomeVouchersController@view_deduction')->name('acctg-voucher.view-deduction');
            Route::put('deductions/send-all/{voucher}', 'AcctgIncomeVouchersController@send_all_deductions')->name('acctg-voucher.send-all-deductions');
            Route::get('deductions/fetch-status/{id}', 'AcctgIncomeVouchersController@fetch_deduction_status')->name('acctg-voucher.fetch-deduction-status');
            Route::get('deductions/validate-approver/{id}', 'AcctgIncomeVouchersController@validate_deductions_approver')->name('acctg-voucher.validate-deductions-approver');
            Route::put('deductions/approve/{id}', 'AcctgIncomeVouchersController@approve_deduction')->name('acctg-voucher.approve-deduction');
            Route::put('deductions/disapprove/{id}', 'AcctgIncomeVouchersController@disapprove_deduction')->name('acctg-voucher.disapprove-deduction');
            Route::get('deductions/fetch-remarks/{id}', 'AcctgIncomeVouchersController@fetch_deduction_remarks')->name('acctg-voucher.fetch-deduction-rmarks');
            Route::get('fetch-document-status', 'AcctgIncomeVouchersController@fetch_document_status')->name('acctg-voucher.fetch-document-status');
            Route::get('fetch-document-remarks', 'AcctgIncomeVouchersController@fetch_document_remarks')->name('acctg-voucher.fetch-document-remarks');
            Route::put('payments/update-payment/{id}', 'AcctgIncomeVouchersController@update_paymentx')->name('acctg-voucher.update-paymentx');
            Route::post('payments/store-payment/{id}', 'AcctgIncomeVouchersController@store_paymentx')->name('acctg-voucher.store-paymentx');
            Route::put('update-vouchers/{voucher}', 'AcctgIncomeVouchersController@vouchers_update')->name('acctg-voucher.vouchers-update');
        });

        // DEBIT MEMO
        Route::prefix('debit-memo')->group(function () {
            Route::get('', 'AcctgDebitMemoController@index')->name('acctg-debit-memo.index');
            Route::get('lists', 'AcctgDebitMemoController@lists')->name('acctg-debit-memo.lists');
            Route::get('employees-lists/{id}', 'AcctgDebitMemoController@employees_lists')->name('acctg-debit-memo.employees-lists');
            Route::get('payables-lists/{id}', 'AcctgDebitMemoController@collections_lists')->name('acctg-debit-memo.collection-lists');
            Route::get('payments-lists/{id}', 'AcctgDebitMemoController@payments_lists')->name('acctg-debit-memo.payment-lists');
            Route::get('deductions-lists/{id}', 'AcctgDebitMemoController@deductions_lists')->name('acctg-debit-memo.deduction-lists');
            Route::get('add', 'AcctgDebitMemoController@create')->name('acctg-debit-memo.create');
            Route::get('validate-voucher/{id}', 'AcctgDebitMemoController@validate_voucher')->name('acctg-debit-memo.validate-voucher');
            Route::put('update/{id}', 'AcctgDebitMemoController@update')->name('acctg-debit-memo.update');
            Route::get('get-voucher', 'AcctgDebitMemoController@get_voucher')->name('acctg-debit-memo.get-voucher-id');
            Route::get('fetch-status/{id}', 'AcctgDebitMemoController@fetch_status')->name('acctg-debit-memo.fetch_status');
            Route::get('edit/{id}', 'AcctgDebitMemoController@edit')->name('acctg-debit-memo.edit');
            Route::get('view/{id}', 'AcctgDebitMemoController@view')->name('acctg-voucher.view');
            Route::get('view/{gl_id}/{voucher_id}', 'AcctgDebitMemoController@viewGL')->name('acctg-debit-memo.view');
            Route::get('view-available-payables/{id}', 'AcctgDebitMemoController@view_available_payables')->name('acctg-debit-memo.view-available-payables');
            Route::post('add-payables/{id}', 'AcctgDebitMemoController@add_payables')->name('acctg-debit-memo.add-payables');
            Route::post('add-disbursement/{id}', 'AcctgDebitMemoController@add_disbursement')->name('acctg-debit-memo.add-disbursement');            
            Route::get('payables/edit/{id}', 'AcctgDebitMemoController@edit_payables')->name('acctg-debit-memo.edit-payables');
            Route::put('payables/update/{id}', 'AcctgDebitMemoController@update_payables')->name('acctg-debit-memo.update-payables');
            Route::put('payables/remove/{id}', 'AcctgDebitMemoController@remove_collections')->name('acctg-debit-memo.remove-collections');
            Route::put('payables/remove-all/{voucher}', 'AcctgDebitMemoController@remove_all_collections')->name('acctg-debit-memo.remove-all-collections');
            Route::put('payables/send-all/{voucher}', 'AcctgDebitMemoController@send_all_collections')->name('acctg-debit-memo.send-all-collections');
            Route::post('payments/store/{id}', 'AcctgDebitMemoController@store_payments')->name('acctg-debit-memo.store-payments');
            Route::get('payments/edit/{id}', 'AcctgDebitMemoController@find_payments')->name('acctg-debit-memo.remove-payments');
            Route::put('payments/update/{id}', 'AcctgDebitMemoController@update_payments')->name('acctg-debit-memo.update-payments');
            Route::put('payments/remove/{id}', 'AcctgDebitMemoController@remove_payments')->name('acctg-debit-memo.remove-payments');
            Route::put('payments/remove-all/{voucher}', 'AcctgDebitMemoController@remove_all_payments')->name('acctg-debit-memo.remove-all-payments');
            Route::put('payments/send-all/{voucher}', 'AcctgDebitMemoController@send_all_payments')->name('acctg-debit-memo.send-all-payments');
            Route::get('find-sl-bank/{id}', 'AcctgDebitMemoController@find_sl_bank')->name('acctg-debit-memo.find-sl-bank');
            Route::get('print/{voucher}', 'AcctgDebitMemoController@print')->name('acctg-debit-memo.print');
            Route::get('preview/{voucher}', 'AcctgDebitMemoController@preview')->name('acctg-debit-memo.preview');
            Route::get('print-cheque/{id}', 'AcctgDebitMemoController@print_cheque')->name('acctg-debit-memo.print-cheque');
            Route::get('fetch-disbursement-type/{id}', 'AcctgDebitMemoController@fetch_disbursement_type')->name('acctg-debit-memo.fetch_disbursement_type');
            Route::get('fetch-disbursement-reference/{id}', 'AcctgDebitMemoController@fetch_disbursement_reference')->name('acctg-debit-memo.fetch_disbursement_reference');
            Route::get('payables/fetch-status/{id}', 'AcctgDebitMemoController@fetch_payable_status')->name('acctg-debit-memo.fetch-payable-status');
            Route::get('payables/validate-approver/{id}', 'AcctgDebitMemoController@validate_payables_approver')->name('acctg-debit-memo.validate-payables-approver');
            Route::put('payables/approve/{id}', 'AcctgDebitMemoController@approve_payable')->name('acctg-debit-memo.approve-payable');
            Route::put('payables/disapprove/{id}', 'AcctgDebitMemoController@disapprove_payable')->name('acctg-debit-memo.disapprove-payable');
            Route::get('payables/fetch-remarks/{id}', 'AcctgDebitMemoController@fetch_payable_remarks')->name('acctg-debit-memo.fetch-payable-rmarks');
            Route::get('payments/fetch-status/{id}', 'AcctgDebitMemoController@fetch_payment_status')->name('acctg-debit-memo.fetch_payment_status');
            Route::get('payments/validate-approver/{id}', 'AcctgDebitMemoController@validate_payments_approver')->name('acctg-debit-memo.validate-payments-approver');
            Route::put('payments/approve/{id}', 'AcctgDebitMemoController@approve_payment')->name('acctg-debit-memo.approve-payment');
            Route::put('payments/disapprove/{id}', 'AcctgDebitMemoController@disapprove_payment')->name('acctg-debit-memo.disapprove-payment');
            Route::get('payments/fetch-remarks/{id}', 'AcctgDebitMemoController@fetch_payment_remarks')->name('acctg-debit-memo.fetch-payment-rmarks');        
            Route::put('complete/{id}', 'AcctgDebitMemoController@complete')->name('acctg-debit-memo.complete');
            Route::get('fetch-voucher-print', 'AcctgDebitMemoController@fetch_voucher_print')->name('acctg-debit-memo.fetch-voucher-print');
            Route::put('update-voucher-date/{id}', 'AcctgDebitMemoController@update_voucher_date')->name('acctg-debit-memo.update-voucher-date');
            Route::get('deductions/view/{gl_id}/{voucher_id}', 'AcctgDebitMemoController@view_deduction')->name('acctg-debit-memo.view-deduction');
            Route::put('deductions/send-all/{voucher}', 'AcctgDebitMemoController@send_all_deductions')->name('acctg-debit-memo.send-all-deductions');
            Route::get('deductions/fetch-status/{id}', 'AcctgDebitMemoController@fetch_deduction_status')->name('acctg-debit-memo.fetch-deduction-status');
            Route::get('deductions/validate-approver/{id}', 'AcctgDebitMemoController@validate_deductions_approver')->name('acctg-debit-memo.validate-deductions-approver');
            Route::put('deductions/approve/{id}', 'AcctgDebitMemoController@approve_deduction')->name('acctg-debit-memo.approve-deduction');
            Route::put('deductions/disapprove/{id}', 'AcctgDebitMemoController@disapprove_deduction')->name('acctg-debit-memo.disapprove-deduction');
            Route::get('deductions/fetch-remarks/{id}', 'AcctgDebitMemoController@fetch_deduction_remarks')->name('acctg-debit-memo.fetch-deduction-rmarks');
            Route::get('fetch-document-status', 'AcctgDebitMemoController@fetch_document_status')->name('acctg-debit-memo.fetch-document-status');
            Route::get('fetch-document-remarks', 'AcctgDebitMemoController@fetch_document_remarks')->name('acctg-debit-memo.fetch-document-remarks');
        });
    });

    /* Setup Data Routes */
    Route::prefix('setup-data')->group(function () {
        Route::prefix('expanded-vatable-taxes')->group(function () {
            Route::get('', 'AcctgExpandedVatableTaxesController@index')->name('acctg.setup-data.evat.index');
            Route::get('lists', 'AcctgExpandedVatableTaxesController@lists')->name('acctg.setup-data.evat.lists');
            Route::post('store', 'AcctgExpandedVatableTaxesController@store')->name('acctg.setup-data.evat.store');
            Route::get('edit/{id}', 'AcctgExpandedVatableTaxesController@find')->name('acctg.setup-data.evat.find');
            Route::put('update/{id}', 'AcctgExpandedVatableTaxesController@update')->name('acctg.setup-data.evat.update');
            Route::put('remove/{id}', 'AcctgExpandedVatableTaxesController@remove')->name('acctg.setup-data.evat.remove');
            Route::put('restore/{id}', 'AcctgExpandedVatableTaxesController@restore')->name('acctg.setup-data.evat.restore');
        });

        Route::prefix('expanded-withholding-taxes')->group(function () {
            Route::get('', 'AcctgExpandedWithholdingTaxesController@index')->name('acctg.setup-data.ewt.index');
            Route::get('lists', 'AcctgExpandedWithholdingTaxesController@lists')->name('acctg.setup-data.ewt.lists');
            Route::post('store', 'AcctgExpandedWithholdingTaxesController@store')->name('acctg.setup-data.ewt.store');
            Route::get('edit/{id}', 'AcctgExpandedWithholdingTaxesController@find')->name('acctg.setup-data.ewt.find');
            Route::put('update/{id}', 'AcctgExpandedWithholdingTaxesController@update')->name('acctg.setup-data.ewt.update');
            Route::put('remove/{id}', 'AcctgExpandedWithholdingTaxesController@remove')->name('acctg.setup-data.ewt.remove');
            Route::put('restore/{id}', 'AcctgExpandedWithholdingTaxesController@restore')->name('acctg.setup-data.ewt.restore');
        });

        Route::prefix('payment-types')->group(function () {
            Route::get('', 'AcctgPaymentTypeController@index')->name('acctg.setup-data.payment-type.index');
            Route::get('lists', 'AcctgPaymentTypeController@lists')->name('acctg.setup-data.payment-type.lists');
            Route::post('store', 'AcctgPaymentTypeController@store')->name('acctg.setup-data.payment-type.store');
            Route::get('edit/{id}', 'AcctgPaymentTypeController@find')->name('acctg.setup-data.payment-type.find');
            Route::put('update/{id}', 'AcctgPaymentTypeController@update')->name('acctg.setup-data.payment-type.update');
            Route::put('remove/{id}', 'AcctgPaymentTypeController@remove')->name('acctg.setup-data.payment-type.remove');
            Route::put('restore/{id}', 'AcctgPaymentTypeController@restore')->name('acctg.setup-data.payment-type.restore');
        });

        Route::prefix('banks')->group(function () {
            Route::get('', 'AcctgBankController@index')->name('acctg.setup-data.bank.index');
            Route::get('lists', 'AcctgBankController@lists')->name('acctg.setup-data.bank.lists');
            Route::post('store', 'AcctgBankController@store')->name('acctg.setup-data.bank.store');
            Route::get('edit/{id}', 'AcctgBankController@find')->name('acctg.setup-data.bank.find');
            Route::put('update/{id}', 'AcctgBankController@update')->name('acctg.setup-data.bank.update');
            Route::put('remove/{id}', 'AcctgBankController@remove')->name('acctg.setup-data.bank.remove');
            Route::put('restore/{id}', 'AcctgBankController@restore')->name('acctg.setup-data.bank.restore');
        });
    });

    Route::prefix('fixed-assets')->group(function () {
        Route::get('', 'AcctgFixedAssetController@index')->name('acctg.fixed-asset.index');
        Route::get('lists', 'AcctgFixedAssetController@lists')->name('acctg.fixed-asset.lists');
        Route::get('history-lists/{fixedAsse}', 'AcctgFixedAssetController@history_lists')->name('acctg.fixed-asset.history-lists');
        Route::post('store', 'AcctgFixedAssetController@store')->name('acctg.fixed-asset.store');
        Route::get('edit/{id}', 'AcctgFixedAssetController@find')->name('acctg.fixed-asset.find');
        Route::put('update/{id}', 'AcctgFixedAssetController@update')->name('acctg.fixed-asset.update');
        Route::get('reload-items-via-gl/{gl_account}', 'AcctgFixedAssetController@reload_items_via_gl')->name('acctg.fixed-asset.find');
        Route::put('lock/{id}', 'AcctgFixedAssetController@lock')->name('acctg.fixed-asset.lock');
    });

    Route::prefix('general-journals')->group(function () {
        Route::get('', 'AcctgGeneralJournalController@index')->name('acctg.general-journal.index');
        Route::get('lists', 'AcctgGeneralJournalController@lists')->name('acctg.general-journal.lists');
        Route::get('line-lists/{journal}', 'AcctgGeneralJournalController@line_lists')->name('acctg.general-journal.line-lists');
        Route::get('reload-fixed-asset/{journal}', 'AcctgGeneralJournalController@reload_fixed_asset')->name('acctg.general-journal.reload_fixed_asset');
        Route::get('fetch-status/{id}', 'AcctgGeneralJournalController@fetch_status')->name('acctg.general-journal.fetch_status');
        Route::put('update/{id}', 'AcctgGeneralJournalController@update')->name('acctg.general-journal.update');
        Route::get('edit/{id}', 'AcctgGeneralJournalController@find')->name('acctg.general-journal.find');
        Route::get('edit-entry/{id}', 'AcctgGeneralJournalController@find_entry')->name('acctg.general-journal.find_entry');
        Route::post('store-entry/{id}', 'AcctgGeneralJournalController@store_entry')->name('acctg.general-journal.store_entry');
        Route::put('modify-entry/{id}', 'AcctgGeneralJournalController@modify_entry')->name('acctg.general-journal.modify_entry');
        Route::put('remove-entry/{id}', 'AcctgGeneralJournalController@remove_entry')->name('acctg.general-journal.remove_entry');
        Route::get('validate/{id}', 'AcctgGeneralJournalController@validate_journal')->name('acctg.general-journal.validate');
        Route::put('complete/{id}', 'AcctgGeneralJournalController@complete')->name('acctg.general-journal.complete');
    });

    
});