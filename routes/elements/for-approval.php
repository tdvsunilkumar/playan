<?php 


use Illuminate\Support\Facades\Route;

// ------------------------ For Approval Routes -------------------------------
Route::middleware(['auth'])->prefix('for-approvals')->group(function () {
    /* For Approval Departmental Requisition Routes */
    Route::prefix('departmental-requisition')->group(function () {
        Route::get('', 'ForApprovalsDepartmentalRequisitionController@index')->name('for-approvals.departmental-request.index');
        Route::get('lists', 'ForApprovalsDepartmentalRequisitionController@lists')->name('for-approvals.departmental-request.lists');
        Route::get('validate-approver/{id}/{sequence}', 'ForApprovalsDepartmentalRequisitionController@validate_approver')->name('for-approvals.departmental-request.validate-approver');
        Route::put('approve/{id}', 'ForApprovalsDepartmentalRequisitionController@approve')->name('for-approvals.departmental-request.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsDepartmentalRequisitionController@disapprove')->name('for-approvals.departmental-request.disapprove');   
        Route::get('fetch-remarks/{id}', 'ForApprovalsDepartmentalRequisitionController@fetch_remarks')->name('for-approvals.departmental-request.fetch-remarks');
        Route::get('reload-designation/{id}', 'ForApprovalsDepartmentalRequisitionController@reload_designation')->name('for-approvals.departmental-request.reload-designation');
        Route::get('reload-divisions-employees/{id}', 'ForApprovalsDepartmentalRequisitionController@reload_divisions_employees')->name('for-approvals.departmental-request.reload-divisions-employees');
        Route::get('fetch-status/{id}', 'ForApprovalsDepartmentalRequisitionController@fetch_status')->name('for-approvals.departmental-request.fetch-status');
        Route::get('item-lists/{id}', 'ForApprovalsDepartmentalRequisitionController@item_lists')->name('for-approvals.departmental-request.item-lists');
        Route::get('edit/{id}', 'ForApprovalsDepartmentalRequisitionController@find')->name('for-approvals.departmental-request.find');
    });

    /* For Approval Budget Proposal Routes */
    Route::prefix('budget-proposal')->group(function () {
        Route::get('', 'ForApprovalsBudgetProposalController@index')->name('for-approvals.budget-proposal.index');
        Route::get('lists', 'ForApprovalsBudgetProposalController@lists')->name('for-approvals.budget-proposal.lists');
        Route::get('line-lists/{id}', 'CboBudgetController@line_lists')->name('for-approvals.budget-proposal.line-lists');
        Route::get('validate-approver/{id}', 'ForApprovalsBudgetProposalController@validate_approver')->name('for-approvals.budget-proposal.validate-approver');
        Route::put('approve/{id}', 'ForApprovalsBudgetProposalController@approve')->name('for-approvals.budget-proposal.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsBudgetProposalController@disapprove')->name('for-approvals.budget-proposal.disapprove');   
        Route::get('reload-division-via-department/{department}', 'ForApprovalsBudgetProposalController@reload_division')->name('for-approvals.budget-proposal.reload-division');
        Route::get('fetch-status/{id}', 'ForApprovalsBudgetProposalController@fetch_status')->name('for-approvals.budget-proposal.fetch-status');
        Route::get('fetch-remarks/{id}', 'ForApprovalsBudgetProposalController@fetch_remarks')->name('for-approvals.budget-proposal.fetch-remarks');
        Route::get('edit/{id}', 'ForApprovalsBudgetProposalController@find')->name('for-approvals.budget-proposal.edit');
    });

    /* For Approval Budget Allocation Routes */
    Route::prefix('budget-allocation')->group(function () {
        Route::get('', 'ForApprovalsBudgetAllocationController@index')->name('for-approvals.budget-allocation.index');
        Route::get('lists', 'ForApprovalsBudgetAllocationController@lists')->name('for-approvals.budget-allocation.lists');
        Route::get('reload-divisions-employees/{id}', 'ForApprovalsBudgetAllocationController@reload_divisions_employees')->name('for-approvals.budget-allocation.reload-divisions-employees');
        Route::get('reload-designation/{id}', 'ForApprovalsBudgetAllocationController@reload_designation')->name('for-approvals.budget-allocation.reload-designation');
        Route::get('reload-items/{id}', 'ForApprovalsBudgetAllocationController@reload_items')->name('for-approvals.budget-allocation.reload-items');
        Route::get('validate-approver/{id}/{sequence}', 'ForApprovalsBudgetAllocationController@validate_approver')->name('for-approvals.budget-allocation.validate-approver');
        Route::get('fetch-status/{id}', 'ForApprovalsBudgetAllocationController@fetch_status')->name('for-approvals.budget-allocation.fetch-status');
        Route::get('fetch-remarks/{id}', 'ForApprovalsBudgetAllocationController@fetch_remarks')->name('for-approvals.budget-allocation.fetch-remarks');
        Route::put('approve/{id}', 'ForApprovalsBudgetAllocationController@approve')->name('for-approvals.budget-allocation.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsBudgetAllocationController@disapprove')->name('for-approvals.budget-allocation.disapprove');   
        Route::get('fetch-allotment-via-pr2/{id}', 'ForApprovalsBudgetAllocationController@fetch_allotment_via_pr2')->name('for-approvals.budget-allocation.fetch-allotment-via-pr2');
        Route::get('fetch-allotment-via-pr/{id}', 'ForApprovalsBudgetAllocationController@fetch_allotment_via_pr')->name('for-approvals.budget-allocation.fetch-allotment-via-pr');
        Route::get('alob-lists/{id}', 'ForApprovalsBudgetAllocationController@alob_lists')->name('for-approvals.budget-allocation.alob-lists');
        Route::get('item-lists/{id}', 'ForApprovalsBudgetAllocationController@item_lists')->name('for-approvals.budget-allocation.item-lists');
        Route::get('reload-division-via-department/{department}', 'ForApprovalsBudgetAllocationController@reload_division')->name('for-approvals.budget-allocation.reload-division');
        Route::get('alob-lists2/{id}', 'ForApprovalsBudgetAllocationController@alob_lists2')->name('for-approvals.budget-allocation.alob-lists2');
        Route::get('view/{id}', 'ForApprovalsBudgetAllocationController@find')->name('cbo.budget-allocation.find');
        Route::get('views/{id}', 'ForApprovalsBudgetAllocationController@find_obligation')->name('cbo.budget-allocation.find_obligation');
    });

    /* For Approval Purchase Request Routes */
    Route::prefix('purchase-request')->group(function () {
        Route::get('', 'ForApprovalsPurchaseRequestController@index')->name('for-approvals.purchase-request.index');
        Route::get('lists', 'ForApprovalsPurchaseRequestController@lists')->name('for-approvals.purchase-request.lists');
        Route::get('validate-approver/{id}/{sequence}', 'ForApprovalsPurchaseRequestController@validate_approver')->name('for-approvals.purchase-request.validate-approver');
        Route::put('approve/{id}', 'ForApprovalsPurchaseRequestController@approve')->name('for-approvals.purchase-request.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsPurchaseRequestController@disapprove')->name('for-approvals.purchase-request.disapprove');   
        Route::get('fetch-status/{id}', 'ForApprovalsPurchaseRequestController@fetch_status')->name('for-approvals.purchase-request.fetch_status');
        Route::get('fetch-remarks/{id}', 'ForApprovalsPurchaseRequestController@fetch_remarks')->name('for-approvals.purchase-request.fetch-remarks');
        Route::get('edit/{id}', 'ForApprovalsPurchaseRequestController@find')->name('for-approvals.purchase-request.find');
        Route::get('item-lists/{id}', 'ForApprovalsPurchaseRequestController@item_lists')->name('for-approvals.purchase-request.item-lists');
        Route::get('reload-items/{id}', 'ForApprovalsPurchaseRequestController@reload_items')->name('for-approvals.purchase-request.reload-items');
        Route::get('reload-divisions-employees/{id}', 'ForApprovalsPurchaseRequestController@reload_divisions_employees')->name('for-approvals.purchase-request.reload-divisions-employees');
        Route::get('reload-designation/{id}', 'ForApprovalsPurchaseRequestController@reload_designation')->name('for-approvals.purchase-request.reload-designation');
        Route::get('fetch-allotment-via-pr/{id}', 'ForApprovalsPurchaseRequestController@fetch_allotment_via_pr')->name('for-approvals.purchase-request.fetch-allotment-via-pr');
        Route::get('alob-lists/{id}', 'ForApprovalsPurchaseRequestController@alob_lists')->name('for-approvals.purchase-request.alob-lists');
    });

    /* For Approval BAC RFQ Routes */
    Route::prefix('request-for-quotation')->group(function () {
        Route::get('', 'ForApprovalsRequestForQuotationController@index')->name('for-approvals.request-for-quotation.index');
        Route::get('lists', 'ForApprovalsRequestForQuotationController@lists')->name('for-approvals.request-for-quotation.lists');
        Route::get('edit/{id}', 'ForApprovalsRequestForQuotationController@find')->name('for-approvals.request-for-quotation.find');
        Route::get('pr-lists/{id}', 'ForApprovalsRequestForQuotationController@pr_lists')->name('for-approvals.request-for-quotation.pr-lists');
        Route::get('supplier-lists/{id}', 'ForApprovalsRequestForQuotationController@supplier_lists')->name('for-approvals.request-for-quotation.supplier-lists');
        Route::get('item-lists/{id}', 'ForApprovalsRequestForQuotationController@item_lists')->name('for-approvals.request-for-quotation.item-lists');
        Route::get('fetch-status/{id}', 'ForApprovalsRequestForQuotationController@fetch_status')->name('for-approvals.request-for-quotation.fetch_status');
        Route::put('approve/{id}', 'ForApprovalsRequestForQuotationController@approve')->name('for-approvals.request-for-quotation.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsRequestForQuotationController@disapprove')->name('for-approvals.request-for-quotation.disapprove'); 
        Route::get('validate-approver/{id}', 'ForApprovalsRequestForQuotationController@validate_approver')->name('for-approvals.request-for-quotation.validate-approver');
        Route::get('fetch-remarks/{id}', 'ForApprovalsRequestForQuotationController@fetch_remarks')->name('for-approvals.request-for-quotation.fetch-remarks');
        Route::get('edit-supplier/{id}', 'ForApprovalsRequestForQuotationController@edit_supplier')->name('for-approvals.request-for-quotation.edit-supplier');
    });

    /* For Approval BAC Abstract of Canvass Routes */
    Route::prefix('abstract-of-canvass')->group(function () {
        Route::get('', 'ForApprovalsAbstractOfCanvassController@index')->name('for-approvals.abstract-of-canvass.index');
        Route::get('lists', 'ForApprovalsAbstractOfCanvassController@lists')->name('for-approvals.abstract-of-canvass.lists');
        Route::get('edit/{id}', 'ForApprovalsAbstractOfCanvassController@find')->name('for-approvals.abstract-of-canvass.find');
        Route::get('pr-lists/{id}', 'ForApprovalsAbstractOfCanvassController@pr_lists')->name('for-approvals.abstract-of-canvass.pr-lists');
        Route::get('supplier-lists/{id}', 'ForApprovalsAbstractOfCanvassController@supplier_lists')->name('for-approvals.abstract-of-canvass.supplier-lists');
        Route::get('item-lists/{id}', 'ForApprovalsAbstractOfCanvassController@item_lists')->name('for-approvals.abstract-of-canvass.item-lists');
        Route::get('fetch-status/{id}', 'ForApprovalsAbstractOfCanvassController@fetch_status')->name('for-approvals.abstract-of-canvass.fetch_status');
        Route::put('approve/{id}', 'ForApprovalsAbstractOfCanvassController@approve')->name('for-approvals.abstract-of-canvass.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsAbstractOfCanvassController@disapprove')->name('for-approvals.abstract-of-canvass.disapprove'); 
        Route::get('validate-approver/{id}', 'ForApprovalsAbstractOfCanvassController@validate_approver')->name('for-approvals.abstract-of-canvass.validate-approver');
        Route::get('fetch-remarks/{id}', 'ForApprovalsAbstractOfCanvassController@fetch_remarks')->name('for-approvals.abstract-of-canvass.fetch-remarks');
        Route::get('committee-lists/{id}', 'ForApprovalsAbstractOfCanvassController@committee_lists')->name('for-approvals.abstract-of-canvass.committee-lists');
    });

    /* For Approval BAC Resolution Routes */
    Route::prefix('resolution')->group(function () {
        Route::get('', 'ForApprovalsResolutionController@index')->name('for-approvals.resolution.index');
        Route::get('lists', 'ForApprovalsResolutionController@lists')->name('for-approvals.resolution.lists');
        Route::get('edit/{id}', 'ForApprovalsResolutionController@find')->name('for-approvals.resolution.find');
        Route::get('pr-lists/{id}', 'ForApprovalsResolutionController@pr_lists')->name('for-approvals.resolution.pr-lists');
        Route::get('supplier-lists/{id}', 'ForApprovalsResolutionController@supplier_lists')->name('for-approvals.resolution.supplier-lists');
        Route::get('item-lists/{id}', 'ForApprovalsResolutionController@item_lists')->name('for-approvals.resolution.item-lists');
        Route::get('fetch-status/{id}', 'ForApprovalsResolutionController@fetch_status')->name('for-approvals.resolution.fetch_status');
        Route::put('approve/{id}', 'ForApprovalsResolutionController@approve')->name('for-approvals.resolution.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsResolutionController@disapprove')->name('for-approvals.resolution.disapprove'); 
        Route::get('validate-approver/{id}', 'ForApprovalsResolutionController@validate_approver')->name('for-approvals.resolution.validate-approver');
        Route::get('fetch-remarks/{id}', 'ForApprovalsResolutionController@fetch_remarks')->name('for-approvals.resolution.fetch-remarks');
        Route::get('committee-lists/{id}', 'ForApprovalsResolutionController@committee_lists')->name('for-approvals.resolution.committee-lists');
    });

    /* For Approval Purchase Order Routes */
    Route::prefix('purchase-order')->group(function () {
        Route::get('', 'ForApprovalsPurchaseOrderController@index')->name('for-approvals.purchase-order.index');
        Route::get('lists', 'ForApprovalsPurchaseOrderController@lists')->name('for-approvals.purchase-order.lists');
        Route::get('validate-approver/{id}', 'ForApprovalsPurchaseOrderController@validate_approver')->name('for-approvals.purchase-order.validate-approver');
        Route::put('approve/{id}', 'ForApprovalsPurchaseOrderController@approve')->name('for-approvals.purchase-order.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsPurchaseOrderController@disapprove')->name('for-approvals.purchase-order.disapprove');   
        Route::get('fetch-status/{id}', 'ForApprovalsPurchaseOrderController@fetch_status')->name('for-approvals.purchase-order.fetch_status');
        Route::get('pr-lists/{id}', 'ForApprovalsPurchaseOrderController@pr_lists')->name('for-approvals.purchase-order.pr-lists');
        Route::get('item-lists/{id}', 'ForApprovalsPurchaseOrderController@item_lists')->name('for-approvals.purchase-order.item-lists');
        Route::get('reload-available-control-no/{id}', 'ForApprovalsPurchaseOrderController@reload_available_control_no')->name('for-approvals.purchase-order.reload-available-control-no');
        Route::get('edit/{id}', 'ForApprovalsPurchaseOrderController@find')->name('for-approvals.purchase-order.find');
    });

    /* For Approval Obligation Request Routes */
    Route::prefix('obligation-request')->group(function () {
        Route::get('', 'ForApprovalsObligationRequestController@index')->name('for-approvals.obligation-request.index');
        Route::get('lists', 'ForApprovalsObligationRequestController@lists')->name('for-approvals.obligation-request.lists');
        Route::get('reload-divisions-employees/{id}', 'ForApprovalsObligationRequestController@reload_divisions_employees')->name('for-approvals.obligation-request.reload-divisions-employees');
        Route::get('reload-division-via-department/{department}', 'ForApprovalsObligationRequestController@reload_division')->name('for-approvals.obligation-request.reload-division');
        Route::get('alob-lists2/{id}', 'ForApprovalsObligationRequestController@alob_lists2')->name('for-approvals.obligation-request.alob-lists2');
        Route::get('fetch-allotment-via-pr2/{id}', 'ForApprovalsObligationRequestController@fetch_allotment_via_pr2')->name('for-approvals.obligation-request.fetch-allotment-via-pr2');
        Route::get('fetch-status/{id}', 'ForApprovalsObligationRequestController@fetch_status')->name('for-approvals.obligation-request.fetch_status');
        Route::get('validate-approver/{id}', 'ForApprovalsObligationRequestController@validate_approver')->name('for-approvals.obligation-request.validate-approver');
        Route::put('approve/{id}', 'ForApprovalsObligationRequestController@approve')->name('for-approvals.obligation-request.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsObligationRequestController@disapprove')->name('for-approvals.obligation-request.disapprove');   
        Route::get('fetch-remarks/{id}', 'ForApprovalsObligationRequestController@fetch_remarks')->name('for-approvals.obligation-request.fetch-remarks');
        Route::get('edits/{id}', 'ForApprovalsObligationRequestController@find_obligation')->name('for-approvals.obligation-request.find_obligation');
    });

    /* For Approval Issuance Routes */
    Route::prefix('issuance')->group(function () {
        Route::get('', 'ForApprovalsIssuanceController@index')->name('for-approvals.issuance.index');
        Route::get('lists', 'ForApprovalsIssuanceController@lists')->name('for-approvals.issuance.lists');
        Route::get('item-lists/{id}', 'ForApprovalsIssuanceController@item_lists')->name('for-approvals.issuance.item_lists');
        Route::get('fetch-status/{id}', 'ForApprovalsIssuanceController@fetch_status')->name('for-approvals.issuance.fetch_status');
        Route::get('validate-approver/{id}', 'ForApprovalsIssuanceController@validate_approver')->name('for-approvals.issuance.validate-approver');
        Route::put('approve/{id}', 'ForApprovalsIssuanceController@approve')->name('for-approvals.issuance.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsIssuanceController@disapprove')->name('for-approvals.issuance.disapprove');   
        Route::get('fetch-remarks/{id}', 'ForApprovalsIssuanceController@fetch_remarks')->name('for-approvals.issuance.fetch-remarks');
        Route::get('edit/{id}', 'ForApprovalsIssuanceController@find')->name('for-approvals.issuance.find');
    });

    /* For Approval Issuance Routes */
    Route::prefix('item-adjustment')->group(function () {
        Route::get('', 'ForApprovalsItemAdjustmentController@index')->name('for-approvals.item-adjustment.index');
        Route::get('lists', 'ForApprovalsItemAdjustmentController@lists')->name('for-approvals.item-adjustment.lists');
        Route::get('fetch-status/{id}', 'ForApprovalsItemAdjustmentController@fetch_status')->name('for-approvals.item-adjustment.fetch_status');
        Route::get('validate-approver/{id}', 'ForApprovalsItemAdjustmentController@validate_approver')->name('for-approvals.item-adjustment.validate-approver');
        Route::put('approve/{id}', 'ForApprovalsItemAdjustmentController@approve')->name('for-approvals.item-adjustment.approve');   
        Route::post('disapprove/{id}', 'ForApprovalsItemAdjustmentController@disapprove')->name('for-approvals.item-adjustment.disapprove');   
        Route::get('fetch-remarks/{id}', 'ForApprovalsItemAdjustmentController@fetch_remarks')->name('for-approvals.item-adjustment.fetch-remarks');
        Route::get('edit/{id}', 'ForApprovalsItemAdjustmentController@find')->name('for-approvals.item-adjustment.find');
    });

    /* For Approval Payables Routes */
    Route::prefix('account-payables')->group(function () {
        Route::get('', 'ForApprovalsAccountPayableController@index')->name('for-approvals.account-payable.index');
        Route::get('lists', 'ForApprovalsAccountPayableController@lists')->name('for-approvals.account-payable.lists');
        Route::get('fetch-status/{id}', 'ForApprovalsAccountPayableController@fetch_status')->name('for-approvals.account-payable.fetch-status');
        Route::get('validate-approver/{id}', 'ForApprovalsAccountPayableController@validate_approver')->name('for-approvals.account-payable.validate-approver');
        Route::put('approve-all', 'ForApprovalsAccountPayableController@approve_all')->name('for-approvals.account-payable.approve-all');
        Route::put('disapprove-all', 'ForApprovalsAccountPayableController@disapprove_all')->name('for-approvals.account-payable.approve-all');
        Route::put('approve/{id}', 'ForApprovalsAccountPayableController@approve')->name('for-approvals.account-payable.approve');
        Route::put('disapprove/{id}', 'ForApprovalsAccountPayableController@disapprove')->name('for-approvals.account-payable.disapprove');
        Route::get('view/{id}', 'ForApprovalsAccountPayableController@find')->name('for-approvals.account-payable.view');
        Route::get('fetch-remarks/{id}', 'ForApprovalsAccountPayableController@fetch_remarks')->name('for-approvals.account-payable.fetch-remarks');
    });

    /* For Approval Disbursement Routes */
    Route::prefix('disbursements')->group(function () {
        Route::get('', 'ForApprovalsAccountDisbursementController@index')->name('for-approvals.disbursement.index');
        Route::get('lists', 'ForApprovalsAccountDisbursementController@lists')->name('for-approvals.disbursement.lists');
        Route::get('fetch-status/{id}', 'ForApprovalsAccountDisbursementController@fetch_status')->name('for-approvals.disbursement.fetch-status');
        Route::get('validate-approver/{id}', 'ForApprovalsAccountDisbursementController@validate_approver')->name('for-approvals.disbursement.validate-approver');
        Route::put('approve-all', 'ForApprovalsAccountDisbursementController@approve_all')->name('for-approvals.disbursement.approve-all');
        Route::put('disapprove-all', 'ForApprovalsAccountDisbursementController@disapprove_all')->name('for-approvals.disbursement.approve-all');
        Route::put('approve/{id}', 'ForApprovalsAccountDisbursementController@approve')->name('for-approvals.disbursement.approve');
        Route::put('disapprove/{id}', 'ForApprovalsAccountDisbursementController@disapprove')->name('for-approvals.disbursement.disapprove');
        Route::get('view/{id}', 'ForApprovalsAccountDisbursementController@find')->name('for-approvals.disbursement.view');
        Route::get('fetch-remarks/{id}', 'ForApprovalsAccountDisbursementController@fetch_remarks')->name('for-approvals.disbursement.fetch-remarks');
    });

    /* For Approval PPMP Routes */
    Route::prefix('ppmp')->group(function () {
        Route::get('', 'ForApprovalsPPMPController@index')->name('for-approvals.ppmp.index');
        Route::get('lists', 'ForApprovalsPPMPController@lists')->name('for-approvals.ppmp.lists');
        Route::get('validate-approver/{id}/{sequence}', 'ForApprovalsPPMPController@validate_approver')->name('for-approvals.ppmp.validate-approver');
        Route::put('approve/{id}', 'ForApprovalsPPMPController@approve')->name('for-approvals.ppmp.approve');
        Route::put('disapprove/{id}', 'ForApprovalsPPMPController@disapprove')->name('for-approvals.ppmp.disapprove');
        Route::get('fetch-status/{id}', 'ForApprovalsPPMPController@fetch_status')->name('for-approvals.disbursement.fetch-status');
        Route::get('fetch-remarks/{id}', 'ForApprovalsPPMPController@fetch_remarks')->name('for-approvals.ppmp.fetch-remarks');
    });

    /* For Approval Petty Cash Routes */
    Route::prefix('petty-cash')->group(function () {
        /* For Approval Disbursement Routes */
        Route::prefix('disbursement')->group(function () {
            Route::get('', 'ForApprovalsPettyCashDisbursementController@index')->name('for-approvals.petty-cash.index');
            Route::get('lists', 'ForApprovalsPettyCashDisbursementController@lists')->name('for-approvals.petty-cash.lists');
            Route::get('validate-approver/{id}/{sequence}', 'ForApprovalsPettyCashDisbursementController@validate_approver')->name('for-approvals.petty-cash.validate-approver');
            Route::put('approve/{id}', 'ForApprovalsPettyCashDisbursementController@approve')->name('for-approvals.petty-cash.approve');
            Route::put('disapprove/{id}', 'ForApprovalsPettyCashDisbursementController@disapprove')->name('for-approvals.petty-cash.disapprove');
            Route::get('fetch-status/{id}', 'ForApprovalsPettyCashDisbursementController@fetch_status')->name('for-approvals.petty-cash.fetch-status');
            Route::get('fetch-remarks/{id}', 'ForApprovalsPettyCashDisbursementController@fetch_remarks')->name('for-approvals.petty-cash.fetch-remarks');
        });
        /* For Approval Replenishment Routes */
        Route::prefix('replenishment')->group(function () {
            Route::get('', 'ForApprovalsPettyCashReplenishmentController@index')->name('for-approvals.petty-cash-replenishment.index');
            Route::get('lists', 'ForApprovalsPettyCashReplenishmentController@lists')->name('for-approvals.petty-cash-replenishment.lists');
            Route::get('validate-approver/{id}/{sequence}', 'ForApprovalsPettyCashReplenishmentController@validate_approver')->name('for-approvals.petty-cash-replenishment.validate-approver');
            Route::put('approve/{id}', 'ForApprovalsPettyCashReplenishmentController@approve')->name('for-approvals.petty-cash-replenishment.approve');
            Route::put('disapprove/{id}', 'ForApprovalsPettyCashReplenishmentController@disapprove')->name('for-approvals.petty-cash-replenishment.disapprove');
            Route::get('fetch-status/{id}', 'ForApprovalsPettyCashReplenishmentController@fetch_status')->name('for-approvals.petty-cash-replenishment.fetch-status');
            Route::get('fetch-remarks/{id}', 'ForApprovalsPettyCashReplenishmentController@fetch_remarks')->name('for-approvals.petty-cash-replenishment.fetch-remarks');
        });
    });

    /* For Approval Petty Cash Routes */
    Route::prefix('repairs-and-inspections')->group(function () {
        /* Requests Routes */
        Route::prefix('requests')->group(function () {
            Route::get('', 'ForApprovalsRepairsRequestController@index')->name('for-approvals.repairs-manage.index');
            Route::get('lists', 'ForApprovalsRepairsRequestController@lists')->name('for-approvals.repairs-manage.lists');
            Route::get('view/{id}', 'ForApprovalsRepairsRequestController@view')->name('for-approvals.repairs-manage.view');
            Route::get('validate-approver/{id}/{sequence}', 'ForApprovalsRepairsRequestController@validate_approver')->name('for-approvals.repairs-manage.validate-approver');
            Route::put('approve/{id}', 'ForApprovalsRepairsRequestController@approve')->name('for-approvals.repairs-manage.approve');
            Route::put('disapprove/{id}', 'ForApprovalsRepairsRequestController@disapprove')->name('for-approvals.repairs-manage.disapprove');
            Route::get('fetch-status/{id}', 'ForApprovalsRepairsRequestController@fetch_status')->name('for-approvals.repairs-manage.fetch-status');
            Route::get('fetch-remarks/{id}', 'ForApprovalsRepairsRequestController@fetch_remarks')->name('for-approvals.repairs-manage.fetch-remarks');
            Route::get('preload-fixed-asset/{fixedAsset}', 'ForApprovalsRepairsRequestController@preload_fixed_asset')->name('for-approvals.repairs-manage.preload-fixed-asset');
            Route::get('history-lists/{id}', 'ForApprovalsRepairsRequestController@history_lists')->name('for-approvals.repairs-manage.history-lists');
        });
        /* Inspections Routes */
        Route::prefix('inspections')->group(function () {
            Route::get('', 'ForApprovalsRepairsInspectionController@index')->name('for-approvals.repairs-inspection.index');
            Route::get('lists', 'ForApprovalsRepairsInspectionController@lists')->name('for-approvals.repairs-inspection.lists');
            Route::get('view/{id}', 'ForApprovalsRepairsInspectionController@view')->name('for-approvals.repairs-inspection.view');
            Route::get('validate-approver/{id}/{sequence}', 'ForApprovalsRepairsInspectionController@validate_approver')->name('for-approvals.repairs-inspection.validate-approver');
            Route::put('approve/{id}', 'ForApprovalsRepairsInspectionController@approve')->name('for-approvals.repairs-inspection.approve');
            Route::put('disapprove/{id}', 'ForApprovalsRepairsInspectionController@disapprove')->name('for-approvals.repairs-inspection.disapprove');
            Route::get('fetch-status/{id}', 'ForApprovalsRepairsInspectionController@fetch_status')->name('for-approvals.repairs-inspection.fetch-status');
            Route::get('fetch-remarks/{id}', 'ForApprovalsRepairsInspectionController@fetch_remarks')->name('for-approvals.repairs-inspection.fetch-remarks');
            Route::get('preload-fixed-asset/{fixedAsset}', 'ForApprovalsRepairsInspectionController@preload_fixed_asset')->name('for-approvals.repairs-inspection.preload-fixed-asset');
            Route::get('history-lists/{id}', 'ForApprovalsRepairsInspectionController@history_lists')->name('for-approvals.repairs-inspection.history-lists');
            Route::get('item-lists/{id}', 'ForApprovalsRepairsInspectionController@item_lists')->name('for-approvals.repairs-inspection.item-lists');
            Route::get('edit-item/{id}', 'ForApprovalsRepairsInspectionController@find_item')->name('for-approvals.repairs-inspection.find-item');
        });
    });

    Route::prefix('collections')->group(function () {
        Route::get('', 'ForApprovalsTreasuryCollectionController@index')->name('for-approvals.treasury.collection.index');
        Route::get('lists', 'ForApprovalsTreasuryCollectionController@lists')->name('for-approvals.treasury.collection.lists');
        Route::get('validate-approver/{id}/{sequence}', 'ForApprovalsTreasuryCollectionController@validate_approver')->name('for-approvals.treasury.collection.validate-approver');
        Route::put('approve/{id}', 'ForApprovalsTreasuryCollectionController@approve')->name('for-approvals.treasury.collection.approve');
        Route::put('disapprove/{id}', 'ForApprovalsTreasuryCollectionController@disapprove')->name('for-approvals.treasury.collection.disapprove');
        Route::get('fetch-status/{id}', 'ForApprovalsTreasuryCollectionController@fetch_status')->name('for-approvals.treasury.collection.fetch-status');
        Route::get('fetch-remarks/{id}', 'ForApprovalsTreasuryCollectionController@fetch_remarks')->name('for-approvals.treasury.collection.fetch-remarks');
        Route::get('view/{id}', 'ForApprovalsTreasuryCollectionController@view')->name('for-approvals.treasury.collection.view');
        Route::get('transaction-lists/{id}', 'ForApprovalsTreasuryCollectionController@transaction_lists')->name('for-approvals.treasury.collection.transaction-lists');
        Route::get('receipt-lists/{id}', 'ForApprovalsTreasuryCollectionController@receipt_lists')->name('for-approvals.treasury.collection.receipt-lists');
        Route::get('get-denominations/{id}', 'ForApprovalsTreasuryCollectionController@get_denominations')->name('for-approvals.treasury.collection.get-denominations');
    });

    /* For Approval Economic and Investment Cemetery Routes */
    Route::prefix('economic-and-investment')->group(function () {
        /* Cemetery Routes */
        Route::prefix('cemetery-application')->group(function () {
            Route::get('', 'ForApprovalsCemeteryApplicationController@index')->name('for-approvals.cemetery.index');
            Route::get('lists', 'ForApprovalsCemeteryApplicationController@lists')->name('for-approvals.cemetery.lists');
            Route::get('validate-approver/{id}', 'ForApprovalsCemeteryApplicationController@validate_approver')->name('for-approvals.cemetery.validate-approver');
            Route::put('approve/{id}', 'ForApprovalsCemeteryApplicationController@approve')->name('for-approvals.cemetery.approve');   
            Route::post('disapprove/{id}', 'ForApprovalsCemeteryApplicationController@disapprove')->name('for-approvals.cemetery.disapprove');   
            Route::get('fetch-status/{id}', 'ForApprovalsCemeteryApplicationController@fetch_status')->name('for-approvals.cemetery.fetch-status');
            Route::get('fetch-remarks/{id}', 'ForApprovalsCemeteryApplicationController@fetch_remarks')->name('for-approvals.cemetery.fetch-remarks');
            Route::get('view/{id}', 'ForApprovalsCemeteryApplicationController@find')->name('for-approvals.cemetery.edit');
            Route::get('reload-cemetery-lot/{id}', 'ForApprovalsCemeteryApplicationController@reload_cemetery_lot')->name('cemetery.reload-cemetery-lot');
            Route::get('reload-cemetery-name', 'ForApprovalsCemeteryApplicationController@reload_cemetery_name')->name('cemetery.reload-cemetery-name');
        });

        /* Rental Routes */
        Route::prefix('rental-application')->group(function () {
            Route::get('', 'ForApprovalsRentalApplicationController@index')->name('for-approvals.rental.index');
            Route::get('lists', 'ForApprovalsRentalApplicationController@lists')->name('for-approvals.rental.lists');
            Route::get('validate-approver/{id}', 'ForApprovalsRentalApplicationController@validate_approver')->name('for-approvals.rental.validate-approver');
            Route::put('approve/{id}', 'ForApprovalsRentalApplicationController@approve')->name('for-approvals.rental.approve');   
            Route::post('disapprove/{id}', 'ForApprovalsRentalApplicationController@disapprove')->name('for-approvals.rental.disapprove');   
            Route::get('fetch-status/{id}', 'ForApprovalsRentalApplicationController@fetch_status')->name('for-approvals.rental.fetch-status');
            Route::get('fetch-remarks/{id}', 'ForApprovalsRentalApplicationController@fetch_remarks')->name('for-approvals.rental.fetch-remarks');
            Route::get('view/{id}', 'ForApprovalsRentalApplicationController@find')->name('for-approvals.rental.edit');
            Route::get('reload-reception-name', 'ForApprovalsRentalApplicationController@reload_reception_name')->name('for-approvals.rental.reload-reception-name');
            Route::get('reload-reception-class/{id}', 'ForApprovalsRentalApplicationController@reload_reception_class')->name('for-approvals.rental.reload-reception-class');
            Route::get('fetch-multiplier-amount', 'ForApprovalsRentalApplicationController@fetch_multiplier_amount')->name('for-approvals.rental.fetch-multiplier-amount');
        });
    });

    /* For Approval Journal Entries Routes */
    Route::prefix('journal-entries')->group(function () {
        /* For Approval JEV Payables */
        Route::prefix('payables')->group(function () {
            Route::get('', 'ForApprovalsPayablesJEVController@index')->name('for-approvals.jev.payables.index');
            Route::get('lists', 'ForApprovalsPayablesJEVController@lists')->name('for-approvals.jev.payables.lists');
            Route::get('validate-approver/{id}', 'ForApprovalsPayablesJEVController@validate_approver')->name('for-approvals.jev.payables.validate-approver');
            Route::put('approve/{id}', 'ForApprovalsPayablesJEVController@approve')->name('for-approvals.jev.payables.approve');
            Route::put('disapprove/{id}', 'ForApprovalsPayablesJEVController@disapprove')->name('for-approvals.jev.payables.disapprove');
            Route::get('fetch-status/{id}', 'ForApprovalsPayablesJEVController@fetch_status')->name('for-approvals.jev.payables.fetch-status');
            Route::get('fetch-remarks/{id}', 'ForApprovalsPayablesJEVController@fetch_remarks')->name('for-approvals.jev.payables.fetch-remarks');
            Route::get('print/{voucher}', 'ForApprovalsPayablesJEVController@print')->name('for-approvals.jev.payables.print');
        });
        /* For Approval JEV Incomes */
        Route::prefix('incomes')->group(function () {
            Route::get('', 'ForApprovalsIncomesJEVController@index')->name('for-approvals.jev.incomes.index');
            Route::get('lists', 'ForApprovalsIncomesJEVController@lists')->name('for-approvals.jev.incomes.lists');
            Route::get('validate-approver/{id}', 'ForApprovalsIncomesJEVController@validate_approver')->name('for-approvals.jev.incomes.validate-approver');
            Route::put('approve/{id}', 'ForApprovalsIncomesJEVController@approve')->name('for-approvals.jev.incomes.approve');
            Route::put('disapprove/{id}', 'ForApprovalsIncomesJEVController@disapprove')->name('for-approvals.jev.incomes.disapprove');
            Route::get('fetch-status/{id}', 'ForApprovalsIncomesJEVController@fetch_status')->name('for-approvals.jev.incomes.fetch-status');
            Route::get('fetch-remarks/{id}', 'ForApprovalsIncomesJEVController@fetch_remarks')->name('for-approvals.jev.incomes.fetch-remarks');
            Route::get('print/{voucher}', 'ForApprovalsIncomesJEVController@print')->name('for-approvals.jev.incomes.print');
        });
    });
});
