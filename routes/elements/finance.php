<?php 


use Illuminate\Support\Facades\Route;

// ------------------------------ Finance Routes ------------------------------
Route::middleware(['auth'])->prefix('finance')->group(function () {
    /* Payee Routes */
    Route::prefix('payee')->group(function () {
        Route::get('', 'CboPayeeController@index')->name('payee.index');
        Route::get('lists', 'CboPayeeController@lists')->name('payee.lists');
        Route::post('store', 'CboPayeeController@store')->name('payee.store');
        Route::get('edit/{id}', 'CboPayeeController@find')->name('payee.find');
        Route::put('update/{id}', 'CboPayeeController@update')->name('payee.update');
        Route::put('remove/{id}', 'CboPayeeController@remove')->name('payee.remove');
        Route::put('restore/{id}', 'CboPayeeController@restore')->name('payee.restore');
        Route::get('print/{id}', 'CboPayeeController@print')->name('payee.print');//print bir 2307
        Route::get('fetch-group-code', 'CboPayeeController@fetch_group_code')->name('payee.fetch');
        Route::get('fatch-sup-data', 'CboPayeeController@fetch_sup_data')->name('payee.reload_major');
    });

    /* Budget Proposal Routes */
    Route::prefix('budget-proposal')->group(function () {
        Route::get('', 'CboBudgetController@index')->name('cbo.budget-proposal.index');
        Route::get('lists', 'CboBudgetController@lists')->name('cbo.budget-proposal.lists');
        Route::get('line-lists/{id}', 'CboBudgetController@line_lists')->name('cbo.budget-proposal.line-lists');
        Route::put('update/{id}', 'CboBudgetController@update')->name('cbo.budget-proposal.update');
        Route::get('fetch-status/{id}', 'CboBudgetController@fetch_status')->name('cbo.budget-proposal.fetch-status');
        Route::get('edit/{id}', 'CboBudgetController@find')->name('cbo.budget-proposal.edit');
        Route::get('reload-division/{department}', 'CboBudgetController@reload_division')->name('cbo.budget-proposal.reload-division');
        Route::post('add-breakdown/{id}', 'CboBudgetController@store_breakdown')->name('cbo.budget-proposal.add-breakdown');
        Route::get('edit-breakdown/{id}', 'CboBudgetController@find_breakdown')->name('cbo.budget-proposal.edit-breakdown');
        Route::put('update-breakdown/{id}', 'CboBudgetController@update_breakdown')->name('cbo.budget-proposal.update-breakdown');
        Route::put('remove/{id}', 'CboBudgetController@remove')->name('cbo.budget-proposal.remove');
        Route::put('restore/{id}', 'CboBudgetController@restore')->name('cbo.budget-proposal.restore');
        Route::put('send/{detail}/{id}', 'CboBudgetController@send')->name('cbo.budget-proposal.send');
        Route::get('validate-budget/{id}', 'CboBudgetController@validate_budget')->name('cbo.budget-proposal.validate-budget');
        Route::get('validate-amount/{id}', 'CboBudgetController@validate_amount')->name('cbo.budget-proposal.validate-amount');
        Route::get('reload-division-via-department/{department}', 'CboBudgetController@reload_division')->name('cbo.budget-proposal.reload-division');
        Route::get('year-lists', 'CboBudgetController@year_lists')->name('cbo.budget-proposal.year-lists');
        Route::post('copy', 'CboBudgetController@copy_proposal')->name('cbo.budget-proposal.copy');
        Route::get('fetch-breakdown-status/{id}', 'CboBudgetController@fetch_breakdown_status')->name('cbo.budget-proposal.fetch-breakdown-status');
    });

    /* Budget Allocation Routes */
    Route::prefix('budget-allocations')->group(function () {
        Route::get('', 'CboBudgetAllocationController@index')->name('cbo.budget-allocation.index');
        Route::get('lists', 'CboBudgetAllocationController@lists')->name('cbo.budget-allocation.lists');
        Route::get('item-lists/{id}', 'CboBudgetAllocationController@item_lists')->name('cbo.budget-allocation.item-lists');
        Route::get('reload-items/{id}', 'CboBudgetAllocationController@reload_items')->name('cbo.budget-allocation.reload-items');
        Route::get('reload-uom/{id}', 'CboBudgetAllocationController@reload_uom')->name('cbo.budget-allocation.reload-uom');
        Route::get('reload-divisions-employees/{id}', 'CboBudgetAllocationController@reload_divisions_employees')->name('cbo.budget-allocation.reload-divisions-employees');
        Route::get('reload-designation/{id}', 'CboBudgetAllocationController@reload_designation')->name('cbo.budget-allocation.reload-designation');
        Route::get('edit/{id}', 'CboBudgetAllocationController@find')->name('cbo.budget-allocation.find');
        Route::get('edits/{id}', 'CboBudgetAllocationController@find_obligation')->name('cbo.budget-allocation.find_obligation');
        Route::put('update/{id}', 'CboBudgetAllocationController@update')->name('cbo.budget-allocation.update');
        Route::get('fetch-payee-details/{id}/{column}', 'CboBudgetAllocationController@fetch_payee_details')->name('cbo.budget-allocation.fetch-payee-details');
        Route::get('view-alob-lines/{id}', 'CboBudgetAllocationController@view_alob_lines')->name('cbo.budget-allocation.view_alob_lines');
        Route::get('view-alob-lines2/{id}', 'CboBudgetAllocationController@view_alob_lines2')->name('cbo.budget-allocation.view_alob_lines2');
        Route::get('alob-lists/{id}', 'CboBudgetAllocationController@alob_lists')->name('cbo.budget-allocation.alob-lists');
        Route::get('alob-lists2/{id}', 'CboBudgetAllocationController@alob_lists2')->name('cbo.budget-allocation.alob-lists2');
        Route::put('update-row/{id}', 'CboBudgetAllocationController@update_row')->name('cbo.budget-allocation.update-row');
        Route::put('update-row2/{id}', 'CboBudgetAllocationController@update_row2')->name('cbo.budget-allocation.update-row2');
        Route::put('remove-line/{id}', 'CboBudgetAllocationController@remove_line')->name('cbo.budget-allocation.remove-line');
        Route::get('fetch-allotment-via-pr/{id}', 'CboBudgetAllocationController@fetch_allotment_via_pr')->name('cbo.budget-allocation.fetch-allotment-via-pr');
        Route::put('send/{detail}/{id}', 'CboBudgetAllocationController@send')->name('cbo.budget-allocation.send');
        Route::put('approve/{id}', 'CboBudgetAllocationController@approve')->name('cbo.budget-allocation.approve');
        Route::put('disapprove/{id}', 'CboBudgetAllocationController@disapprove')->name('cbo.budget-allocation.disapprove');
        Route::get('fetch-alob-status/{id}', 'CboBudgetAllocationController@fetch_alob_status')->name('cbo.budget-allocation.fetch-alob-status');
        Route::get('fetch-allotment-via-pr2/{id}', 'CboBudgetAllocationController@fetch_allotment_via_pr2')->name('cbo.budget-allocation.fetch-allotment-via-pr2');
        Route::get('fetch-status/{id}', 'CboBudgetAllocationController@fetch_status')->name('cbo.budget-allocation.fetch-status');
        Route::get('reload-division-via-department/{department}', 'CboBudgetAllocationController@reload_division')->name('cbo.budget-allocation.reload-division');
    });

    /* Obligation Request Routes */
    Route::prefix('obligation-requests')->group(function () {
        $obligations = ['procurement', 'reimbursement', 'cash-advance', 'replenishment', 'repairs-and-maintenance', 'social-welfare', 'petty-cash', 'payroll'];
        foreach ($obligations as $obligation) {
            Route::prefix($obligation)->group(function () {
                Route::get('', 'GsoObligationRequestController@index')->name('gso.obligation-request.index');
                Route::get('lists', 'GsoObligationRequestController@lists')->name('gso.obligation-request.lists');
                Route::get('employee-list/{id}', 'GsoObligationRequestController@payrollComputationList')->name('gso.obligation-request.lists');//for payroll
                Route::get('item-lists/{id}', 'GsoObligationRequestController@item_lists')->name('gso.obligation-request.item-lists');
                Route::get('reload-items/{id}', 'GsoObligationRequestController@reload_items')->name('gso.obligation-request.reload-items');
                Route::get('reload-uom/{id}', 'GsoObligationRequestController@reload_uom')->name('gso.obligation-request.reload-uom');
                Route::get('reload-divisions-employees/{id}', 'GsoObligationRequestController@reload_divisions_employees')->name('gso.obligation-request.reload-divisions-employees');
                Route::get('reload-designation/{id}', 'GsoObligationRequestController@reload_designation')->name('gso.obligation-request.reload-designation');
                Route::get('edit/{id}', 'GsoObligationRequestController@find')->name('gso.obligation-request.find');
                Route::get('edits/{id}', 'GsoObligationRequestController@find_obligation')->name('gso.obligation-request.find_obligation');
                Route::get('print/{control_no}', 'GsoObligationRequestController@print')->name('gso.obligation-request.print');
                Route::get('print-disbursement/{control_no}', 'GsoObligationRequestController@print_disbursement')->name('gso.obligation-request.print-disbursement');
                Route::put('update/{id}', 'GsoObligationRequestController@update')->name('gso.obligation-request.update');
                Route::get('fetch-alob-status/{id}', 'GsoObligationRequestController@fetch_alob_status')->name('gso.obligation-request.fetch-alob-status');
                Route::put('update-row2/{id}', 'GsoObligationRequestController@update_row2')->name('gso.obligation-request.update-row2');
                Route::get('view-alob-lines/{id}', 'GsoObligationRequestController@view_alob_lines')->name('gso.obligation-request.view_alob_lines');
                Route::get('view-alob-lines2/{id}', 'GsoObligationRequestController@view_alob_lines2')->name('gso.obligation-request.view_alob_lines2');
                Route::get('fetch-allotment-via-pr2/{id}', 'GsoObligationRequestController@fetch_allotment_via_pr2')->name('gso.obligation-request.fetch-allotment-via-pr2');
                Route::put('send/{detail}/{id}', 'GsoObligationRequestController@send')->name('gso.obligation-request.send');
                Route::put('remove-line/{id}', 'GsoObligationRequestController@remove_line')->name('gso.obligation-request.remove-line');
                Route::get('alob-lists/{id}', 'GsoObligationRequestController@alob_lists')->name('gso.obligation-request.alob-lists');
                Route::get('alob-lists2/{id}', 'GsoObligationRequestController@alob_lists2')->name('gso.obligation-request.alob-lists2');
                Route::get('fetch-allotment-via-pr/{id}', 'GsoObligationRequestController@fetch_allotment_via_pr')->name('gso.obligation-request.fetch-allotment-via-pr');
                Route::get('reload-division-via-department/{department}', 'GsoObligationRequestController@reload_division')->name('gso.obligation-request.reload-division');
                Route::get('fetch-status/{id}', 'GsoObligationRequestController@fetch_status')->name('gso.obligation-request.fetch-status');
                Route::get('fetch-payee-details/{id}/{column}', 'GsoObligationRequestController@fetch_payee_details')->name('gso.obligation-request.fetch-payee-details');
                Route::get('validate-gl-funds', 'GsoObligationRequestController@validate_gl_funds')->name('gso.obligation-request.validate-gl-funds');
            });
        }
    });

    /* Budget Allocation Routes */
    Route::prefix('setup-data')->group(function () {
        Route::prefix('obligation-types')->group(function () {
            Route::get('', 'CboObligationTypeController@index')->name('cbo.obligation-type.index');
            Route::get('lists', 'CboObligationTypeController@lists')->name('cbo.obligation-type.lists');
            Route::post('store', 'CboObligationTypeController@store')->name('cbo.obligation-type.store');
            Route::get('edit/{id}', 'CboObligationTypeController@find')->name('cbo.obligation-type.find');
            Route::put('update/{id}', 'CboObligationTypeController@update')->name('cbo.obligation-type.update');
            Route::put('remove/{id}', 'CboObligationTypeController@remove')->name('cbo.obligation-type.remove');
            Route::put('restore/{id}', 'CboObligationTypeController@restore')->name('cbo.obligation-type.restore');
        });
    });

    /* PPMP Routes */
    Route::prefix('procurement-plan')->group(function () {
        Route::get('', 'CboPPMPController@index')->name('cbo.ppmp.index');
        Route::get('lists', 'CboPPMPController@lists')->name('cbo.ppmp.lists');
        Route::get('add', 'CboPPMPController@create')->name('cbo.ppmp.create');
        Route::get('find/{id}', 'CboPPMPController@find')->name('cbo.ppmp.find');
        Route::get('edit/{id}', 'CboPPMPController@edit')->name('cbo.ppmp.edit');
        Route::get('fetch-item-details/{id}', 'CboPPMPController@fetch_item_details')->name('cbo.ppmp.fetch-item-details');
        Route::get('fetch-status/{id}', 'CboPPMPController@fetch_status')->name('cbo.ppmp.fetch-status');
        Route::get('fetch-remarks/{id}', 'CboPPMPController@fetch_remarks')->name('cbo.ppmp.fetch-remarks');
        Route::get('fetch-division-status/{id}', 'CboPPMPController@fetch_division_status')->name('cbo.ppmp.fetch-division-status');
        Route::get('get-identity', 'CboPPMPController@get_identity')->name('cbo.ppmp.get-identity');
        Route::put('update/{id}', 'CboPPMPController@update')->name('cbo.ppmp.update');
        Route::get('get-item-field/{id}', 'CboPPMPController@get_item_field')->name('cbo.ppmp.get-item-field');
        Route::put('update-lines/{id}', 'CboPPMPController@update_lines')->name('cbo.ppmp.update');
        Route::post('copy/{id}', 'CboPPMPController@copy')->name('cbo.ppmp.copy');
        Route::post('store', 'CboPPMPController@store')->name('cbo.ppmp.store');
        Route::put('lock-division/{id}', 'CboPPMPController@lock_division')->name('cbo.ppmp.lock-division');
        Route::get('manage/{id}', 'CboPPMPController@manage')->name('cbo.ppmp.manage');
        Route::get('view/{id}', 'CboPPMPController@view')->name('cbo.ppmp.view');
        Route::put('remove-lines/{id}', 'CboPPMPController@remove_lines')->name('cbo.ppmp.remove-lines');
        Route::put('send/{detail}/{id}', 'CboPPMPController@send')->name('cbo.ppmp.send');
        Route::get('validate-division-status/{id}', 'CboPPMPController@validate_division_status')->name('cbo.ppmp.validate-division-status');
        Route::put('unlock/{id}', 'CboPPMPController@unlock')->name('cbo.ppmp.unlock');
        Route::put('lock/{id}', 'CboPPMPController@lock')->name('cbo.ppmp.lock');
        Route::get('fetch-budgets/{id}', 'CboPPMPController@fetch_budgets')->name('cbo.ppmp.fetch-budgets');
        Route::get('year-lists', 'CboPPMPController@year_lists')->name('cbo.ppmp.year-lists');
        Route::get('validate-item-removal/{id}', 'CboPPMPController@validate_item_removal')->name('cbo.ppmp.validate-item-removal');
    });
});
