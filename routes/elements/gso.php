<?php 


use Illuminate\Support\Facades\Route;

// ------------------------- General Services Routes -----------------------------
Route::middleware(['auth'])->prefix('general-services')->group(function () {
    /* Departmental Requisition Routes */
    Route::prefix('departmental-requisitions')->group(function () {
        Route::get('', 'GsoDepartmentalRequisitionController@index')->name('gso.departmental-requisition.index');
        Route::get('lists', 'GsoDepartmentalRequisitionController@lists')->name('gso.departmental-requisition.lists');
        Route::get('item-lists/{id}', 'GsoDepartmentalRequisitionController@item_lists')->name('gso.departmental-requisition.item-lists');
        Route::get('reload-itemx/{fund}/{department}/{division}/{request_date}/{category}', 'GsoDepartmentalRequisitionController@reload_itemx')->name('gso.departmental-requisition.reload-itemx');
        Route::get('reload-items/{id}', 'GsoDepartmentalRequisitionController@reload_items')->name('gso.departmental-requisition.reload-items');
        Route::get('reload-uom/{id}', 'GsoDepartmentalRequisitionController@reload_uom')->name('gso.departmental-requisition.reload-uom');
        Route::get('reload-unit-cost/{id}', 'GsoDepartmentalRequisitionController@reload_unit_cost')->name('gso.departmental-requisition.reload-unit-cost');
        Route::get('reload-divisions-employees/{id}', 'GsoDepartmentalRequisitionController@reload_divisions_employees')->name('gso.departmental-requisition.reload-divisions-employees');
        Route::get('reload-designation/{id}', 'GsoDepartmentalRequisitionController@reload_designation')->name('gso.departmental-requisition.reload-designation');
        Route::get('edit/{id}', 'GsoDepartmentalRequisitionController@find')->name('gso.departmental-requisition.find');
        Route::post('store', 'GsoDepartmentalRequisitionController@store')->name('gso.departmental-requisition.store');
        Route::put('update/{id}', 'GsoDepartmentalRequisitionController@update')->name('gso.departmental-requisition.update');
        Route::get('edit-item/{id}', 'GsoDepartmentalRequisitionController@find_item')->name('gso.departmental-requisition.find-item');
        Route::put('update-item/{id}', 'GsoDepartmentalRequisitionController@update_item')->name('gso.departmental-requisition.update-item');
        Route::put('update-line/{id}', 'GsoDepartmentalRequisitionController@updateLine')->name('gso.departmental-requisition.update-line');
        Route::delete('remove-line/{id}', 'GsoDepartmentalRequisitionController@removeLine')->name('gso.departmental-requisition.remove-line');
        Route::get('edit-line/{id}', 'GsoDepartmentalRequisitionController@findLine')->name('gso.departmental-requisition.find-line');
        Route::get('fetch-status/{id}', 'GsoDepartmentalRequisitionController@fetch_status')->name('gso.departmental-requisition.fetch-status');
        Route::put('send/{detail}/{id}', 'GsoDepartmentalRequisitionController@send')->name('gso.departmental-requisition.send');
        Route::get('print/{control_no}', 'GsoObligationRequestController@print')->name('gso.obligation-request.print');
        Route::get('fetch-remarks/{id}', 'GsoDepartmentalRequisitionController@fetch_remarks')->name('gso.departmental-requisition.fetch-remarks');
        Route::get('alob-lists/{id}', 'GsoDepartmentalRequisitionController@alob_lists')->name('gso.departmental-requisition.alob-lists');
        Route::get('fetch-allotment-via-pr/{id}', 'GsoDepartmentalRequisitionController@fetch_allotment_via_pr')->name('gso.departmental-requisition.fetch-allotment-via-pr');
        Route::get('validate-item-request', 'GsoDepartmentalRequisitionController@validate_item_request')->name('gso.departmental-requisition.validate-item-request');
        Route::get('track-request/{id}', 'GsoDepartmentalRequisitionController@track_request')->name('gso.departmental-requisition.track-request');
    });

    /* Inventory Routes */
    Route::prefix('inventory')->group(function () {
        Route::get('', 'GsoInventoryController@index')->name('gso.inventory.index');
        Route::get('lists', 'GsoInventoryController@lists')->name('gso.inventory.lists');
        Route::get('edit/{id}', 'GsoInventoryController@find')->name('gso.inventory.find');
        Route::get('history-lists/{id}', 'GsoInventoryController@history_lists')->name('gso.inventory.history-lists');
        Route::post('send/{id}', 'GsoInventoryController@send')->name('gso.inventory.send');   
    });

    /* Issuance Routes */
    Route::prefix('issuance')->group(function () {
        Route::get('', 'GsoIssuanceController@index')->name('gso.issuance.index');
        Route::get('lists', 'GsoIssuanceController@lists')->name('gso.issuance.lists');
        Route::get('item-lists/{id}', 'GsoIssuanceController@item_lists')->name('gso.issuance.item_lists');
        Route::get('edit/{id}', 'GsoIssuanceController@find')->name('gso.issuance.find');
        Route::put('update/{id}', 'GsoIssuanceController@update')->name('gso.issuance.update');
        Route::get('fetch-status/{id}', 'GsoIssuanceController@fetch_status')->name('gso.issuance.fetch_status');
        Route::get('view-available-items/{id}', 'GsoIssuanceController@view_available_items')->name('gso.issuance.view-available-items');
        Route::post('post/{id}', 'GsoIssuanceController@post')->name('gso.issuance.post');
        Route::put('send/{detail}/{id}', 'GsoIssuanceController@send')->name('gso.issuance.send');
        Route::put('remove-line/{id}', 'GsoIssuanceController@remove_line')->name('gso.issuance.remove-line');
        Route::get('print/{control_no}', 'GsoIssuanceController@print')->name('gso.issuance.print');
        Route::get('validate-issuance/{id}', 'GsoIssuanceController@validate_issuance')->name('gso.issuance.validate');
    });

    /* Purchase Request Routes */
    Route::prefix('purchase-requests')->group(function () {
        Route::get('', 'GsoPurchaseRequestController@index')->name('gso.purchase-request.index');
        Route::get('lists', 'GsoPurchaseRequestController@lists')->name('gso.purchase-request.lists');
        Route::get('item-lists/{id}', 'GsoPurchaseRequestController@item_lists')->name('gso.purchase-request.item-lists');
        Route::get('item-lists2/{id}', 'GsoPurchaseRequestController@item_lists2')->name('gso.purchase-request.item-lists2');
        Route::get('edit/{id}', 'GsoPurchaseRequestController@find')->name('gso.purchase-request.find');
        Route::get('edits/{id}', 'GsoPurchaseRequestController@find_obligation')->name('gso.purchase-request.finds');
        Route::post('generate/{id}', 'GsoPurchaseRequestController@generate')->name('gso.purchase-request.generate');
        Route::put('update-via-pr/{id}', 'GsoPurchaseRequestController@update_via_pr')->name('gso.purchase-request.update');
        Route::put('update-pr-via-alob/{id}', 'GsoPurchaseRequestController@update_pr_via_alob')->name('gso.purchase-request.update-pr-via-alob');        
        Route::put('send/{detail}/{id}', 'GsoPurchaseRequestController@send')->name('gso.purchase-request.send');
        Route::get('view-item-lines/{id}', 'GsoPurchaseRequestController@view_item_lines')->name('gso.purchase-request.view-item-lines');
        Route::put('update-item-line/{id}', 'GsoPurchaseRequestController@update_item_line')->name('gso.purchase-request.update-item-line');
        Route::put('approve/{id}', 'GsoPurchaseRequestController@approve')->name('gso.purchase-request.approve');
        Route::put('disapprove/{id}', 'GsoPurchaseRequestController@disapprove')->name('gso.purchase-request.disapprove');
        Route::get('print/{prNo}', 'GsoPurchaseRequestController@print')->name('gso.purchase-request.print');
        Route::get('validate-pr/{id}', 'GsoPurchaseRequestController@validate_pr')->name('gso.purchase-request.validate');
        Route::get('alob-lists/{id}', 'GsoPurchaseRequestController@alob_lists')->name('gso.purchase-request.alob-lists');
        Route::get('fetch-allotment-via-pr/{id}', 'GsoPurchaseRequestController@fetch_allotment_via_pr')->name('gso.purchase-request.fetch-allotment-via-pr');
        Route::get('reload-items/{id}', 'GsoPurchaseRequestController@reload_items')->name('gso.purchase-request.reload-items');
        Route::get('reload-uom/{id}', 'GsoPurchaseRequestController@reload_uom')->name('gso.purchase-request.reload-uom');
        Route::get('reload-divisions-employees/{id}', 'GsoPurchaseRequestController@reload_divisions_employees')->name('gso.purchase-request.reload-divisions-employees');
        Route::get('reload-designation/{id}', 'GsoPurchaseRequestController@reload_designation')->name('gso.purchase-request.reload-designation');
        Route::get('fetch-status/{id}', 'GsoPurchaseRequestController@fetch_status')->name('gso.purchase-request.fetch-status');
        Route::get('fetch-pr-status-via-alob/{id}', 'GsoPurchaseRequestController@fetch_pr_status_via_alob')->name('gso.purchase-request.fetch-pr-status-via-alob');
        Route::post('add-pr-line/{id}', 'GsoPurchaseRequestController@add_pr_line')->name('gso.purchase-request.add-pr-line');
        Route::put('modify-pr-line/{id}', 'GsoPurchaseRequestController@modify_pr_line')->name('gso.purchase-request.modify-pr-line');
        Route::get('edit-pr-line/{id}', 'GsoPurchaseRequestController@find_pr_line')->name('gso.purchase-request.find-pr-line');
        Route::put('remove-pr-line/{id}', 'GsoPurchaseRequestController@remove_pr_line')->name('gso.purchase-request.remove-pr-line');
        Route::get('fetch-pr-line-status/{id}', 'GsoPurchaseRequestController@fetch_pr_line_status')->name('gso.purchase-request.fetch-pr-line-status');
        Route::get('fetch-pr-amount/{id}', 'GsoPurchaseRequestController@fetch_pr_amount')->name('gso.purchase-request.fetch-pr-amount');
    });

    /* BAC Routes */
    Route::prefix('bac')->group(function () {
        /* RFQ Routes */
        Route::prefix('request-for-quotations')->group(function () {
            Route::get('', 'BacRequestForQuotationController@index')->name('bac.rfq.index');
            Route::get('edit/{id}', 'BacRequestForQuotationController@find')->name('bac.rfq.edit');
            Route::get('lists', 'BacRequestForQuotationController@lists')->name('bac.rfq.lists');
            Route::get('pr-lists/{id}', 'BacRequestForQuotationController@pr_lists')->name('bac.rfq.pr-lists');
            Route::get('supplier-lists/{id}', 'BacRequestForQuotationController@supplier_lists')->name('bac.rfq.supplier-lists');
            Route::get('item-lists/{id}', 'BacRequestForQuotationController@item_lists')->name('bac.rfq.item-lists');
            Route::get('fetch-status/{id}', 'BacRequestForQuotationController@fetch_status')->name('bac.rfq.fetch_status');
            Route::get('view-available-suppliers/{id}', 'BacRequestForQuotationController@view_available_suppliers')->name('bac.rfq.view-available-suppliers');
            Route::post('add-suppliers/{id}', 'BacRequestForQuotationController@add_suppliers')->name('bac.rfq.add-suppliers');
            Route::get('view-available-purchase-requests/{id}', 'BacRequestForQuotationController@view_available_purchase_requests')->name('bac.rfq.view-available-purchase-requests');
            Route::post('add-purchase-request/{id}', 'BacRequestForQuotationController@add_purchase_request')->name('bac.rfq.add-purchase-request');
            Route::put('update/{id}', 'BacRequestForQuotationController@update')->name('bac.rfq.update');
            Route::put('remove-pr/{id}', 'BacRequestForQuotationController@remove_pr')->name('bac.rfq.remove_pr');
            Route::put('remove-supplier/{id}', 'BacRequestForQuotationController@remove_supplier')->name('bac.rfq.remove_supplier');
            Route::get('edit-supplier/{id}', 'BacRequestForQuotationController@edit_supplier')->name('bac.rfq.edit-supplier');
            Route::put('update-row/{id}', 'BacRequestForQuotationController@update_row')->name('bac.rfq.update-row');
            Route::get('validate-supplier/{id}', 'BacRequestForQuotationController@validate_supplier')->name('bac.rfq.validate-supplier');
            Route::put('update-canvass/{id}/{supplier}', 'BacRequestForQuotationController@update_canvass')->name('bac.rfq.update-canvass');
            Route::put('submit-canvass/{id}/{supplier}', 'BacRequestForQuotationController@submit_canvass')->name('bac.rfq.submit-canvass');
            Route::put('save-canvass/{id}/{supplier}', 'BacRequestForQuotationController@save_canvass')->name('bac.rfq.save-canvass');
            Route::put('send/{detail}/{id}', 'BacRequestForQuotationController@send')->name('bac.rfq.send');
            Route::get('print/{control_no}', 'GsoPrintController@print_rfq')->name('bac.rfq.print');
            Route::get('preview/{control_no}', 'GsoPrintController@preview_rfq')->name('bac.rfq.preview');
        });

        Route::prefix('abstract-of-canvass')->group(function () {
            Route::get('', 'BacAbstractOfCanvassController@index')->name('bac.abstract.index');
            Route::get('edit/{id}', 'BacAbstractOfCanvassController@find')->name('bac.abstract.edit');
            Route::put('update/{id}', 'BacAbstractOfCanvassController@update')->name('bac.abstract.update');
            Route::get('lists', 'BacAbstractOfCanvassController@lists')->name('bac.abstract.lists');
            Route::get('pr-lists/{id}', 'BacAbstractOfCanvassController@pr_lists')->name('bac.abstract.pr-lists');
            Route::get('supplier-lists/{id}', 'BacAbstractOfCanvassController@supplier_lists')->name('bac.abstract.supplier-lists');
            Route::get('committee-lists/{id}', 'BacAbstractOfCanvassController@committee_lists')->name('bac.abstract.committee-lists');
            Route::put('send/{detail}/{id}', 'BacAbstractOfCanvassController@send')->name('bac.abstract.send');
            Route::get('fetch-status/{id}', 'BacAbstractOfCanvassController@fetch_status')->name('bac.abstract.fetch_status');
            Route::put('update-canvass/{id}/{supplier}', 'BacAbstractOfCanvassController@update_canvass')->name('bac.abstract.update-canvass');
            Route::put('submit-canvass/{id}/{supplier}', 'BacAbstractOfCanvassController@submit_canvass')->name('bac.abstract.submit-canvass');           
            Route::get('view-available-committees/{id}', 'BacAbstractOfCanvassController@view_available_committees')->name('bac.abstract.view-available-committees');
            Route::post('add-committees/{id}', 'BacAbstractOfCanvassController@add_committees')->name('bac.abstract.add-committees');
            Route::put('remove-committee/{rfq}/{id}', 'BacAbstractOfCanvassController@remove_committee')->name('bac.abstract.remove_committee');
            Route::put('award/{id}/{supplier}', 'BacAbstractOfCanvassController@award')->name('bac.abstract.award');
            Route::get('print/{control_no}', 'GsoPrintController@print_abstract')->name('bac.abstract-of-canvass.print');
        });

        Route::prefix('resolution')->group(function () {
            Route::get('', 'BacResolutionController@index')->name('bac.resolution.index');
            Route::get('edit/{id}', 'BacResolutionController@find')->name('bac.resolution.edit');
            Route::get('lists', 'BacResolutionController@lists')->name('bac.resolution.lists');
            Route::get('committee-lists/{id}', 'BacResolutionController@committee_lists')->name('bac.resolution.committee-lists');
            Route::get('fetch-status/{id}', 'BacResolutionController@fetch_status')->name('bac.resolution.fetch_status');
            Route::get('view-available-committees/{id}', 'BacResolutionController@view_available_committees')->name('bac.resolution.view-available-committees');
            Route::post('add-committees/{id}', 'BacResolutionController@add_committees')->name('bac.resolution.add-committees');
            Route::put('remove-committee/{rfq}/{id}', 'BacResolutionController@remove_committee')->name('bac.resolution.remove_committee');
            Route::put('award/{id}/{supplier}', 'BacResolutionController@award')->name('bac.resolution.award');  
            Route::put('send/{detail}/{id}', 'BacResolutionController@send')->name('bac.resolution.send');         
            Route::get('print/{control_no}', 'GsoPrintController@print_resolution')->name('bac.resolution.print');         
        });
    });

    /* Purchase Request Routes */
    Route::prefix('purchase-orders')->group(function () {
        Route::get('', 'GsoPurchaseOrderController@index')->name('gso.purchase-order.index');
        Route::get('lists', 'GsoPurchaseOrderController@lists')->name('gso.purchase-order.lists');
        Route::get('pr-lists/{id}', 'GsoPurchaseOrderController@pr_lists')->name('gso.purchase-order.pr-lists');
        Route::get('item-lists/{id}', 'GsoPurchaseOrderController@item_lists')->name('gso.purchase-order.item-lists');
        Route::get('reload-available-control-no/{id}', 'GsoPurchaseOrderController@reload_available_control_no')->name('gso.purchase-order.reload-available-control-no');
        Route::get('fetch-status/{id}', 'GsoPurchaseOrderController@fetch_status')->name('gso.purchase-order.fetch_status');
        Route::put('update/{id}', 'GsoPurchaseOrderController@update')->name('gso.purchase-order.update');
        Route::get('edit/{id}', 'GsoPurchaseOrderController@find')->name('gso.purchase-order.find');
        Route::put('send/{detail}/{id}', 'GsoPurchaseOrderController@send')->name('gso.purchase-order.send');   
        Route::get('print/{poNum}', 'GsoPurchaseOrderController@print')->name('gso.purchase-order.print');  
        Route::get('get-local-address', 'GsoPurchaseOrderController@get_local_address')->name('gso.purchase-order.get_local_address');  
    });

    /* Purchase Request Routes */
    Route::prefix('inspection-and-acceptance')->group(function () {
        Route::get('', 'GsoInspectionAcceptanceController@index')->name('gso.inpsection.index');
        Route::get('lists', 'GsoInspectionAcceptanceController@lists')->name('gso.inpsection.lists');
        Route::get('edit/{id}', 'GsoInspectionAcceptanceController@find')->name('gso.inpsection.find');
        Route::get('pr-lists/{id}', 'GsoInspectionAcceptanceController@pr_lists')->name('gso.inpsection.pr-lists');
        Route::get('item-lists/{id}', 'GsoInspectionAcceptanceController@item_lists')->name('gso.inpsection.item-lists');
        Route::get('posting-lists/{id}', 'GsoInspectionAcceptanceController@posting_lists')->name('gso.inpsection.posting-lists');
        Route::get('fetch-status/{id}', 'GsoInspectionAcceptanceController@fetch_status')->name('gso.inpsection.fetch-status');
        Route::get('view-available-posting/{id}', 'GsoInspectionAcceptanceController@view_available_posting')->name('gso.inpsection.view-available-posting');
        Route::post('posting/{id}', 'GsoInspectionAcceptanceController@posting')->name('gso.inpsection.posting');
        Route::get('print/{poNum}', 'GsoInspectionAcceptanceController@print')->name('gso.inpsection.print');
        Route::get('print-disbursement/{poNum}', 'GsoInspectionAcceptanceController@print_disbursement')->name('gso.inpsection.print-disbursement');
    });

    Route::prefix('setup-data')->group(function (){
        /* Item Managements Routes */
        Route::prefix('item-managements')->group(function () {
            Route::get('', 'AdminGsoItemController@index')->name('admin-gso.item.index');
            Route::get('lists', 'AdminGsoItemController@lists')->name('admin-gso.item.lists');
            Route::post('store', 'AdminGsoItemController@store')->name('admin-gso.item.store');
            Route::get('edit/{id}', 'AdminGsoItemController@find')->name('admin-gso.item.find');
            Route::put('update/{id}', 'AdminGsoItemController@update')->name('admin-gso.item.update');
            Route::put('remove/{id}', 'AdminGsoItemController@remove')->name('admin-gso.item.remove');
            Route::put('restore/{id}', 'AdminGsoItemController@restore')->name('admin-gso.item.restore');
            Route::post('upload/{id}', 'AdminGsoItemController@upload')->name('admin-gso.item.upload');
            Route::get('download/{id}', 'AdminGsoItemController@download')->name('admin-gso.item.download');
            Route::delete('delete/{id}', 'AdminGsoItemController@delete')->name('admin-gso.item.delete');
            Route::get('upload-lists/{id}', 'AdminGsoItemController@upload_lists')->name('admin-gso.item.upload-lists');
            Route::get('fetch-gl-via-item-category/{id}', 'AdminGsoItemController@fetch_gl_via_item_category')->name('admin-gso.item.fetch-gl');
            Route::get('generate-item-code/{id}', 'AdminGsoItemController@generate_item_code')->name('admin-gso.item.generate-item-code');            
            Route::get('fetch-based-uom/{id}', 'AdminGsoItemController@fetch_based_uom')->name('admin-gso.item.fetch-based-uom');
            Route::get('conversion-lists/{id}', 'AdminGsoItemController@conversion_lists')->name('admin-gso.conversion.lists');
            Route::post('store-conversion/{id}', 'AdminGsoItemController@store_conversion')->name('admin-gso.item.store-conversion');
            Route::put('update-conversion/{id}', 'AdminGsoItemController@update_conversion')->name('admin-gso.item.update-conversion');
            Route::put('remove-conversion/{id}', 'AdminGsoItemController@remove_conversion')->name('admin-gso.item.remove-conversion');
            Route::put('restore-conversion/{id}', 'AdminGsoItemController@restore_conversion')->name('admin-gso.item.restore-conversion');
            Route::get('edit-conversion/{id}', 'AdminGsoItemController@find_conversion')->name('admin-gso.item.find-conversion');
            Route::get('preload-uom/{item_type}', 'AdminGsoItemController@preload_uom')->name('admin-gso.item.preload_uom');
        });

        /* Item Categories Routes */
        Route::prefix('item-categories')->group(function () {
            Route::get('', 'AdminGsoItemCategoryController@index')->name('admin-gso.item-category.index');
            Route::get('lists', 'AdminGsoItemCategoryController@lists')->name('admin-gso.item-category.lists');
            Route::post('store', 'AdminGsoItemCategoryController@store')->name('admin-gso.item-category.store');
            Route::get('edit/{id}', 'AdminGsoItemCategoryController@find')->name('admin-gso.item-category.find');
            Route::put('update/{id}', 'AdminGsoItemCategoryController@update')->name('admin-gso.item-category.update');
            Route::put('remove/{id}', 'AdminGsoItemCategoryController@remove')->name('admin-gso.item-category.remove');
            Route::put('restore/{id}', 'AdminGsoItemCategoryController@restore')->name('admin-gso.item-category.restore');
        });

        /* Suppliers Routes */  
        Route::prefix('suppliers')->group(function () {
            Route::get('', 'AdminGsoSupplierController@index')->name('admin-gso.supplier.index');
            Route::get('lists', 'AdminGsoSupplierController@lists')->name('admin-gso.supplier.lists');
            Route::post('store', 'AdminGsoSupplierController@store')->name('admin-gso.supplier.store');
            Route::get('edit/{id}', 'AdminGsoSupplierController@find')->name('admin-gso.supplier.find');
            Route::put('update/{id}', 'AdminGsoSupplierController@update')->name('admin-gso.supplier.update');
            Route::put('remove/{id}', 'AdminGsoSupplierController@remove')->name('admin-gso.supplier.remove');
            Route::put('restore/{id}', 'AdminGsoSupplierController@restore')->name('admin-gso.supplier.restore');
            Route::post('upload/{id}', 'AdminGsoSupplierController@upload')->name('admin-gso.supplier.upload');
            Route::get('download/{id}', 'AdminGsoSupplierController@download')->name('admin-gso.supplier.download');
            Route::delete('delete/{id}', 'AdminGsoSupplierController@delete')->name('admin-gso.supplier.delete');
            Route::get('upload-lists/{id}', 'AdminGsoSupplierController@upload_lists')->name('admin-gso.supplier.upload-lists');
            Route::get('generate-code', 'AdminGsoSupplierController@generate_code')->name('admin-gso.supplier.generate-code');
            
            /* Suppliers Contact Persons Routes */  
            Route::prefix('contact-persons')->group(function () {
                Route::get('lists/{id}', 'AdminGsoSupplierController@contact_lists')->name('admin-gso.supplier.contact-lists');
                Route::post('store/{id}', 'AdminGsoSupplierController@storeContactPerson')->name('admin-gso.supplier-contact.store');
                Route::get('edit/{id}', 'AdminGsoSupplierController@findContactPerson')->name('admin-gso.supplier-contact.find');
                Route::put('update/{id}', 'AdminGsoSupplierController@updateContactPerson')->name('admin-gso.supplier-contact.update');
                Route::put('remove/{id}', 'AdminGsoSupplierController@removeContactPerson')->name('admin-gso.supplier-contact.remove');
                Route::put('restore/{id}', 'AdminGsoSupplierController@restoreContactPerson')->name('admin-gso.supplier-contact.restore');
            });
        });
    });

    /* PPMP Routes */
    Route::prefix('project-procurement-management-plan')->group(function () {
        Route::get('', 'GsoPPMPController@index')->name('gso.ppmp.index');
        Route::get('lists', 'GsoPPMPController@lists')->name('gso.ppmp.lists');
        Route::get('add', 'GsoPPMPController@create')->name('gso.ppmp.create');
        Route::get('find/{id}', 'GsoPPMPController@find')->name('gso.ppmp.find');
        Route::get('edit/{id}', 'GsoPPMPController@edit')->name('gso.ppmp.edit');
        Route::get('fetch-item-details/{id}', 'GsoPPMPController@fetch_item_details')->name('gso.ppmp.fetch-item-details');
        Route::get('fetch-status/{id}', 'GsoPPMPController@fetch_status')->name('gso.ppmp.fetch-status');
        Route::get('fetch-remarks/{id}', 'GsoPPMPController@fetch_remarks')->name('gso.ppmp.fetch-remarks');
        Route::get('fetch-division-status/{id}', 'GsoPPMPController@fetch_division_status')->name('gso.ppmp.fetch-division-status');
        Route::get('get-identity', 'GsoPPMPController@get_identity')->name('gso.ppmp.get-identity');
        Route::put('update/{id}', 'GsoPPMPController@update')->name('gso.ppmp.update');
        Route::get('get-item-field/{id}', 'GsoPPMPController@get_item_field')->name('gso.ppmp.get-item-field');
        Route::put('update-lines/{id}', 'GsoPPMPController@update_lines')->name('gso.ppmp.update');
        Route::post('copy/{id}', 'GsoPPMPController@copy')->name('gso.ppmp.copy');
        Route::post('store', 'GsoPPMPController@store')->name('gso.ppmp.store');
        Route::put('lock-division/{id}', 'GsoPPMPController@lock_division')->name('gso.ppmp.lock-division');
        Route::get('manage/{id}', 'GsoPPMPController@manage')->name('gso.ppmp.manage');
        Route::get('view/{id}', 'GsoPPMPController@view')->name('gso.ppmp.view');
        Route::put('remove-lines/{id}', 'GsoPPMPController@remove_lines')->name('gso.ppmp.remove-lines');
        Route::put('send/{detail}/{id}', 'GsoPPMPController@send')->name('gso.ppmp.send');
        Route::get('validate-division-status/{id}', 'GsoPPMPController@validate_division_status')->name('gso.ppmp.validate-division-status');
        Route::put('unlock/{id}', 'GsoPPMPController@unlock')->name('gso.ppmp.unlock');
        Route::get('fetch-budgets/{id}', 'GsoPPMPController@fetch_budgets')->name('gso.ppmp.fetch-budgets');
        Route::get('year-lists', 'GsoPPMPController@year_lists')->name('gso.ppmp.year-lists');
        Route::get('validate-item-removal/{id}', 'GsoPPMPController@validate_item_removal')->name('gso.ppmp.validate-item-removal');
        Route::get('fetch-budget-lists', 'GsoPPMPController@fetch_budget_lists')->name('gso.ppmp.fetch-budget-lists');
    });

    /* Repairs and Inspection Routes */
    Route::prefix('repairs-and-inspections')->group(function () {
        /* Repairs Manage Routes */
        Route::prefix('manage')->group(function () {
            Route::get('', 'GsoRepairManageController@index')->name('gso.repair-manage.index');
            Route::get('lists', 'GsoRepairManageController@lists')->name('gso.repair-manage.lists');
            Route::get('history-lists/{id}', 'GsoRepairManageController@history_lists')->name('gso.repair-manage.history-lists');
            Route::post('store', 'GsoRepairManageController@store')->name('gso.repair-manage.store');
            Route::get('edit/{id}', 'GsoRepairManageController@find')->name('gso.repair-manage.find');
            Route::get('fetch-status/{id}', 'GsoRepairManageController@fetch_status')->name('gso.repair-manage.fetch-status');
            Route::put('update/{id}', 'GsoRepairManageController@update')->name('gso.repair-manage.update');
            Route::get('preload-fixed-asset/{fixedAsset}', 'GsoRepairManageController@preload_fixed_asset')->name('gso.repair-manage.preload-fixed-asset');
            Route::put('send/{detail}/{id}', 'GsoRepairManageController@send')->name('gso.repair-manage.send');
        });

        /* Repairs Inspection Routes */
        Route::prefix('inspection')->group(function () {
            Route::get('', 'GsoRepairInspectionController@index')->name('gso.repair-inspection.index');
            Route::get('lists', 'GsoRepairInspectionController@lists')->name('gso.repair-inspection.lists');
            Route::get('history-lists/{id}', 'GsoRepairInspectionController@history_lists')->name('gso.repair-inspection.history-lists');
            Route::get('item-lists/{id}', 'GsoRepairInspectionController@item_lists')->name('gso.repair-inspection.item-lists');
            Route::get('edit/{id}', 'GsoRepairInspectionController@find')->name('gso.repair-inspection.find');
            Route::get('reload-item-cost/{id}', 'GsoRepairInspectionController@reload_item_cost')->name('gso.repair-inspection.reload-item-cost');            
            Route::get('fetch-status/{id}', 'GsoRepairInspectionController@fetch_status')->name('gso.repair-inspection.fetch-status');
            Route::put('update/{id}', 'GsoRepairInspectionController@update_inspection')->name('gso.repair-inspection.update');
            Route::put('send/{detail}/{id}', 'GsoRepairInspectionController@send_inspection')->name('gso.repair-inspection.send');
            Route::post('store-item/{id}', 'GsoRepairInspectionController@add_item')->name('gso.repair-inspection.store-item');
            Route::put('update-item/{id}', 'GsoRepairInspectionController@update_item')->name('gso.repair-inspection.update-item');
            Route::get('edit-item/{id}', 'GsoRepairInspectionController@find_item')->name('gso.repair-inspection.find-item');
            Route::put('remove-item/{id}', 'GsoRepairInspectionController@remove_item')->name('gso.repair-inspection.remove-item');
            Route::get('preload-fixed-asset/{fixedAsset}', 'GsoRepairInspectionController@preload_fixed_asset')->name('gso.repair-manage.preload-fixed-asset');
        });
    });
    
    /* Waste Materials Routes */
    Route::prefix('waste-materials')->group(function () {
        Route::get('', 'GsoWasteMaterialController@index')->name('gso.waste-material.index');
        Route::get('lists', 'GsoWasteMaterialController@lists')->name('gso.waste-material.lists');
        Route::get('export', 'GsoWasteMaterialController@export')->name('gso.waste-material.export');
    });
});