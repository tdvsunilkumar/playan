<?php


use App\Models\Utility;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('auth/login');
});

require __DIR__.'/auth.php';

Route::get('/login/{lang?}', 'Auth\AuthenticatedSessionController@showLoginForm')->name('login');
Route::get('sendLoginNotification', 'Auth\AuthenticatedSessionController@sendLoginNotification')->name('notification');

Route::post('change-password', 'UserController@updatePassword')->name('update.password');
Route::any('user-reset-password/{id}', 'UserController@userPassword')->name('users.reset');
Route::post('user-reset-password/{id}', 'UserController@userPasswordReset')->name('user.password.update');

Route::get('digital-sign', 'CommonController@digitalLoad')->name('digital-sign-load');//digital sign loader

Route::get(
    '/change/mode', [
                      'as' => 'change.mode',
                      'uses' => 'UserController@changeMode',
                  ]
);

Route::get('/account-dashboard', 'DashboardController@index')->name('dashboard')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::get('/dashboard', 'DashboardController@dashboard_index')->name('dashboard')->middleware(
// Route::get('/dashboard', 'DashboardController@index')->name('index')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::get('/project-dashboard', 'DashboardController@project_dashboard_index')->name('project.dashboard')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::get('/hrm-dashboard', 'DashboardController@hrm_dashboard_index')->name('hrm.dashboard')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::get('profile', 'UserController@profile')->name('profile')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::post('edit-profile', 'UserController@editprofile')->name('update.account')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::post('update-signature', 'UserController@updateSignature')->name('update.signature')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::resource('users', 'UserController')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::resource('roles', 'RoleController')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::resource('permissions', 'PermissionController')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){
    Route::get('change-language/{lang}', 'LanguageController@changeLanquage')->name('change.language');
    Route::get('manage-language/{lang}', 'LanguageController@manageLanguage')->name('manage.language');
    Route::post('store-language-data/{lang}', 'LanguageController@storeLanguageData')->name('store.language.data');
    Route::get('create-language', 'LanguageController@createLanguage')->name('create.language');
    Route::post('store-language', 'LanguageController@storeLanguage')->name('store.language');

    Route::delete('/lang/{lang}', 'LanguageController@destroyLang')->name('lang.destroy');
   }
);

Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::resource('systems', 'SystemController');
    Route::post('email-settings', 'SystemController@saveEmailSettings')->name('email.settings');
    Route::post('company-settings', 'SystemController@saveCompanySettings')->name('company.settings');
    Route::post('system-settings', 'SystemController@saveSystemSettings')->name('system.settings');
    Route::get('system-settings', 'SystemController@companyIndex')->name('company.setting');
    Route::post('zoom-settings', 'SystemController@saveZoomSettings')->name('zoom.settings');
    Route::post('slack-settings', 'SystemController@saveSlackSettings')->name('slack.settings');
    Route::post('telegram-settings', 'SystemController@saveTelegramSettings')->name('telegram.settings');
    Route::post('twilio-setting', 'SystemController@saveTwilioSettings')->name('twilio.setting');

    Route::get('print-setting', 'SystemController@printIndex')->name('print.setting');
    Route::get('company-setting', 'SystemController@companyIndex')->name('company.setting');
    Route::post('business-setting', 'SystemController@saveBusinessSettings')->name('business.setting');
    Route::post('company-payment-setting', 'SystemController@saveCompanyPaymentSettings')->name('company.payment.settings');
    Route::get('test-mail', 'SystemController@testMail')->name('test.mail');
    Route::post('test-mail', 'SystemController@testSendMail')->name('test.send.mail');
    Route::post('stripe-settings', 'SystemController@savePaymentSettings')->name('payment.settings');
    Route::post('pusher-setting', 'SystemController@savePusherSettings')->name('pusher.setting');
    Route::post('recaptcha-settings',['as' => 'recaptcha.settings.store','uses' =>'SystemController@recaptchaSettingStore'])->middleware(['auth']);
}
);

Route::get('productservice/index', 'ProductServiceController@index')->name('productservice.index');
Route::resource('productservice', 'ProductServiceController')->middleware(
    [ 
        'auth','revalidate',
    ]
);

Route::resource('productstock', 'ProductStockController')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::get('customer/{id}/show', 'CustomerController@show')->name('customer.show');
    Route::resource('customer', 'CustomerController');

});


Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::get('vender/{id}/show', 'VenderController@show')->name('vender.show');
    Route::resource('vender', 'VenderController');

}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::resource('bank-account', 'BankAccountController');

}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::get('bank-transfer/index', 'BankTransferController@index')->name('bank-transfer.index');
    Route::resource('bank-transfer', 'BankTransferController');

}
);


Route::resource('taxes', 'TaxController')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::resource('product-category', 'ProductServiceCategoryController')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::resource('product-unit', 'ProductServiceUnitController')->middleware(
    [
        'auth','revalidate',
    ]
);




Route::get('invoice/pdf/{id}', 'InvoiceController@invoice')->name('invoice.pdf')->middleware(
    [
        'revalidate',
    ]
);
Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){


    Route::get('invoice/{id}/duplicate', 'InvoiceController@duplicate')->name('invoice.duplicate');
    Route::get('invoice/{id}/shipping/print', 'InvoiceController@shippingDisplay')->name('invoice.shipping.print');
    Route::get('invoice/{id}/payment/reminder', 'InvoiceController@paymentReminder')->name('invoice.payment.reminder');
    Route::get('invoice/index', 'InvoiceController@index')->name('invoice.index');
    Route::post('invoice/product/destroy', 'InvoiceController@productDestroy')->name('invoice.product.destroy');
    Route::post('invoice/product', 'InvoiceController@product')->name('invoice.product');
    Route::post('invoice/customer', 'InvoiceController@customer')->name('invoice.customer');
    Route::get('invoice/{id}/sent', 'InvoiceController@sent')->name('invoice.sent');
    Route::get('invoice/{id}/resent', 'InvoiceController@resent')->name('invoice.resent');
    Route::get('invoice/{id}/payment', 'InvoiceController@payment')->name('invoice.payment');
    Route::post('invoice/{id}/payment', 'InvoiceController@createPayment')->name('invoice.payment');
    Route::post('invoice/{id}/payment/{pid}/destroy', 'InvoiceController@paymentDestroy')->name('invoice.payment.destroy');
    Route::get('invoice/items', 'InvoiceController@items')->name('invoice.items');

    Route::resource('invoice', 'InvoiceController');
    Route::get('invoice/create/{cid}', 'InvoiceController@create')->name('invoice.create');
}
);

Route::get(
    '/invoices/preview/{template}/{color}', [
                                              'as' => 'invoice.preview',
                                              'uses' => 'InvoiceController@previewInvoice',
                                          ]
);
Route::post(
    '/invoices/template/setting', [
                                    'as' => 'template.setting',
                                    'uses' => 'InvoiceController@saveTemplateSettings',
                                ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){


    Route::get('credit-note', 'CreditNoteController@index')->name('credit.note');
    Route::get('custom-credit-note', 'CreditNoteController@customCreate')->name('invoice.custom.credit.note');
    Route::post('custom-credit-note', 'CreditNoteController@customStore')->name('invoice.custom.credit.note');
    Route::get('credit-note/invoice', 'CreditNoteController@getinvoice')->name('invoice.get');
    Route::get('invoice/{id}/credit-note', 'CreditNoteController@create')->name('invoice.credit.note');
    Route::post('invoice/{id}/credit-note', 'CreditNoteController@store')->name('invoice.credit.note');
    Route::get('invoice/{id}/credit-note/edit/{cn_id}', 'CreditNoteController@edit')->name('invoice.edit.credit.note');
    Route::post('invoice/{id}/credit-note/edit/{cn_id}', 'CreditNoteController@update')->name('invoice.edit.credit.note');
    Route::delete('invoice/{id}/credit-note/delete/{cn_id}', 'CreditNoteController@destroy')->name('invoice.delete.credit.note');

}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){


    Route::get('debit-note', 'DebitNoteController@index')->name('debit.note');
    Route::get('custom-debit-note', 'DebitNoteController@customCreate')->name('bill.custom.debit.note');
    Route::post('custom-debit-note', 'DebitNoteController@customStore')->name('bill.custom.debit.note');
    Route::get('debit-note/bill', 'DebitNoteController@getbill')->name('bill.get');
    Route::get('bill/{id}/debit-note', 'DebitNoteController@create')->name('bill.debit.note');
    Route::post('bill/{id}/debit-note', 'DebitNoteController@store')->name('bill.debit.note');
    Route::get('bill/{id}/debit-note/edit/{cn_id}', 'DebitNoteController@edit')->name('bill.edit.debit.note');
    Route::post('bill/{id}/debit-note/edit/{cn_id}', 'DebitNoteController@update')->name('bill.edit.debit.note');
    Route::delete('bill/{id}/debit-note/delete/{cn_id}', 'DebitNoteController@destroy')->name('bill.delete.debit.note');

}
);


Route::get(
    '/bill/preview/{template}/{color}', [
                                          'as' => 'bill.preview',
                                          'uses' => 'BillController@previewBill',
                                      ]
);
Route::post(
    '/bill/template/setting', [
                                'as' => 'bill.template.setting',
                                'uses' => 'BillController@saveBillTemplateSettings',
                            ]
);

Route::resource('taxes', 'TaxController')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::get('revenue/index', 'RevenueController@index')->name('revenue.index')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::resource('revenue', 'RevenueController')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::get('bill/pdf/{id}', 'BillController@bill')->name('bill.pdf')->middleware(
    [
        
        'revalidate',
    ]
);
Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::get('bill/{id}/duplicate', 'BillController@duplicate')->name('bill.duplicate');
    Route::get('bill/{id}/shipping/print', 'BillController@shippingDisplay')->name('bill.shipping.print');
    Route::get('bill/index', 'BillController@index')->name('bill.index');
    Route::post('bill/product/destroy', 'BillController@productDestroy')->name('bill.product.destroy');
    Route::post('bill/product', 'BillController@product')->name('bill.product');
    Route::post('bill/vender', 'BillController@vender')->name('bill.vender');
    Route::get('bill/{id}/sent', 'BillController@sent')->name('bill.sent');
    Route::get('bill/{id}/resent', 'BillController@resent')->name('bill.resent');
    Route::get('bill/{id}/payment', 'BillController@payment')->name('bill.payment');
    Route::post('bill/{id}/payment', 'BillController@createPayment')->name('bill.payment');
    Route::post('bill/{id}/payment/{pid}/destroy', 'BillController@paymentDestroy')->name('bill.payment.destroy');
    Route::get('bill/items', 'BillController@items')->name('bill.items');

    Route::resource('bill', 'BillController');
    Route::get('bill/create/{cid}', 'BillController@create')->name('bill.create');
}
);


Route::get('payment/index', 'PaymentController@index')->name('payment.index')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::resource('payment', 'PaymentController')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::get('report/transaction', 'TransactionController@index')->name('transaction.index');


}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::get('report/income-summary', 'ReportController@incomeSummary')->name('report.income.summary');
    Route::get('report/expense-summary', 'ReportController@expenseSummary')->name('report.expense.summary');
    Route::get('report/income-vs-expense-summary', 'ReportController@incomeVsExpenseSummary')->name('report.income.vs.expense.summary');
    Route::get('report/tax-summary', 'ReportController@taxSummary')->name('report.tax.summary');
    Route::get('report/profit-loss-summary', 'ReportController@profitLossSummary')->name('report.profit.loss.summary');

    Route::get('report/invoice-summary', 'ReportController@invoiceSummary')->name('report.invoice.summary');
    Route::get('report/bill-summary', 'ReportController@billSummary')->name('report.bill.summary');
    Route::get('report/product-stock-report', 'ReportController@productStock')->name('report.product.stock.report');


    Route::get('report/invoice-report', 'ReportController@invoiceReport')->name('report.invoice');
    Route::get('report/account-statement-report', 'ReportController@accountStatement')->name('report.account.statement');

    Route::get('report/balance-sheet', 'ReportController@balanceSheet')->name('report.balance.sheet');
    Route::get('report/ledger', 'ReportController@ledgerSummary')->name('report.ledger');
    Route::get('report/trial-balance', 'ReportController@trialBalanceSummary')->name('trial.balance');
}
);


Route::get('proposal/pdf/{id}', 'ProposalController@proposal')->name('proposal.pdf')->middleware(
    [
        
        'revalidate',
    ]
);
Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::get('proposal/{id}/status/change', 'ProposalController@statusChange')->name('proposal.status.change');
    Route::get('proposal/{id}/convert', 'ProposalController@convert')->name('proposal.convert');
    Route::get('proposal/{id}/duplicate', 'ProposalController@duplicate')->name('proposal.duplicate');
    Route::post('proposal/product/destroy', 'ProposalController@productDestroy')->name('proposal.product.destroy');
    Route::post('proposal/customer', 'ProposalController@customer')->name('proposal.customer');
    Route::post('proposal/product', 'ProposalController@product')->name('proposal.product');
    Route::get('proposal/items', 'ProposalController@items')->name('proposal.items');
    Route::get('proposal/{id}/sent', 'ProposalController@sent')->name('proposal.sent');
    Route::get('proposal/{id}/resent', 'ProposalController@resent')->name('proposal.resent');

    Route::resource('proposal', 'ProposalController');
    Route::get('proposal/create/{cid}', 'ProposalController@create')->name('proposal.create');
}
);




Route::get(
    '/proposal/preview/{template}/{color}', [
                                              'as' => 'proposal.preview',
                                              'uses' => 'ProposalController@previewProposal',
                                          ]
);
Route::post(
    '/proposal/template/setting', [
                                    'as' => 'proposal.template.setting',
                                    'uses' => 'ProposalController@saveProposalTemplateSettings',
                                ]
);

Route::resource('goal', 'GoalController')->middleware(
    [
        'auth','revalidate',
    ]
);


//Budget Planner //

Route::resource('budget', 'BudgetController')->middleware(
    [
        'auth','revalidate',
    ]
);


Route::resource('account-assets', 'AssetController')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::resource('custom-field', 'CustomFieldController')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::post('chart-of-account/subtype', 'ChartOfAccountController@getSubType')->name('charofAccount.subType')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::resource('chart-of-account', 'ChartOfAccountController');

}
);





Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::post('journal-entry/account/destroy', 'JournalEntryController@accountDestroy')->name('journal.account.destroy');
    Route::resource('journal-entry', 'JournalEntryController');

}
);

// Client Module
Route::resource('clients', 'ClientController')->middleware(
    [
        'auth',
    ]
);
Route::any('client-reset-password/{id}', 'ClientController@clientPassword')->name('clients.reset');
Route::post('client-reset-password/{id}', 'ClientController@clientPasswordReset')->name('client.password.update');
// Deal Module
Route::post(
    '/deals/user', [
    'as' => 'deal.user.json',
    'uses' => 'DealController@jsonUser',
]
);
Route::post(
    '/deals/order', [
    'as' => 'deals.order',
    'uses' => 'DealController@order',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/deals/change-pipeline', [
    'as' => 'deals.change.pipeline',
    'uses' => 'DealController@changePipeline',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/deals/change-deal-status/{id}', [
    'as' => 'deals.change.status',
    'uses' => 'DealController@changeStatus',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/{id}/labels', [
    'as' => 'deals.labels',
    'uses' => 'DealController@labels',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/deals/{id}/labels', [
    'as' => 'deals.labels.store',
    'uses' => 'DealController@labelStore',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/{id}/users', [
    'as' => 'deals.users.edit',
    'uses' => 'DealController@userEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/deals/{id}/users', [
    'as' => 'deals.users.update',
    'uses' => 'DealController@userUpdate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/deals/{id}/users/{uid}', [
    'as' => 'deals.users.destroy',
    'uses' => 'DealController@userDestroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/{id}/clients', [
    'as' => 'deals.clients.edit',
    'uses' => 'DealController@clientEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/deals/{id}/clients', [
    'as' => 'deals.clients.update',
    'uses' => 'DealController@clientUpdate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/deals/{id}/clients/{uid}', [
    'as' => 'deals.clients.destroy',
    'uses' => 'DealController@clientDestroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/{id}/products', [
    'as' => 'deals.products.edit',
    'uses' => 'DealController@productEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/deals/{id}/products', [
    'as' => 'deals.products.update',
    'uses' => 'DealController@productUpdate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/deals/{id}/products/{uid}', [
    'as' => 'deals.products.destroy',
    'uses' => 'DealController@productDestroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/{id}/sources', [
    'as' => 'deals.sources.edit',
    'uses' => 'DealController@sourceEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/deals/{id}/sources', [
    'as' => 'deals.sources.update',
    'uses' => 'DealController@sourceUpdate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/deals/{id}/sources/{uid}', [
    'as' => 'deals.sources.destroy',
    'uses' => 'DealController@sourceDestroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/deals/{id}/file', [
    'as' => 'deals.file.upload',
    'uses' => 'DealController@fileUpload',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/{id}/file/{fid}', [
    'as' => 'deals.file.download',
    'uses' => 'DealController@fileDownload',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/deals/{id}/file/delete/{fid}', [
    'as' => 'deals.file.delete',
    'uses' => 'DealController@fileDelete',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/deals/{id}/note', [
    'as' => 'deals.note.store',
    'uses' => 'DealController@noteStore',
]
)->middleware(['auth']);
Route::get(
    '/deals/{id}/task', [
    'as' => 'deals.tasks.create',
    'uses' => 'DealController@taskCreate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/deals/{id}/task', [
    'as' => 'deals.tasks.store',
    'uses' => 'DealController@taskStore',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/{id}/task/{tid}/show', [
    'as' => 'deals.tasks.show',
    'uses' => 'DealController@taskShow',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/{id}/task/{tid}/edit', [
    'as' => 'deals.tasks.edit',
    'uses' => 'DealController@taskEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/deals/{id}/task/{tid}', [
    'as' => 'deals.tasks.update',
    'uses' => 'DealController@taskUpdate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/deals/{id}/task_status/{tid}', [
    'as' => 'deals.tasks.update_status',
    'uses' => 'DealController@taskUpdateStatus',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/deals/{id}/task/{tid}', [
    'as' => 'deals.tasks.destroy',
    'uses' => 'DealController@taskDestroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/{id}/discussions', [
    'as' => 'deals.discussions.create',
    'uses' => 'DealController@discussionCreate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/deals/{id}/discussions', [
    'as' => 'deals.discussion.store',
    'uses' => 'DealController@discussionStore',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/{id}/permission/{cid}', [
    'as' => 'deals.client.permission',
    'uses' => 'DealController@permission',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/deals/{id}/permission/{cid}', [
    'as' => 'deals.client.permissions.store',
    'uses' => 'DealController@permissionStore',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/deals/list', [
    'as' => 'deals.list',
    'uses' => 'DealController@deal_list',
]
)->middleware(
    [
        'auth',
    ]
);



// Deal Calls
Route::get(
    '/deals/{id}/call', [
    'as' => 'deals.calls.create',
    'uses' => 'DealController@callCreate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/deals/{id}/call', [
    'as' => 'deals.calls.store',
    'uses' => 'DealController@callStore',
]
)->middleware(['auth']);
Route::get(
    '/deals/{id}/call/{cid}/edit', [
    'as' => 'deals.calls.edit',
    'uses' => 'DealController@callEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/deals/{id}/call/{cid}', [
    'as' => 'deals.calls.update',
    'uses' => 'DealController@callUpdate',
]
)->middleware(['auth']);
Route::delete(
    '/deals/{id}/call/{cid}', [
    'as' => 'deals.calls.destroy',
    'uses' => 'DealController@callDestroy',
]
)->middleware(
    [
        'auth',
    ]
);

// Deal Email
Route::get(
    '/deals/{id}/email', [
    'as' => 'deals.emails.create',
    'uses' => 'DealController@emailCreate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/deals/{id}/email', [
    'as' => 'deals.emails.store',
    'uses' => 'DealController@emailStore',
]
)->middleware(['auth']);
Route::resource('deals', 'DealController')->middleware(
    [
        'auth',
    ]
);
// end Deal Module

Route::get(
    '/search', [
    'as' => 'search.json',
    'uses' => 'UserController@search',
]
);
Route::post(
    '/stages/order', [
    'as' => 'stages.order',
    'uses' => 'StageController@order',
]
);
Route::post(
    '/stages/json', [
    'as' => 'stages.json',
    'uses' => 'StageController@json',
]
);

Route::resource('stages', 'StageController');
Route::resource('pipelines', 'PipelineController');
Route::resource('labels', 'LabelController');
Route::resource('sources', 'SourceController');
Route::resource('payments', 'PaymentController');
Route::resource('custom_fields', 'CustomFieldController');



// Leads Module
Route::post(
    '/lead_stages/order', [
    'as' => 'lead_stages.order',
    'uses' => 'LeadStageController@order',
]
);
Route::resource('lead_stages', 'LeadStageController')->middleware(['auth']);
Route::post(
    '/leads/json', [
    'as' => 'leads.json',
    'uses' => 'LeadController@json',
]
);
Route::post(
    '/leads/order', [
    'as' => 'leads.order',
    'uses' => 'LeadController@order',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/leads/list', [
    'as' => 'leads.list',
    'uses' => 'LeadController@lead_list',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/leads/{id}/file', [
    'as' => 'leads.file.upload',
    'uses' => 'LeadController@fileUpload',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/leads/{id}/file/{fid}', [
    'as' => 'leads.file.download',
    'uses' => 'LeadController@fileDownload',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/leads/{id}/file/delete/{fid}', [
    'as' => 'leads.file.delete',
    'uses' => 'LeadController@fileDelete',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/leads/{id}/note', [
    'as' => 'leads.note.store',
    'uses' => 'LeadController@noteStore',
]
)->middleware(['auth']);
Route::get(
    '/leads/{id}/labels', [
    'as' => 'leads.labels',
    'uses' => 'LeadController@labels',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/leads/{id}/labels', [
    'as' => 'leads.labels.store',
    'uses' => 'LeadController@labelStore',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/leads/{id}/users', [
    'as' => 'leads.users.edit',
    'uses' => 'LeadController@userEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/leads/{id}/users', [
    'as' => 'leads.users.update',
    'uses' => 'LeadController@userUpdate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/leads/{id}/users/{uid}', [
    'as' => 'leads.users.destroy',
    'uses' => 'LeadController@userDestroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/leads/{id}/products', [
    'as' => 'leads.products.edit',
    'uses' => 'LeadController@productEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/leads/{id}/products', [
    'as' => 'leads.products.update',
    'uses' => 'LeadController@productUpdate',
]
)->middleware(
    [
        'auth',
    ]
);


Route::delete(
    '/leads/{id}/products/{uid}', [
    'as' => 'leads.products.destroy',
    'uses' => 'LeadController@productDestroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/leads/{id}/sources', [
    'as' => 'leads.sources.edit',
    'uses' => 'LeadController@sourceEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/leads/{id}/sources', [
    'as' => 'leads.sources.update',
    'uses' => 'LeadController@sourceUpdate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/leads/{id}/sources/{uid}', [
    'as' => 'leads.sources.destroy',
    'uses' => 'LeadController@sourceDestroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/leads/{id}/discussions', [
    'as' => 'leads.discussions.create',
    'uses' => 'LeadController@discussionCreate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/leads/{id}/discussions', [
    'as' => 'leads.discussion.store',
    'uses' => 'LeadController@discussionStore',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/leads/{id}/show_convert', [
    'as' => 'leads.convert.deal',
    'uses' => 'LeadController@showConvertToDeal',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/leads/{id}/convert', [
    'as' => 'leads.convert.to.deal',
    'uses' => 'LeadController@convertToDeal',
]
)->middleware(
    [
        'auth',
    ]
);

// Lead Calls
Route::get(
    '/leads/{id}/call', [
    'as' => 'leads.calls.create',
    'uses' => 'LeadController@callCreate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/leads/{id}/call', [
    'as' => 'leads.calls.store',
    'uses' => 'LeadController@callStore',
]
)->middleware(['auth']);
Route::get(
    '/leads/{id}/call/{cid}/edit', [
    'as' => 'leads.calls.edit',
    'uses' => 'LeadController@callEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::put(
    '/leads/{id}/call/{cid}', [
    'as' => 'leads.calls.update',
    'uses' => 'LeadController@callUpdate',
]
)->middleware(['auth']);
Route::delete(
    '/leads/{id}/call/{cid}', [
    'as' => 'leads.calls.destroy',
    'uses' => 'LeadController@callDestroy',
]
)->middleware(
    [
        'auth',
    ]
);

// Lead Email
Route::get(
    '/leads/{id}/email', [
    'as' => 'leads.emails.create',
    'uses' => 'LeadController@emailCreate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/leads/{id}/email', [
    'as' => 'leads.emails.store',
    'uses' => 'LeadController@emailStore',
]
)->middleware(['auth']);
Route::resource('leads', 'LeadController')->middleware(
    [
        'auth',
    ]
);
// end Leads Module

Route::get('user/{id}/plan', 'UserController@upgradePlan')->name('plan.upgrade')->middleware(
    [
        'auth',
    ]
);
Route::get('user/{id}/plan/{pid}', 'UserController@activePlan')->name('plan.active')->middleware(
    [
        'auth',
    ]
);


Route::get(
    '/{uid}/notification/seen', [
    'as' => 'notification.seen',
    'uses' => 'UserController@notificationSeen',
]
);
// Email Templates
Route::get('email_template_lang/{id}/{lang?}', 'EmailTemplateController@manageEmailLang')->name('manage.email.language')->middleware(['auth']);
Route::put('email_template_store/{pid}', 'EmailTemplateController@storeEmailLang')->name('store.email.language')->middleware(['auth']);
Route::put('email_template_status/{id}', 'EmailTemplateController@updateStatus')->name('status.email.language')->middleware(['auth']);
Route::resource('email_template', 'EmailTemplateController')->middleware(
    [
        'auth',
    ]
);
// End Email Templates

// HRM

Route::resource('user', 'UserController')->middleware(
    [
        'auth',
    ]
);
Route::post('employee/json', 'EmployeeController@json')->name('employee.json')->middleware(
    [
        'auth',
    ]
);
Route::post('branch/employee/json', 'EmployeeController@employeeJson')->name('branch.employee.json')->middleware(
    [
        'auth',
    ]
);
Route::get('employee-profile', 'EmployeeController@profile')->name('employee.profile')->middleware(
    [
        'auth',
    ]
);
Route::get('show-employee-profile/{id}', 'EmployeeController@profileShow')->name('show.employee.profile')->middleware(
    [
        'auth',
    ]
);
Route::get('lastlogin', 'EmployeeController@lastLogin')->name('lastlogin')->middleware(
    [
        'auth',
    ]
);
Route::resource('employee', 'EmployeeController')->middleware(
    [
        'auth',
    ]
);
Route::post('employee/getdepartment', 'EmployeeController@getDepartment')->name('employee.getdepartment')->middleware(
    [
        'auth',
    ]
);
Route::resource('department', 'DepartmentController')->middleware(
    [
        'auth',
    ]
);
Route::resource('designation', 'DesignationController')->middleware(
    [
        'auth',
    ]
);
Route::resource('document', 'DocumentController')->middleware(
    [
        'auth',
    ]
);
Route::resource('branch', 'BranchController')->middleware(
    [
        'auth',
    ]
);


// Hrm EmployeeController


Route::get('employee/salary/{eid}', 'SetSalaryController@employeeBasicSalary')->name('employee.basic.salary')->middleware(
    [
        'auth',
    ]
);
//payslip

Route::resource('paysliptype', 'PayslipTypeController')->middleware(
    [
        'auth',
    ]
);
Route::resource('allowance', 'AllowanceController')->middleware(
    [
        'auth',
    ]
);
Route::resource('commission', 'CommissionController')->middleware(
    [
        'auth',
    ]
);
Route::resource('allowanceoption', 'AllowanceOptionController')->middleware(
    [
        'auth',
    ]
);
Route::resource('loanoption', 'LoanOptionController')->middleware(
    [
        'auth',
    ]
);
Route::resource('deductionoption', 'DeductionOptionController')->middleware(
    [
        'auth',
    ]
);
Route::resource('loan', 'LoanController')->middleware(
    [
        'auth',
    ]
);
Route::resource('saturationdeduction', 'SaturationDeductionController')->middleware(
    [
        'auth',
    ]
);
Route::resource('otherpayment', 'OtherPaymentController')->middleware(
    [
        'auth',
    ]
);
Route::resource('overtime', 'OvertimeController')->middleware(
    [
        'auth',
    ]
);

Route::get('employee/salary/{eid}', 'SetSalaryController@employeeBasicSalary')->name('employee.basic.salary')->middleware(
    [
        'auth',
    ]
);
Route::post('employee/update/sallary/{id}', 'SetSalaryController@employeeUpdateSalary')->name('employee.salary.update')->middleware(
    [
        'auth',
    ]
);
Route::get('salary/employeeSalary', 'SetSalaryController@employeeSalary')->name('employeesalary')->middleware(
    [
        'auth',
    ]
);
Route::resource('setsalary', 'SetSalaryController')->middleware(
    [
        'auth',
    ]
);

Route::get('allowances/create/{eid}', 'AllowanceController@allowanceCreate')->name('allowances.create')->middleware(
    [
        'auth',
    ]
);
Route::get('commissions/create/{eid}', 'CommissionController@commissionCreate')->name('commissions.create')->middleware(
    [
        'auth',
    ]
);
Route::get('loans/create/{eid}', 'LoanController@loanCreate')->name('loans.create')->middleware(
    [
        'auth',
    ]
);
Route::get('saturationdeductions/create/{eid}', 'SaturationDeductionController@saturationdeductionCreate')->name('saturationdeductions.create')->middleware(
    [
        'auth',
    ]
);
Route::get('otherpayments/create/{eid}', 'OtherPaymentController@otherpaymentCreate')->name('otherpayments.create')->middleware(
    [
        'auth',
    ]
);
Route::get('overtimes/create/{eid}', 'OvertimeController@overtimeCreate')->name('overtimes.create')->middleware(
    [
        'auth',
    ]
);


Route::get('payslip/paysalary/{id}/{date}', 'PaySlipController@paysalary')->name('payslip.paysalary')->middleware(
    [
        'auth',
    ]
);
Route::get('payslip/bulk_pay_create/{date}', 'PaySlipController@bulk_pay_create')->name('payslip.bulk_pay_create')->middleware(
    [
        'auth',
    ]
);
Route::post('payslip/bulkpayment/{date}', 'PaySlipController@bulkpayment')->name('payslip.bulkpayment')->middleware(
    [
        'auth',
    ]
);
Route::post('payslip/search_json', 'PaySlipController@search_json')->name('payslip.search_json')->middleware(
    [
        'auth',
    ]
);
Route::get('payslip/employeepayslip', 'PaySlipController@employeepayslip')->name('payslip.employeepayslip')->middleware(
    [
        'auth',
    ]
);
Route::get('payslip/showemployee/{id}', 'PaySlipController@showemployee')->name('payslip.showemployee')->middleware(
    [
        'auth',
    ]
);
Route::get('payslip/editemployee/{id}', 'PaySlipController@editemployee')->name('payslip.editemployee')->middleware(
    [
        'auth',
    ]
);
Route::post('payslip/editemployee/{id}', 'PaySlipController@updateEmployee')->name('payslip.updateemployee')->middleware(
    [
        'auth',
    ]
);
Route::get('payslip/pdf/{id}/{m}', 'PaySlipController@pdf')->name('payslip.pdf')->middleware(
    [
        'auth',
    ]
);
Route::get('payslip/payslipPdf/{id}', 'PaySlipController@payslipPdf')->name('payslip.payslipPdf')->middleware(
    [
        'auth',
    ]
);
Route::get('payslip/send/{id}/{m}', 'PaySlipController@send')->name('payslip.send')->middleware(
    [
        'auth',
    ]
);
Route::get('payslip/delete/{id}', 'PaySlipController@destroy')->name('payslip.delete')->middleware(
    [
        'auth',
    ]
);
Route::resource('payslip', 'PaySlipController')->middleware(
    [
        'auth',
    ]
);

Route::resource('company-policy', 'CompanyPolicyController')->middleware(
    [
        'auth',
    ]
);

Route::resource('indicator', 'IndicatorController')->middleware(
    [
        'auth',
    ]
);


Route::resource('appraisal', 'AppraisalController')->middleware(
    [
        'auth',
    ]
);
Route::post('branch/employee/json', 'EmployeeController@employeeJson')->name('branch.employee.json')->middleware(
    [
        'auth',
    ]
);
Route::resource('goaltype', 'GoalTypeController')->middleware(
    [
        'auth',
    ]
);
Route::resource('goaltracking', 'GoalTrackingController')->middleware(
    [
        'auth',
    ]
);

Route::resource('account-assets', 'AssetController')->middleware(
    [
        'auth',
    ]
);

Route::post('event/getdepartment', 'EventController@getdepartment')->name('event.getdepartment')->middleware(
    [
        'auth',
    ]
);
Route::post('event/getemployee', 'EventController@getemployee')->name('event.getemployee')->middleware(
    [
        'auth',
    ]
);
Route::resource('event', 'EventController')->middleware(
    [
        'auth',
    ]
);
Route::post('meeting/getdepartment', 'MeetingController@getdepartment')->name('meeting.getdepartment')->middleware(
    [
        'auth',
    ]
);
Route::post('meeting/getemployee', 'MeetingController@getemployee')->name('meeting.getemployee')->middleware(
    [
        'auth',
    ]
);
Route::resource('meeting', 'MeetingController')->middleware(
    [
        'auth',
    ]
);
Route::resource('trainingtype', 'TrainingTypeController')->middleware(
    [
        'auth',
    ]
);
Route::resource('trainer', 'TrainerController')->middleware(
    [
        'auth',
    ]
);
Route::post('training/status', 'TrainingController@updateStatus')->name('training.status')->middleware(
    [
        'auth',
    ]
);
Route::resource('training', 'TrainingController')->middleware(
    [
        'auth',
    ]
);


// HRM - HR Module

Route::resource('awardtype', 'AwardTypeController')->middleware(
    [
        'auth',
    ]
);
Route::resource('award', 'AwardController')->middleware(
    [
        'auth',
    ]
);

Route::resource('resignation', 'ResignationController')->middleware(
    [
        'auth',
    ]
);
Route::resource('travel', 'TravelController')->middleware(
    [
        'auth',
    ]
);
Route::resource('promotion', 'PromotionController')->middleware(
    [
        'auth',
    ]
);
Route::resource('complaint', 'ComplaintController')->middleware(
    [
        'auth',
    ]
);
Route::resource('warning', 'WarningController')->middleware(
    [
        'auth',
    ]
);
Route::resource('termination', 'TerminationController')->middleware(
    [
        'auth',
    ]
);
Route::get('termination/{id}/description', 'TerminationController@description')->name('termination.description');

Route::resource('terminationtype', 'TerminationTypeController')->middleware(
    [
        'auth',
    ]
);
Route::post('announcement/getdepartment', 'AnnouncementController@getdepartment')->name('announcement.getdepartment')->middleware(
    [
        'auth',
    ]
);
Route::post('announcement/getemployee', 'AnnouncementController@getemployee')->name('announcement.getemployee')->middleware(
    [
        'auth',
    ]
);
Route::resource('announcement', 'AnnouncementController')->middleware(
    [
        'auth',
    ]
);
Route::resource('holiday', 'HolidayController')->middleware(
    [
        'auth',
    ]
);
Route::get('holiday-calender', 'HolidayController@calender')->name('holiday.calender')->middleware(
    [
        'auth',
    ]
);

//------------------------------------  Recurtment --------------------------------

Route::resource('job-category', 'JobCategoryController')->middleware(
    [
        'auth',
    ]
);
Route::resource('job-stage', 'JobStageController')->middleware(
    [
        'auth',
    ]
);
Route::post('job-stage/order', 'JobStageController@order')->name('job.stage.order')->middleware(
    [
        'auth',
    ]
);
Route::resource('job', 'JobController')->middleware(['auth']);
Route::get('career/{id}/{lang}', 'JobController@career')->name('career')->middleware(['']);
Route::get('job/requirement/{code}/{lang}', 'JobController@jobRequirement')->name('job.requirement')->middleware(['']);
Route::get('job/apply/{code}/{lang}', 'JobController@jobApply')->name('job.apply')->middleware(['']);
Route::post('job/apply/data/{code}', 'JobController@jobApplyData')->name('job.apply.data')->middleware(['']);


Route::get('candidates-job-applications', 'JobApplicationController@candidate')->name('job.application.candidate')->middleware(
    [
        'auth',
    ]
);

Route::resource('job-application', 'JobApplicationController')->middleware(['auth']);

Route::post('job-application/order', 'JobApplicationController@order')->name('job.application.order')->middleware(
    [
        'auth',
    ]
);
Route::post('job-application/{id}/rating', 'JobApplicationController@rating')->name('job.application.rating')->middleware(
    [
        'auth',
    ]
);
Route::delete('job-application/{id}/archive', 'JobApplicationController@archive')->name('job.application.archive')->middleware(
    [
        'auth',
    ]
);

Route::post('job-application/{id}/skill/store', 'JobApplicationController@addSkill')->name('job.application.skill.store')->middleware(
    [
        'auth',
    ]
);
Route::post('job-application/{id}/note/store', 'JobApplicationController@addNote')->name('job.application.note.store')->middleware(
    [
        'auth',
    ]
);
Route::delete('job-application/{id}/note/destroy', 'JobApplicationController@destroyNote')->name('job.application.note.destroy')->middleware(
    [
        'auth',
    ]
);
Route::post('job-application/getByJob', 'JobApplicationController@getByJob')->name('get.job.application')->middleware(
    [
        'auth',
    ]
);

Route::get('job-onboard', 'JobApplicationController@jobOnBoard')->name('job.on.board')->middleware(
    [
        'auth',
    ]
);
Route::get('job-onboard/create/{id}', 'JobApplicationController@jobBoardCreate')->name('job.on.board.create')->middleware(
    [
        'auth',
    ]
);
Route::post('job-onboard/store/{id}', 'JobApplicationController@jobBoardStore')->name('job.on.board.store')->middleware(
    [
        'auth',
    ]
);

Route::get('job-onboard/edit/{id}', 'JobApplicationController@jobBoardEdit')->name('job.on.board.edit')->middleware(
    [
        'auth',
    ]
);
Route::post('job-onboard/update/{id}', 'JobApplicationController@jobBoardUpdate')->name('job.on.board.update')->middleware(
    [
        'auth',
    ]
);
Route::delete('job-onboard/delete/{id}', 'JobApplicationController@jobBoardDelete')->name('job.on.board.delete')->middleware(
    [
        'auth',
    ]
);
Route::get('job-onboard/convert/{id}', 'JobApplicationController@jobBoardConvert')->name('job.on.board.convert')->middleware(
    [
        'auth',
    ]
);
Route::post('job-onboard/convert/{id}', 'JobApplicationController@jobBoardConvertData')->name('job.on.board.convert')->middleware(
    [
        'auth',
    ]
);

Route::post('job-application/stage/change', 'JobApplicationController@stageChange')->name('job.application.stage.change')->middleware(
    [
        'auth',
    ]
);




Route::resource('custom-question', 'CustomQuestionController')->middleware(['auth',]);
Route::resource('interview-schedule', 'InterviewScheduleController')->middleware(['auth',]);
Route::get('interview-schedule/create/{id?}', 'InterviewScheduleController@create')->name('interview-schedule.create')->middleware(['auth',]);
Route::get(
    'taskboard/{view?}', [
    'as' => 'taskBoard.view',
    'uses' => 'ProjectTaskController@taskBoard',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'taskboard-view', [
    'as' => 'project.taskboard.view',
    'uses' => 'ProjectTaskController@taskboardView',
]
)->middleware(
    [
        'auth',
    ]
);
Route::resource('document-upload', 'DucumentUploadController')->middleware(
    [
        'auth',
    ]
);
Route::resource('transfer', 'TransferController')->middleware(
    [
        'auth',
    ]
);
Route::get('attendanceemployee/bulkattendance', 'AttendanceEmployeeController@bulkAttendance')->name('attendanceemployee.bulkattendance')->middleware(
    [
        'auth',
    ]
);
Route::post('attendanceemployee/bulkattendance', 'AttendanceEmployeeController@bulkAttendanceData')->name('attendanceemployee.bulkattendance')->middleware(
    [
        'auth',
    ]
);

Route::post('attendanceemployee/attendance', 'AttendanceEmployeeController@attendance')->name('attendanceemployee.attendance')->middleware(
    [
        'auth',
    ]
);

Route::resource('attendanceemployee', 'AttendanceEmployeeController')->middleware(
    [
        'auth',
    ]
);
Route::resource('leavetype', 'LeaveTypeController')->middleware(
    [
        'auth',
    ]
);
Route::get('report/leave', 'ReportController@leave')->name('report.leave')->middleware(
    [
        'auth',
    ]
);
Route::get('employee/{id}/leave/{status}/{type}/{month}/{year}', 'ReportController@employeeLeave')->name('report.employee.leave')->middleware(
    [
        'auth',
    ]
);
Route::get('leave/{id}/action', 'LeaveController@action')->name('leave.action')->middleware(
    [
        'auth',
    ]
);
Route::post('leave/changeaction', 'LeaveController@changeaction')->name('leave.changeaction')->middleware(
    [
        'auth',
    ]
);
Route::post('leave/jsoncount', 'LeaveController@jsoncount')->name('leave.jsoncount')->middleware(
    [
        'auth',
    ]
);
Route::resource('leave', 'LeaveController')->middleware(
    [
        'auth',
    ]
);
Route::get('reports-leave', 'ReportController@leave')->name('report.leave')->middleware(
    [
        'auth',
    ]
);
Route::get('employee/{id}/leave/{status}/{type}/{month}/{year}', 'ReportController@employeeLeave')->name('report.employee.leave')->middleware(
    [
        'auth',
    ]
);
Route::get('reports-payroll', 'ReportController@payroll')->name('report.payroll')->middleware(
    [
        'auth',
    ]
);
Route::get('reports-monthly-attendance', 'ReportController@monthlyAttendance')->name('report.monthly.attendance')->middleware(
    [
        'auth',
    ]
);
Route::get('report/attendance/{month}/{branch}/{department}', 'ReportController@exportCsv')->name('report.attendance')->middleware(
    [
        'auth',
    ]
);

// User Module
Route::get(
    'users/{view?}', [
    'as' => 'users',
    'uses' => 'UserController@index',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'users-view', [
    'as' => 'filter.user.view',
    'uses' => 'UserController@filterUserView',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'checkuserexists', [
    'as' => 'user.exists',
    'uses' => 'UserController@checkUserExists',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'profile', [
    'as' => 'profile',
    'uses' => 'UserController@profile',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/profile', [
    'as' => 'update.profile',
    'uses' => 'UserController@updateProfile',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'user/info/{id}', [
    'as' => 'users.info',
    'uses' => 'UserController@userInfo',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'user/{id}/info/{type}', [
    'as' => 'user.info.popup',
    'uses' => 'UserController@getProjectTask',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    'users/{id}', [
    'as' => 'user.destroy',
    'uses' => 'UserController@destroy',
]
)->middleware(
    [
        'auth',
    ]
);
// End User Module

// Search
Route::get(
    '/search', [
    'as' => 'search.json',
    'uses' => 'UserController@search',
]
);



Route::get(
    'projects/{id}/milestone', [
    'as' => 'project.milestone',
    'uses' => 'ProjectController@milestone',
]
)->middleware(
    [
        'auth',
    ]
);
//Route::delete(
//    '/projects/{id}/users/{uid}', [
//                                    'as' => 'projects.users.destroy',
//                                    'uses' => 'ProjectController@userDestroy',
//                                ]
//)->middleware(
//    [
//        'auth',
//        
//    ]
//);
Route::post(
    'projects/{id}/milestone', [
    'as' => 'project.milestone.store',
    'uses' => 'ProjectController@milestoneStore',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'projects/milestone/{id}/edit', [
    'as' => 'project.milestone.edit',
    'uses' => 'ProjectController@milestoneEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    'projects/milestone/{id}', [
    'as' => 'project.milestone.update',
    'uses' => 'ProjectController@milestoneUpdate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    'projects/milestone/{id}', [
    'as' => 'project.milestone.destroy',
    'uses' => 'ProjectController@milestoneDestroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'projects/milestone/{id}/show', [
    'as' => 'project.milestone.show',
    'uses' => 'ProjectController@milestoneShow',
]
)->middleware(
    [
        'auth',
    ]
);
// End Milestone

// Project Module
Route::get(
    'invite-project-member/{id}', [
    'as' => 'invite.project.member.view',
    'uses' => 'ProjectController@inviteMemberView',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    'invite-project-user-member', [
    'as' => 'invite.project.user.member',
    'uses' => 'ProjectController@inviteProjectUserMember',
]
)->middleware(
    [
        'auth',
    ]
);

Route::delete(
    'projects/{id}/users/{uid}', [
                                 'as' => 'projects.user.destroy',
                                 'uses' => 'ProjectController@destroyProjectUser',
                             ]
)->middleware(
    [
        'auth',
    ]
);






Route::get(
    'project/{view?}', [
    'as' => 'projects.list',
    'uses' => 'ProjectController@index',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'projects-view', [
    'as' => 'filter.project.view',
    'uses' => 'ProjectController@filterProjectView',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post('projects/{id}/store-stages/{slug}', 'ProjectController@storeProjectTaskStages')->name('project.stages.store')->middleware(
    [
        'auth',
    ]
);
Route::patch(
    'remove-user-from-project/{project_id}/{user_id}', [
    'as' => 'remove.user.from.project',
    'uses' => 'ProjectController@removeUserFromProject',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'projects-users', [
    'as' => 'project.user',
    'uses' => 'ProjectController@loadUser',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    'projects/{id}/gantt/{duration?}', [
    'as' => 'projects.gantt',
    'uses' => 'ProjectController@gantt',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    'projects/{id}/gantt', [
    'as' => 'projects.gantt.post',
    'uses' => 'ProjectController@ganttPost',
]
)->middleware(
    [
        'auth',
    ]
);
Route::resource('projects', 'ProjectController')->middleware(
    [
        'auth',
    ]
);

// User Permission
Route::get(
    'projects/{id}/user/{uid}/permission', [
    'as' => 'projects.user.permission',
    'uses' => 'ProjectController@userPermission',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    'projects/{id}/user/{uid}/permission', [
    'as' => 'projects.user.permission.store',
    'uses' => 'ProjectController@userPermissionStore',
]
)->middleware(
    [
        'auth',
    ]
);
// End Project Module
// Task Module
Route::get(
    'stage/{id}/tasks', [
    'as' => 'stage.tasks',
    'uses' => 'ProjectTaskController@getStageTasks',
]
)->middleware(
    [
        'auth',
    ]
);

// Project Task Module
Route::get(
    '/projects/{id}/task', [
    'as' => 'projects.tasks.index',
    'uses' => 'ProjectTaskController@index',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/projects/{pid}/task/{sid}', [
    'as' => 'projects.tasks.create',
    'uses' => 'ProjectTaskController@create',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/projects/{pid}/task/{sid}', [
    'as' => 'projects.tasks.store',
    'uses' => 'ProjectTaskController@store',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/projects/{id}/task/{tid}/show', [
    'as' => 'projects.tasks.show',
    'uses' => 'ProjectTaskController@show',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/projects/{id}/task/{tid}/edit', [
    'as' => 'projects.tasks.edit',
    'uses' => 'ProjectTaskController@edit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/projects/{id}/task/update/{tid}', [
    'as' => 'projects.tasks.update',
    'uses' => 'ProjectTaskController@update',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/projects/{id}/task/{tid}', [
    'as' => 'projects.tasks.destroy',
    'uses' => 'ProjectTaskController@destroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::patch(
    '/projects/{id}/task/order', [
    'as' => 'tasks.update.order',
    'uses' => 'ProjectTaskController@taskOrderUpdate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::patch(
    'update-task-priority-color', [
    'as' => 'update.task.priority.color',
    'uses' => 'ProjectTaskController@updateTaskPriorityColor',
]
)->middleware(
    [
        'auth',
    ]
);

Route::post(
    '/projects/{id}/comment/{tid}/file', [
    'as' => 'comment.store.file',
    'uses' => 'ProjectTaskController@commentStoreFile',
]
);
Route::delete(
    '/projects/{id}/comment/{tid}/file/{fid}', [
    'as' => 'comment.destroy.file',
    'uses' => 'ProjectTaskController@commentDestroyFile',
]
);
Route::post(
    '/projects/{id}/comment/{tid}', [
    'as' => 'comment.store',
    'uses' => 'ProjectTaskController@commentStore',
]
);
Route::delete(
    '/projects/{id}/comment/{tid}/{cid}', [
    'as' => 'comment.destroy',
    'uses' => 'ProjectTaskController@commentDestroy',
]
);
Route::post(
    '/projects/{id}/checklist/{tid}', [
    'as' => 'checklist.store',
    'uses' => 'ProjectTaskController@checklistStore',
]
);
Route::post(
    '/projects/{id}/checklist/update/{cid}', [
    'as' => 'checklist.update',
    'uses' => 'ProjectTaskController@checklistUpdate',
]
);
Route::delete(
    '/projects/{id}/checklist/{cid}', [
    'as' => 'checklist.destroy',
    'uses' => 'ProjectTaskController@checklistDestroy',
]
);
Route::post(
    '/projects/{id}/change/{tid}/fav', [
    'as' => 'change.fav',
    'uses' => 'ProjectTaskController@changeFav',
]
);
Route::post(
    '/projects/{id}/change/{tid}/complete', [
    'as' => 'change.complete',
    'uses' => 'ProjectTaskController@changeCom',
]
);
Route::post(
    '/projects/{id}/change/{tid}/progress', [
    'as' => 'change.progress',
    'uses' => 'ProjectTaskController@changeProg',
]
);
Route::get(
    '/projects/task/{id}/get', [
    'as' => 'projects.tasks.get',
    'uses' => 'ProjectTaskController@taskGet',
]
)->middleware(
    [
        'auth',
    ]
);


Route::get(
    '/calendar/{id}/show', [
    'as' => 'task.calendar.show',
    'uses' => 'ProjectTaskController@calendarShow',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/calendar/{id}/drag', [
    'as' => 'task.calendar.drag',
    'uses' => 'ProjectTaskController@calendarDrag',
]
);
Route::get(
    'calendar/{task}/{pid?}', [
    'as' => 'task.calendar',
    'uses' => 'ProjectTaskController@calendarView',
]
)->middleware(
    [
        'auth',
    ]
);
Route::resource('project-task-stages', 'TaskStageController')->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/project-task-stages/order', [
    'as' => 'project-task-stages.order',
    'uses' => 'TaskStageController@order',
]
);
Route::post('project-task-new-stage', 'TaskStageController@storingValue')->name('new-task-stage')->middleware(
    [
        'auth',
    ]
);
// End Task Module

// Project Expense Module
Route::get(
    '/projects/{id}/expense', [
    'as' => 'projects.expenses.index',
    'uses' => 'ExpenseController@index',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/projects/{pid}/expense/create', [
    'as' => 'projects.expenses.create',
    'uses' => 'ExpenseController@create',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/projects/{pid}/expense/store', [
    'as' => 'projects.expenses.store',
    'uses' => 'ExpenseController@store',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/projects/{id}/expense/{eid}/edit', [
    'as' => 'projects.expenses.edit',
    'uses' => 'ExpenseController@edit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/projects/{id}/expense/{eid}', [
    'as' => 'projects.expenses.update',
    'uses' => 'ExpenseController@update',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/projects/{eid}/expense/', [
    'as' => 'projects.expenses.destroy',
    'uses' => 'ExpenseController@destroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/expense-list', [
    'as' => 'expense.list',
    'uses' => 'ExpenseController@expenseList',
]
)->middleware(
    [
        'auth',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){
    Route::resource('contractType', 'ContractTypeController');
}
);




// Project Timesheet
Route::get('append-timesheet-task-html', 'TimesheetController@appendTimesheetTaskHTML')->name('append.timesheet.task.html')->middleware(
    [
        'auth',
    ]
);
Route::get('timesheet-table-view', 'TimesheetController@filterTimesheetTableView')->name('filter.timesheet.table.view')->middleware(
    [
        'auth',
    ]
);
Route::get('timesheet-view', 'TimesheetController@filterTimesheetView')->name('filter.timesheet.view')->middleware(
    [
        'auth',
    ]
);
Route::get('timesheet-list', 'TimesheetController@timesheetList')->name('timesheet.list')->middleware(
    [
        'auth',
    ]
);
Route::get('timesheet-list-get', 'TimesheetController@timesheetListGet')->name('timesheet.list.get')->middleware(
    [
        'auth',
    ]
);

Route::get(
    '/project/{id}/timesheet', [
    'as' => 'timesheet.index',
    'uses' => 'TimesheetController@timesheetView',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/project/{id}/timesheet/create', [
    'as' => 'timesheet.create',
    'uses' => 'TimesheetController@timesheetCreate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/project/timesheet', [
    'as' => 'timesheet.store',
    'uses' => 'TimesheetController@timesheetStore',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/project/timesheet/{project_id}/edit/{timesheet_id}', [
    'as' => 'timesheet.edit',
    'uses' => 'TimesheetController@timesheetEdit',
]
)->middleware(
    [
        'auth',
    ]
);
Route::any(
    '/project/timesheet/update/{timesheet_id}', [
    'as' => 'timesheet.update',
    'uses' => 'TimesheetController@timesheetUpdate',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/project/timesheet/{timesheet_id}', [
    'as' => 'timesheet.destroy',
    'uses' => 'TimesheetController@timesheetDestroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::group(
    [
        'middleware' => [
            'auth',
            
        ],
    ], function (){
    Route::resource('projectstages', 'ProjectstagesController');
    Route::post(
        '/projectstages/order', [
        'as' => 'projectstages.order',
        'uses' => 'ProjectstagesController@order',
    ]
    );
    Route::post('projects/bug/kanban/order', 'ProjectController@bugKanbanOrder')->name('bug.kanban.order');
    Route::get('projects/{id}/bug/kanban', 'ProjectController@bugKanban')->name('task.bug.kanban');
    Route::get('projects/{id}/bug', 'ProjectController@bug')->name('task.bug');
    Route::get('projects/{id}/bug/create', 'ProjectController@bugCreate')->name('task.bug.create');
    Route::post('projects/{id}/bug/store', 'ProjectController@bugStore')->name('task.bug.store');
    Route::get('projects/{id}/bug/{bid}/edit', 'ProjectController@bugEdit')->name('task.bug.edit');
    Route::post('projects/{id}/bug/{bid}/update', 'ProjectController@bugUpdate')->name('task.bug.update');
    Route::delete('projects/{id}/bug/{bid}/destroy', 'ProjectController@bugDestroy')->name('task.bug.destroy');
    Route::get('projects/{id}/bug/{bid}/show', 'ProjectController@bugShow')->name('task.bug.show');
    Route::post('projects/{id}/bug/{bid}/comment', 'ProjectController@bugCommentStore')->name('bug.comment.store');
    Route::post('projects/bug/{bid}/file', 'ProjectController@bugCommentStoreFile')->name('bug.comment.file.store');
    Route::delete('projects/bug/comment/{id}', 'ProjectController@bugCommentDestroy')->name('bug.comment.destroy');
    Route::delete('projects/bug/file/{id}', 'ProjectController@bugCommentDestroyFile')->name('bug.comment.file.destroy');
    Route::resource('bugstatus', 'BugStatusController');
    Route::post(
        '/bugstatus/order', [
        'as' => 'bugstatus.order',
        'uses' => 'BugStatusController@order',
    ]
    );

    Route::get(
        'bugs-report/{view?}', [
        'as' => 'bugs.view',
        'uses' => 'ProjectTaskController@allBugList',
    ]
    )->middleware(
        [
            'auth',
            
        ]
    );

}
);
// User_Todo Module
Route::post(
    '/todo/create', [
    'as' => 'todo.store',
    'uses' => 'UserController@todo_store',
]
)->middleware(
    [
        'auth',
    ]
);
Route::post(
    '/todo/{id}/update', [
    'as' => 'todo.update',
    'uses' => 'UserController@todo_update',
]
)->middleware(
    [
        'auth',
    ]
);
Route::delete(
    '/todo/{id}', [
    'as' => 'todo.destroy',
    'uses' => 'UserController@todo_destroy',
]
)->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/change/mode', [
    'as' => 'change.mode',
    'uses' => 'UserController@changeMode',
]
);

Route::get(
    'dashboard-view', [
    'as' => 'dashboard.view',
    'uses' => 'DashboardController@filterView',
]
)->middleware(
    [
        'auth',
    ]
);


// saas
Route::resource('users', 'UserController')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::resource('plans', 'PlanController')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::resource('coupons', 'CouponController')->middleware(
    [
        'auth','revalidate',
    ]
);
// Orders

Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){

    Route::get('/orders', 'StripePaymentController@index')->name('order.index');
    Route::get('/stripe/{code}', 'StripePaymentController@stripe')->name('stripe');
    Route::post('/stripe', 'StripePaymentController@stripePost')->name('stripe.post');

}
);
Route::get(
    '/apply-coupon', [
                       'as' => 'apply.coupon',
                       'uses' => 'CouponController@applyCoupon',
                   ]
)->middleware(
    [
        'auth','revalidate',
    ]
);




//================================= Form Builder ====================================//


// Form Builder
Route::resource('form_builder', 'FormBuilderController')->middleware(
    [
        'auth',
    ]
);

// Form link base view
Route::get('/form/{code}', 'FormBuilderController@formView')->name('form.view')->middleware(['']);
Route::post('/form_view_store', 'FormBuilderController@formViewStore')->name('form.view.store')->middleware(['']);

// Form Field
Route::get('/form_builder/{id}/field', 'FormBuilderController@fieldCreate')->name('form.field.create')->middleware(
    [
        'auth',
    ]
);
Route::post('/form_builder/{id}/field', 'FormBuilderController@fieldStore')->name('form.field.store')->middleware(
    [
        'auth',
    ]
);
Route::get('/form_builder/{id}/field/{fid}/show', 'FormBuilderController@fieldShow')->name('form.field.show')->middleware(
    [
        'auth',
    ]
);
Route::get('/form_builder/{id}/field/{fid}/edit', 'FormBuilderController@fieldEdit')->name('form.field.edit')->middleware(
    [
        'auth',
    ]
);
Route::post('/form_builder/{id}/field/{fid}', 'FormBuilderController@fieldUpdate')->name('form.field.update')->middleware(
    [
        'auth',
    ]
);
Route::delete('/form_builder/{id}/field/{fid}', 'FormBuilderController@fieldDestroy')->name('form.field.destroy')->middleware(
    [
        'auth',
    ]
);

// Form Response
Route::get('/form_response/{id}', 'FormBuilderController@viewResponse')->name('form.response')->middleware(
    [
        'auth',
    ]
);
Route::get('/response/{id}', 'FormBuilderController@responseDetail')->name('response.detail')->middleware(
    [
        'auth',
    ]
);

// Form Field Bind
Route::get('/form_field/{id}', 'FormBuilderController@formFieldBind')->name('form.field.bind')->middleware(
    [
        'auth',
    ]
);
Route::post('/form_field_store/{id}', 'FormBuilderController@bindStore')->name('form.bind.store')->middleware(
    [
        'auth',
    ]
);



// end Form Builder


Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){
    Route::get('contract/{id}/description', 'ContractController@description')->name('contract.description');
    Route::get('contract/grid', 'ContractController@grid')->name('contract.grid');
    Route::resource('contract', 'ContractController');
}
);


//================================= Custom Landing Page ====================================//

Route::get('/landingpage', 'LandingPageSectionController@index')->name('custom_landing_page.index')->middleware(
    [
        'auth',
    ]
);
Route::get('/LandingPage/show/{id}', 'LandingPageSectionController@show');
Route::post('/LandingPage/setConetent', 'LandingPageSectionController@setConetent')->middleware(
    [
        'auth',
    ]
);
Route::get(
    '/get_landing_page_section/{name}', function ($name){
    $plans = \DB::table('plans')->get();

    return view('custom_landing_page.' . $name, compact('plans'));
}
);
Route::post('/LandingPage/removeSection/{id}', 'LandingPageSectionController@removeSection')->middleware(
    [
        'auth',
    ]
);
Route::post('/LandingPage/setOrder', 'LandingPageSectionController@setOrder')->middleware(
    [
        'auth',
    ]
);
Route::post('/LandingPage/copySection', 'LandingPageSectionController@copySection')->middleware(
    [
        'auth',
    ]
);
Route::get('/customer/invoice/{id}/', 'InvoiceController@invoiceLink')->name('invoice.link.copy');

 Route::get('/customer/bill/{id}/', 'BillController@invoiceLink')->name('bill.link.copy');

 Route::get('/customer/proposal/{id}/', 'ProposalController@invoiceLink')->name('proposal.link.copy');

Route::post('plan-pay-with-paypal', 'PaypalController@planPayWithPaypal')->name('plan.pay.with.paypal')->middleware(
    [
        'auth','revalidate',
    ]
);
Route::get('{id}/plan-get-payment-status', 'PaypalController@planGetPaymentStatus')->name('plan.get.payment.status')->middleware(
    [
        'auth','revalidate',
    ]
);






//================================= Plan Payment Gateways  ====================================//

Route::post('/plan-pay-with-paystack',['as' => 'plan.pay.with.paystack','uses' =>'PaystackPaymentController@planPayWithPaystack'])->middleware(['auth']);
Route::get('/plan/paystack/{pay_id}/{plan_id}', ['as' => 'plan.paystack','uses' => 'PaystackPaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-flaterwave',['as' => 'plan.pay.with.flaterwave','uses' =>'FlutterwavePaymentController@planPayWithFlutterwave'])->middleware(['auth']);
Route::get('/plan/flaterwave/{txref}/{plan_id}', ['as' => 'plan.flaterwave','uses' => 'FlutterwavePaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-razorpay',['as' => 'plan.pay.with.razorpay','uses' =>'RazorpayPaymentController@planPayWithRazorpay'])->middleware(['auth']);
Route::get('/plan/razorpay/{txref}/{plan_id}', ['as' => 'plan.razorpay','uses' => 'RazorpayPaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-paytm',['as' => 'plan.pay.with.paytm','uses' =>'PaytmPaymentController@planPayWithPaytm'])->middleware(['auth']);
Route::post('/plan/paytm/{plan}', ['as' => 'plan.paytm','uses' => 'PaytmPaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-mercado',['as' => 'plan.pay.with.mercado','uses' =>'MercadoPaymentController@planPayWithMercado'])->middleware(['auth']);
Route::get('/plan/mercado/{plan}/{amount}', ['as' => 'plan.mercado','uses' => 'MercadoPaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-mollie',['as' => 'plan.pay.with.mollie','uses' =>'MolliePaymentController@planPayWithMollie'])->middleware(['auth']);
Route::get('/plan/mollie/{plan}', ['as' => 'plan.mollie','uses' => 'MolliePaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-skrill',['as' => 'plan.pay.with.skrill','uses' =>'SkrillPaymentController@planPayWithSkrill'])->middleware(['auth']);
Route::get('/plan/skrill/{plan}', ['as' => 'plan.skrill','uses' => 'SkrillPaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-coingate',['as' => 'plan.pay.with.coingate','uses' =>'CoingatePaymentController@planPayWithCoingate'])->middleware(['auth']);
Route::get('/plan/coingate/{plan}', ['as' => 'plan.coingate','uses' => 'CoingatePaymentController@getPaymentStatus']);



Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){
    Route::get('order', 'StripePaymentController@index')->name('order.index');
    Route::get('/stripe/{code}', 'StripePaymentController@stripe')->name('stripe');
    Route::post('/stripe', 'StripePaymentController@stripePost')->name('stripe.post');
}
);


Route::post('plan-pay-with-paypal', 'PaypalController@planPayWithPaypal')->name('plan.pay.with.paypal')->middleware(
    [
        'auth','revalidate',
    ]
);

Route::get('{id}/plan-get-payment-status', 'PaypalController@planGetPaymentStatus')->name('plan.get.payment.status')->middleware(
    [
        'auth','revalidate',
    ]
);



//================================= Invoice Payment Gateways  ====================================//


Route::post('customer/{id}/payment', 'StripePaymentController@addpayment')->name('customer.payment');

Route::post('{id}/pay-with-paypal', 'PaypalController@customerPayWithPaypal')->name('customer.pay.with.paypal');
Route::get('{id}/get-payment-status', 'PaypalController@customerGetPaymentStatus')->name('customer.get.payment.status')->middleware(
    [
        

    ]
);


Route::post('/customer-pay-with-paystack',['as' => 'customer.pay.with.paystack','uses' =>'PaystackPaymentController@customerPayWithPaystack'])->middleware(['']);
Route::get('/customer/paystack/{pay_id}/{invoice_id}', ['as' => 'customer.paystack','uses' => 'PaystackPaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-flaterwave',['as' => 'customer.pay.with.flaterwave','uses' =>'FlutterwavePaymentController@customerPayWithFlutterwave'])->middleware(['']);
Route::get('/customer/flaterwave/{txref}/{invoice_id}', ['as' => 'customer.flaterwave','uses' => 'FlutterwavePaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-razorpay',['as' => 'customer.pay.with.razorpay','uses' =>'RazorpayPaymentController@customerPayWithRazorpay'])->middleware(['']);
Route::get('/customer/razorpay/{txref}/{invoice_id}', ['as' => 'customer.razorpay','uses' => 'RazorpayPaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-paytm',['as' => 'customer.pay.with.paytm','uses' =>'PaytmPaymentController@customerPayWithPaytm'])->middleware(['']);
Route::post('/customer/paytm/{invoice}/{amount}', ['as' => 'customer.paytm','uses' => 'PaytmPaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-mercado',['as' => 'customer.pay.with.mercado','uses' =>'MercadoPaymentController@customerPayWithMercado'])->middleware(['']);
Route::get('/customer/mercado/{invoice}', ['as' => 'customer.mercado','uses' => 'MercadoPaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-mollie',['as' => 'customer.pay.with.mollie','uses' =>'MolliePaymentController@customerPayWithMollie'])->middleware(['']);
Route::get('/customer/mollie/{invoice}/{amount}', ['as' => 'customer.mollie','uses' => 'MolliePaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-skrill',['as' => 'customer.pay.with.skrill','uses' =>'SkrillPaymentController@customerPayWithSkrill'])->middleware(['']);
Route::get('/customer/skrill/{invoice}/{amount}', ['as' => 'customer.skrill','uses' => 'SkrillPaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-coingate',['as' => 'customer.pay.with.coingate','uses' =>'CoingatePaymentController@customerPayWithCoingate'])->middleware(['']);
Route::get('/customer/coingate/{invoice}/{amount}', ['as' => 'customer.coingate','uses' => 'CoingatePaymentController@getInvoicePaymentStatus']);




Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){
    Route::get('support/{id}/reply', 'SupportController@reply')->name('support.reply');
    Route::post('support/{id}/reply', 'SupportController@replyAnswer')->name('support.reply.answer');
    Route::get('support/grid', 'SupportController@grid')->name('support.grid');
    Route::resource('support', 'SupportController');
}
);


Route::resource('competencies', 'CompetenciesController')->middleware(
    [
        'auth',
    ]
);


Route::group(
    [
        'middleware' => [
            'auth',
            'revalidate',
        ],
    ], function (){
    Route::resource('performanceType', 'PerformanceTypeController');
}
);




// Plan Request Module
Route::get('plan_request', 'PlanRequestController@index')->name('plan_request.index')->middleware(['auth',]);
Route::get('request_frequency/{id}', 'PlanRequestController@requestView')->name('request.view')->middleware(['auth',]);
Route::get('request_send/{id}', 'PlanRequestController@userRequest')->name('send.request')->middleware(['auth',]);
Route::get('request_response/{id}/{response}', 'PlanRequestController@acceptRequest')->name('response.request')->middleware(['auth',]);
Route::get('request_cancel/{id}', 'PlanRequestController@cancelRequest')->name('request.cancel')->middleware(['auth',]);


//QR Code Module




//--------------------------------------------------------Import/Export Data Route-----------------------------------------------------------------


Route::get('export/productservice', 'ProductServiceController@export')->name('productservice.export');
Route::get('import/productservice/file', 'ProductServiceController@importFile')->name('productservice.file.import');
Route::post('import/productservice', 'ProductServiceController@import')->name('productservice.import');

Route::get('export/customer', 'CustomerController@export')->name('customer.export');
Route::get('import/customer/file', 'CustomerController@importFile')->name('customer.file.import');
Route::post('import/customer', 'CustomerController@import')->name('customer.import');

Route::get('export/vender', 'VenderController@export')->name('vender.export');
Route::get('import/vender/file', 'VenderController@importFile')->name('vender.file.import');
Route::post('import/vender', 'VenderController@import')->name('vender.import');

Route::get('export/invoice', 'InvoiceController@export')->name('invoice.export');

Route::get('export/proposal', 'ProposalController@export')->name('proposal.export');

Route::get('export/bill', 'BillController@export')->name('bill.export');


//=================================== Time-Tracker======================================================================
Route::post('stop-tracker', 'DashboardController@stopTracker')->name('stop.tracker')->middleware(['auth']);
Route::get('time-tracker','TimeTrackerController@index')->name('time.tracker')->middleware(['auth']);
Route::delete('tracker/{tid}/destroy', 'TimeTrackerController@Destroy')->name('tracker.destroy');
Route::post('tracker/image-view', ['as' => 'tracker.image.view','uses' => 'TimeTrackerController@getTrackerImages']);
Route::delete('tracker/image-remove', ['as' => 'tracker.image.remove','uses' => 'TimeTrackerController@removeTrackerImages']);
Route::get('projects/time-tracker/{id}','ProjectController@tracker')->name('projecttime.tracker')->middleware(['auth']);


//=================================== Zoom Meeting ======================================================================
Route::resource('zoom-meeting', 'ZoomMeetingController')->middleware(
    [
        'auth',
    ]
);
Route::any('/zoom-meeting/projects/select/{bid}', 'ZoomMeetingController@projectwiseuser')->name('zoom-meeting.projects.select');
Route::get('zoom-meeting-calender', 'ZoomMeetingController@calender')->name('zoom-meeting.calender')->middleware(
    [
        'auth',
    ]
);


// ------------------------------------- PaymentWall ------------------------------

Route::post('/paymentwalls' , ['as' => 'plan.paymentwallpayment','uses' =>'PaymentWallPaymentController@paymentwall'])->middleware(['']);
Route::post('/plan-pay-with-paymentwall/{plan}',['as' => 'plan.pay.with.paymentwall','uses' =>'PaymentWallPaymentController@planPayWithPaymentWall'])->middleware(['']);
Route::get('/plan/{flag}', ['as' => 'error.plan.show','uses' => 'PaymentWallPaymentController@planeerror']);


Route::post('/paymentwall' , ['as' => 'invoice.paymentwallpayment','uses' =>'PaymentWallPaymentController@invoicepaymentwall'])->middleware(['']);
Route::post('/invoice-pay-with-paymentwall/{plan}',['as' => 'invoice.pay.with.paymentwall','uses' =>'PaymentWallPaymentController@invoicePayWithPaymentwall'])->middleware(['']);
Route::get('/invoices/{flag}/{invoice}', ['as' => 'error.invoice.show','uses' => 'PaymentWallPaymentController@invoiceerror']);

 

Route::post('allaplicant/updateapprove', 'allaplicantController@updateapprove')->name('allaplicant.updateapprove')->middleware(
   [
       'auth',
       
    ]
);


// --------------- ---------------------- Bplo Applicant ------------------------------
Route::middleware(['auth'])->prefix('business-permit')->group(function () {
    /* Designation Routes */
    Route::prefix('application')->group(function () {
        Route::get('', 'ApplicantController@index')->name('bplo.business.index');
        Route::get('lists', 'ApplicantController@getList')->name('bplo.business.lists');
        Route::post('store', 'ApplicantController@store')->name('bplo.business.store');
        Route::get('edit/{bus_id}', 'ApplicantController@find')->name('bplo.business.find');
        Route::put('update/{id}', 'ApplicantController@update')->name('bplo.business.update');
        Route::post('ActiveInactive', 'ApplicantController@ActiveInactive')->name('bplo.business.ActiveInactive');
        Route::post('NewRenew', 'ApplicantController@NewRenew')->name('bplo.business.NewRenew');

        Route::get('busn_psic_lists/{bus_id}', 'ApplicantController@busn_psic_lists')->name('bplo.business.busn_psic_lists');
        Route::get('requirment_doc_list/{bus_id}', 'ApplicantController@requirment_doc_list')->name('bplo.business.requirment_doc_list');
        Route::get('busn_measure_lists/{bus_id}', 'ApplicantController@busn_measure_lists')->name('bplo.business.busn_measure_lists');
        Route::post('add-business-plan', 'ApplicantController@add_business_plan')->name('bplo.business.add_business_plan');
        Route::get('reload-busn-plan/{id}', 'ApplicantController@reload_busn_plan')->name('bplo.business.reload_busn_plan');
        Route::get('reload-sub-class', 'ApplicantController@reload_sub_class')->name('bplo.business.reload_sub_class');
        Route::get('reload-measure-pax/{id}', 'ApplicantController@reload_measure_pax')->name('bplo.business.reload_measure_pax');
        Route::get('load_floor_val/{id}', 'ApplicantController@load_floor_val')->name('bplo.business.load_floor_val');

        Route::get('reload-requirments/{id}', 'ApplicantController@reload_requirments')->name('bplo.business.reload_requirments');
        Route::get('reload-address/{id}', 'ApplicantController@reload_address')->name('bplo.business.reload-address');
        Route::get('reload-barangay', 'ApplicantController@reload_barangay')->name('bplo.business.reload_barangay');
        Route::get('reload-summary/{id}', 'ApplicantController@reload_summary')->name('bplo.business.reload_summary');
        Route::post('add-measure-pax', 'ApplicantController@add_measure_pax')->name('bplo.business.add-measure-pax');
        Route::get('edit-measure-pax/{id}', 'ApplicantController@edit_measure_pax')->name('bplo.business.edit_measure_pax');
        Route::get('edit-busn-plan/{id}', 'ApplicantController@edit_busn_plan')->name('bplo.business.edit_busn_plan');
        Route::post('update_measure_pax', 'ApplicantController@update_measure_pax')->name('bplo.business.update_measure_pax');
        Route::get('refresh_client', 'ApplicantController@refresh_client')->name('bplo.business.refresh_client');
        Route::get('reload_client_det/{id}', 'ApplicantController@reload_client_det')->name('bplo.business.reload_client_det');
        Route::get('reload_rpt_info/{id}/{year}', 'ApplicantController@reload_rpt_info')->name('bplo.business.reload_rpt_info');
        Route::get('checkMuncByBrgy/{id}', 'ApplicantController@checkMuncByBrgy')->name('bplo.business.checkMuncByBrgy');

        
        Route::post('add-requirment-doc', 'ApplicantController@add_requirment_doc')->name('bplo.business.add_requirment_doc');
        Route::delete('remove-busn-plan/{id}', 'ApplicantController@remove_busn_plan')->name('bplo.business.remove_busn_plan');
        Route::delete('remove-measure/{id}', 'ApplicantController@remove_measure')->name('bplo.business.remove_measure');
        Route::delete('remove-req-doc/{id}', 'ApplicantController@remove_req_doc')->name('bplo.business.remove_req_doc');
        Route::get('print-summary/{id}', 'ApplicantController@print_summary')->name('bplo.business.print_summary');
        Route::any('sendEmail','ApplicantController@sendEmail');
        Route::get('business-permit/application/generatePaymentPdf', 'ApplicantController@generatePaymentPdf');
       
        Route::get('item-lists/{id}', 'ApplicantController@item_lists')->name('bplo.business.item-lists');
        Route::get('reload-uom/{id}', 'ApplicantController@reload_uom')->name('bplo.business.reload-uom');
        Route::get('reload-divisions-employees/{id}', 'ApplicantController@reload_divisions_employees')->name('bplo.business.reload-divisions-employees');
        Route::get('reload-designation/{id}', 'ApplicantController@reload_designation')->name('bplo.business.reload-designation');
        Route::get('edit/{id}', 'ApplicantController@find')->name('bplo.business.find');
       
        Route::get('edit-item/{id}', 'ApplicantController@find_item')->name('bplo.business.find-item');
        Route::put('update-item/{id}', 'ApplicantController@update_item')->name('bplo.business.update-item');
        Route::put('update-line/{id}', 'ApplicantController@updateLine')->name('bplo.business.update-line');
        Route::delete('remove-line/{id}', 'ApplicantController@removeLine')->name('bplo.business.remove-line');
        Route::get('edit-line/{id}', 'ApplicantController@findLine')->name('bplo.business.find-line');
        Route::get('fetch-status/{id}', 'ApplicantController@fetch_status')->name('bplo.business.fetch-status');
        Route::put('send/{detail}/{id}', 'ApplicantController@send')->name('bplo.business.send');
        Route::get('print/{control_no}', 'GsoObligationRequestController@print')->name('gso.obligation-request.print');

        //change status rout 
        Route::post('verify-applications', 'BussinessVerifyApplications@index')->name('bplo.business.verify-applications');
        Route::post('change-status', 'BussinessVerifyApplications@changestatus')->name('bplo.business.change-status');
        Route::get('viewapp', 'BussinessVerifyApplications@viewapp')->name('bplo.business.viewapp');
        Route::post('getrequirements', 'BussinessVerifyApplications@getRequirements')->name('bplo.business.getRequirements');
        Route::post('uploadDocument', 'BussinessVerifyApplications@uploadDocument');
        Route::post('deleteAttachment', 'BussinessVerifyApplications@deleteAttachment');
        Route::post('DeclineAttachment', 'BussinessVerifyApplications@DeclineAttachment');
        Route::post('ActivateAttachment', 'BussinessVerifyApplications@ActivateAttachment');
        Route::post('unclockBusiness', 'ApplicantController@unclockBusiness');

        // This is for Testing Previous Year data, so don't remove this
        Route::post('updateBusinessDateForTest', 'ApplicantController@updateBusinessDateForTest');
        //giolocation by rpt
        Route::post('getLocationsbypropid', 'ApplicantController@getLocationsbypropid');
        Route::any('bulkUpload', 'ApplicantController@bulkUpload');
        Route::post('uploadBulkBusinessData', 'ApplicantController@uploadBulkBusinessData');
        Route::any('downloadBusinessPermitTemplate', 'ApplicantController@downloadBusinessPermitTemplate');
        Route::any('downloadPSICSubclassTemplate', 'ApplicantController@downloadPSICSubclassTemplate');
        Route::any('downloadMeasurePaxTemplate', 'ApplicantController@downloadMeasurePaxTemplate');
        
        
    });
    Route::prefix('directory')->group(function () {
        Route::any('', 'BploAssessmentController@index')->name('bp.bploassessment.index');
    });

      Route::prefix('business-owners')->group(function () {
        Route::any('', 'ProfileController@index')->name('bp.profileuser.index');
        Route::get('getList', 'ProfileController@getList')->name('bp.profileuser.getList');
        Route::any('store', 'ProfileController@store');
        Route::get('getProfileDetails', 'ProfileController@getProfileDetails');
        Route::post('store/formValidation', 'ProfileController@formValidation')->name('bp.profileuser.post');
    });

    Route::prefix('business-permit')->group(function () {
        Route::any('', 'BploBussinessPermitController@index')->name('bp.business-permit.index');
       
    });  
 
    /* Employeee Routes */
});
    
//------------------------- business-permit-------------------------
Route::get('bplobusinesspermit/getList', 'BploBussinessPermitController@getList')->name('bplobusinesspermit.getList');
Route::get('bplobusinesspermit/store', 'BploBussinessPermitController@store');
Route::resource('bplobusinesspermit', 'BploBussinessPermitController')->middleware(['auth','revalidate']);
Route::post('bplobusinesspermit/formValidation', 'BploBussinessPermitController@formValidation')->name('bplobusinesspermit.post');
Route::any('bplobusinesspermitPrint', 'BploBussinessPermitController@bplobusinesspermitPrint');
Route::get('business-permit/business-permit/endorsmentview', 'BploBussinessPermitController@endorsmentview')->name('bplobusinesspermit.endorsmentview');
Route::get('getbploDetails', 'BploBussinessPermitController@getbploappdetails');
Route::get('business-permit/business-permit/{id}', 'BploBussinessPermitController@print_summary')->name('bplo.business.print_summary');
Route::post('uploadAttachment', 'BploBussinessPermitController@uploadAttachment');
Route::post('updateBusinessPermit', 'BploBussinessPermitController@updateBusinessPermit');
Route::post('cancelBusinessPermit', 'BploBussinessPermitController@cancelBusinessPermit');
Route::post('approverBusinessPermit', 'BploBussinessPermitController@approverBusinessPermit');
Route::post('getPermitIsseuDetails', 'BploBussinessPermitController@getPermitIsseuDetails');
Route::post('deleteAttachment', 'BploBussinessPermitController@deleteAttachment');
Route::middleware(['auth'])->prefix('real-property')->group(function () {
    /* Designation Routes */
    Route::prefix('property-owners')->group(function () {
        Route::any('', 'RptPropertyOwnerController@index')->name('bp.property-owners.index');
        Route::get('taxpayer-ajax-request', 'RptPropertyOwnerController@taxPayerAjaxRequest');
        // Route::any('store', 'RptPropertyOwnerController@store')->name('bp.property-owners.store');
        // Route::post('delete', 'RptPropertyOwnerController@Delete');
        // Route::post('ActiveInactive', 'RptPropertyOwnerController@ActiveInactive');
        // Route::get('getProfileDetails', 'RptPropertyOwnerController@getProfileDetails');
        // Route::post('store/formValidation', 'RptPropertyOwnerController@formValidation')->name('bp.property-owners.post');
        // Route::post('printapplication', 'RptPropertyOwnerController@printapplication')->name('property-owners.printapplication');
     });

      Route::prefix('property-data')->group(function () {
        /* Groups Routes */
      Route::prefix('property')->group(function () {
            // Route::get('', 'ComponentMenuGroupController@index')->name('component.menu-group.index');
            // Route::get('lists', 'ComponentMenuGroupController@lists')->name('component.menu-group.lists');
            // Route::post('store', 'ComponentMenuGroupController@store')->name('component.menu-group.store');
            // Route::get('edit/{id}', 'ComponentMenuGroupController@find')->name('component.menu-group.find');
            // Route::put('update/{id}', 'ComponentMenuGroupController@update')->name('component.menu-group.update');
            // Route::put('remove/{id}', 'ComponentMenuGroupController@remove')->name('component.menu-group.remove');
            // Route::put('restore/{id}', 'ComponentMenuGroupController@restore')->name('component.menu-group.restore');
            // Route::put('order/{order}/{id}', 'ComponentMenuGroupController@order')->name('component.menu-sub-module.order');
            Route::get('', 'RptPropertyController@index')->name('rptproperty.index');
        });
         Route::prefix('machinery')->group(function (){
            Route::get('', 'RptPropertyMachineryController@index')->name('machinery.index');
        });
    });
    /* Inquiries Routes */
    Route::prefix('inquiries')->group(function (){
            Route::get('', 'InquiriesByArpNoController@index')->name('inquiries.index');
            Route::get('lists', 'InquiriesByArpNoController@lists')->name('inquiries.lists');
            Route::get('listByTct', 'InquiriesByArpNoController@listByTct')->name('inquiries.listByTct');
            Route::get('listByCct', 'InquiriesByArpNoController@listByCct')->name('inquiries.listByCct');
            Route::get('listByOwn', 'InquiriesByArpNoController@listByOwn')->name('inquiries.listByOwn');
            Route::get('listByBuildKind', 'InquiriesByArpNoController@listByBuildKind')->name('inquiries.listByBuildKind');
            Route::get('listByServey', 'InquiriesByArpNoController@listByServey')->name('inquiries.by-arp-no.listByServey');
            Route::any('printTaxDec', 'InquiriesByArpNoController@printTaxDec')->name('inquiries.printTaxDec');
            Route::any('printFAAS', 'InquiriesByArpNoController@printFAAS')->name('inquiries.printFAAS2');
            Route::any('printFAASBuilding', 'InquiriesByArpNoController@printFAASBuilding')->name('inquiries.printFAAS2');
            Route::any('printTax/{id}', 'InquiriesByArpNoController@printTax')->name('inquiries.printTax2');
            Route::any('pdfFaas/{id}', 'InquiriesByArpNoController@pdfFaas')->name('inquiries.pdfFaas');
            Route::any('pdfFaasBuilding/{id}', 'InquiriesByArpNoController@pdfFaasBuilding')->name('inquiries.pdfFaasBuilding');
    });
    
    
    Route::prefix('property')->group(function () {
            Route::prefix('kind')->group(function (){
                Route::get('', 'RptPropertyKindController@index')->name('kind.index');
                Route::get('getList', 'RptPropertyKindController@getList')->name('kind.getList');
                Route::any('store', 'RptPropertyKindController@store');
                Route::post('ActiveInactive', 'RptPropertyKindController@ActiveInactive');
                Route::post('delete', 'RptPropertyKindController@Delete');
                Route::post('store/formValidation', 'RptPropertyKindController@formValidation')->name('kind.post');
           });
             Route::prefix('class')->group(function (){
                Route::get('', 'RptPropertyClassController@index')->name('class.index');
                Route::get('getList', 'RptPropertyClassController@getList')->name('class.getList');
                Route::any('store', 'RptPropertyClassController@store');
                Route::any('subclasssStore', 'RptPropertyClassController@subclasssStore');
                Route::any('actualUseStore', 'RptPropertyClassController@actualUseStore');
                Route::post('ActiveInactive', 'RptPropertyClassController@ActiveInactive');
                Route::post('delete', 'RptPropertyClassController@Delete');
                Route::post('store/formValidation', 'RptPropertyClassController@formValidation')->name('class.post');
                Route::post('subclasssStore/formValidation', 'RptPropertyClassController@formValidationSubclass')->name('class.post');
                Route::post('actualUseStore/formValidation', 'RptPropertyClassController@formValidationActualUse')->name('class.post');
           });
              Route::prefix('sub-class')->group(function (){
                Route::get('', 'RptPropertySubclassificationController@index')->name('sub-classproperty.index');
           });
               Route::prefix('actual-use')->group(function (){
                Route::get('', 'RptPropertyActualUseController@index')->name('propertyactualuse.index');
           });
            
        });  

    /* Employeee Routes */
});






Route::get('rptproperty/index', 'RptPropertyController@index')->name('rptproperty.index');
Route::get('rptproperty/getList', 'RptPropertyController@getList')->name('rptproperty.getList');
Route::get('rptproperty/store', 'RptPropertyController@store');

Route::post('rptproperty/savegeolocationdata', 'RptPropertyController@savegeolocationdata');
Route::post('rptproperty/uploadDocument', 'RptPropertyController@uploadDocument');
Route::post('rptproperty/deleteAttachment', 'RptPropertyController@deleteAttachment');
Route::post('rptproperty/deletelocationlink', 'RptPropertyController@deletelocationlink');
Route::post('rptproperty/getLocationsbypropid', 'RptPropertyController@getLocationsbypropid');
Route::post('rptproperty/verifypsw', 'RptPropertyController@verifyPasswordToUpdate');
// ------------------------------------- RptPropertyClass ------------------------------
// Route::get('rptpropertyclass/index', 'RptPropertyClassController@index')->name('rptpropertyclass.index');
// Route::get('rptpropertyclass/getList', 'RptPropertyClassController@getList')->name('rptpropertyclass.getList');
// Route::get('rptpropertyclass/store', 'RptPropertyClassController@store');
// Route::post('rptpropertyclass/ActiveInactive', 'RptPropertyClassController@ActiveInactive');
// Route::post('rptpropertyclass/delete', 'RptPropertyClassController@Delete');
// Route::resource('rptpropertyclass', 'RptPropertyClassController')->middleware(['auth','revalidate']);
Route::post('rptpropertyclass/formValidation', 'RptPropertyClassController@formValidation')->name('rptpropertyclass.post');

/*Property Building */
/* Duplicate Copy */
Route::post('rptbuilding/dup', 'RptPropertyBuidingController@dupFunctionlaity');
Route::post('rptbuilding/dup/submit', 'RptPropertyBuidingController@dupFunctionlaitySbubmit');
/* Duplicate Copy */

/* Dispute and Removed */
Route::post('rptbuilding/dp', 'RptPropertyBuidingController@dpFunctionlaity');
Route::post('rptbuilding/dp/submit', 'RptPropertyBuidingController@dpFunctionlaitySbubmit');
/* Dispute and Removed */

/* Transfer Of Ownership*/
Route::post('rptbuilding/tr', 'RptPropertyBuidingController@trFunctionlaity');
Route::post('rptbuilding/tr/submit', 'RptPropertyBuidingController@trFunctionlaitySbubmit');
/* Transfer Of Ownership*/

/* Superseded */
Route::post('rptbuilding/ssd', 'RptPropertyBuidingController@ssdFunctionlaity');
Route::post('rptbuilding/ssd/submit', 'RptPropertyBuidingController@ssdFunctionlaitySbubmit');
/* Superseded */

/* Reclassification */
Route::post('rptbuilding/rc', 'RptPropertyBuidingController@rcFunctionlaity');
Route::post('rptbuilding/rc/submit', 'RptPropertyBuidingController@rcFunctionlaitySbubmit');
/* Reclassification */

/* Physical Changes */
Route::post('rptbuilding/pc', 'RptPropertyBuidingController@pcFunctionlaity');
Route::post('rptbuilding/pc/submit', 'RptPropertyBuidingController@pcFunctionlaitySbubmit');
/* Physical Changes */

/* Consolidation  */
Route::post('rptbuilding/cs', 'RptPropertyBuidingController@csFunctionlaity');
Route::post('rptbuilding/cs/addtaxdeclarationinlist', 'RptPropertyBuidingController@addTaxDeclarationInList');
Route::post('rptbuilding/cs/loadtaxdecltoconsoldate', 'RptPropertyBuidingController@loadTaxDeclToConsolidate');
Route::post('rptbuilding/cs/deletetaxdeclaration', 'RptPropertyBuidingController@csDeleteTaxDeclaration');
Route::post('rptbuilding/cs/submit', 'RptPropertyBuidingController@csFunctionlaitySbubmit');
/* Consolidation */

/* Subdivision */
Route::post('rptbuilding/sd', 'RptPropertyBuidingController@sdFunctionlaity');
Route::get('rptbuilding/sd/loadfloorvalues', 'RptPropertyBuidingController@sdLoadFloorValues');
Route::get('rptbuilding/sd/loadnewtdfloorvalues', 'RptPropertyBuidingController@loadNewTdFloorValues');
Route::post('rptbuilding/sd/step2', 'RptPropertyBuidingController@sdFunctionlaitySecondStep');
Route::post('rptbuilding/sd/getlisting', 'RptPropertyBuidingController@sdgetListing');
Route::post('rptbuilding/sd/submit', 'RptPropertyBuidingController@sdFunctionlaitySbubmit');
Route::post('rptbuilding/sd/deletetaxdeclaration', 'RptPropertyBuidingController@sdDeleteTaxDeclaration');
Route::post('rptbuilding/sd/updateTaxDeclaration', 'RptPropertyBuidingController@sdUpdateTaxDeclaration');
/* Subdivision */

/* Load Previous Owner Details */
//Route::post('rptproperty/loadpreviousowner', 'RptPropertyController@loadPreviousOwner');
Route::any('rptbuilding/loadpreviousowner', 'RptPropertyBuidingController@loadPreviousOwner');
Route::get('rptbuilding/getpreviousownertddetails', 'RptPropertyBuidingController@getPreviousOwnertdDetails');
Route::post('rptbuilding/deletepreviousownertd', 'RptPropertyBuidingController@deletePreviousOwnerTd');
/* Load Previous Owner Details */

/* Bulk Upload data for building */
Route::any('rptbuilding/bulkUpload', 'RptPropertyBuidingController@bulkUpload');
Route::any('rptbuilding/downloadbuildTDTemplate', 'RptPropertyBuidingController@downloadbuildTDTemplate');
Route::post('rptbuilding/uploadBulkBuildData', 'RptPropertyBuidingController@uploadBulkBuildData');
Route::any('rptbuilding/downloadBuildAppraisalTemplate', 'RptPropertyBuidingController@downloadBuildAppraisalTemplate');
/* Bulk Upload data for building */

Route::get('rptbuilding/getremotedataforpermits', 'RptPropertyBuidingController@getBuildPermitRemoteSelect');
Route::get('rptbuilding/fllorvaluedepreciation', 'RptPropertyBuidingController@fllorValueDepreciation');
Route::get('rptbuilding/loadassessementsummary', 'RptPropertyBuidingController@loadAssessementSummary');
Route::get('rptbuilding/storefloorvalue', 'RptPropertyBuidingController@showFloorValueForm');
Route::post('rptbuilding/storefloorvalue', 'RptPropertyBuidingController@storeFloorValue');
Route::post('rptbuilding/getfloorvalues', 'RptPropertyBuidingController@getFloorValues');
Route::post('rptbuilding/deletefloorvalue', 'RptPropertyBuidingController@deleteFloorValue');
Route::get('rptbuilding/index', 'RptPropertyBuidingController@index')->name('rptbuilding.index');
Route::get('rptbuilding/get-all-profiles', 'RptPropertyBuidingController@getAllProfiles')->name('rptbuilding.index');
Route::get('rptbuilding/getList', 'RptPropertyBuidingController@getList')->name('rptbuilding.getList');
Route::get('rptbuilding/fllorvalue', 'RptPropertyBuidingController@fllorvalue');
Route::get('rptbuilding/anootationspeicalpropertystatus', 'RptPropertyBuidingController@anootationSpeicalPropertystatus');
Route::post('rptbuilding/anootationspeicalpropertystatus', 'RptPropertyBuidingController@storeAnootationSpeicalPropertystatus');
Route::get('rptbuilding/swornstatment', 'RptPropertyBuidingController@swornStatment');
Route::post('rptbuilding/swornstatment', 'RptPropertyBuidingController@storeSwornStatment');
Route::post('rptbuilding/deleteannotaion', 'RptPropertyBuidingController@deleteAnnotaion');
Route::get('rptbuilding/loadpropertyannotations', 'RptPropertyBuidingController@loadPropertyAnnotations');
Route::get('rptbuilding/store', 'RptPropertyBuidingController@store');
Route::get('rptbuilding/approve', 'RptPropertyBuidingController@Approve');
Route::post('rptbuilding/approve', 'RptPropertyBuidingController@storeApprove');
Route::post('rptbuilding/searchland', 'RptPropertyBuidingController@searchLand');
Route::get('rptbuilding/storetressadjustmentfactor', 'RptPropertyBuidingController@storePlantsAdjustmentFactor');
Route::post('rptbuilding/getmachineryappraisal', 'RptPropertyBuidingController@getBuidingAppraisal');
Route::post('rptbuilding/getassessmentsummarylisting', 'RptPropertyBuidingController@getAssessmentSummaryListing');
Route::post('rptbuilding/storeBuildingfloorval', 'RptPropertyBuidingController@storeBuildingfloorval');
Route::post('rptbuilding/storebuildingstructure', 'RptPropertyBuidingController@storebuildingstructure');
Route::get('rptbuilding/autofillmainform', 'RptPropertyBuidingController@autoFillMainForm');
Route::get('rptbuilding/generatepinsuffix', 'RptPropertyBuidingController@createPinSuffix');
Route::resource('rptbuilding', 'RptPropertyBuidingController')->middleware(['auth','revalidate']);
Route::post('rptbuilding/formValidation', 'RptPropertyBuidingController@formValidation')->name('rptbuilding.post');
Route::post('rptbuilding/getbuildingunitvalue', 'RptPropertyBuidingController@getBuildingUnitValue');
Route::post('getTaxDeclaresionNODetails', 'RptPropertyBuidingController@getTaxDeclaresionNODetails');
Route::post('getTaxDeclaresionNODetailsAll', 'RptPropertyBuidingController@getTaxDeclaresionNODetailsAll');
Route::get('taxDeclarationsId', 'RptPropertyBuidingController@taxDeclarationsId');
Route::get('rptbuilding/getalltds', 'RptPropertyBuidingController@getAllTds')->name('build.getAllTds');


/*Property Machinery */
/* Duplicate Copy */
Route::get('rptmachinery/loadpreviousowner', 'RptPropertyMachineryController@loadPreviousOwner');
Route::post('rptmachinery/dup', 'RptPropertyMachineryController@dupFunctionlaity');
Route::post('rptmachinery/dup/submit', 'RptPropertyMachineryController@dupFunctionlaitySbubmit');
/* Duplicate Copy */
Route::post('getTaxDeclaresionNOBuildingDetails', 'RptPropertyMachineryController@getTaxDeclaresionNOBuildingDetails');
Route::post('getTaxDeclaresionNODetailsBuildingAll', 'RptPropertyMachineryController@getTaxDeclaresionNODetailsBuildingAll');
Route::post('getTaxDeclaresionNOLandDetails', 'RptPropertyMachineryController@getTaxDeclaresionNOLandDetails');
Route::post('getTaxDeclaresionNODetailsLandAll', 'RptPropertyMachineryController@getTaxDeclaresionNODetailsLandAll');
Route::post('getAdmistrativeDetails', 'RptPropertyMachineryController@getAdmistrativeDetails');
/* Dispute and Removed */
Route::post('rptmachinery/dp', 'RptPropertyMachineryController@dpFunctionlaity');
Route::post('rptmachinery/dp/submit', 'RptPropertyMachineryController@dpFunctionlaitySbubmit');
Route::get('rptmachinery/get-all-profiles', 'RptPropertyMachineryController@getAllProfile');
/* Dispute and Removed */

/* Transfer Of Ownership*/
Route::post('rptmachinery/tr', 'RptPropertyMachineryController@trFunctionlaity');
Route::post('rptmachinery/tr/submit', 'RptPropertyMachineryController@trFunctionlaitySbubmit');
/* Transfer Of Ownership*/

/* Superseded */
Route::post('rptmachinery/ssd', 'RptPropertyMachineryController@ssdFunctionlaity');
Route::post('rptmachinery/ssd/submit', 'RptPropertyMachineryController@ssdFunctionlaitySbubmit');
/* Superseded */

/* Reclassification */
Route::post('rptmachinery/rc', 'RptPropertyMachineryController@rcFunctionlaity');
Route::post('rptmachinery/rc/submit', 'RptPropertyMachineryController@rcFunctionlaitySbubmit');
/* Reclassification */

/* Physical Changes */
Route::post('rptmachinery/pc', 'RptPropertyMachineryController@pcFunctionlaity');
Route::post('rptmachinery/pc/submit', 'RptPropertyMachineryController@pcFunctionlaitySbubmit');
/* Physical Changes */

/* Consolidation  */
Route::post('rptmachinery/cs', 'RptPropertyMachineryController@csFunctionlaity');
Route::post('rptmachinery/cs/addtaxdeclarationinlist', 'RptPropertyMachineryController@addTaxDeclarationInList');
Route::post('rptmachinery/cs/loadtaxdecltoconsoldate', 'RptPropertyMachineryController@loadTaxDeclToConsolidate');
Route::post('rptmachinery/cs/deletetaxdeclaration', 'RptPropertyMachineryController@csDeleteTaxDeclaration');
Route::post('rptmachinery/cs/submit', 'RptPropertyMachineryController@csFunctionlaitySbubmit');
/* Consolidation */

/* Subdivision */
Route::post('rptmachinery/sd', 'RptPropertyMachineryController@sdFunctionlaity');
Route::get('rptmachinery/sd/loadmachineappraisals', 'RptPropertyMachineryController@sdLoadMachineAppraisals');
Route::get('rptmachinery/sd/loadnewtdmachineappra', 'RptPropertyMachineryController@loadNewTdMachineAppra');
Route::post('rptmachinery/sd/step2', 'RptPropertyMachineryController@sdFunctionlaitySecondStep');
Route::post('rptmachinery/sd/getlisting', 'RptPropertyMachineryController@sdgetListing');
Route::post('rptmachinery/sd/submit', 'RptPropertyMachineryController@sdFunctionlaitySbubmit');
Route::post('rptmachinery/sd/deletetaxdeclaration', 'RptPropertyMachineryController@sdDeleteTaxDeclaration');
Route::post('rptmachinery/sd/updateTaxDeclaration', 'RptPropertyMachineryController@sdUpdateTaxDeclaration');
/* Subdivision */


/* Load Previous Owner Details */
//Route::post('rptproperty/loadpreviousowner', 'RptPropertyController@loadPreviousOwner');
Route::any('rptmachinery/loadpreviousowner', 'RptPropertyMachineryController@loadPreviousOwner');
Route::get('rptmachinery/getpreviousownertddetails', 'RptPropertyMachineryController@getPreviousOwnertdDetails');
Route::post('rptmachinery/deletepreviousownertd', 'RptPropertyMachineryController@deletePreviousOwnerTd');
/* Load Previous Owner Details */

/* Bulk Upload Machinery Data*/
Route::any('rptmachinery/bulkUpload', 'RptPropertyMachineryController@bulkUpload');
Route::any('rptmachinery/downloadmachineTDTemplate', 'RptPropertyMachineryController@downloadmachineTDTemplate');
Route::post('rptmachinery/uploadBulkMachineData', 'RptPropertyMachineryController@uploadBulkMachineData');
Route::any('rptmachinery/downloadMachineAppraisalTemplate', 'RptPropertyMachineryController@downloadMachineAppraisalTemplate');
/* Bulk Upload Machinery Data*/

Route::post('rptmachinery/deleteannotaion', 'RptPropertyMachineryController@deleteAnnotaion');
Route::get('rptmachinery/anootationspeicalpropertystatus', 'RptPropertyMachineryController@anootationSpeicalPropertystatus');
Route::post('rptmachinery/anootationspeicalpropertystatus', 'RptPropertyMachineryController@storeAnootationSpeicalPropertystatus');
Route::get('rptmachinery/loadpropertyannotations', 'RptPropertyMachineryController@loadPropertyAnnotations');
Route::get('rptmachinery/swornstatment', 'RptPropertyMachineryController@swornStatment');
Route::post('rptmachinery/swornstatment', 'RptPropertyMachineryController@storeSwornStatment');
Route::get('rptmachinery/approve', 'RptPropertyMachineryController@Approve');
Route::post('rptmachinery/approve', 'RptPropertyMachineryController@storeApprove');
Route::post('rptmachinery/deletemachineappraisal', 'RptPropertyMachineryController@deleteMachineAppraisal');
Route::get('rptmachinery/loadassessementsummary', 'RptPropertyMachineryController@loadAssessementSummary');
Route::get('rptmachinery/storemachineappraisal', 'RptPropertyMachineryController@showMachineAppraisalForm');
Route::post('rptmachinery/storemachineappraisal', 'RptPropertyMachineryController@storeMachineAppraisal');
Route::get('rptmachinery/index', 'RptPropertyMachineryController@index')->name('rptmachinery.index');
Route::get('rptmachinery/getList', 'RptPropertyMachineryController@getList')->name('rptmachinery.getList');
Route::post('rptmachinery/searchlandorbuilding', 'RptPropertyMachineryController@searchLandOrBuilding');
Route::get('rptmachinery/store', 'RptPropertyMachineryController@store');
Route::get('rptmachinery/storetressadjustmentfactor', 'RptPropertyMachineryController@storePlantsAdjustmentFactor');
Route::post('rptmachinery/getmachineryappraisal', 'RptPropertyMachineryController@getMachineryAppraisal');
Route::post('rptmachinery/getassessmentsummarylisting', 'RptPropertyMachineryController@getAssessmentSummaryListing');
Route::get('rptmachinery/generatepinsuffix', 'RptPropertyMachineryController@createPinSuffix');
Route::resource('rptmachinery', 'RptPropertyMachineryController')->middleware(['auth','revalidate']);
Route::post('rptmachinery/formValidation', 'RptPropertyMachineryController@formValidation')->name('rptmachinery.post');



//-------------------------------- General Revision -----------------------
Route::middleware(['auth'])->prefix('real-property')->group(function () {
Route::prefix('property-data')->group(function () {
        /* Groups Routes */
      Route::prefix('generalrevision')->group(function () {
            Route::get('', 'GeneralRevisionController@index')->name('generalrevision.index');
        });
    });
});
Route::post('generalrevision/landunitvaluelisting', 'GeneralRevisionController@landUnitValueListing');
Route::post('generalrevision/planttreesunitvaluelisting', 'GeneralRevisionController@plantTreesUnitValueListing');
Route::post('generalrevision/loadtaxdeclarations', 'GeneralRevisionController@loadRelatedTds');
Route::post('generalrevision/loaddraftedtaxdeclarations', 'GeneralRevisionController@loadDraftedTaxDeclarations');
Route::get('generalrevision/store', 'GeneralRevisionController@store');
Route::get('generalrevision/getList', 'GeneralRevisionController@getList');
Route::post('generalrevision/reviseorrollback', 'GeneralRevisionController@reviseOrRollback');
Route::post('generalrevision/store', 'GeneralRevisionController@storeData');
Route::post('generalrevision/showlandunitvaluescheduleview', 'GeneralRevisionController@showLandUnitValueScheduleView');
Route::post('generalrevision/showplantstreesunitvaluescheduleview', 'GeneralRevisionController@showPlantsTreesUnitValueScheduleView');
Route::post('generalrevision/showbuildingunitvaluescheduleview', 'GeneralRevisionController@showBuildingUnitValueScheduleView');
Route::post('generalrevision/buildingunitvaluelisting', 'GeneralRevisionController@buildingunitvaluelisting');
Route::post('generalrevision/showassessmentscheduleview', 'GeneralRevisionController@showAssessmentScheduleView');
Route::post('generalrevision/assessementlevellisting', 'GeneralRevisionController@assessementLevelListing');

//-------------------------------- Billing Form -----------------------
Route::middleware(['auth'])->prefix('treasurer')->group(function () {
Route::prefix('property-billing')->group(function () {
        /* Groups Routes */
      Route::prefix('single-property-billing')->group(function () {
            Route::get('', 'BillingFormController@index')->name('billing.index');
        });
      Route::prefix('multiple-property-billing')->group(function () {
            Route::get('', 'BillingFormController@multiplePropertyIndex')->name('billing.multipleindex');
        });
    });
});
Route::get('billingform/store', 'BillingFormController@store')->name('billing.showform');
Route::post('billingform/store', 'BillingFormController@storeData')->name('billing.store');
Route::get('billingform/getList', 'BillingFormController@getList');
Route::post('billingform/searchbytd', 'BillingFormController@searchByTdNo');
Route::post('billingform/getbarangaybyid', 'BillingFormController@getbarangaybyid');
Route::post('billingform/ActiveInactive', 'BillingFormController@deleteRow');
Route::get('billingform/computebillingdata', 'BillingFormController@computeBillingData');
Route::post('billingform/genratebill', 'BillingFormController@genrateBill');
Route::post('billingform/delete', 'BillingFormController@deleteRow');
Route::get('billingform/show', 'BillingFormController@show');
Route::get('billingform/printbill/{id}', 'BillingFormController@printBill');
Route::post('billingform/storRemoteRptBillReceipt', 'BillingFormController@storRemoteRptBillReceipt');
Route::post('billingform/storRemoteRptOnlineAccess', 'BillingFormController@storRemoteRptOnlineAccess');

/* Multiple property billing */
Route::get('billingform/storemultiple', 'BillingFormController@storeMultiple')->name('billing.showmultiplebillingform');
Route::get('billingform/getListmultiple', 'BillingFormController@getMultipleList');
Route::get('billingform/loadmultipleprops', 'BillingFormController@loadMultipleProps');
Route::post('billingform/updateowner', 'BillingFormController@updateOwner');
Route::get('billingform/multiplepropertiesprintbill/{con_num}', 'BillingFormController@printBillForMultiple');
/* Multiple property billing */


//-------------------------------- TAX CLEARANCE -----------------------
Route::middleware(['auth'])->prefix('treasurer')->group(function () {
Route::prefix('tax-clearance')->group(function (){
        Route::get('', 'TaxClearanceController@index')->name('taxclearance.index');
    });
});


Route::get('taxclearance/store', 'TaxClearanceController@store')->name('taxclearance.showform');
Route::get('taxclearance/getpaymentlist', 'TaxClearanceController@getPaymentList')->name('taxclearance.getpaymentlist');
Route::get('taxclearance/getList', 'TaxClearanceController@getList');
Route::post('taxclearance/searchbytd', 'TaxClearanceController@searchByTdNo');
Route::get('taxclearance/loadselectedtds', 'TaxClearanceController@loadSelectedTds');
Route::post('taxclearance/deletedtd', 'TaxClearanceController@deleteSelectedTd');
Route::post('taxclearance/store', 'TaxClearanceController@storeData')->name('taxclearance.store');
Route::get('taxclearance/print/{c_no}', 'TaxClearanceController@print');
Route::get('getClientTaxClearance', 'TaxClearanceController@getClientTaxClearance');
Route::post('getTDNoTaxClearance', 'TaxClearanceController@getTDNoTaxClearance');
Route::post('getTDNoTaxClearanceAll', 'TaxClearanceController@getTDNoTaxClearanceAll');
Route::get('getPropertyClientName', 'TaxClearanceController@getPropertyClientName');
Route::get('taxclearance/gethremployees', 'TaxClearanceController@gethremployees');
Route::post('getOrNoForOwner', 'TaxClearanceController@getOrNoForOwner');
Route::get('getEmployeeTaxApproved', 'TaxClearanceController@getEmployeeTaxApproved');
Route::get('getEmployeeTaxRecommendin', 'TaxClearanceController@getEmployeeTaxRecommendin');
Route::get('tax-clearance/gettdsforajaxselectlist', 'TaxClearanceController@gettdsforajaxselectlist');
//-------------------------------- TAX CLEARANCE -----------------------


//-------------------------------- Bplo PERMIT lICENING-----------------------

// Route::get('rptpropertykind/index', 'RptPropertyKindController@index')->name('rptpropertykind.index');
// Route::get('rptpropertykind/getList', 'RptPropertyKindController@getList')->name('rptpropertykind.getList');
// Route::get('rptpropertykind/store', 'RptPropertyKindController@store');
// Route::post('rptpropertykind/ActiveInactive', 'RptPropertyKindController@ActiveInactive');
// Route::post('rptpropertykind/delete', 'RptPropertyKindController@Delete');
// Route::resource('rptpropertykind', 'RptPropertyKindController')->middleware(['auth','revalidate']);
Route::post('rptpropertykind/formValidation', 'RptPropertyKindController@formValidation')->name('rptpropertykind.post');

// Route::get('bplobusinesspermit', 'BploBussinessPermitController@index')->name('bplobusinesspermit.index');

// Route::get('/index', 'ProfileController@index')->name('profileuser.index');
 Route::get('profileuser/store', 'ProfileController@store');
// Route::resource('profileuser', 'ProfileController')->middleware(['auth','revalidate']);
// Route::post('profileuser/formValidation', 'ProfileController@formValidation')->name('profileuser.post');

// ------------------------------------- RptPropertyOwner------------------------------
Route::post('rptpropertyowner-uploadDocument', 'RptPropertyOwnerController@uploadDocument')->name('rptpropertyowner.uploadDocument');
Route::post('rptpropertyowner-deleteAttachment', 'RptPropertyOwnerController@deleteAttachment')->name('rptpropertyowner.deleteAttachment');
//Route::get('rptpropertyowner/index', 'RptPropertyOwnerController@index')->name('rptpropertyowner.index');
Route::post('rptpropertyowner/delete', 'RptPropertyOwnerController@Delete');
Route::get('rptpropertyowner/getClientsDetails', 'RptPropertyOwnerController@getClientsDetails');
Route::get('getProfileDetails', 'RptPropertyOwnerController@getProfileDetails');
Route::get('rptpropertyowner/getClientsDetails', 'RptPropertyOwnerController@getClientsDetails');
Route::post('rptpropertyowner/ActiveInactive', 'RptPropertyOwnerController@ActiveInactive');
Route::get('rptpropertyowner/getList', 'RptPropertyOwnerController@getList')->name('rptpropertyowner.getList');
Route::get('rptpropertyowner/getallclients', 'RptPropertyOwnerController@getAllClients');
Route::any('rptpropertyowner/store', 'RptPropertyOwnerController@store');
Route::resource('rptpropertyowner', 'RptPropertyOwnerController')->middleware(['auth','revalidate']);
Route::post('rptpropertyowner/formValidation', 'RptPropertyOwnerController@formValidation')->name('rptpropertyowner.post');

// ------------------------------------- EngClients------------------------------
Route::post('engclients-uploadDocument', 'Engneering\EngClientsController@uploadDocument')->name('engclients.uploadDocument');
Route::post('engclients-deleteAttachment', 'Engneering\EngClientsController@deleteAttachment')->name('engclients.deleteAttachment');
//Route::get('engclients/index', 'Engneering\EngClientsController@index')->name('engclients.index');
Route::post('engclients/delete', 'Engneering\EngClientsController@Delete');
Route::get('engclients/getClientsDetails', 'Engneering\EngClientsController@getProfileDetails');
Route::post('engclients/ActiveInactive', 'Engneering\EngClientsController@ActiveInactive');
Route::get('engclients/getList', 'Engneering\EngClientsController@getList')->name('engclients.getList');
Route::any('engclients/store', 'Engneering\EngClientsController@store');
Route::resource('engclients', 'Engneering\EngClientsController')->middleware(['auth','revalidate']);
Route::post('engclients/formValidation', 'Engneering\EngClientsController@formValidation')->name('engclients.post');
Route::prefix('fire-protection-taxpayers')->group(function () {
       Route::get('', 'FireClientsController@index')->name('fireclients.index');
     });
// ------------------------------------- FireClients------------------------------
Route::post('fireclients-uploadDocument', 'FireClientsController@uploadDocument')->name('fireclients.uploadDocument');
Route::post('fireclients-deleteAttachment', 'FireClientsController@deleteAttachment')->name('fireclients.deleteAttachment');
// Route::get('fireclients/index', 'FireClientsController@index')->name('fireclients.index');
Route::post('fireclients/delete', 'FireClientsController@Delete');
Route::get('fireclients/getClientsDetails', 'FireClientsController@getProfileDetails');
Route::post('fireclients/ActiveInactive', 'FireClientsController@ActiveInactive');
Route::get('fireclients/getList', 'FireClientsController@getList')->name('fireclients.getList');
Route::any('fireclients/store', 'FireClientsController@store');
Route::post('fireclients/formValidation', 'FireClientsController@formValidation')->name('fireclients.post');
Route::resource('fireclients', 'FireClientsController')->middleware(['auth','revalidate']);


// ------------------------------------- Tax Category ------------------------------
// Route::get('business-permit/allaplicant', 'allaplicantController@index')->name('allaplicant.index');
// Route::get('allaplicant/store', 'allaplicantController@store');
// Route::get('allaplicant/getList', 'allaplicantController@getList')->name('allaplicant.getList');
// Route::resource('allaplicant', 'allaplicantController')->middleware(['auth','revalidate']);
// Route::post('allaplicant/formValidation', 'allaplicantController@formValidation')->name('allaplicant.post');
// Route::get('getprofilesallapplicant', 'allaplicantController@getprofiles');
// ------------------------------------- RPT Prpoerty ActualUse ------------------------------
//Route::get('rptpropertyactualuse/index', 'RptPropertyActualUseController@index')->name('rptpropertyactualuse.index');
Route::get('rptpropertyactualuse/getList', 'RptPropertyActualUseController@getList')->name('rptpropertyactualuse.getList');
Route::get('rptpropertyactualuse/store', 'RptPropertyActualUseController@store');
Route::post('rptpropertyactualuse/ActiveInactive', 'RptPropertyActualUseController@ActiveInactive');
Route::post('rptpropertyactualuse/delete', 'RptPropertyActualUseController@Delete');
Route::resource('rptpropertyactualuse', 'RptPropertyActualUseController')->middleware(['auth','revalidate']);
Route::post('rptpropertyactualuse/formValidation', 'RptPropertyActualUseController@formValidation')->name('rptpropertyactualuse.post');

// ------------------------------------- RptPropertySubclass ------------------------------
// Route::get('rptPropertysubclassification/index', 'RptPropertySubclassificationController@index')->name('rptPropertysubclassification.index');
Route::get('rptPropertysubclassification/getList', 'RptPropertySubclassificationController@getList')->name('rptPropertysubclassification.getList');
Route::get('rptPropertysubclassification/store', 'RptPropertySubclassificationController@store');
Route::post('rptPropertysubclassification/ActiveInactive', 'RptPropertySubclassificationController@ActiveInactive');
Route::post('rptPropertysubclassification/delete', 'RptPropertySubclassificationController@Delete');
Route::resource('rptPropertysubclassification', 'RptPropertySubclassificationController')->middleware(['auth','revalidate']);
Route::post('rptPropertysubclassification/formValidation', 'RptPropertySubclassificationController@formValidation')->name('rptPropertysubclassification.post');


// Route::get('taxcategory/index', 'TaxCategoryController@index')->name('taxcategory.index');
// Route::get('taxcategory/store', 'TaxCategoryController@store');
// Route::get('taxcategory/getList', 'TaxCategoryController@getList')->name('taxcategory.getList');
// Route::post('taxcategory/ActiveInactive', 'TaxCategoryController@ActiveInactive');
// Route::resource('taxcategory', 'TaxCategoryController')->middleware(['auth','revalidate']);
// Route::post('taxcategory/formValidation', 'TaxCategoryController@formValidation')->name('taxcategory.post');
// ------------------------------------- Tax Type ------------------------------
// Route::get('taxtype/index', 'TaxtypeController@index')->name('taxtype.index');
// Route::get('taxtype/store', 'TaxtypeController@store');
// Route::get('taxtype/getList', 'TaxtypeController@getList')->name('taxtype.getList');
// Route::post('taxtype/ActiveInactive', 'TaxtypeController@ActiveInactive');
// Route::resource('taxtype', 'TaxtypeController')->middleware(['auth','revalidate']);
// Route::post('taxtype/formValidation', 'TaxtypeController@formValidation')->name('taxtype.post');
// ------------------------------------- Tax Class ------------------------------
Route::get('taxclass/index', 'TaxClassController@index')->name('taxclass.index');
Route::get('taxclass/getList', 'TaxClTaxClassControllerassController@getList')->name('taxclass.getList');
Route::get('taxclass/store', 'TaxClassController@store');
Route::post('taxclass/ActiveInactive', 'TaxClassController@ActiveInactive');
Route::resource('taxclass', 'TaxClassController')->middleware(['auth','revalidate']);
Route::post('taxclass/formValidation', 'TaxClassController@formValidation')->name('taxclass.post');

// ------------------------------------- PSIC Class ------------------------------
Route::get('psicclass/index', 'PsicClassController@index')->name('psicclass.index');
Route::get('psicclass/getList', 'PsicClassController@getList')->name('psicclass.getList');
Route::get('psicclass/store', 'PsicClassController@store');
Route::resource('psicclass', 'PsicClassController')->middleware(['auth','revalidate']);
Route::post('psicclass/formValidation', 'PsicClassController@formValidation')->name('psicclass.post');

// ----------------------------- BPLO Business Classification ----------------------------

// ----------------------------- BFP Inspection Order  ----------------------------

Route::post('bfpinspectionorder/deleteEndrosmentInspectionAttachment', 'BfpInspectionOrderController@deleteEndrosmentInspectionAttachment');
Route::post('bfpinspectionorder/uploadAttachmentInspection', 'BfpInspectionOrderController@uploadAttachmentInspection');
Route::get('bfpinspectionorder', 'BfpInspectionOrderController@index')->name('bfpinspectionorder.index');
Route::get('bfpinspectionorder/getList', 'BfpInspectionOrderController@getList')->name('bfpinspectionorder.getList');
Route::post('bfpinspectionorder-approvedsataus', 'BfpInspectionOrderController@approvedsataus');
Route::post('bfpinspectionorder-biorecommendingapproval', 'BfpInspectionOrderController@biorecommendingapproval');
Route::get('bfpinspectionorder/getPosition', 'BfpInspectionOrderController@getPosition');
Route::post('bfpinspectionorder/ActiveInactive', 'BfpInspectionOrderController@ActiveInactive');
Route::get('getAccounrnumber', 'BfpInspectionOrderController@getAccountnumber');
Route::any('inspectionPrint', 'BfpInspectionOrderController@inspectionPrint');
Route::get('getBusinessId', 'BfpInspectionOrderController@getBusinessId');
Route::get('bfpinspectionorder/store', 'BfpInspectionOrderController@store');
Route::resource('bfpinspectionorder', 'BfpInspectionOrderController')->middleware(['auth','revalidate']);
Route::post('bfpinspectionorder/formValidation', 'BfpInspectionOrderController@formValidation')->name('bfpinspectionorder.post');
// ------------------------------------- environmental-inspection-report ------------------------------
Route::any('environmental-inspection-report/printreport', 'EnvironmentalInspectionReport@printreport');
//Route::post('environmental-inspection-report/printreportsss', 'EnvironmentalInspectionReport@printreportajax');
Route::post('environmental/deleteEndrosmentInspectionAttachment', 'EnvironmentalInspectionReport@deleteEndrosmentInspectionAttachment');
Route::post('environmental/uploadAttachmentInspection', 'EnvironmentalInspectionReport@uploadAttachmentInspection');
Route::get('environmental-inspection-report', 'EnvironmentalInspectionReport@index')->name('environmental-inspection-report.index');
Route::post('environmental-positionbyid', 'EnvironmentalInspectionReport@positionbyid');
Route::get('environmental-inspection-report/store', 'EnvironmentalInspectionReport@store');
Route::post('environmental-inspection-report/ActiveInactive', 'EnvironmentalInspectionReport@ActiveInactive');
Route::get('environmental-inspection-report/getList', 'EnvironmentalInspectionReport@getList')->name('locationclearance.getList');
Route::get('environmental-inspection-report/getClientsDetails', 'EnvironmentalInspectionReport@getClientsDetails');
Route::resource('environmental-inspection-report', 'EnvironmentalInspectionReport')->middleware(['auth','revalidate']);
Route::post('environmental-inspection-report/formValidation', 'EnvironmentalInspectionReport@formValidation')->name('locationclearance.post');
// ------------------------------------- environmental-clearance ------------------------------
Route::get('environmental-clearance/printreport','EnvironmentalClearance@printreport');
Route::any('envirclearance/EnvirClearance', 'EnvironmentalClearance@printreportajax');
//Route::post('environmental-clearance/printreportsss', 'EnvironmentalClearance@printreportajax');
Route::post('clearance/deleteEndrosmentInspectionAttachment', 'EnvironmentalClearance@deleteEndrosmentInspectionAttachment');
Route::post('clearance/uploadAttachmentInspection', 'EnvironmentalClearance@uploadAttachmentInspection');
Route::get('environmental-clearance','EnvironmentalClearance@index')->name('environmentalclearance.index');
Route::post('environmental-clearance-positionbyid','EnvironmentalClearance@positionbyid');
Route::get('environmental-clearance/store','EnvironmentalClearance@store');
Route::post('environmental-clearance/ActiveInactive','EnvironmentalClearance@ActiveInactive');
Route::get('environmental-clearance/getList','EnvironmentalClearance@getList')->name('environmentalclearance.getList');
Route::get('environmental-clearance/getClientsDetails','EnvironmentalClearance@getClientsDetails');
Route::resource('environmental-clearance','EnvironmentalClearance')->middleware(['auth','revalidate']);
Route::post('environmental-clearance/formValidation', 'EnvironmentalClearance@formValidation')->name('environmentalclearance.post');
// ------------------------------------- BploBusinessClassification ------------------------------
Route::get('bplobusinessclassification/index', 'BploBusinessClassificationController@index')->name('bplobusinessclassification.index');
Route::get('bplobusinessclassification/getList', 'BploBusinessClassificationController@getList')->name('bplobusinessclassification.getList');
Route::get('bplobusinessclassification/store', 'BploBusinessClassificationController@store');
Route::post('bplobusinessclassification/ActiveInactive', 'BploBusinessClassificationController@ActiveInactive');
Route::resource('bplobusinessclassification', 'BploBusinessClassificationController')->middleware(['auth','revalidate']);
Route::post('bplobusinessclassification/formValidation', 'BploBusinessClassificationController@formValidation')->name('bplobusinessclassification.post');
Route::post('gettaxTypeBytaxClass', 'BploBusinessClassificationController@gettaxTypeBytaxClass');
// // ------------------------------------- BploBusinessActivity ------------------------------
// Route::get('bplobusinessactivity/index', 'BploBusinessActivityController@index')->name('bplobusinessactivity.index');
// Route::get('bplobusinessactivity/getList', 'BploBusinessActivityController@getList')->name('bplobusinessactivity.getList');
// Route::get('bplobusinessactivity/store', 'BploBusinessActivityController@store');
// Route::post('bplobusinessactivity/ActiveInactive', 'BploBusinessActivityController@ActiveInactive');
// Route::resource('bplobusinessactivity', 'BploBusinessActivityController')->middleware(['auth','revalidate']);
// Route::post('bplobusinessactivity/formValidation', 'BploBusinessActivityController@formValidation')->name('bplobusinessactivity.post');
// Route::post('gettaxTypeBytaxClassActivity', 'BploBusinessActivityController@gettaxTypeBytaxClassActivity');
// Route::post('getClassificationBytaxClassType', 'BploBusinessActivityController@getClassificationBytaxClassType');
// ------------------------------------- Requirements ------------------------------
Route::get('requirements/index', 'RequirementsController@index')->name('requirementscon.index');
Route::get('requirements/store', 'RequirementsController@store');
Route::resource('requirements', 'RequirementsController')->middleware(['auth','revalidate']);
Route::post('requirements/formValidation', 'RequirementsController@formValidation')->name('requirements.post');

// ------------------------------------- BPLO Application ------------------------------
Route::get('bploapplication/index', 'BploApplicationController@index')->name('bploapplication.index');
Route::get('getbarangayaDetails', 'BploApplicationController@getBarangyaDetails');
Route::get('bploapplication/getList', 'BploApplicationController@getList')->name('bploapplication.getList');
Route::get('bploapplication/store', 'BploApplicationController@store');
Route::get('getprofiles', 'BploApplicationController@getprofiles');
Route::get('getTradedropdown', 'BploApplicationController@getTradedropdown');
Route::post('bploapplication/grosssalereceipt', 'BploApplicationController@grosssaleReceipt');
Route::resource('bploapplication', 'BploApplicationController')->middleware(['auth','revalidate']);
Route::post('bploapplication/formValidation', 'BploApplicationController@formValidation')->name('bploapplication.post');
Route::post('getrequirementofnature', 'BploApplicationController@getRequirementsofNature');
Route::post('deleteBploRequirement', 'BploApplicationController@deleteBploRequirement');
Route::get('checkApptype', 'BploApplicationController@checkApptype');

// ------------------------------------- BPLO System Parameters ------------------------------
Route::get('bplosystemparameters/index', 'BploSystemParametersController@index')->name('bplosystemparameters.index');
Route::get('bplosystemparameters/getList', 'BploSystemParametersController@getList')->name('bplosystemparameters.getList');
Route::get('bplosystemparameters/store', 'BploSystemParametersController@store');
Route::post('bplosystemparameters/delete', 'BploSystemParametersController@Delete');
Route::resource('bplosystemparameters', 'BploSystemParametersController')->middleware(['auth','revalidate']);
Route::post('bplosystemparameters/formValidation', 'BploSystemParametersController@formValidation')->name('bplosystemparameters.post');




Route::get('psicsection/index', 'PsicSectionController@index')->name('psicsection.index');
Route::get('psicsection/store', 'PsicSectionController@store');
Route::post('getdivisionbysection', 'PsicSectionController@getdivisionbysection');
Route::resource('psicsection', 'PsicSectionController')->middleware(['auth','revalidate']);
Route::post('psicsection/formValidation', 'PsicSectionController@formValidation')->name('psicsection.post');

Route::get('psicdivision/index', 'PsicDivisionController@index')->name('psicdivision.index');
Route::get('psicdivision/store', 'PsicDivisionController@store');
Route::get('psicdivision/getList', 'PsicDivisionController@getList')->name('psicdivision.getList');
Route::post('getgroupbydivision', 'PsicDivisionController@getgroupbydivision');
Route::resource('psicdivision', 'PsicDivisionController')->middleware(['auth','revalidate']);
Route::post('psicdivision/formValidation', 'PsicDivisionController@formValidation')->name('psicdivision.post');

Route::get('psicgroup/index', 'PsicGroupController@index')->name('psicgroup.index');
Route::get('psicgroup/store', 'PsicGroupController@store');
Route::get('psicgroup/getList', 'PsicGroupController@getList')->name('psicgroup.getList');
Route::post('getclassbygroup', 'PsicGroupController@getclassbygroup');
Route::resource('psicgroup', 'PsicGroupController')->middleware(['auth','revalidate']);
Route::post('psicgroup/formValidation', 'PsicGroupController@formValidation')->name('psicgroup.post');

Route::get('psicsubclass/index', 'PsicSubclassController@index')->name('psicsubclass.index');
Route::get('psicsubclass/view', 'PsicSubclassController@Establishmentview')->name('psicsubclass.view');
Route::get('psicsubclass/store', 'PsicSubclassController@store');
Route::post('getsubclassbyclass', 'PsicSubclassController@getsubclassbyclass');
Route::get('psicsubclass/getList', 'PsicSubclassController@getList')->name('psicsubclass.getList');
Route::get('psicsubclass/getViewList', 'PsicSubclassController@getViewList')->name('psicsubclass.getViewList');
Route::resource('psicsubclass', 'PsicSubclassController')->middleware(['auth','revalidate']);
Route::post('psicsubclass/formValidation', 'PsicSubclassController@formValidation')->name('psicsubclass.post');
Route::post('getPsicSectionsId', 'PsicSubclassController@getPsicSectionsId');
Route::post('getPsicGroupId', 'PsicSubclassController@getPsicGroupId');
Route::post('getSubclassgroupbyClass', 'PsicSubclassController@getgroupbyClass');
Route::post('sectioncodeList', 'PsicSubclassController@sectioncodeList');
Route::get('classcodeList', 'PsicSubclassController@classcodeList');
Route::post('divisionAllData', 'PsicSubclassController@divisionAllData');
Route::post('groupAllData', 'PsicSubclassController@groupAllData');
Route::post('classAllData', 'PsicSubclassController@classAllData');
Route::post('sectionAjaxList', 'PsicSubclassController@sectionAjaxList');
Route::get('divisionAjaxList', 'PsicSubclassController@divisionAjaxList');
Route::get('groupAjaxList', 'PsicSubclassController@groupAjaxList');
Route::get('classAjaxList', 'PsicSubclassController@classAjaxList');



// Route::get('typeofbussiness/index', 'TypeofbussinessController@index')->name('typeofbussiness.index');
// Route::get('typeofbussiness/store', 'TypeofbussinessController@store');
// Route::resource('typeofbussiness', 'TypeofbussinessController')->middleware(['auth','revalidate']);
// Route::post('typeofbussiness/formValidation', 'TypeofbussinessController@formValidation')->name('typeofbussiness.post');

//--------------------------------------District------------------------------
Route::get('district/index', 'DistrictController@index')->name('district.index');
Route::post('district/delete', 'DistrictController@Delete');
Route::get('getLocalIdDetails', 'DistrictController@getLocalIdDetails');
Route::post('district/districtActiveInactive', 'DistrictController@districtActiveInactive');
Route::get('district/getList', 'DistrictController@getList')->name('district.getList');
Route::get('district/store', 'DistrictController@store');



// Route::post('getprofileProvcodeId', 'DistrictController@getprofileProvcodeId');


Route::resource('district', 'DistrictController')->middleware(['auth','revalidate']);
Route::post('district/formValidation', 'DistrictController@formValidation')->name('district.post');

//--------------------------------------Assessment Level------------------------------
Route::middleware(['auth'])->prefix('real-property')->group(function () {
    /* Designation Routes */
    Route::prefix('assessment-level')->group(function () {
      Route::get('', 'AssessmentLevelController@index')->name('assessmentlevel.index');  
    });
});



Route::get('assessmentlevel/index', 'AssessmentLevelController@index')->name('assessmentlevel.index');
Route::post('assessmentlevel/delete', 'AssessmentLevelController@Delete');
Route::get('getSubClassDetailss', 'AssessmentLevelController@getSubClassDetailss');
Route::get('getClassDetailss', 'AssessmentLevelController@getClassDetailss');
Route::get('getKindDetailss', 'AssessmentLevelController@getKindDetailss');
Route::get('getKindDetailssActualUs', 'AssessmentLevelController@getKindDetailssActualUs');
Route::get('getPropertyClassDetails', 'AssessmentLevelController@getPropertyClassDetails');
Route::get('pau_actual_use_code', 'AssessmentLevelController@pau_actual_use_code');
Route::get('assessmentlevel/view', 'AssessmentLevelController@view');
Route::get('rvy_revision_year', 'AssessmentLevelController@rvy_revision_year');
Route::get('assessmentlevel/getList', 'AssessmentLevelController@getList')->name('assessmentlevel.getList');
Route::post('assessmentlevel/ApproveUnapprove', 'AssessmentLevelController@ApproveUnapprove');
Route::post('assessmentlevel/ActiveInactive', 'AssessmentLevelController@ActiveInactive');
Route::get('assessmentlevel/store', 'AssessmentLevelController@store');
Route::post('DeleteRelation', 'AssessmentLevelController@DeleteRelation');
Route::resource('assessmentlevel', 'AssessmentLevelController')->middleware(['auth','revalidate']);
Route::post('assessmentlevel/formValidation', 'AssessmentLevelController@formValidation')->name('assessmentlevel.post');
Route::middleware(['auth'])->prefix('administrative')->group(function () {
    /* Designation Routes */
    Route::prefix('setup-locality')->group(function () {
      Route::get('', 'LocalityController@index')->name('locality.index'); 
    });
});


//--------------------------------------Locality------------------------------
Route::get('locality/index', 'LocalityController@index')->name('locality.index');
Route::post('locality/delete', 'LocalityController@Delete');
Route::get('locality/getList', 'LocalityController@getList')->name('locality.getList');
Route::get('locality/store', 'LocalityController@store');
Route::post('locality/ActiveInactive', 'LocalityController@ActiveInactive');
Route::resource('locality', 'LocalityController')->middleware(['auth','revalidate']);
Route::post('locality/formValidation', 'LocalityController@formValidation')->name('locality.post');

//--------------------------------------Plant Tress------------------------------
Route::middleware(['auth'])->prefix('real-property')->group(function () {
    /* Designation Routes */
    Route::prefix('property')->group(function () {
        Route::prefix('plant-trees')->group(function () {
          Route::get('', 'PlantTressController@index')->name('planttress.index');
        });
    });
});

Route::get('planttress/index', 'PlantTressController@index')->name('planttress.index');
Route::post('planttress/delete', 'PlantTressController@Delete');
Route::get('planttress/getList', 'PlantTressController@getList')->name('planttress.getList');
Route::get('planttress/store', 'PlantTressController@store');
Route::post('planttress/ActiveInactive', 'PlantTressController@ActiveInactive');
Route::resource('planttress', 'PlantTressController')->middleware(['auth','revalidate']);
Route::post('planttress/formValidation', 'PlantTressController@formValidation')->name('planttress.post');

//--------------------------------------Building Type------------------------------


// Route::get('buildingtype/index', 'BuildingTypeController@index')->name('buildingtype.index');
// Route::post('buildingtype/delete', 'BuildingTypeController@Delete');
// Route::get('buildingtype/getList', 'BuildingTypeController@getList')->name('buildingtype.getList');
// Route::get('buildingtype/store', 'BuildingTypeController@store');
// Route::resource('buildingtype', 'BuildingTypeController')->middleware(['auth','revalidate']);
// Route::post('buildingtype/formValidation', 'BuildingTypeController@formValidation')->name('buildingtype.post');

//--------------------------------------Building Kind------------------------------
Route::middleware(['auth'])->prefix('real-property')->group(function () {
    /* Designation Routes */
        Route::prefix('building')->group(function () {
        Route::prefix('kind-structure')->group(function () {
          Route::get('', 'RptBuildingKindController@index')->name('rptbuildingkind.index');
        });
    });
});



Route::get('rptbuildingkind/index', 'RptBuildingKindController@index')->name('rptbuildingkind.index');
Route::get('rptbuildingkind/getList', 'RptBuildingKindController@getList')->name('rptbuildingkind.getList');
Route::get('rptbuildingkind/store', 'RptBuildingKindController@store');
Route::post('rptbuildingkind/ActiveInactive', 'RptBuildingKindController@ActiveInactive');
Route::post('rptbuildingkind/delete', 'RptBuildingKindController@Delete');
Route::resource('rptbuildingkind', 'RptBuildingKindController')->middleware(['auth','revalidate']);
Route::post('rptbuildingkind/formValidation', 'RptBuildingKindController@formValidation')->name('rptbuildingkind.post');



// ------------------------------------- Barangay------------------------------
Route::get('barangay/index', 'BarangayController@index')->name('barangay.index');
Route::get('barangay/store', 'BarangayController@store');
Route::post('getBarngayMunProvRegionList', 'BarangayController@getBarngayMunProvRegionList');
Route::get('barangay/getList', 'BarangayController@getList')->name('barangay.getList');
Route::post('getprofileProvcodeId', 'BarangayController@getprofileProvcodeId');
Route::post('getDistrictCodes', 'BarangayController@getDistrictCodes');
Route::post('barangay/ActiveInactive', 'BarangayController@ActiveInactive');
Route::resource('barangay', 'BarangayController')->middleware(['auth','revalidate']);
Route::post('barangay/formValidation', 'BarangayController@formValidation')->name('barangay.post');

Route::get('bplorequirements/index', 'BblorequirementController@index')->name('bplorequirements.index');
Route::get('bplorequirements/getList', 'BblorequirementController@getList')->name('bplorequirements.getList');
Route::get('bplorequirements/store', 'BblorequirementController@store');
Route::get('bplorequirements/view', 'BblorequirementController@view');
Route::post('bplorequirements/delete', 'BblorequirementController@Delete');
Route::resource('bplorequirements', 'BblorequirementController')->middleware(['auth','revalidate']);
Route::post('bplorequirements/formValidation', 'BblorequirementController@formValidation')->name('bplorequirements.post');


// ------------------------------------- BPLO Business Permit Fee------------------------------
// Route::get('bplobusinesspermitfee/index', 'BploBusinessPermitfeeController@index')->name('bplobusinesspermitfee.index');
// Route::get('bplobusinesspermitfee/getList', 'BploBusinessPermitfeeController@getList')->name('bplobusinesspermitfee.getList');
// Route::get('bplobusinesspermitfee/store', 'BploBusinessPermitfeeController@store');
// Route::post('bplobusinesspermitfee/ActiveInactive', 'BploBusinessPermitfeeController@ActiveInactive');
// Route::resource('bplobusinesspermitfee', 'BploBusinessPermitfeeController')->middleware(['auth','revalidate']);
// Route::post('bplobusinesspermitfee/formValidation', 'BploBusinessPermitfeeController@formValidation')->name('bplobusinesspermitfee.post');
// Route::post('getactivityCodebyid', 'BploBusinessPermitfeeController@getactivityCodebyid');
// Route::post('getbussinessbyTaxtype', 'BploBusinessPermitfeeController@getbussinessbyTaxtype');
// Route::post('getbussinessbyTaxtypenew', 'BploBusinessPermitfeeController@getbussinessbyTaxtypenew');
// Route::get('getActivitybbaCode', 'BploBusinessPermitfeeController@getActivitybbaCode');
// Route::post('getCategoryDropdown', 'BploBusinessPermitfeeController@getCategoryDropdown');
// Route::post('getAreaDropdown', 'BploBusinessPermitfeeController@getAreaDropdown');

// // ------------------------------------- BPLO Business Sanitary Fee------------------------------
// Route::get('bplobusinesssanitaryfee/index', 'BploBusinessSanitaryfeeController@index')->name('bplobusinesssanitaryfee.index');
// Route::get('bplobusinesssanitaryfee/getList', 'BploBusinessSanitaryfeeController@getList')->name('bplobusinesssanitaryfee.getList');
// Route::get('bplobusinesssanitaryfee/store', 'BploBusinessSanitaryfeeController@store');
// Route::post('bplobusinesssanitaryfee/ActiveInactive', 'BploBusinessSanitaryfeeController@ActiveInactive');
// Route::resource('bplobusinesssanitaryfee', 'BploBusinessSanitaryfeeController')->middleware(['auth','revalidate']);
// Route::post('bplobusinesssanitaryfee/formValidation', 'BploBusinessSanitaryfeeController@formValidation')->name('bplobusinesssanitaryfee.post');
// Route::post('getSanitaryCategoryDropdown', 'BploBusinessSanitaryfeeController@getSanitaryCategoryDropdown');
// Route::post('getSanitaryAreaDropdown', 'BploBusinessSanitaryfeeController@getSanitaryAreaDropdown');

// // ------------------------------------- BPLO Business Garbege Fee------------------------------
// Route::get('bplobusinessgarbagefee/index', 'BploBusinessGarbagefeeController@index')->name('bplobusinessgarbagefee.index');
// Route::get('bplobusinessgarbagefee/getList', 'BploBusinessGarbagefeeController@getList')->name('bplobusinessgarbagefee.getList');
// Route::get('bplobusinessgarbagefee/store', 'BploBusinessGarbagefeeController@store');
// Route::post('bplobusinessgarbagefee/ActiveInactive', 'BploBusinessGarbagefeeController@ActiveInactive');
// Route::resource('bplobusinessgarbagefee', 'BploBusinessGarbagefeeController')->middleware(['auth','revalidate']);
// Route::post('bplobusinessgarbagefee/formValidation', 'BploBusinessGarbagefeeController@formValidation')->name('bplobusinessgarbagefee.post');
// Route::post('getGarbageCategoryDropdown', 'BploBusinessGarbagefeeController@getGarbageCategoryDropdown');
// Route::post('getGarbageAreaDropdown', 'BploBusinessGarbagefeeController@getGarbageAreaDropdown');


// // ------------------------------------- BPLO Business Environmental Fee------------------------------
// Route::get('bplobusinessenvfees/index', 'BploBusinessenvfeesController@index')->name('bplobusinessenvfees.index');
// Route::get('bplobusinessenvfees/getList', 'BploBusinessenvfeesController@getList')->name('bplobusinessenvfees.getList');
// Route::get('bplobusinessenvfees/store', 'BploBusinessenvfeesController@store');
// Route::post('bplobusinessenvfees/ActiveInactive', 'BploBusinessenvfeesController@ActiveInactive');
// Route::post('bplobusinessenvfees/delete', 'BploBusinessenvfeesController@Delete');
// Route::resource('bplobusinessenvfees', 'BploBusinessenvfeesController')->middleware(['auth','revalidate']);
// Route::post('bplobusinessenvfees/formValidation', 'BploBusinessenvfeesController@formValidation')->name('bplobusinessenvfees.post');
// ------------------------------------- BPLO Assessment ------------------------------

// ------------------------------------- Bplo Applicant ------------------------------


// ------------------------------------- BPLO Assessment ------------------------------
Route::get('bploassessment/index', 'BploAssessmentController@index')->name('bploassessment.index');
Route::get('bploassessment/getList', 'BploAssessmentController@getList')->name('bploassessment.getList');
Route::get('bploassessment/store', 'BploAssessmentController@store');
Route::get('getprofilesasses', 'BploAssessmentController@getprofilesasses');
Route::get('getBussinessData', 'BploAssessmentController@getBussinessData');
Route::get('getTasktypesData', 'BploAssessmentController@getTasktypesData');
Route::get('getClasificationDesc', 'BploAssessmentController@getClasificationDesc');
Route::get('getActivityDesc', 'BploAssessmentController@getActivityDesc');
Route::post('getClassificationByType', 'BploAssessmentController@getClassificationByType'); 
Route::post('getActivitybyClass', 'BploAssessmentController@getActivitybyClass');

Route::get('bploassessment/asses', 'BploAssessmentController@assessNow')->name('bploassessment.asses');

// Route::get('bploassessment/index', 'BploAssessmentController@index')->name('bploassessment.index');
// Route::get('bploassessment/getList', 'BploAssessmentController@getList')->name('bploassessment.getList');
// Route::get('bploassessment/store', 'BploAssessmentController@store');
// Route::get('getprofilesasses', 'BploAssessmentController@getprofilesasses');

Route::resource('bploassessment', 'BploAssessmentController')->middleware(['auth','revalidate']);
Route::post('bploassessment/formValidation', 'BploAssessmentController@formValidation')->name('bploassessment.post');
Route::post('getAllFeeDetails', 'BploAssessmentController@getAllFeeDetails'); 

Route::get('getEngneeringFeeDetails', 'BploAssessmentController@getEngneeringFeeDetails');

Route::middleware(['auth'])->prefix('treasurer')->group(function () {
    /* Designation Routes */
    Route::prefix('applications')->group(function () {
        Route::get('', 'BploAssessmentController@index')->name('bploassessment.index');
    });

    Route::prefix('assessment')->group(function () {
        Route::get('/', 'Bplo\TreasurerAssessmentController@index')->name('assessment.index');
        Route::get('getList', 'Bplo\TreasurerAssessmentController@getList')->name('assessment.list');
        Route::get('asses', 'Bplo\TreasurerAssessmentController@assessNow')->name('assessment.asses');
        Route::post('getPeriodDetails', 'Bplo\TreasurerAssessmentController@getPeriodDetails');
        Route::post('getAssessmentDetails', 'Bplo\TreasurerAssessmentController@getAssessmentDetails');
        Route::post('saveFinalAssessDtls', 'Bplo\TreasurerAssessmentController@saveFinalAssessDtls');
        Route::post('displayTaxOrderOfPayment', 'Bplo\TreasurerAssessmentController@displayTaxOrderOfPayment');
        Route::get('generatePaymentPdf', 'Bplo\TreasurerAssessmentController@generatePaymentPdf');
        Route::post('sendEmail', 'Bplo\TreasurerAssessmentController@sendEmail');
        Route::post('storRemoteBploBillReceipt', 'Bplo\TreasurerAssessmentController@storRemoteBploBillReceipt');
        Route::post('restoreDeleteAssessmentFee', 'Bplo\TreasurerAssessmentController@restoreDeleteAssessmentFee');
        Route::get('generateORPaymentPdf', 'Bplo\TreasurerAssessmentController@generateORPaymentPdf');
    });
    Route::prefix('penalty-rates')->group(function () {
        Route::get('', 'BploAssessPenaltyRatesController@index')->name('bplopenaltyrates.index');
    });
});
Route::middleware(['auth'])->prefix('planning-and-development')->group(function () {
    /* Designation Routes */
    Route::prefix('application')->group(function () {
        Route::get('', 'PdoBploAppClearanceController@index')->name('pdobploappclearance.index');
    });
  
});
Route::middleware(['auth'])->prefix('environmental')->group(function () {
    /* Designation Routes */
    Route::prefix('application')->group(function () {
        Route::get('', 'EnroBploappClearanceController@index')->name('bploappclearance.index');
    });
 
});

//-------------------------------- Pdo HO App Healthcert -----------------------


Route::post('getCitizenAjax', 'HoAppHealthCertController@getCitizenAjax');
Route::post('getBusinessAjax', 'HoAppHealthCertController@getBusinessAjax');
Route::middleware(['auth'])->prefix('healthy-and-safety')->group(function () {
    /* Designation Routes */    
    Route::prefix('health-certificate')->group(function () {
        Route::get('', 'HoAppHealthCertController@index')->name('hoapphealthcert.index');
        Route::get('getList', 'HoAppHealthCertController@getList')->name('hoapphealthcert.getList');
        Route::get('getCitizenDetails', 'HoAppHealthCertController@getCitizenDetails')->name('hoapphealthcert.getCitizenDetails');
        Route::get('getPosition', 'HoAppHealthCertController@getPosition')->name('hoapphealthcert.getPosition');
        Route::get('ActiveInactive/{id}', 'HoAppHealthCertController@ActiveInactive');
        Route::get('getOccuSuggestions', 'HoAppHealthCertController@getOccuSuggestions')->name('hoapphealthcert.getOccuSuggestions');
        Route::get('getWorkAddressSuggestions', 'HoAppHealthCertController@getWorkAddressSuggestions')->name('hoapphealthcert.getWorkAddressSuggestions');
        Route::get('store', 'HoAppHealthCertController@store');

        Route::any('upload', 'HoAppHealthCertController@upload');
        Route::post('uploadDocument', 'HoAppHealthCertController@uploadDocument')->name('hoapphealthcert.uploadDocument');
        Route::post('deleteAttachment', 'HoAppHealthCertController@deleteAttachment')->name('hoapphealthcert.deleteAttachment');
        Route::get('deleteCertificateReq/{id}', 'HoAppHealthCertController@deleteCertificateReq')->name('hoapphealthcert.deleteCertificateReq');

        Route::resource('', 'HoAppHealthCertController')->middleware(['auth','revalidate']);
        Route::post('formValidation', 'HoAppHealthCertController@formValidation')->name('hoapphealthcert.post');
        Route::any('hoapphealthcertPrint', 'HoAppHealthCertController@hoapphealthcertPrint');
    });
    
    Route::prefix('app-sanitary')->group(function () {
       Route::get('', 'HoApplicationSanitaryController@index')->name('hoappsanitary.index');
       Route::get('getList', 'HoApplicationSanitaryController@getList')->name('hoappsanitary.getList');
	   Route::post('uploadDocument', 'HoApplicationSanitaryController@uploadDocument')->name('hoappsanitary.uploadDocument');
	   Route::post('deleteAttachment', 'HoApplicationSanitaryController@deleteAttachment')->name('hoappsanitary.deleteAttachment');
       Route::get('getBusnComAddress', 'HoApplicationSanitaryController@getBusnComAddress')->name('hoappsanitary.getBusnComAddress');
       Route::get('getPosition', 'HoApplicationSanitaryController@getPosition')->name('hoappsanitary.getPosition');
       Route::get('getEstablisSuggestions', 'HoApplicationSanitaryController@getEstablisSuggestions')->name('hoappsanitary.getEstablisSuggestions');
       Route::get('deleteSanitaryReq/{id}', 'HoApplicationSanitaryController@deleteSanitaryReq')->name('hoappsanitary.deleteSanitaryReq');
       
       Route::get('removeSanitary/{id}', 'HoApplicationSanitaryController@removeSanitary')->name('hoappsanitary.removeSanitary');
       
        Route::get('store', 'HoApplicationSanitaryController@store');
        Route::any('approve', 'HoApplicationSanitaryController@approve');
        Route::resource('', 'HoApplicationSanitaryController')->middleware(['auth','revalidate']);
        Route::post('formValidation', 'HoApplicationSanitaryController@formValidation')->name('hoappsanitary.post');
        Route::get('getpbloAppdetails', 'HoApplicationSanitaryController@getpbloAppdetails');
        Route::any('hoapphealthsanitaryprint', 'HoApplicationSanitaryController@hoapphealthsanitaryprint');

    });
    
    Route::prefix('water-potability')->group(function () {
		Route::post('uploadDocument', 'HoWaterPotabilityController@uploadDocument')->name('howaterpotability.uploadDocument');
		Route::post('deleteAttachment', 'HoWaterPotabilityController@deleteAttachment')->name('howaterpotability.deleteAttachment');
        Route::get('', 'HoWaterPotabilityController@index')->name('howaterpotability.index');
        Route::get('getList', 'HoWaterPotabilityController@getList')->name('howaterpotability.getList');
        Route::post('ActiveInactive', 'HoWaterPotabilityController@ActiveInactive');
        Route::get('getBrgyDetails', 'HoWaterPotabilityController@getBrgyDetails')->name('howaterpotability.getBrgyDetails');
        Route::get('getInsPosDetails', 'HoWaterPotabilityController@getInsPosDetails')->name('howaterpotability.getInsPosDetails');
        Route::get('getAppPosDetails', 'HoWaterPotabilityController@getAppPosDetails')->name('howaterpotability.getAppPosDetails');
        Route::get('get-or-no/{id}','HoWaterPotabilityController@getORNoDetails')->name('water-potability.get-or-no');
        Route::post('get-or-list/{id}','HoWaterPotabilityController@getOr')->name('water-potability.get-or-list');
        Route::get('print/{id}', 'HoWaterPotabilityController@print')->name('howaterpotability.print');
		Route::get('store', 'HoWaterPotabilityController@store');
		Route::resource('', 'HoWaterPotabilityController')->middleware(['auth','revalidate']);
        Route::post('formValidation', 'HoWaterPotabilityController@formValidation')->name('howaterpotability.post');
        // Route::post('uploadDocument', 'HoApplicationSanitaryController@uploadDocument')->name('hoappsanitary.uploadDocument');
        // Route::post('deleteAttachment', 'HoApplicationSanitaryController@deleteAttachment')->name('hoappsanitary.deleteAttachment');
        // Route::get('getBusnComAddress', 'HoApplicationSanitaryController@getBusnComAddress')->name('hoappsanitary.getBusnComAddress');
        // Route::get('getPosition', 'HoApplicationSanitaryController@getPosition')->name('hoappsanitary.getPosition');
        // Route::get('getEstablisSuggestions', 'HoApplicationSanitaryController@getEstablisSuggestions')->name('hoappsanitary.getEstablisSuggestions');
        // Route::get('deleteSanitaryReq/{id}', 'HoApplicationSanitaryController@deleteSanitaryReq')->name('hoappsanitary.deleteSanitaryReq');
        // Route::any('howaterpotabilityprint/{id}', 'HoApplicationSanitaryController@howaterpotabilityprint');
        // Route::get('removeSanitary/{id}', 'HoApplicationSanitaryController@removeSanitary')->name('hoappsanitary.removeSanitary');
         
        //  Route::any('approve', 'HoApplicationSanitaryController@approve');
         
        //  Route::get('getpbloAppdetails', 'HoApplicationSanitaryController@getpbloAppdetails');
        //  Route::any('hoapphealthsanitaryprint', 'HoApplicationSanitaryController@HoHealthSanitaryPrint');
 
     });

    Route::prefix('transfer-of-cadaver')->group(function () {
        Route::get('', 'HoTransCadaverController@index')->name('hotranscadaver.index');
        Route::get('getList', 'HoTransCadaverController@getList')->name('hotranscadaver.getList');
        Route::get('getCitizenDetails', 'HoTransCadaverController@getCitizenDetails')->name('hotranscadaver.getCitizenDetails');
        Route::get('getPosition', 'HoTransCadaverController@getPosition')->name('hotranscadaver.getPosition');
        Route::post('ActiveInactive', 'HoTransCadaverController@ActiveInactive');
        // Route::get('getOccuSuggestions', 'HoTransCadaverController@getOccuSuggestions')->name('hoapphealthcert.getOccuSuggestions');
         // Route::get('getWorkAddressSuggestions', 'HoTransCadaverController@getWorkAddressSuggestions')->name('hoapphealthcert.getWorkAddressSuggestions');
        Route::get('store', 'HoTransCadaverController@store');
         // Route::any('upload', 'HoTransCadaverController@upload');
         // Route::post('uploadDocument', 'HoTransCadaverController@uploadDocument')->name('hoapphealthcert.uploadDocument');
         // Route::post('deleteAttachment', 'HoTransCadaverController@deleteAttachment')->name('hoapphealthcert.deleteAttachment');
        Route::get('deleteDeceased/{id}', 'HoTransCadaverController@deleteDeceased')->name('hotranscadaver.deleteDeceased');

        Route::resource('', 'HoTransCadaverController')->middleware(['auth','revalidate']);
        Route::post('formValidation', 'HoTransCadaverController@formValidation')->name('hotranscadaver.post');
         // Route::any('hoapphealthcertPrint/{id}', 'HoTransCadaverController@hoapphealthcertPrint');
        Route::get('print-open-niche/{id}', 'HoTransCadaverController@openNichePrint')->name('hotranscadaver.print.openNiche');
        Route::get('print-transfer-cadaver/{id}', 'HoTransCadaverController@transferCadaverPrint')->name('hotranscadaver.print.transferCadaver');
        Route::get('print-transfer-remain/{id}', 'HoTransCadaverController@transferRemainPrint')->name('hotranscadaver.print.transferRemain');
    });
    
});
Route::any('/healthy-and-safety/app-sanitary/healthsanitaryprint', 'HoApplicationSanitaryController@hoapphealthsanitaryprint');
Route::middleware(['auth'])->prefix('health-and-safety')->group(function () {

    Route::prefix('setup-data')->group(function () {
        /* Item Managements Routes */
        Route::prefix('item-managements')->group(function () {
            Route::get('', 'HealthSafetyItemController@index')->name('health-safety.item.index');
            Route::get('lists', 'HealthSafetyItemController@lists')->name('health-safety.item.lists');
            Route::post('store', 'HealthSafetyItemController@store')->name('health-safety.item.store');
            Route::get('edit/{id}', 'HealthSafetyItemController@find')->name('health-safety.item.find');
            Route::put('update/{id}', 'HealthSafetyItemController@update')->name('health-safety.item.update');
            Route::put('remove/{id}', 'HealthSafetyItemController@remove')->name('health-safety.item.remove');
            Route::put('restore/{id}', 'HealthSafetyItemController@restore')->name('health-safety.item.restore');
            Route::post('upload/{id}', 'HealthSafetyItemController@upload')->name('health-safety.item.upload');
            Route::get('download/{id}', 'HealthSafetyItemController@download')->name('health-safety.item.download');
            Route::delete('delete/{id}', 'HealthSafetyItemController@delete')->name('health-safety.item.delete');
            Route::get('upload-lists/{id}', 'HealthSafetyItemController@upload_lists')->name('health-safety.item.upload-lists');
            Route::get('fetch-gl-via-item-category/{id}', 'HealthSafetyItemController@fetch_gl_via_item_category')->name('health-safety.item.fetch-gl');
            Route::get('generate-item-code/{id}', 'HealthSafetyItemController@generate_item_code')->name('health-safety.item.generate-item-code');
            Route::get('validate-category/{category}', 'HealthSafetyItemController@validate_category')->name('health-safety.item.validate-category');
            Route::get('fetch-based-uom/{id}', 'HealthSafetyItemController@fetch_based_uom')->name('admin-gso.item.fetch-based-uom');
            Route::get('conversion-lists/{id}', 'HealthSafetyItemController@conversion_lists')->name('health-safety.conversion.lists');
            Route::post('store-conversion/{id}', 'HealthSafetyItemController@store_conversion')->name('health-safety.item.store-conversion');
            Route::put('update-conversion/{id}', 'HealthSafetyItemController@update_conversion')->name('health-safety.item.update-conversion');
            Route::put('remove-conversion/{id}', 'HealthSafetyItemController@remove_conversion')->name('health-safety.item.remove-conversion');
            Route::put('restore-conversion/{id}', 'HealthSafetyItemController@restore_conversion')->name('health-safety.item.restore-conversion');
            Route::get('edit-conversion/{id}', 'HealthSafetyItemController@find_conversion')->name('health-safety.item.find-conversion');
        });
    });

    Route::prefix('mental-health')->group(function () {
        Route::prefix('record-card')->group(function () {
            Route::get('', 'MenHelRecordCardController@index')->name('recordcard.index');
            Route::post('/getCitizens', 'MenHelRecordCardController@getCitizens')->name('recordcard.getCitizens');
            Route::post('recordcard-uploadDocument', 'MenHelRecordCardController@uploadDocument')->name('recordcard.uploadDocument');
            Route::post('recordcard-deleteAttachment', 'MenHelRecordCardController@deleteAttachment')->name('recordcard.deleteAttachment');
            Route::get('recordcard/store', 'MenHelRecordCardController@store');
            Route::get('recordcard/getList', 'MenHelRecordCardController@getList')->name('recordcard.getList');
            Route::get('getCitizenDetailsRecord', 'MenHelRecordCardController@getCitizenDetailsRecord')->name('getCitizenDetailsRecord');
            Route::get('getGuardianDetailsRecord', 'MenHelRecordCardController@getGuardianDetailsRecord')->name('getGuardianDetailsRecord');
            Route::resource('recordcard', 'MenHelRecordCardController')->middleware(['auth','revalidate']);
            Route::post('recordcard/formValidation', 'MenHelRecordCardController@formValidation')->name('recordcard.post');
            Route::post('recordcard/activate', 'MenHelRecordCardController@activate');
            Route::get('guardian/activate/{id}', 'MenHelRecordCardController@guardianActivate');
            Route::get('deleteRecordCard/{id}', 'MenHelRecordCardController@deleteRecordCard')->name('recordcard.deleteRecordCard');
            Route::get('deleteMedicalRecordCard/{id}', 'MenHelRecordCardController@deleteMedicalRecordCard')->name('recordcard.deleteMedicalRecordCard');
            Route::get('deleteTreatment/{id}', 'MenHelRecordCardController@deleteTreatment')->name('recordcard.deleteTreatment');
            Route::get('deleteDiagnosis/{id}', 'MenHelRecordCardController@deleteDiagnosis')->name('recordcard.deleteDiagnosis');
            Route::post('store', 'MenHelRecordCardController@store');
        }); 
    });
});




    /* Laboratory Request Routes */
    Route::prefix('laboratory-request')->group(function () {
		Route::post('/uploadDocument', 'LaboratoryReqController@uploadDocument')->name('laboratoryreq.uploadDocument');
		Route::post('/deleteAttachment', 'LaboratoryReqController@deleteAttachment')->name('laboratoryreq.deleteAttachment');
        Route::get('', 'LaboratoryReqController@index')->name('laboratoryreq.index');
        
        Route::get('/store', 'LaboratoryReqController@store')->name('laboratoryreq.store');
        Route::get('/getList', 'LaboratoryReqController@getList')->name('laboratoryreq.getList');
        Route::get('/getList/{cit_id}','LaboratoryReqController@getListSpecific')->name('laboratoryreq/getListSpecific');
        Route::get('getReqPhysDetails', 'LaboratoryReqController@getReqPhysDetails')->name('hoapphealthcert.getReqPhysDetails');
       
        Route::get('/print/{id}', 'LaboratoryReqController@print')->name('laboratoryreq.print');
        Route::get('/submit/{id}', 'LaboratoryReqController@submit')->name('laboratoryreq.submit');
        Route::get('/del-fees/{id}', 'LaboratoryReqController@removeFee')->name('laboratoryreq.removeFee');
        Route::resource('', 'LaboratoryReqController')->middleware(['auth','revalidate']);
        Route::post('/formValidation', 'LaboratoryReqController@formValidation')->name('laboratoryreq.post');
        Route::post('/laboratoryActiveInactive', 'LaboratoryReqController@laboratoryActiveInactive');
    }); 

    Route::get('getCitizenDetailsLab', 'LaboratoryReqController@getCitizenDetailsLab')->name('getCitizenDetailsLab');
    Route::post('getServices', 'HealthSafetySetupDataServiceController@getServices')->name('getLabServices');
    Route::post('getService', 'HealthSafetySetupDataServiceController@getService')->name('getLabService');
	
	
    /* health-and-safety/occupational-health/request-permit */
	Route::post('OccupationalPermit-uploadDocument', 'RequestPermitController@uploadDocument')->name('requestpermit.uploadDocument');
	Route::post('OccupationalPermit-deleteAttachment', 'RequestPermitController@deleteAttachment')->name('requestpermit.deleteAttachment');
	Route::get('civil-registrar/request-permit', 'RequestPermitController@index')->name('requestpermit.index');
    Route::get('OccupationalPermit/del-fees/{id}', 'RequestPermitController@removeFee')->name('laboratoryreq.removeFee');
	Route::get('OccupationalPermit/store', 'RequestPermitController@store')->name('requestpermit.store');
	Route::get('OccupationalPermit/getList', 'RequestPermitController@getList')->name('requestpermit.getList');
	Route::get('OccupationalPermit/getList/{cit_id}','RequestPermitController@getListSpecific')->name('requestpermit/getListSpecific');
	Route::get('OccupationalPermit/print/{id}', 'RequestPermitController@print')->name('requestpermit.print'); // print
	Route::get('OccupationalPermit/submit/{id}', 'RequestPermitController@submit')->name('requestpermit.submit');
	Route::resource('OccupationalPermit', 'RequestPermitController')->middleware(['auth','revalidate']);
	Route::post('OccupationalPermit/formValidation', 'RequestPermitController@formValidation')->name('requestpermit.post');
	Route::post('OccupationalPermit/laboratoryActiveInactive', 'RequestPermitController@laboratoryActiveInactive');
	Route::post('OccupationalPermit/getServices', 'HealthSafetySetupDataServiceController@getServicesPermit')->name('getLabServices');
    Route::post('OccupationalPermit/getService', 'HealthSafetySetupDataServiceController@getServicePermit')->name('getLabServices');

// ----------------------------------transfer of cadaver----------------------------------------------------------------
    Route::prefix('civil-registrar/permits')->group(function () {
		Route::post('uploadDocument', 'HoTransCadaverController@uploadDocument')->name('hoapphealthcert.uploadDocument');
        Route::post('deleteAttachment', 'HoTransCadaverController@deleteAttachment')->name('hoapphealthcert.deleteAttachment');
        Route::get('', 'HoTransCadaverController@index')->name('hotranscadaver.index');
        Route::get('getList', 'HoTransCadaverController@getList')->name('hotranscadaver.getList');
        Route::get('getCitizenDetails', 'HoTransCadaverController@getCitizenDetails')->name('hotranscadaver.getCitizenDetails');
        Route::get('getPosition', 'HoTransCadaverController@getPosition')->name('hotranscadaver.getPosition');
        Route::post('ActiveInactive', 'HoTransCadaverController@ActiveInactive');
        // Route::get('getOccuSuggestions', 'HoTransCadaverController@getOccuSuggestions')->name('hoapphealthcert.getOccuSuggestions');
        // Route::get('getWorkAddressSuggestions', 'HoTransCadaverController@getWorkAddressSuggestions')->name('hoapphealthcert.getWorkAddressSuggestions');
        Route::get('store', 'HoTransCadaverController@store');
        // Route::any('upload', 'HoTransCadaverController@upload');
        Route::get('deleteDeceased/{id}', 'HoTransCadaverController@deleteDeceased')->name('hotranscadaver.deleteDeceased');
        Route::resource('', 'HoTransCadaverController')->middleware(['auth','revalidate']);
        Route::post('/formValidation', 'HoTransCadaverController@formValidation')->name('hotranscadaver.post');
        //Route::any('hoapphealthcertPrint/{id}', 'HoTransCadaverController@hoapphealthcertPrint');
        Route::get('print-open-niche/{id}', 'HoTransCadaverController@openNichePrint')->name('hotranscadaver.print.openNiche');
        Route::get('print-transfer-cadaver/{id}', 'HoTransCadaverController@transferCadaverPrint')->name('hotranscadaver.print.transferCadaver');
        Route::get('print-transfer-remain/{id}', 'HoTransCadaverController@transferRemainPrint')->name('hotranscadaver.print.transferRemain');
    });
/*----------------------------------add record card---------------------------------*/

Route::prefix('record-card')->group(function () {
    Route::get('', 'RecordCardController@index')->name('recordcard.index');
    Route::post('/getCitizens', 'RecordCardController@getCitizens')->name('recordcard.getCitizens');
}); 
Route::post('recordcard-uploadDocument', 'RecordCardController@uploadDocument')->name('recordcard.uploadDocument');
Route::post('recordcard-deleteAttachment', 'RecordCardController@deleteAttachment')->name('recordcard.deleteAttachment');
Route::get('recordcard/store', 'RecordCardController@store');
Route::get('recordcard/getList', 'RecordCardController@getList')->name('recordcard.getList');
Route::get('getCitizenDetailsRecord', 'RecordCardController@getCitizenDetailsRecord')->name('getCitizenDetailsRecord');
Route::get('getGuardianDetailsRecord', 'RecordCardController@getGuardianDetailsRecord')->name('getGuardianDetailsRecord');
Route::resource('recordcard', 'RecordCardController')->middleware(['auth','revalidate']);
Route::post('recordcard/formValidation', 'RecordCardController@formValidation')->name('recordcard.post');
Route::post('recordcard/activate', 'RecordCardController@activate');
Route::get('guardian/activate/{id}', 'RecordCardController@guardianActivate');
Route::get('deleteRecordCard/{id}', 'RecordCardController@deleteRecordCard')->name('recordcard.deleteRecordCard');
Route::get('deleteMedicalRecordCard/{id}', 'RecordCardController@deleteMedicalRecordCard')->name('recordcard.deleteMedicalRecordCard');
Route::get('deleteTreatment/{id}', 'RecordCardController@deleteTreatment')->name('recordcard.deleteTreatment');
Route::get('deleteDiagnosis/{id}', 'RecordCardController@deleteDiagnosis')->name('recordcard.deleteDiagnosis');
Route::post('store', 'RecordCardController@store');


/*-------------------------------medical record-------------------------------------*/
Route::prefix('medical-record')->group(function () {
	Route::post('/uploadDocument', 'MedicalRecordCardController@uploadDocument')->name('medical.uploadDocument');
    Route::post('/deleteAttachment', 'MedicalRecordCardController@deleteAttachment')->name('medical.deleteAttachment');
    Route::get('', 'MedicalRecordCardController@index')->name('medical.index'); 
    Route::get('/store', 'MedicalRecordCardController@store')->name('medical.store'); 
    Route::get('diagnosis/activate/{id}', 'MedicalRecordCardController@diagnosisActive')->name('diagnosis/activate'); 
    Route::get('treatment/activate/{id}', 'MedicalRecordCardController@treatmentisActive')->name('treatment/activate'); 
    Route::resource('', 'MedicalRecordCardController')->middleware(['auth','revalidate']);
    Route::post('formValidation', 'MedicalRecordCardController@formValidation')->name('medical.formValidation'); 
    Route::post('selectDiagnosis', 'MedicalRecordCardController@selectDiagnosis')->name('medical.selectDiagnosis');
});

Route::get('medical/getList', 'MedicalRecordCardController@getList')->name('medical.getList');
Route::get('medical-record/getList/{id}', 'MedicalRecordCardController@getList')->name('medical.getListSpecficPatient');
Route::post('medical/recordActiveInactive', 'MedicalRecordCardController@recordActiveInactive');

/*-----------------------Health safety prints---------------------------*/
Route::get('medical-record/print/{id}', 'MedicalCertificateController@medCertPrint')->name('medical.print');
Route::get('urinalysis/print/{id}', 'HealthSafetyMpdfController@urinalysis')->name('urinalysis.print');
Route::get('fecalysis/print/{id}', 'HealthSafetyMpdfController@fecalysis')->name('fecalysis.print');
Route::get('serology/print/{id}', 'HealthSafetyMpdfController@serology')->name('serology.print');
Route::get('medicine-supplies-report/{year}','HoInventoryReport@utilReportPrint')->name('medicine-supplies-report');
Route::get('morbidity-report/{year}','HoInventoryReport@morbidReportPrint')->name('morbidity-report');
Route::get('morbidity-report-specific/{range}/{year}','HoInventoryReport@morbidPrint')->name('morbidity-report-specific');
Route::get('monthlyUtilBalance','HoInventoryReport@monthlyUtilBalance')->name('monthlyUtilBalance');


Route::middleware(['auth'])->prefix('fire-protection')->group(function () {
    /* Designation Routes */
    Route::prefix('applications')->group(function () {
        Route::get('', 'BfpApplicationFormController@index')->name('bfpapplicationform.index');
    });
    Route::prefix('inspection-order')->group(function () {
        Route::get('', 'BfpInspectionOrderController@index')->name('bfpinspectionorder.index');
    });
    Route::prefix('inspection-report')->group(function () {
        Route::any('', 'BfpAfterInspectionReportController@InspectionReportFiles')->name('aftertinspectionreport.reportfiles');
    });
    Route::prefix('cashiering')->group(function () {
        Route::get('', 'Bplo\BfpAssessmentController@index')->name('BfpAssessment.index');
        Route::get('getList', 'Bplo\BfpAssessmentController@getList')->name('BfpAssessment.list');
        Route::get('store', 'Bplo\BfpAssessmentController@store');
        Route::post('store', 'Bplo\BfpAssessmentController@store');
        Route::post('checkOrAppNoUsedOrNot', 'Bplo\BfpAssessmentController@checkOrAppNoUsedOrNot');
        Route::post('getOrnumber', 'Bplo\BfpAssessmentController@getOrnumber');
        Route::post('getOptionDetails', 'Bplo\BfpAssessmentController@getOptionDetails');
        Route::post('cancelOr', 'Bplo\BfpAssessmentController@cancelOr');
        Route::post('uploadDocument', 'Bplo\BfpAssessmentController@uploadDocument');
        Route::post('deleteAttachment', 'Bplo\BfpAssessmentController@deleteAttachment');
        Route::post('cancelNaturePaymentOption', 'Bplo\BfpAssessmentController@cancelNaturePaymentOption');
        Route::get('generatePaymentPdf', 'Bplo\BfpAssessmentController@generatePaymentPdf');
        Route::get('printReceipt', 'Bplo\BfpAssessmentController@printReceipt');
    });
});



Route::get('aftertinspectionreport', 'BfpAfterInspectionReportController@InspectionReportFiles')->name('aftertinspectionreport.reportfiles');
Route::get('aftertinspectionreport/getList', 'BfpAfterInspectionReportController@getList')->name('aftertinspectionreport.getList');

Route::get('bplopermitandlicence', 'BploBussiPermitandLicenceController@index');
Route::get('bplopermitandlicence/index', 'BploBussiPermitandLicenceController@index')->name('bplopermitandlicence.index');
Route::get('bplopermitandlicence/getList', 'BploBussiPermitandLicenceController@getList')->name('bplopermitandlicence.getList');
Route::get('bplopermitandlicence/store', 'BploBussiPermitandLicenceController@store');
Route::get('getAssesmentData', 'BploBussiPermitandLicenceController@getAssesmentData');
Route::post('bplopermitandlicence/print', 'BploBussiPermitandLicenceController@printPayment');
Route::resource('bplopermitandlicence', 'BploBussiPermitandLicenceController')->middleware(['auth','revalidate']);

Route::post('bplopermitandlicence/formValidation', 'BploBussiPermitandLicenceController@formValidation')->name('bplopermitandlicence.post');

// ------------------------------------- BfpApplicationForm------------------------------
Route::get('bfpapplicationform/index', 'BfpApplicationFormController@index')->name('bfpapplicationform.index');
Route::get('bfpapplicationform/getList', 'BfpApplicationFormController@getList')->name('bfpapplicationform.getList');
Route::post('bfpapplicationform/deleteAttachment', 'BfpApplicationFormController@deleteAttachment');
Route::post('bfpapplicationform/uploadAttachment', 'BfpApplicationFormController@uploadAttachment');
Route::post('getRefreshCitizen', 'BfpApplicationFormController@getRefreshCitizen');
Route::post('getRefreshEmployee', 'BfpApplicationFormController@getRefreshEmployee');
Route::any('bfpapplicationform/store', 'BfpApplicationFormController@store');
Route::get('getCertified', 'BfpApplicationFormController@getCertified');
Route::get('getBotIdDetails', 'BfpApplicationFormController@getBotIdDetails');
Route::get('getRepresentative', 'BfpApplicationFormController@getRepresentative');
Route::post('getClientDetails', 'BfpApplicationFormController@getClientDetails');
Route::get('getCategoryDetails', 'BfpApplicationFormController@getCategoryDetails');
Route::get('getocuppancyDetails', 'BfpApplicationFormController@getocuppancyDetails');
Route::get('getprofileClient', 'BfpApplicationFormController@getprofileClient');
Route::post('getBusinessNoId', 'BfpApplicationFormController@getBusinessNoId');
Route::get('getprofilesasses2', 'BfpApplicationFormController@getprofilesasses2');
Route::get('bfpgetBussinessData', 'BfpApplicationFormController@bfpgetBussinessData');
Route::post('getClientsBfpAjax', 'BfpApplicationFormController@getClientsBfpAjax');
Route::get('bfpgetTasktypesData', 'BfpApplicationFormController@bfpgetTasktypesData');
Route::get('bfpgetClasificationDesc', 'BfpApplicationFormController@bfpgetClasificationDesc');
Route::get('bfpgetActivityDesc', 'BfpApplicationFormController@bfpgetActivityDesc');
Route::post('bfpgetClassificationByType', 'BfpApplicationFormController@bfpgetClassificationByType'); 
Route::post('bfpgetActivitybyClass', 'BfpApplicationFormController@bfpgetActivitybyClass');
Route::get('bfpapplicationform/asses', 'BfpApplicationFormController@assessNow')->name('bfpapplicationform.asses');
Route::post('bfpapplicationform/assesnow', 'BfpApplicationFormController@assessNow');
Route::resource('bfpapplicationform', 'BfpApplicationFormController')->middleware(['auth','revalidate']);
Route::post('bfpapplicationform/formValidation', 'BfpApplicationFormController@formValidation')->name('bfpapplicationform.post');
Route::post('bfpgetAllFeeDetails', 'BfpApplicationFormController@bfpgetAllFeeDetails');  
Route::get('bfpgetEngneeringFeeDetails', 'BfpApplicationFormController@bfpgetEngneeringFeeDetails');
Route::any('BfpAssessmentPrint', 'BfpApplicationFormController@BfpAssessmentPrint');
Route::any('BfpChequePrint', 'BfpApplicationFormController@BfpChequePrint');


//-----------------------health and safety (opd) masters---------------------------------
// Route::prefix('hematology-category')->group(function () {
//     Route::get('', 'HematologyCategoryController@index')->name('hemacategory.index');
// });
Route::get('hemacategory/index', 'HematologyCategoryController@index')->name('hemacategory.index');
Route::get('hemacategory/getList', 'HematologyCategoryController@getList')->name('hemacategory.getList');
Route::get('hemacategory/store', 'HematologyCategoryController@store')->name('hemacategory.store');
Route::post('hemacategory/delete', 'HematologyCategoryController@Delete');
Route::post('hemacategory/categoryActiveInactive', 'HematologyCategoryController@categoryActiveInactive');
Route::resource('hemacategory', 'HematologyCategoryController')->middleware(['auth','revalidate']);
Route::post('hemacategory/formValidation', 'HematologyCategoryController@formValidation')->name('hemacategory.post');

//-----------------------------Ho Reporting Range----------------------------------------------
//dont need this
Route::get('reporange/index', 'ReportingRangeController@index')->name('reporange.index');
Route::get('reporange/getList', 'ReportingRangeController@getList')->name('reporange.getList');
Route::get('reporange/store', 'ReportingRangeController@store')->name('reporange.store');
Route::post('reporange/delete', 'ReportingRangeController@Delete');
Route::post('reporange/reporangeActiveInactive', 'ReportingRangeController@reporangeActiveInactive');
Route::resource('reporange', 'ReportingRangeController')->middleware(['auth','revalidate']);
Route::post('reporange/formValidation', 'ReportingRangeController@formValidation')->name('reporange.post');

//----------------------------------Morbidity Cases---------------------------------------------------------
Route::get('healthy-and-safety/reports/morbid-cases', 'MorbidityCaseController@index')->name('morbiditycase.index');
Route::get('healthy-and-safety/reports/morbid-cases/getList', 'MorbidityCaseController@getList')->name('morbiditycase.getList');
Route::get('healthy-and-safety/reports/morbid-cases/store', 'MorbidityCaseController@store')->name('morbiditycase.store');
Route::resource('healthy-and-safety/reports/morbid-cases', 'MorbidityCaseController')->middleware(['auth','revalidate']);
Route::post('healthy-and-safety/reports/morbid-cases/formValidation', 'MorbidityCaseController@formValidation')->name('morbiditycase.post');

//------------------------------hema range-------------------------------------------------------
Route::get('hemarange/index', 'HematologyRangeController@index')->name('hemarange.index');
Route::get('hemarange/getList', 'HematologyRangeController@getList')->name('hemarange.getList');
Route::get('hemarange/store', 'HematologyRangeController@store');
Route::post('hemarange/delete', 'HematologyRangeController@Delete');
Route::post('hemarange/rangeActiveInactive', 'HematologyRangeController@rangeActiveInactive');
Route::resource('hemarange', 'HematologyRangeController')->middleware(['auth','revalidate']);
Route::post('hemarange/formValidation', 'HematologyRangeController@formValidation')->name('hemarange.post');

//------------------------------hema-parameters master------------------------------------------
Route::get('hemaparameter/index', 'HematologyParametersController@index')->name('hemaparameter.index');
Route::get('hemaparameter/getList', 'HematologyParametersController@getList')->name('hemaparameter.getList');
Route::get('hemaparameter/store', 'HematologyParametersController@store');
Route::post('hemaparameter/delete', 'HematologyParametersController@Delete');
Route::post('hemaparameter/parametersActiveInactive', 'HematologyParametersController@parametersActiveInactive');
Route::resource('hemaparameter', 'HematologyParametersController')->middleware(['auth','revalidate']);
Route::post('hemaparameter/formValidation', 'HematologyParametersController@formValidation')->name('hemaparameter.post');

//--------------------------------------Ho-disease------------------------------------
Route::get('hodisease/index', 'HoDiseaseController@index')->name('hodisease.index');
Route::get('hodisease/getList', 'HoDiseaseController@getList')->name('hodisease.getList');
Route::get('hodisease/store', 'HoDiseaseController@store');
Route::post('hodisease/delete', 'HoDiseaseController@Delete');
Route::post('hodisease/diseaseActiveInactive', 'HoDiseaseController@diseaseActiveInactive');
Route::resource('hodisease', 'HoDiseaseController')->middleware(['auth','revalidate']);
Route::post('hodisease/formValidation', 'HoDiseaseController@formValidation')->name('hodisease.post');

//-----------------------------------ho-diagnosis------------------------------------------------------
Route::get('healthy-and-safety/setup-data/diagnosis/index', 'DiagnosisController@index')->name('diagnosis.index');
Route::get('healthy-and-safety/setup-data/diagnosis/getList', 'DiagnosisController@getList')->name('diagnosis.getList');
Route::get('healthy-and-safety/setup-data/diagnosis/store', 'DiagnosisController@store');
Route::post('healthy-and-safety/setup-data/diagnosis/delete', 'DiagnosisController@Delete');
Route::post('diagnosis/diagnosisActiveInactive', 'DiagnosisController@diagnosisActiveInactive');
Route::resource('healthy-and-safety/setup-data/diagnosis', 'DiagnosisController')->middleware(['auth','revalidate']);
Route::post('healthy-and-safety/setup-data/diagnosis/formValidation', 'DiagnosisController@formValidation')->name('diagnosis.post');

 //---------------------------setupdata- ho-icd10 group master-----------------------------------
 Route::get('icdgroup/index', 'IcdGroupController@index')->name('icdgroup.index');
 Route::get('icdgroup/getList', 'IcdGroupController@getList')->name('icdgroup.getList');
 Route::get('icdgroup/store', 'IcdGroupController@store');
 Route::post('icdgroup/delete', 'IcdGroupController@Delete');
 Route::post('icdgroup/icdgroupActiveInactive', 'IcdGroupController@icdgroupActiveInactive');
 Route::resource('icdgroup', 'IcdGroupController')->middleware(['auth','revalidate']);
 Route::post('icdgroup/formValidation', 'IcdGroupController@formValidation')->name('icdgroup.post');


 //----------------------------setup data- icd10 codes master--------------------------------------
 Route::get('icdcode/index', 'IcdCodeController@index')->name('icdcode.index');
 Route::get('icdcode/getList', 'IcdCodeController@getList')->name('icdcode.getList');
 Route::get('icdcode/store', 'IcdCodeController@store');
 Route::post('icdcode/delete', 'IcdCodeController@Delete');
 Route::post('icdcode/icdCodeActiveInactive', 'IcdCodeController@icdCodeActiveInactive');
 Route::resource('icdcode', 'IcdCodeController')->middleware(['auth','revalidate']);
 Route::post('icdcode/formValidation', 'IcdCodeController@formValidation')->name('icdcode.post');

// ------------------------------------- RptAppraisers ------------------------------
 Route::prefix('appraisers')->group(function () {
        Route::get('', 'RptAppraisersController@index')->name('rptappraisers.index');
    });

Route::middleware(['auth'])->prefix('real-property')->group(function () {
    /* Designation Routes */
   
    Route::prefix('land-stripping')->group(function () {
        Route::get('', 'RptLandStrippingController@index')->name('rptlandstripping.index');
    });
});
Route::get('rptappraisers/index', 'RptAppraisersController@index')->name('rptappraisers.index');
Route::get('rptappraisers/getList', 'RptAppraisersController@getList')->name('rptappraisers.getList');
Route::get('rptappraisers/store', 'RptAppraisersController@store');
Route::post('rptappraisers/delete', 'RptAppraisersController@Delete');
Route::post('getEmployeeDetails', 'RptAppraisersController@getEmployeeDetails');
Route::post('rptappraisers/appraisersActiveInactive', 'RptAppraisersController@appraisersActiveInactive');
Route::resource('rptappraisers', 'RptAppraisersController')->middleware(['auth','revalidate']);
Route::post('rptappraisers/formValidation', 'RptAppraisersController@formValidation')->name('rptappraisers.post');
// ------------------------------------- RptLandStripping ------------------------------
Route::get('rptlandstripping/index', 'RptLandStrippingController@index')->name('rptlandstripping.index');
Route::get('rptlandstripping/getList', 'RptLandStrippingController@getList')->name('rptlandstripping.getList');
Route::get('rptlandstripping/store', 'RptLandStrippingController@store');
Route::post('rptlandstripping/ActiveInactive', 'RptLandStrippingController@ActiveInactive');
Route::post('rptlandstripping/delete', 'RptLandStrippingController@Delete');
Route::resource('rptlandstripping', 'RptLandStrippingController')->middleware(['auth','revalidate']);
Route::post('rptlandstripping/formValidation', 'RptLandStrippingController@formValidation')->name('rptlandstripping.post');

// ------------------------------------- RptLandUnitValue ------------------------------
Route::get('real-property/unit-value/land', 'RptLandUnitValueController@index')->name('rptlandunitvalue.index');
Route::get('rptlandunitvalue/getList', 'RptLandUnitValueController@getList')->name('rptlandunitvalue.getList');
Route::get('rptlandunitvalue/class-ajax-request', 'RptLandUnitValueController@classAjaxRequest');
Route::get('getsubclass', 'RptLandUnitValueController@getsubclass');
Route::get('getActualdata', 'RptLandUnitValueController@getActualdata');
Route::get('rptlandunitvalue/store', 'RptLandUnitValueController@store');
Route::post('rptlandunitvalue/ActiveInactive', 'RptLandUnitValueController@ActiveInactive');
Route::post('rptlandunitvalue/ApproveUnapprove', 'RptLandUnitValueController@ApproveUnapprove');
Route::post('rptlandunitvalue/delete', 'RptLandUnitValueController@Delete');
Route::resource('rptlandunitvalue', 'RptLandUnitValueController')->middleware(['auth','revalidate']);
Route::post('rptlandunitvalue/formValidation', 'RptLandUnitValueController@formValidation')->name('rptlandunitvalue.post');


// ------------------------------------- Rpt Building Flooring ------------------------------
Route::middleware(['auth'])->prefix('real-property')->group(function () {
    /* Designation Routes */
    Route::prefix('building')->group(function () {
        Route::prefix('flooring')->group(function () {
          Route::get('', 'RptBuildingFlooringController@index')->name('rptbuildingflooring.index');
        });
        Route::prefix('walling')->group(function () {
          Route::get('', 'RptBuildingWallingController@index')->name('rptbuildingwalling.index');
        });
        Route::prefix('roofing')->group(function () {
          Route::get('', 'BuildingRoofingController@index')->name('buildingroofing.index');
        });
        Route::prefix('extra-item')->group(function () {
         Route::get('', 'RptBuildingExtraItemController@index')->name('rptbuildingextraitem.index');
        });
    });
});



//--------------------------------------Building Roofing------------------------------
Route::get('buildingroofing/index', 'BuildingRoofingController@index')->name('buildingroofing.index');
Route::post('buildingroofing/delete', 'BuildingRoofingController@Delete');
Route::get('buildingroofing/getList', 'BuildingRoofingController@getList')->name('buildingroofing.getList');
Route::get('buildingroofing/store', 'BuildingRoofingController@store');
Route::post('buildingroofing/ActiveInactive', 'BuildingRoofingController@ActiveInactive');
Route::resource('buildingroofing', 'BuildingRoofingController')->middleware(['auth','revalidate']);
Route::post('buildingroofing/formValidation', 'BuildingRoofingController@formValidation')->name('buildingroofing.post');
Route::get('rptbuildingflooring/index', 'RptBuildingFlooringController@index')->name('rptbuildingflooring.index');
Route::get('rptbuildingflooring/getList', 'RptBuildingFlooringController@getList')->name('rptbuildingflooring.getList');
Route::get('rptbuildingflooring/store', 'RptBuildingFlooringController@store');
Route::post('rptbuildingflooring/ActiveInactive', 'RptBuildingFlooringController@ActiveInactive');
Route::post('rptbuildingflooring/delete', 'RptBuildingFlooringController@Delete');
Route::resource('rptbuildingflooring', 'RptBuildingFlooringController')->middleware(['auth','revalidate']);
Route::post('rptbuildingflooring/formValidation', 'RptBuildingFlooringController@formValidation')->name('rptbuildingflooring.post');

// ------------------------------------- Rpt Building Walling ------------------------------
Route::get('rptbuildingwalling/index', 'RptBuildingWallingController@index')->name('rptbuildingwalling.index');
Route::get('rptbuildingwalling/getList', 'RptBuildingWallingController@getList')->name('rptbuildingwalling.getList');
Route::get('rptbuildingwalling/store', 'RptBuildingWallingController@store');
Route::post('rptbuildingwalling/ActiveInactive', 'RptBuildingWallingController@ActiveInactive');
Route::post('rptbuildingwalling/delete', 'RptBuildingWallingController@Delete');
Route::resource('rptbuildingwalling', 'RptBuildingWallingController')->middleware(['auth','revalidate']);
Route::post('rptbuildingwalling/formValidation', 'RptBuildingWallingController@formValidation')->name('rptbuildingwalling.post');
// ------------------------------------- Rpt Building Extra Item ------------------------------
Route::get('rptbuildingextraitem/index', 'RptBuildingExtraItemController@index')->name('rptbuildingextraitem.index');
Route::get('rptbuildingextraitem/getList', 'RptBuildingExtraItemController@getList')->name('rptbuildingextraitem.getList');
Route::get('rptbuildingextraitem/store', 'RptBuildingExtraItemController@store');
Route::post('rptbuildingextraitem/ActiveInactive', 'RptBuildingExtraItemController@ActiveInactive');
Route::post('rptbuildingextraitem/delete', 'RptBuildingExtraItemController@Delete');
Route::resource('rptbuildingextraitem', 'RptBuildingExtraItemController')->middleware(['auth','revalidate']);
Route::post('rptbuildingextraitem/formValidation', 'RptBuildingExtraItemController@formValidation')->name('rptbuildingextraitem.post');

Route::middleware(['auth'])->prefix('real-property')->group(function () {
    /* Designation Routes */
    Route::prefix('rivision-setup')->group(function () {
        Route::get('', 'RevisionyearController@index')->name('revisionyear.index');
    });
});
//--------------------------------------Revision Year------------------------------
Route::get('real-property/revision-setup', 'RevisionyearController@index')->name('revisionyear.index');
Route::get('real-property/revision-setup/assessor-ajax-request', 'RevisionyearController@assesserAjaxRequest');
Route::post('revisionyear/delete', 'RevisionyearController@Delete');
Route::get('revisionyear/getList', 'RevisionyearController@getList')->name('revisionyear.getList');
Route::get('revisionyear/store', 'RevisionyearController@store');
Route::post('revisionyear/defaultUpdateCode', 'RevisionyearController@defaultUpdateCode');
Route::post('revisionyear/ActiveInactive', 'RevisionyearController@ActiveInactive');
Route::resource('revisionyear', 'RevisionyearController')->middleware(['auth','revalidate']);
Route::post('revisionyear/formValidation', 'RevisionyearController@formValidation')->name('revisionyear.post');
// ------------------------------------- Rpt Building Type ------------------------------

Route::middleware(['auth'])->prefix('real-property')->group(function () {
    /* Designation Routes */
    Route::prefix('building')->group(function () {
        Route::prefix('type')->group(function () {
          Route::get('', 'RptBuildingTypeController@index')->name('rptbuildingtype.index');
        });
    });
});

Route::get('rptbuildingtype/index', 'RptBuildingTypeController@index')->name('rptbuildingtype.index');
Route::get('rptbuildingtype/getList', 'RptBuildingTypeController@getList')->name('rptbuildingtype.getList');
Route::get('rptbuildingtype/store', 'RptBuildingTypeController@store');
Route::post('rptbuildingtype/ActiveInactive', 'RptBuildingTypeController@ActiveInactive');
Route::post('rptbuildingtype/delete', 'RptBuildingTypeController@Delete');
Route::resource('rptbuildingtype', 'RptBuildingTypeController')->middleware(['auth','revalidate']);
Route::post('rptbuildingtype/formValidation', 'RptBuildingTypeController@formValidation')->name('rptbuildingtype.post');
Route::middleware(['auth'])->prefix('real-property')->group(function () {
    /* Designation Routes */
    Route::prefix('unit-value')->group(function () {
        Route::prefix('building')->group(function () {
          Route::get('', 'RptBuildingUnitValueController@index')->name('rptbuildingunitvalue.index');
        });
        Route::prefix('plant-trees')->group(function () {
           Route::get('', 'RptPlantTressUnitValueController@index')->name('rptplanttressunitvalue.index');
        });
    });
});

// ------------------------------------- RptPlantTressUnitValue ------------------------------
Route::get('rptplanttressunitvalue/index', 'RptPlantTressUnitValueController@index')->name('rptplanttressunitvalue.index');
Route::get('rptplanttressunitvalue/getList', 'RptPlantTressUnitValueController@getList')->name('rptplanttressunitvalue.getList');
Route::get('getPlantTreesSubClassDetailss', 'RptPlantTressUnitValueController@getPlantTreesSubClassDetailss');
Route::post('getRptsubclass', 'RptPlantTressUnitValueController@getRptsubclass');
Route::get('planttree-ajax-request', 'RptPlantTressUnitValueController@plantTreeAjaxRequest');
Route::get('class-subclass-ajax-request', 'RptPlantTressUnitValueController@classSubAjaxRequest');
Route::get('rptplanttressunitvalue/store', 'RptPlantTressUnitValueController@store');
Route::post('rptplanttressunitvalue/ActiveInactive', 'RptPlantTressUnitValueController@ActiveInactive');
Route::post('rptplanttressunitvalue/ApproveUnapprove', 'RptPlantTressUnitValueController@ApproveUnapprove');
Route::post('rptplanttressunitvalue/delete', 'RptPlantTressUnitValueController@Delete');
Route::resource('rptplanttressunitvalue', 'RptPlantTressUnitValueController')->middleware(['auth','revalidate']);
Route::post('rptplanttressunitvalue/formValidation', 'RptPlantTressUnitValueController@formValidation')->name('rptplanttressunitvalue.post');
// ------------------------------------- Rpt Building Unit Value ------------------------------
Route::get('rptbuildingunitvalue/index', 'RptBuildingUnitValueController@index')->name('rptbuildingunitvalue.index');
Route::get('rptbuildingunitvalue/getList', 'RptBuildingUnitValueController@getList')->name('rptbuildingunitvalue.getList');
Route::post('rptbuildingunitvalue/ApproveUnapprove', 'RptBuildingUnitValueController@ApproveUnapprove');
Route::get('rptbuildingunitvalue/store', 'RptBuildingUnitValueController@store');
Route::get('rptbuildingunitvalue/kind-ajax-request', 'RptBuildingUnitValueController@kindAjaxRequest');
Route::get('rptbuildingunitvalue/type-ajax-request', 'RptBuildingUnitValueController@typeAjaxRequest');
Route::post('rptbuildingunitvalue/ActiveInactive', 'RptBuildingUnitValueController@ActiveInactive');

Route::post('rptbuildingunitvalue/ApproveUnapprove', 'RptBuildingUnitValueController@ApproveUnapprove');
Route::post('rptbuildingunitvalue/delete', 'RptBuildingUnitValueController@Delete');
Route::resource('rptbuildingunitvalue', 'RptBuildingUnitValueController')->middleware(['auth','revalidate']);
Route::post('rptbuildingunitvalue/formValidation', 'RptBuildingUnitValueController@formValidation')->name('rptbuildingunitvalue.post');
Route::middleware(['auth'])->prefix('real-property')->group(function () {
    /* Designation Routes */
    Route::prefix('rpt-cto-basic-sef-taxrate')->group(function () {
        Route::get('', 'RptCtoBasicSefTaxrateController@index')->name('rptctobasicseftaxrate.index');
    });
    Route::prefix('cto-payment-schedule')->group(function () {
        Route::get('', 'ScheduleDescriptionController@index')->name('scheduledescription.index');
    });
    Route::prefix('schedule-description')->group(function () {
        Route::get('', 'ScheduleDescriptionController@index')->name('scheduledescription.index');
    });
    Route::prefix('rpt-cto-penalty-schedule')->group(function () {
        Route::get('', 'CtoPaymentScheduleController@index')->name('ctopaymentschedule.index');
    });
    Route::prefix('rpt-cto-penalty-table')->group(function () {
        Route::get('', 'RptCtoPenaltyTableController@index')->name('rptctopenaltytable.index');
    });
    Route::prefix('tax-revenue')->group(function () {
        Route::get('', 'RptCtoTaxRevenueController@index')->name('taxrevenue.index');
    });
});
//--------------------------------------Tax Revenue------------------------------
Route::get('taxrevenue/getList', 'RptCtoTaxRevenueController@getList')->name('taxrevenue.getList');
Route::post('taxrevenue/store', 'RptCtoTaxRevenueController@store');
Route::get('taxrevenue/store', 'RptCtoTaxRevenueController@create');

//--------------------------------------RPT Cto Basic Sef Taxrate------------------------------
Route::get('rptctobasicseftaxrate/index', 'RptCtoBasicSefTaxrateController@index')->name('rptctobasicseftaxrate.index');
Route::post('rptctobasicseftaxrate/delete', 'RptCtoBasicSefTaxrateController@Delete');
Route::get('rptctobasicseftaxrate/getList', 'RptCtoBasicSefTaxrateController@getList')->name('rptctobasicseftaxrate.getList');
Route::get('rptctobasicseftaxrate/store', 'RptCtoBasicSefTaxrateController@store');
Route::post('rptctobasicseftaxrate/ActiveInactive', 'RptCtoBasicSefTaxrateController@ActiveInactive');
Route::resource('rptctobasicseftaxrate', 'RptCtoBasicSefTaxrateController')->middleware(['auth','revalidate']);
Route::post('rptctobasicseftaxrate/formValidation', 'RptCtoBasicSefTaxrateController@formValidation')->name('rptctobasicseftaxrate.post');
//--------------------------------------Schedule Description------------------------------
Route::get('scheduledescription/index', 'ScheduleDescriptionController@index')->name('scheduledescription.index');
Route::post('scheduledescription/delete', 'ScheduleDescriptionController@Delete');
Route::get('scheduledescription/getList', 'ScheduleDescriptionController@getList')->name('scheduledescription.getList');
Route::get('scheduledescription/store', 'ScheduleDescriptionController@store');
Route::post('scheduledescription/ActiveInactive', 'ScheduleDescriptionController@ActiveInactive');
Route::resource('scheduledescription', 'ScheduleDescriptionController')->middleware(['auth','revalidate']);
Route::post('scheduledescription/formValidation', 'ScheduleDescriptionController@formValidation')->name('scheduledescription.post');

//-------------------------------------RPT Cto Payment Schedule------------------------------
Route::get('ctopaymentschedule/index', 'CtoPaymentScheduleController@index')->name('ctopaymentschedule.index');
Route::post('ctopaymentschedule/delete', 'CtoPaymentScheduleController@Delete');
Route::get('ctopaymentschedule/getList', 'CtoPaymentScheduleController@getList')->name('ctopaymentschedule.getList');
Route::get('ctopaymentschedule/store', 'CtoPaymentScheduleController@store');
Route::post('ctopaymentschedule/ActiveInactive', 'CtoPaymentScheduleController@ActiveInactive');
Route::resource('ctopaymentschedule', 'CtoPaymentScheduleController')->middleware(['auth','revalidate']);
Route::post('ctopaymentschedule/formValidation', 'CtoPaymentScheduleController@formValidation')->name('ctopaymentschedule.post');
// ------------------------------------- Rpt Cto Penalty Table ------------------------------
Route::get('rptctopenaltytable/index', 'RptCtoPenaltyTableController@index')->name('rptctopenaltytable.index');
Route::get('rptctopenaltytable/getList', 'RptCtoPenaltyTableController@getList')->name('rptctopenaltytable.getList');
Route::get('rptctopenaltytable/store', 'RptCtoPenaltyTableController@store');
Route::post('rptctopenaltytable/delete', 'RptCtoPenaltyTableController@Delete');
Route::post('rptctopenaltytable/activeinactive', 'RptCtoPenaltyTableController@activeInactive');
Route::resource('rptctopenaltytable', 'RptCtoPenaltyTableController')->middleware(['auth','revalidate']);
Route::post('rptctopenaltytable/formValidation', 'RptCtoPenaltyTableController@formValidation')->name('rptctopenaltytable.post');
// ------------------------------------- Rpt Cto Penalty Schedule ------------------------------
Route::get('rptctopenaltyschedule/index', 'RptCtoPenaltyScheduleController@index')->name('rptctopenaltyschedule.index');
Route::get('rptctopenaltyschedule/getList', 'RptCtoPenaltyScheduleController@getList')->name('rptctopenaltyschedule.getList');
Route::get('rptctopenaltyschedule/store', 'RptCtoPenaltyScheduleController@store');
Route::post('rptctopenaltyschedule/delete', 'RptCtoPenaltyScheduleController@Delete');
Route::resource('rptctopenaltyschedule', 'RptCtoPenaltyScheduleController')->middleware(['auth','revalidate']);
Route::post('rptctopenaltyschedule/formValidation', 'RptCtoPenaltyScheduleController@formValidation')->name('rptctopenaltyschedule.post');




// //--------------------------------------Assessment Level------------------------------
// Route::get('assessmentlevel/index', 'AssessmentLevelController@index')->name('assessmentlevel.index');
// Route::post('assessmentlevel/delete', 'AssessmentLevelController@Delete');
// Route::get('getPropertyKindDetails', 'AssessmentLevelController@getPropertyKindDetails');
// Route::get('getPropertyClassDetails', 'AssessmentLevelController@getPropertyClassDetails');
// Route::get('assessmentlevel/view', 'AssessmentLevelController@view');
// Route::get('pau_actual_use_code', 'AssessmentLevelController@pau_actual_use_code');
// Route::get('rvy_revision_year', 'AssessmentLevelController@rvy_revision_year');
// Route::get('assessmentlevel/getList', 'AssessmentLevelController@getList')->name('assessmentlevel.getList');
// Route::get('assessmentlevel/store', 'AssessmentLevelController@store');
// Route::post('assessmentlevel/ActiveInactive', 'AssessmentLevelController@ActiveInactive');
// Route::post('assessmentlevel/ApproveUnapprove', 'AssessmentLevelController@ApproveUnapprove');
// Route::resource('assessmentlevel', 'AssessmentLevelController')->middleware(['auth','revalidate']);
// Route::post('assessmentlevel/formValidation', 'AssessmentLevelController@formValidation')->name('assessmentlevel.post');






// ------------------------------------- BPLO Bussiness Permit & Licence ------------------------------
Route::get('bplopenaltyrates/index', 'BploAssessPenaltyRatesController@index')->name('bplopenaltyrates.index');
Route::post('updatepenaltyrates', 'BploAssessPenaltyRatesController@updatePenaltyRates');
Route::resource('bplopenaltyrates', 'BploAssessPenaltyRatesController')->middleware(['auth','revalidate']);

// ------------------------------------- Running Balance ------------------------------
Route::get('runningbalance/index', 'RunningBalanceController@index')->name('runningbalance.index');
Route::post('runningbalance', 'RunningBalanceController@updateRunningBalance');
Route::resource('runningbalance', 'RunningBalanceController')->middleware(['auth','revalidate']);


// ------------------------------------- Summary Report ------------------------------
Route::get('summaryreport/index', 'SummaryReportController@index')->name('summaryreport.index');
Route::post('summaryreport', 'SummaryReportController@updateSummaryReport');
Route::resource('summaryreport', 'SummaryReportController')->middleware(['auth','revalidate']);

// ------------------------------------- System Setup ------------------------------
// Route::get('configuration/index', 'ConfigurationController@index')->name('configuration.index');
// Route::post('updateconfiguration', 'ConfigurationController@updateSystemSetup');
// Route::resource('configuration', 'ConfigurationController')->middleware(['auth','revalidate']);

// //------------------------------------- Graduated tax ------------------------------
// Route::get('bplobusinesstax/index', 'BploBussinessTaxController@index')->name('bplobusinesstax.index');
Route::get('bplobusinesstax/getList', 'BploBussinessTaxController@getList')->name('bplobusinesstax.getList');
Route::get('bplobusinesstax/store', 'BploBussinessTaxController@store');
Route::post('bussinesstax/delete', 'BploBussinessTaxController@Delete');
Route::resource('bplobusinesstax', 'BploBussinessTaxController')->middleware(['auth','revalidate']);
Route::post('bplobusinesstax/formValidation', 'BploBussinessTaxController@formValidation')->name('bplobusinesstax.post');
// ------------------------------------- BPLO Assess Tax Rate Effectivit ------------------------------
// Route::get('bploassessetaxrateeffectivit/index', 'BploAssessTaxRateEffectivitController@index')->name('bploassessetaxrateeffectivit.index');
Route::get('bploassessetaxrateeffectivit/getList', 'BploAssessTaxRateEffectivitController@getList')->name('bploassessetaxrateeffectivit.getList');
Route::get('bploassessetaxrateeffectivit/store', 'BploAssessTaxRateEffectivitController@store');
Route::post('bploassessetaxrateeffectivit/ActiveInactive', 'BploAssessTaxRateEffectivitController@ActiveInactive');
Route::post('bploassessetaxrateeffectivit/delete', 'BploAssessTaxRateEffectivitController@Delete');
Route::resource('bploassessetaxrateeffectivit', 'BploAssessTaxRateEffectivitController')->middleware(['auth','revalidate']);
Route::post('bploassessetaxrateeffectivit/formValidation', 'BploAssessTaxRateEffectivitController@formValidation')->name('bploassessetaxrateeffectivit.post');

// // ------------------------------------- Fixed Taxes & Fees ------------------------------
// Route::get('bplobusinessfixedtax/index', 'BploBusinessFixedTaxController@index')->name('bplobusinessfixedtax.index');
Route::get('getTaxDetails', 'BploBusinessFixedTaxController@getTaxDetails');
Route::get('getcodebyid', 'BploBusinessFixedTaxController@getTaxDetails');
Route::post('getactivityCodebyid', 'BploBusinessFixedTaxController@getactivityCodebyid');
Route::post('bplobusinessfixedtax/delete', 'BploBusinessFixedTaxController@Delete');
Route::get('getbbaDetails', 'BploBusinessFixedTaxController@getbbaDetails');
Route::get('bplobusinessfixedtax/getList', 'BploBusinessFixedTaxController@getList')->name('bplobusinessfixedtax.getList');
Route::get('bplobusinessfixedtax/store', 'BploBusinessFixedTaxController@store');
Route::resource('bplobusinessfixedtax', 'BploBusinessFixedTaxController')->middleware(['auth','revalidate']);
Route::post('bplobusinessfixedtax/formValidation', 'BploBusinessFixedTaxController@formValidation')->name('bplobusinessfixedtax.post');
Route::delete('bplobusinessfixedtax/destroy', 'BploBusinessFixedTaxController@destroy')->name('bplobusinessfixedtax.destroy');
// ------------------------------------- Check Type Master ------------------------------
// Route::get('checktypemaster/index', 'CheckTypeMasterController@index')->name('checktypemaster.index');
// Route::get('checktypemaster/getList', 'CheckTypeMasterController@getList')->name('checktypemaster.getList');
// Route::get('checktypemaster/store', 'CheckTypeMasterController@store');
// Route::post('checktypemaster/ActiveInactive', 'CheckTypeMasterController@ActiveInactive');
// Route::post('checktypemaster/delete', 'CheckTypeMasterController@Delete');
// Route::resource('checktypemaster', 'CheckTypeMasterController')->middleware(['auth','revalidate']);
// Route::post('checktypemaster/formValidation', 'CheckTypeMasterController@formValidation')->name('checktypemaster.post');

// // ------------------------------------- Setup Pop Receipts ------------------------------
// Route::get('setuppopreceipts/index', 'SetupPopReceiptsController@index')->name('setuppopreceipts.index');
// Route::get('setuppopreceipts/getList', 'SetupPopReceiptsController@getList')->name('setuppopreceipts.getList');
// Route::get('setuppopreceipts/store', 'SetupPopReceiptsController@store');
// Route::post('setuppopreceipts/delete', 'SetupPopReceiptsController@Delete');
// Route::post('setuppopreceipts/PrintOptionUpdate', 'SetupPopReceiptsController@PrintOptionUpdate');
// Route::resource('setuppopreceipts', 'SetupPopReceiptsController')->middleware(['auth','revalidate']);
// Route::post('setuppopreceipts/formValidation', 'SetupPopReceiptsController@formValidation')->name('setuppopreceipts.post');                 
// // ------------------------------------- Collectors ------------------------------
// Route::get('collectors/index', 'CollectorsController@index')->name('collectors.index');
// Route::get('collectors/getList', 'CollectorsController@getList')->name('collectors.getList');
// Route::get('collectors/store', 'CollectorsController@store');
// Route::post('collectors/ActiveInactive', 'CollectorsController@ActiveInactive');
// Route::post('collectors/delete', 'CollectorsController@Delete');
// Route::resource('collectors', 'CollectorsController')->middleware(['auth','revalidate']);
// Route::post('collectors/formValidation', 'CollectorsController@formValidation')->name('collectors.post');

// Route::get('country/index', 'CountryController@index')->name('country.index');
Route::get('country/getList', 'CountryController@getList')->name('country.getList');
Route::get('country/store', 'CountryController@store');
Route::post('country/ActiveInactive', 'CountryController@ActiveInactive');
Route::post('country/delete', 'CountryController@Delete');
Route::post('country/defaultUpdateCode', 'CountryController@defaultUpdateCode');
Route::resource('country', 'CountryController')->middleware(['auth','revalidate']);
Route::post('country/formValidation', 'CountryController@formValidation')->name('country.post');

// ------------------------------------- Profile Region ------------------------------
Route::get('profileregion/index', 'ProfileRegionController@index')->name('profileregion.index');
Route::get('profileregion/getList', 'ProfileRegionController@getList')->name('profileregion.getList');
Route::get('profileregion/store', 'ProfileRegionController@store');
Route::post('profileregion/ActiveInactive', 'ProfileRegionController@ActiveInactive');
Route::post('profileregion/delete', 'ProfileRegionController@Delete');
Route::resource('profileregion', 'ProfileRegionController')->middleware(['auth','revalidate']);
Route::post('profileregion/formValidation', 'ProfileRegionController@formValidation')->name('profileregion.post');
// ------------------------------------- Profile Province ------------------------------
Route::get('profileprovince/index', 'ProfileProvinceController@index')->name('profileprovince.index');
Route::get('profileprovince/getList', 'ProfileProvinceController@getList')->name('profileprovince.getList');
Route::get('profileprovince/store', 'ProfileProvinceController@store');
Route::post('profileprovince/ActiveInactive', 'ProfileProvinceController@ActiveInactive');
Route::get('ProfileProvinceData', 'ProfileProvinceController@ProfileProvinceData');
Route::post('profileprovince/delete', 'ProfileProvinceController@Delete');
Route::resource('profileprovince', 'ProfileProvinceController')->middleware(['auth','revalidate']);
Route::post('profileprovince/formValidation', 'ProfileProvinceController@formValidation')->name('profileprovince.post');

// ------------------------------------- Profile Municipalitie ------------------------------
Route::get('profilemunicipalitie/index', 'ProfileMunicipalitieController@index')->name('profilemunicipalitie.index');
Route::get('profilemunicipalitie/getList', 'ProfileMunicipalitieController@getList')->name('profilemunicipalitie.getList');
Route::get('profilemunicipalitie/store', 'ProfileMunicipalitieController@store');
Route::post('profilemunicipalitie/ActiveInactive', 'ProfileMunicipalitieController@ActiveInactive');
Route::post('profilemunicipalitie/updateDataMenuPermission', 'ProfileMunicipalitieController@updateDataMenuPermission');
Route::post('getprofileRegioncodeId', 'ProfileMunicipalitieController@getprofileRegioncodeId');
Route::post('getUACScode', 'ProfileMunicipalitieController@getUACScode');
Route::post('profilemunicipalitie/delete', 'ProfileMunicipalitieController@Delete');
Route::get('getOfficerposition', 'ProfileMunicipalitieController@getOfficerposition');
Route::resource('profilemunicipalitie', 'ProfileMunicipalitieController')->middleware(['auth','revalidate']);
Route::post('profilemunicipalitie/formValidation', 'ProfileMunicipalitieController@formValidation')->name('profilemunicipalitie.post');

// // ------------------------------------- BfpOccupancyType ------------------------------
Route::get('bfpoccupancytype/index', 'BfpOccupancyTypeController@index')->name('bfpoccupancytype.index');
Route::get('bfpoccupancytype/getList', 'BfpOccupancyTypeController@getList')->name('bfpoccupancytype.getList');
Route::get('bfpoccupancytype/store', 'BfpOccupancyTypeController@store');
Route::post('bfpoccupancytype/ActiveInactive', 'BfpOccupancyTypeController@ActiveInactive');
Route::post('bfpoccupancytype/delete', 'BfpOccupancyTypeController@Delete');
Route::resource('bfpoccupancytype', 'BfpOccupancyTypeController')->middleware(['auth','revalidate']);
Route::post('bfpoccupancytype/formValidation', 'BfpOccupancyTypeController@formValidation')->name('bfpoccupancytype.post');


 //--------------Congratulations----------------------

// Route::get('configuration/index', 'ConfigurationController@index')->name('configuration.index');
// Route::get('configuration/getList', 'ConfigurationController@getList')->name('configuration.getList');
// Route::get('configuration/store', 'ConfigurationController@store');
// Route::resource('configuration', 'ConfigurationController')->middleware(['auth','revalidate']);
// Route::post('configuration/formValidation', 'ConfigurationController@formValidation')->name('configuration.post');






// ------------------------------------- BPLO payments Schedule------------------------------
Route::get('bplopaymentsschedule/index', 'BploAssessPaymentScheduleController@index')->name('bplopaymentsschedule.index');
Route::get('bplopaymentsschedule/getList', 'BploAssessPaymentScheduleController@getList')->name('bplopaymentsschedule.getList');
Route::get('bplopaymentsschedule/store', 'BploAssessPaymentScheduleController@store');
Route::resource('bplopaymentsschedule', 'BploAssessPaymentScheduleController')->middleware(['auth','revalidate']);
Route::post('bplopaymentsschedule/formValidation', 'BploAssessPaymentScheduleController@formValidation')->name('bplopaymentsschedule.post');



//------------------------------------- Upload Occupancy Report------------------------------
Route::any('inspectionreportfile', 'BfpAfterInspectionReportController@InspectionReportFiles')->name('inspectionreportfile');
Route::any('inspectionreportfile/store', 'BfpAfterInspectionReportController@store');


//----------------------------------- Inspection Report ----------------


Route::any('inspectionreportfile', 'BfpAfterInspectionReportController@InspectionReportFiles')->name('inspectionreportfile');
Route::post('inspectionreportfile/store', 'BfpAfterInspectionReportController@store');
Route::post('inspectionreportfile/delete', 'BfpAfterInspectionReportController@Deletefile');



//--------------------------------BFP Certificates -----------------------
Route::get('bfpcertificate', 'BfpCertificateController@index')->name('bfpcertificate.index');
Route::get('bfpcertificate/getList', 'BfpCertificateController@getList')->name('bfpcertificate.getList');
Route::get('bfpcertificate/store', 'BfpCertificateController@store');
Route::post('bfpcertificate/deleteAttachment', 'BfpCertificateController@deleteAttachment');
Route::post('bfpcertificate/uploadAttachment', 'BfpCertificateController@uploadAttachment');
Route::post('bfpcertificate/ActiveInactive', 'BfpCertificateController@ActiveInactive');
Route::post('bfpCertificate-approvedsataus', 'BfpCertificateController@approvedsataus');
Route::post('bfpCertificate-recommendingapproval', 'BfpCertificateController@recommendingsataus');
Route::get('getEmployeeApprovedDetails', 'BfpCertificateController@getEmployeeApprovedDetails');
Route::get('getEmployeeRecommendinDetails', 'BfpCertificateController@getEmployeeRecommendinDetails');
Route::get('getBusineClient', 'BfpCertificateController@getBusineClient');
Route::resource('bfpcertificate', 'BfpCertificateController')->middleware(['auth','revalidate']);
Route::post('bfpcertificate/formValidation', 'BfpCertificateController@formValidation')->name('bfpcertificate.post');
Route::any('bfpCertificatePrint', 'BfpCertificateController@bfpCertificatePrint');
Route::any('isPrinted', 'BfpCertificateController@isPrinted');
Route::any('bfpCertificateRelease', 'BfpCertificateController@bfpCertificateRelease');

//-------------------------------- Enro Bplo App Clearance -----------------------
Route::get('bploappclearance', 'EnroBploappClearanceController@index')->name('bploappclearance.index');
Route::get('bploappclearance/getList', 'EnroBploappClearanceController@getList')->name('bploappclearance.getList');
Route::get('bploappclearance/store', 'EnroBploappClearanceController@store');
Route::resource('bploappclearance', 'EnroBploappClearanceController')->middleware(['auth','revalidate']);
Route::post('bploappclearance/formValidation', 'EnroBploappClearanceController@formValidation')->name('bploappclearance.post');
Route::any('bploappclearanceprint', 'EnroBploappClearanceController@bploAppclearancePrint');
Route::any('bploappclearancereport', 'EnroBploappClearanceController@EnroBploAppClearanceReport');
Route::any('enroinspectionreportprint', 'EnroBploappClearanceController@bploInspectionReportPrint');

//-------------------------------- Pdo Bplo App Clearance -----------------------
Route::get('pdobploappclearance', 'PdoBploAppClearanceController@index')->name('pdobploappclearance.index');
Route::get('pdobploappclearance/getList', 'PdoBploAppClearanceController@getList')->name('pdobploappclearance.getList');
Route::get('pdobploappclearance/store', 'PdoBploAppClearanceController@store');
Route::resource('pdobploappclearance', 'PdoBploAppClearanceController')->middleware(['auth','revalidate']);
Route::post('pdobploappclearance/formValidation', 'PdoBploAppClearanceController@formValidation')->name('pdobploappclearance.post');
Route::any('pdobploappclearancePrint', 'PdoBploAppClearanceController@bploAppclearancePrint');
Route::get('getpdoPbloClearancedetails', 'PdoBploAppClearanceController@getpdoPbloClearancedetails');



//-------------------------------- HO App Sanitary -----------------------
// Route::get('hoappsanitary', 'HoApplicationSanitaryController@index')->name('hoappsanitary.index');
// Route::get('hoappsanitary/getList', 'HoApplicationSanitaryController@getList')->name('hoappsanitary.getList');
// Route::get('hoappsanitary/store', 'HoApplicationSanitaryController@store');
// Route::resource('hoappsanitary', 'HoApplicationSanitaryController')->middleware(['auth','revalidate']);
// Route::post('hoappsanitary/formValidation', 'HoApplicationSanitaryController@formValidation')->name('hoappsanitary.post');
// Route::any('hoappsanitaryPrint', 'HoApplicationSanitaryController@hoapphealthcertPrint');
// Route::get('getpbloAppdetails', 'HoApplicationSanitaryController@getpbloAppdetails');
// Route::any('hoapphealthsanitaryprint', 'HoApplicationSanitaryController@HoHealthSanitaryPrint');




// ------------------------------------- RPT UPDATE CODE ------------------------------
Route::get('rptupdatecode/index', 'RptUpdateCodeController@index')->name('rptupdatecode.index');
Route::get('rptupdatecode/getList', 'RptUpdateCodeController@getList')->name('rptupdatecode.getList');
Route::get('rptupdatecode/store', 'RptUpdateCodeController@store');
Route::post('rptupdatecode/PrintOptionUpdateCode', 'RptUpdateCodeController@PrintOptionUpdateCode');
Route::post('rptupdatecode/ActiveInactive', 'RptUpdateCodeController@ActiveInactive');
Route::post('rptupdatecode/delete', 'RptUpdateCodeController@Delete');
Route::resource('rptupdatecode', 'RptUpdateCodeController')->middleware(['auth','revalidate']);
Route::post('rptupdatecode/formValidation', 'RptUpdateCodeController@formValidation')->name('rptupdatecode.post');

Route::middleware(['auth'])->prefix('real-property')->group(function () {
          Route::prefix('update-code')->group(function (){
                Route::get('', 'RptUpdateCodeController@index')->name('rptupdatecode.index');
           });
});
// ------------------------------------- RPT Property KIND ------------------------------




// ------------------------------------- RPT Prpoerty ------------------------------
/* Duplicate Copy */
Route::post('rptproperty/dup', 'RptPropertyController@dupFunctionlaity');
Route::post('rptproperty/dup/submit', 'RptPropertyController@dupFunctionlaitySbubmit');
/* Duplicate Copy */

/* Dispute and Removed */
Route::post('rptproperty/dp', 'RptPropertyController@dpFunctionlaity');
Route::post('rptproperty/dp/submit', 'RptPropertyController@dpFunctionlaitySbubmit');
/* Dispute and Removed */

/* Transfer Of Ownership*/
Route::post('rptproperty/tr', 'RptPropertyController@trFunctionlaity');
Route::post('rptproperty/tr/submit', 'RptPropertyController@trFunctionlaitySbubmit');
/* Transfer Of Ownership*/

/* Physical Changes */
Route::post('rptproperty/pc', 'RptPropertyController@pcFunctionlaity');
Route::post('rptproperty/pc/submit', 'RptPropertyController@pcFunctionlaitySbubmit');
/* Physical Changes */

/* Physical Changes */
Route::post('rptproperty/ssd', 'RptPropertyController@ssdFunctionlaity');
Route::post('rptproperty/ssd/submit', 'RptPropertyController@ssdFunctionlaitySbubmit');
/* Physical Changes */

/* Reclassification  */
Route::post('rptproperty/rc', 'RptPropertyController@rcFunctionlaity');
Route::post('rptproperty/rc/submit', 'RptPropertyController@rcFunctionlaitySbubmit');
/* Reclassification */

/* Consolidation  */
Route::post('rptproperty/cs', 'RptPropertyController@csFunctionlaity');
Route::post('rptproperty/cs/addtaxdeclarationinlist', 'RptPropertyController@addTaxDeclarationInList');
Route::post('rptproperty/cs/loadtaxdecltoconsoldate', 'RptPropertyController@loadTaxDeclToConsolidate');
Route::post('rptproperty/cs/deletetaxdeclaration', 'RptPropertyController@csDeleteTaxDeclaration');
Route::post('rptproperty/cs/submit', 'RptPropertyController@csFunctionlaitySbubmit');
/* Consolidation */

/* Subdivision */
Route::post('rptproperty/sd/step2', 'RptPropertyController@sdFunctionlaitySecondStep');
Route::post('rptproperty/sd', 'RptPropertyController@sdFunctionlaity');
Route::post('rptproperty/sd/getlisting', 'RptPropertyController@sdgetListing');
Route::post('rptproperty/sd/submit', 'RptPropertyController@sdFunctionlaitySbubmit');
Route::post('rptproperty/sd/deletetaxdeclaration', 'RptPropertyController@sdDeleteTaxDeclaration');
Route::post('rptproperty/sd/updateTaxDeclaration', 'RptPropertyController@sdUpdateTaxDeclaration');
/* Subdivision */

/* Land Appraisal Adjustment Factor */
Route::post('rptproperty/landAppraisalFactors', 'RptPropertyController@storelandAppraisalFactors');
Route::get('rptproperty/landappraisalfactors', 'RptPropertyController@landAppraisalFactorsform');
/* Land Appraisal Adjustment Factor */

/* Load Assessement Summary */
Route::get('rptproperty/loadassessementsummary', 'RptPropertyController@loadAssessementSummary');
Route::get('rptproperty/relatedBuildingsummary', 'RptPropertyController@relatedBuildingsummary');
/* Load Assessement Summary */

/* Load Previous Owner Details */
//Route::post('rptproperty/loadpreviousowner', 'RptPropertyController@loadPreviousOwner');
Route::any('rptproperty/loadpreviousowner', 'RptPropertyController@loadPreviousOwner');
Route::get('rptproperty/getpreviousownertddetails', 'RptPropertyController@getPreviousOwnertdDetails');
Route::post('rptproperty/deletepreviousownertd', 'RptPropertyController@deletePreviousOwnerTd');
/* Load Previous Owner Details */
Route::get('real-property/property-data/property', 'RptPropertyController@index')->name('rptproperty.index');
Route::get('rptproperty/getList', 'RptPropertyController@getList')->name('rptproperty.getList');
Route::get('rptproperty/gettdsforajaxselectlist', 'RptPropertyController@getTdsForAjaxSelectList');
Route::get('rptproperty/gebrgyforajaxselectlist', 'RptPropertyController@getBrgyForAjaxSelectList');
Route::get('rptproperty/store', 'RptPropertyController@store');
Route::get('rptproperty/anootationspeicalpropertystatus', 'RptPropertyController@anootationSpeicalPropertystatus');
Route::post('rptproperty/anootationspeicalpropertystatus', 'RptPropertyController@storeAnootationSpeicalPropertystatus');
Route::get('rptproperty/swornstatment', 'RptPropertyController@swornStatment');
Route::post('rptproperty/swornstatment', 'RptPropertyController@storeSwornStatment');
Route::post('getClientRptProperty', 'RptPropertyController@getClientRptProperty');
Route::post('getOrNumberDetails', 'RptPropertyController@getOrNumberDetails');
Route::post('getEmpDetails', 'RptPropertyController@getEmpDetails');
Route::post('getIssuanceDetails', 'RptPropertyController@getIssuanceDetails');
Route::post('rptproperty/deleteannotaion', 'RptPropertyController@deleteAnnotaion');
Route::get('rptproperty/loadpropertyannotations', 'RptPropertyController@loadPropertyAnnotations');
Route::get('rptproperty/storetressadjustmentfactor', 'RptPropertyController@storePlantsAdjustmentFactor');
Route::get('rptproperty/getmuncipalitycodedetails', 'RptPropertyController@getMuncipalityCodes');
Route::get('rptproperty/getbarangycodedetails', 'RptPropertyController@getBarangyCodeDetails');
Route::post('rptproperty/storeplantstreesadjustmentfactor', 'RptPropertyController@storePlantsTreesAdjustmentFactor');
Route::get('rptproperty/storelandappraisal', 'RptPropertyController@showLandAppraisalForm');
Route::post('rptproperty/storelandappraisal', 'RptPropertyController@storeLandAppraisal');
Route::get('rptproperty/gelandstrippingdetails', 'RptPropertyController@getLandStrippingDetails');
Route::get('rptproperty/getdistrictcodes', 'RptPropertyController@getDistrictCodes');
Route::get('rptproperty/get-all-profiles', 'RptPropertyController@getAllProfiles');
Route::get('rptproperty/getprofiledata', 'RptPropertyController@getprofileData');
Route::get('rptproperty/getpropertyowners', 'RptPropertyController@getPropertyOwners');
Route::post('rptproperty/deletelandappraisal', 'RptPropertyController@deleteLandAppraisal');
Route::post('rptproperty/deleteplanttreeappraisal', 'RptPropertyController@deletePlantTreeAppraisal');
Route::post('rptproperty/getlandunitvalue', 'RptPropertyController@getLandUnitValue');
Route::post('rptproperty/getassessementlevel', 'RptPropertyController@getAssessementLevel');
Route::post('rptproperty/getplanttreeunitvalue', 'RptPropertyController@getPlantTreeUnitValue');
Route::get('rptproperty/getsubclasses', 'RptPropertyController@getSubClasses');
Route::get('rptproperty/getactualuses', 'RptPropertyController@getActualUses');
Route::get('rptproperty/getclassdetails', 'RptPropertyController@getClassDetails');
Route::get('rptproperty/getrevisionyeardetails', 'RptPropertyController@getRevisionYearDetails');
Route::post('rptproperty/getplantstreesadjustmentfactor', 'RptPropertyController@getPlantsTreesAdjustmentFactor');
Route::post('rptproperty/getlandappraisal', 'RptPropertyController@getLandAppraisal');
Route::get('rptproperty/approve', 'RptPropertyController@Approve');
Route::post('rptproperty/approve', 'RptPropertyController@storeApprove');
Route::post('rptproperty/delete', 'RptPropertyController@Delete');
Route::get('rptproperty/certificateprint', 'RptPropertyController@PrintCertificate')->name('rptproperty.certificateprint');
Route::any('rptproperty/bulkUpload', 'RptPropertyController@bulkUpload');
Route::any('rptproperty/downloadlandTDTemplate', 'RptPropertyController@downloadlandTDTemplate');
Route::post('rptproperty/uploadBulkLandData', 'RptPropertyController@uploadBulkLandData');
Route::any('rptproperty/downloadLandAppraisalTemplate', 'RptPropertyController@downloadLandAppraisalTemplate');
/*Route::any('downloadMeasurePaxTemplate', 'ApplicantController@downloadMeasurePaxTemplate');*/
// Route::get('rptproperty/nolandcertificateprint', 'RptPropertyController@PrintnolandCertificate')->name('rptproperty.nolandcertificateprint');;
Route::resource('rptproperty', 'RptPropertyController')->middleware(['auth','revalidate']);
Route::post('rptproperty/formValidation', 'RptPropertyController@formValidation')->name('rptproperty.post');

//-------- RPt Building-------//
Route::get('rptbuildingy/addfloorvaluedescription', 'RptPropertyBuidingController@addfloorvaluedescription');
Route::get('rptbuildingy/addbuildingstructres', 'RptPropertyBuidingController@getAllBuildingStructure');

// ------------------------------------- Rpt Building Flooring ------------------------------

Route::get('rptbuildingflooring/index', 'RptBuildingFlooringController@index')->name('rptbuildingflooring.index');
Route::get('rptbuildingflooring/getList', 'RptBuildingFlooringController@getList')->name('rptbuildingflooring.getList');
Route::get('rptbuildingflooring/store', 'RptBuildingFlooringController@store');
Route::post('rptbuildingflooring/delete', 'RptBuildingFlooringController@Delete');
Route::resource('rptbuildingflooring', 'RptBuildingFlooringController')->middleware(['auth','revalidate']);
Route::post('rptbuildingflooring/formValidation', 'RptBuildingFlooringController@formValidation')->name('rptbuildingflooring.post');

// ------------------------------------- Rpt Building Walling ------------------------------
Route::get('rptbuildingwalling/index', 'RptBuildingWallingController@index')->name('rptbuildingwalling.index');
Route::get('rptbuildingwalling/getList', 'RptBuildingWallingController@getList')->name('rptbuildingwalling.getList');
Route::get('rptbuildingwalling/store', 'RptBuildingWallingController@store');
Route::post('rptbuildingwalling/delete', 'RptBuildingWallingController@Delete');
Route::resource('rptbuildingwalling', 'RptBuildingWallingController')->middleware(['auth','revalidate']);
Route::post('rptbuildingwalling/formValidation', 'RptBuildingWallingController@formValidation')->name('rptbuildingwalling.post');


// -------------------------------------Cbo Budget ------------------------------
// Route::get('cbobudget/index', 'CboBudgetController@index')->name('cbobudget.index');
Route::get('cbobudget/getList', 'CboBudgetController@getList')->name('cbobudget.getList');
Route::get('cbobudget/store', 'CboBudgetController@store');
Route::get('cbobudget/adjust', 'CboBudgetController@adjust');

Route::get('getdivclasss', 'CboBudgetController@getdivclasss');
Route::post('cbobudget/ActiveInactive', 'CboBudgetController@ActiveInactive');
Route::post('cbobudget/DraftSubmit', 'CboBudgetController@DraftSubmit');
Route::post('cbobudget/Unlock', 'CboBudgetController@Unlock');

Route::post('cbobudget/Approve', 'CboBudgetController@Approve');
Route::post('cbobudget/delete', 'CboBudgetController@Delete');
Route::resource('cbobudget', 'CboBudgetController')->middleware(['auth','revalidate']);
Route::post('cbobudget/formValidation', 'CboBudgetController@formValidation')->name('cbobudget.post');

// ------------------------------------- Human Resource ------------------------------
Route::middleware(['auth'])->prefix('human-resource')->group(function () {
    /* Designation Routes */
    Route::prefix('designations')->group(function () {
        Route::get('', 'HrDesignationController@index')->name('hr.designations.index');
        Route::get('lists', 'HrDesignationController@lists')->name('hr.designations.lists');
        Route::post('store', 'HrDesignationController@store')->name('hr.designations.store');
        Route::get('edit/{id}', 'HrDesignationController@find')->name('hr.designations.find');
        Route::put('update/{id}', 'HrDesignationController@update')->name('hr.designations.update');
        Route::put('remove/{id}', 'HrDesignationController@remove')->name('hr.designations.remove');
        Route::put('restore/{id}', 'HrDesignationController@restore')->name('hr.designations.restore');
    });

    /* Employeee Routes */
    Route::prefix('employees')->group(function () {
        Route::get('', 'HrEmployeeController@index')->name('hr.employees.index');
        Route::get('index2', 'HrEmployeeController@index2')->name('hr.employees.index2');
        Route::post('remove-row', 'HrEmployeeController@removeRowRelation')->name('hr.employees.remove-row');
        Route::post('select-division/{department}', 'HrEmployeeController@selectDivision')->name('hr.employees.select-division');
        Route::match(array('GET', 'POST'), 'store2', 'HrEmployeeController@create')->name('hr.employees.create');
        Route::get('lists', 'HrEmployeeController@lists')->name('hr.employees.lists');
        Route::post('store', 'HrEmployeeController@store')->name('hr.employees.store');
        Route::match(array('GET', 'POST'), 'store2', 'HrEmployeeController@create')->name('hr.employees.create');
        Route::post('store2/formValidation', 'HrEmployeeController@validation')->name('hr.employees.lists');
        Route::get('edit/{id}', 'HrEmployeeController@find')->name('hr.employees.validation');
        Route::put('update/{id}', 'HrEmployeeController@update')->name('hr.employees.update');
        Route::put('remove/{id}', 'HrEmployeeController@remove')->name('hr.employees.remove');
        Route::put('restore/{id}', 'HrEmployeeController@restore')->name('hr.employees.restore');
        Route::get('reload-division-via-department/{department}', 'HrEmployeeController@reload_division')->name('hr.employees.reload-division');
        Route::get('upload-lists/{id}', 'HrEmployeeController@upload_lists')->name('hr.employees.upload-lists');
        Route::post('upload/{id}', 'HrEmployeeController@upload')->name('hr.employees.upload');
        Route::get('download/{id}', 'HrEmployeeController@download')->name('hr.employees.download');
        Route::delete('delete/{id}', 'HrEmployeeController@delete')->name('hr.employees.delete');
    });
});

// ------------------------------------- tax-revenue ------------------------------
Route::middleware(['auth'])->prefix('tax-revenue')->group(function () {

    Route::get('', 'TreasurerIncomeAccountsController@index')->name('incomeaccount.index');
    Route::get('getList', 'TreasurerIncomeAccountsController@getList')->name('incomeaccount.getList');
    Route::any('store', 'TreasurerIncomeAccountsController@store');
    Route::any('importincomeaccount', 'TreasurerIncomeAccountsController@importExcel')->name('incomeaccount.importExcel');
    Route::post('store/formValidation', 'TreasurerIncomeAccountsController@formValidation')->name('incomeaccount.post');
});

// ------------------------------------- payment-system ------------------------------
Route::middleware(['auth'])->prefix('payment-system')->group(function () {
     /* psic-libraries */
     Route::prefix('side-menu')->group(function () {
        Route::prefix('check-type-master-file')->group(function () {
            Route::get('', 'CheckTypeMasterController@index')->name('checktypemaster.index');
            Route::get('getList', 'CheckTypeMasterController@getList')->name('checktypemaster.getList');
            Route::any('store', 'CheckTypeMasterController@store');
            Route::post('ActiveInactive', 'CheckTypeMasterController@ActiveInactive');
            Route::post('delete', 'CheckTypeMasterController@Delete');
            Route::post('store/formValidation', 'CheckTypeMasterController@formValidation')->name('checktypemaster.post');
        });
        Route::prefix('system-setup')->group(function () {
            Route::get('', 'ConfigurationController@index')->name('configuration.index');
            Route::any('updateconfiguration', 'ConfigurationController@updateSystemSetup');
           
        });
        Route::prefix('setup-receipts')->group(function () {
            Route::get('', 'SetupPopReceiptsController@index')->name('setuppopreceipts.index');
            Route::get('getList', 'SetupPopReceiptsController@getList')->name('setuppopreceipts.getList');
            Route::any('store', 'SetupPopReceiptsController@store');
            Route::post('delete', 'SetupPopReceiptsController@Delete');
            Route::post('PrintOptionUpdate', 'SetupPopReceiptsController@PrintOptionUpdate');
            Route::post('store/formValidation', 'SetupPopReceiptsController@formValidation')->name('setuppopreceipts.post');        
        });
        Route::prefix("collectors-file")->group(function () {
            Route::get('', 'CollectorsController@index')->name('collectors.index');
            Route::get('getList', 'CollectorsController@getList')->name('collectors.getList');
            Route::any('store', 'CollectorsController@store');
            Route::post('ActiveInactive', 'CollectorsController@ActiveInactive');
            Route::post('delete', 'CollectorsController@Delete');
            Route::post('store/formValidation', 'CollectorsController@formValidation')->name('collectors.post');
        });
        
    });
});





// ------------------------------------- Administrative ------------------------------
Route::post('psicsubclass/ActiveInactive', 'PsicSubclassController@ActiveInactive');

Route::middleware(['auth'])->prefix('administrative')->group(function () {
     /* psic-libraries */
     Route::prefix('psic-libraries')->group(function () {
        Route::prefix('section')->group(function () {
            Route::get('', 'PsicSectionController@index')->name('psicsection.index');
            Route::get('psicsection/store', 'PsicSectionController@store');
            Route::post('getdivisionbysection', 'PsicSectionController@getdivisionbysection');
            Route::resource('psicsection', 'PsicSectionController')->middleware(['auth','revalidate']);
            Route::post('psicsection/formValidation', 'PsicSectionController@formValidation')->name('psicsection.post');
        });
        Route::prefix('division')->group(function () {
            Route::get('', 'PsicDivisionController@index')->name('psicdivision.index');
            Route::get('psicdivision/store', 'PsicDivisionController@store');
            Route::post('getgroupbydivision', 'PsicDivisionController@getgroupbydivision');
            Route::resource('psicdivision', 'PsicDivisionController')->middleware(['auth','revalidate']);
            Route::post('psicdivision/formValidation', 'PsicDivisionController@formValidation')->name('psicdivision.post');
        });
        Route::prefix('group')->group(function () {
            Route::get('', 'PsicGroupController@index')->name('psicgroup.index');
            Route::get('psicgroup/store', 'PsicGroupController@store');
            Route::post('getclassbygroup', 'PsicGroupController@getclassbygroup');
            Route::resource('psicgroup', 'PsicGroupController')->middleware(['auth','revalidate']);
            Route::post('psicgroup/formValidation', 'PsicGroupController@formValidation')->name('psicgroup.post');
        });
        Route::prefix('class')->group(function () {
            Route::get('', 'PsicClassController@index')->name('psicsubclass.index');
            Route::get('psicsubclass/store', 'PsicClassController@store');
            Route::post('getsubclassbyclass', 'PsicClassController@getsubclassbyclass');
            Route::get('psicsubclass/getList', 'PsicClassController@getList')->name('psicsubclass.getList');
            Route::resource('psicsubclass', 'PsicClassController')->middleware(['auth','revalidate']);
            Route::post('psicsubclass/formValidation', 'PsicClassController@formValidation')->name('psicsubclass.post');
        });
        Route::prefix('sub-class')->group(function () {
            Route::get('', 'PsicSubclassController@index')->name('psicsubclass.index');
            Route::get('psicsubclass/store', 'PsicSubclassController@store');
            Route::post('getsubclassbyclass', 'PsicSubclassController@getsubclassbyclass');
            Route::get('psicsubclass/getList', 'PsicSubclassController@getList')->name('psicsubclass.getList');
            Route::resource('psicsubclass', 'PsicSubclassController')->middleware(['auth','revalidate']);
            Route::post('psicsubclass/formValidation', 'PsicSubclassController@formValidation')->name('psicsubclass.post');
            Route::get('psicsubclass/view', 'PsicSubclassController@Establishmentview');
        });   
             

    });
     /* psic-libraries end */

    /* requirements */
     Route::prefix('requirements')->group(function () {
        Route::prefix('manage')->group(function () {
            Route::get('', 'RequirementsController@index')->name('requirements.index');
        });
        // Route::prefix('business-permit')->group(function () {
        //     Route::get('', 'BblorequirementController@index')->name('bplorequirements.index');
        //     Route::get('getList', 'BblorequirementController@getList')->name('bplorequirements.getList');
        //     Route::any('store', 'BblorequirementController@store');
        //     Route::get('view', 'BblorequirementController@view');
        //     Route::post('delete', 'BblorequirementController@Delete');
        //     Route::post('store/formValidation', 'BblorequirementController@formValidation')->name('bplorequirements.post');
        // });
    });
     /* requirements end */
    /* bus.-classificication */
    Route::prefix('bus-classificication')->group(function () {
        Route::prefix('manage')->group(function () {
            Route::get('', 'BploBusinessClassificationController@index')->name('bplobusinessclassification.index');
            Route::get('bplobusinessclassification/getList', 'BploBusinessClassificationController@getList')->name('bplobusinessclassification.getList');
            Route::get('bplobusinessclassification/store', 'BploBusinessClassificationController@store');
            Route::post('bplobusinessclassification/ActiveInactive', 'BploBusinessClassificationController@ActiveInactive');
           
            Route::post('bplobusinessclassification/formValidation', 'BploBusinessClassificationController@formValidation')->name('bplobusinessclassification.post');
            Route::post('gettaxTypeBytaxClass', 'BploBusinessClassificationController@gettaxTypeBytaxClass');


        });
        Route::prefix('activities')->group(function () {
            Route::get('', 'BploBusinessActivityController@index')->name('bplobusinessactivity.index');
            Route::get('getList', 'BploBusinessActivityController@getList')->name('bplobusinessactivity.getList');
            Route::any('store', 'BploBusinessActivityController@store');
            Route::post('ActiveInactive', 'BploBusinessActivityController@ActiveInactive');
            Route::post('store/formValidation', 'BploBusinessActivityController@formValidation')->name('bplobusinessactivity.post');
            Route::post('gettaxTypeBytaxClassActivity', 'BploBusinessActivityController@gettaxTypeBytaxClassActivity');
            Route::post('getClassificationBytaxClassType', 'BploBusinessActivityController@getClassificationBytaxClassType');
        });
    });
     /* bus.-classificication end */
    /* tax-libraries */
     Route::prefix('tax-libraries')->group(function () {
        Route::prefix('category')->group(function () {
            Route::get('', 'TaxCategoryController@index')->name('taxcategory.index');
            Route::any('store', 'TaxCategoryController@store');
            Route::get('getList', 'TaxCategoryController@getList')->name('taxcategory.getList');
            Route::post('ActiveInactive', 'TaxCategoryController@ActiveInactive');
            Route::post('store/formValidation', 'TaxCategoryController@formValidation')->name('taxcategory.post');
       });
        Route::prefix('type')->group(function () {
            // ------------------------------------- Tax Type ------------------------------
            Route::get('', 'TaxtypeController@index')->name('taxtype.index');
            Route::any('store', 'TaxtypeController@store');
            Route::get('getList', 'TaxtypeController@getList')->name('taxtype.getList');
            Route::post('ActiveInactive', 'TaxtypeController@ActiveInactive');
            Route::post('store/formValidation', 'TaxtypeController@formValidation')->name('taxtype.post');


        });

        Route::prefix('class')->group(function () {
             // ------------------------------------- Tax Class ------------------------------
            Route::get('', 'TaxClassController@index')->name('taxclass.index');
            Route::get('getList', 'TaxClassController@getList')->name('taxclass.getList');
            Route::any('store', 'TaxClassController@store');
            Route::post('ActiveInactive', 'TaxClassController@ActiveInactive');
            Route::post('store/formValidation', 'TaxClassController@formValidation')->name('taxclass.post');
        });
        
    });
     /* tax-libraries end */
    /* taxation-schedule */
     Route::prefix('taxation-schedule')->group(function () {
        Route::prefix('tax-rate-effectivity')->group(function () {
            
            Route::get('', 'BploAssessTaxRateEffectivitController@index')->name('bploassessetaxrateeffectivit.index');
            // Route::get('getList', 'BploAssessTaxRateEffectivitController@getList')->name('bploassessetaxrateeffectivit.getList');
            // Route::any('store', 'BploAssessTaxRateEffectivitController@store');
            // Route::post('ActiveInactive', 'BploAssessTaxRateEffectivitController@ActiveInactive');
            // Route::post('delete', 'BploAssessTaxRateEffectivitController@Delete');
            
            // Route::post('store/formValidation', 'BploAssessTaxRateEffectivitController@formValidation')->name('bploassessetaxrateeffectivit.post');
       });
        Route::prefix('fixed-taxes-and-fees')->group(function () {
            Route::get('', 'BploBusinessFixedTaxController@index')->name('bplobusinessfixedtax.index');
            // Route::get('getTaxDetails', 'BploBusinessFixedTaxController@getTaxDetails');
            // Route::post('delete', 'BploBusinessFixedTaxController@Delete');
            // Route::get('getbbaDetails', 'BploBusinessFixedTaxController@getbbaDetails');
            // Route::get('getList', 'BploBusinessFixedTaxController@getList')->name('bplobusinessfixedtax.getList');
            // Route::any('store', 'BploBusinessFixedTaxController@store');
            // Route::resource('bplobusinessfixedtax', 'BploBusinessFixedTaxController')->middleware(['auth','revalidate']);
            // Route::post('store/formValidation', 'BploBusinessFixedTaxController@formValidation')->name('bplobusinessfixedtax.post');
            // Route::delete('bplobusinessfixedtax/destroy', 'BploBusinessFixedTaxController@destroy')->name('bplobusinessfixedtax.destroy');
            // Route::get('getTaxDetails', 'BploBusinessFixedTaxController@getTaxDetails');

        });
        

        Route::prefix('graduated-new-tax')->group(function () {
             // ------------------------------------- Tax Class ------------------------------
            Route::get('', 'BploBussinessTaxController@index')->name('bplobusinesstax.index');
           //  Route::get('getList', 'BploBussinessTaxController@getList')->name('bplobusinesstax.getList');
           //  Route::any('store', 'BploBussinessTaxController@store');
           //  Route::post('delete', 'BploBussinessTaxController@Delete');
           // Route::post('store/formValidation', 'BploBussinessTaxController@formValidation')->name('bplobusinesstax.post');
        });
        
    });
     /* taxation-schedule end */
    /* business-types */
     Route::prefix('business-types')->group(function () {
        Route::get('', 'TypeofbussinessController@index')->name('typeofbussiness.index');
        Route::any('store', 'TypeofbussinessController@store');
        Route::post('store/formValidation', 'TypeofbussinessController@formValidation')->name('typeofbussiness.post');  
     });
    /* business-types end */
  
    /* fire-protection */
     Route::prefix('fire-protection')->group(function () {
        Route::prefix('occupancy-type')->group(function () {
            Route::get('', 'BfpOccupancyTypeController@index')->name('bfpoccupancytype.index');
            Route::get('getList', 'BfpOccupancyTypeController@getList')->name('bfpoccupancytype.getList');
            Route::any('store', 'BfpOccupancyTypeController@store');
            Route::post('ActiveInactive', 'BfpOccupancyTypeController@ActiveInactive');
            Route::post('delete', 'BfpOccupancyTypeController@Delete');
            Route::post('store/formValidation', 'BfpOccupancyTypeController@formValidation')->name('bfpoccupancytype.post');
        });   
     });

    /* fire-protection end */

    /* country */
     Route::prefix('country')->group(function () {
        Route::get('', 'CountryController@index')->name('country.index');
        // Route::get('getList', 'CountryController@getList')->name('country.getList');
        // Route::any('store', 'CountryController@store');
        // Route::post('ActiveInactive', 'CountryController@ActiveInactive');
        // Route::post('delete', 'CountryController@Delete');
        // Route::post('store/formValidation', 'CountryController@formValidation')->name('country.post');
     });
    
    /* country end */
    /* system-parameters */
     Route::prefix('system-parameters')->group(function () {
        Route::get('', 'BploSystemParametersController@index')->name('bplosystemparameters.index');
        Route::get('bplosystemparameters/getList', 'BploSystemParametersController@getList')->name('bplosystemparameters.getList');
        Route::get('bplosystemparameters/store', 'BploSystemParametersController@store');
        Route::post('bplosystemparameters/delete', 'BploSystemParametersController@Delete');
        Route::resource('bplosystemparameters', 'BploSystemParametersController')->middleware(['auth','revalidate']);
        Route::post('bplosystemparameters/formValidation', 'BploSystemParametersController@formValidation')->name('bplosystemparameters.post');
     });

    /* system-parameters end */
    /* system-parameters */
     Route::prefix('application-types')->group(function () {
        Route::get('', 'PbloapptypeController@index')->name('pbloapptypes.index');
        Route::get('pbloapptypes/store', 'PbloapptypeController@store');
        Route::resource('pbloapptypes', 'PbloapptypeController')->middleware(['auth','revalidate']);
        Route::post('pbloapptypes/formValidation', 'PbloapptypeController@formValidation')->name('pbloapptypes.post');
     });

    /* system-parameters end */
    
    
     
    /* General Services Routes */
    Route::prefix('general-services')->group(function () {
        /* Purchase Types Routes */
        Route::prefix('purchase-types')->group(function () {
            Route::get('', 'AdminGsoPurchaseTypeController@index')->name('admin-gso.purchase-type.index');
            Route::get('lists', 'AdminGsoPurchaseTypeController@lists')->name('admin-gso.purchase-type.lists');
            Route::post('store', 'AdminGsoPurchaseTypeController@store')->name('admin-gso.purchase-type.store');
            Route::get('edit/{id}', 'AdminGsoPurchaseTypeController@find')->name('admin-gso.purchase-type.find');
            Route::put('update/{id}', 'AdminGsoPurchaseTypeController@update')->name('admin-gso.purchase-type.update');
            Route::put('remove/{id}', 'AdminGsoPurchaseTypeController@remove')->name('admin-gso.purchase-type.remove');
            Route::put('restore/{id}', 'AdminGsoPurchaseTypeController@restore')->name('admin-gso.purchase-type.restore');
        });

        /* Item Types Routes */
        Route::prefix('item-types')->group(function () {
            Route::get('', 'AdminGsoItemTypeController@index')->name('admin-gso.item-type.index');
            Route::get('lists', 'AdminGsoItemTypeController@lists')->name('admin-gso.item-type.lists');
            Route::post('store', 'AdminGsoItemTypeController@store')->name('admin-gso.item-type.store');
            Route::get('edit/{id}', 'AdminGsoItemTypeController@find')->name('admin-gso.item-type.find');
            Route::put('update/{id}', 'AdminGsoItemTypeController@update')->name('admin-gso.item-type.update');
            Route::put('remove/{id}', 'AdminGsoItemTypeController@remove')->name('admin-gso.item-type.remove');
            Route::put('restore/{id}', 'AdminGsoItemTypeController@restore')->name('admin-gso.item-type.restore');
        });

        

        /* Unit Of Measurements Routes */
        Route::prefix('unit-of-measurements')->group(function () {
            Route::get('', 'AdminGsoUnitOfMeasurementController@index')->name('admin-gso.unit-of-measurement.index');
            Route::get('lists', 'AdminGsoUnitOfMeasurementController@lists')->name('admin-gso.unit-of-measurement.lists');
            Route::post('store', 'AdminGsoUnitOfMeasurementController@store')->name('admin-gso.unit-of-measurement.store');
            Route::get('edit/{id}', 'AdminGsoUnitOfMeasurementController@find')->name('admin-gso.unit-of-measurement.find');
            Route::put('update/{id}', 'AdminGsoUnitOfMeasurementController@update')->name('admin-gso.unit-of-measurement.update');
            Route::put('remove/{id}', 'AdminGsoUnitOfMeasurementController@remove')->name('admin-gso.unit-of-measurement.remove');
            Route::put('restore/{id}', 'AdminGsoUnitOfMeasurementController@restore')->name('admin-gso.unit-of-measurement.restore');
        });

        /* Product Lines Routes */  
        Route::prefix('product-lines')->group(function () {
            Route::get('', 'AdminGsoProductLineController@index')->name('admin-gso.product-line.index');
            Route::get('lists', 'AdminGsoProductLineController@lists')->name('admin-gso.product-line.lists');
            Route::post('store', 'AdminGsoProductLineController@store')->name('admin-gso.product-line.store');
            Route::get('edit/{id}', 'AdminGsoProductLineController@find')->name('admin-gso.product-line.find');
            Route::put('update/{id}', 'AdminGsoProductLineController@update')->name('admin-gso.product-line.update');
            Route::put('remove/{id}', 'AdminGsoProductLineController@remove')->name('admin-gso.product-line.remove');
            Route::put('restore/{id}', 'AdminGsoProductLineController@restore')->name('admin-gso.product-line.restore');
        });

    });

    /* address */
    Route::prefix('address')->group(function () {
    Route::prefix('region')->group(function () {
        Route::get('', 'ProfileRegionController@index')->name('profileregion.index');
        Route::get('profileregion/getList', 'ProfileRegionController@getList')->name('profileregion.getList');
        Route::get('profileregion/store', 'ProfileRegionController@store');
        Route::post('profileregion/ActiveInactive', 'ProfileRegionController@ActiveInactive');
        Route::post('profileregion/delete', 'ProfileRegionController@Delete');
        Route::resource('profileregion', 'ProfileRegionController')->middleware(['auth','revalidate']);
        Route::post('profileregion/formValidation', 'ProfileRegionController@formValidation')->name('profileregion.post');
    });
    Route::prefix('province')->group(function () {
        Route::get('', 'ProfileProvinceController@index')->name('profileprovince.index');
        Route::get('profileprovince/getList', 'ProfileProvinceController@getList')->name('profileprovince.getList');
        Route::get('profileprovince/store', 'ProfileProvinceController@store');
        Route::post('profileprovince/ActiveInactive', 'ProfileProvinceController@ActiveInactive');
        Route::get('ProfileProvinceData', 'ProfileProvinceController@ProfileProvinceData');
        Route::post('profileprovince/delete', 'ProfileProvinceController@Delete');
        Route::resource('profileprovince', 'ProfileProvinceController')->middleware(['auth','revalidate']);
        Route::post('profileprovince/formValidation', 'ProfileProvinceController@formValidation')->name('profileprovince.post');
    }); 
    Route::prefix('municipality')->group(function () {
        Route::get('', 'ProfileMunicipalitieController@index')->name('profilemunicipalitie.index');
        Route::get('profilemunicipalitie/getList', 'ProfileMunicipalitieController@getList')->name('profilemunicipalitie.getList');
        Route::get('profilemunicipalitie/store', 'ProfileMunicipalitieController@store');
        Route::post('profilemunicipalitie/ActiveInactive', 'ProfileMunicipalitieController@ActiveInactive');
        Route::post('getprofileRegioncodeId', 'ProfileMunicipalitieController@getprofileRegioncodeId');
        Route::post('profilemunicipalitie/delete', 'ProfileMunicipalitieController@Delete');
        Route::get('getOfficerposition', 'ProfileMunicipalitieController@getOfficerposition');
        Route::resource('profilemunicipalitie', 'ProfileMunicipalitieController')->middleware(['auth','revalidate']);
        Route::post('profilemunicipalitie/formValidation', 'ProfileMunicipalitieController@formValidation')->name('profilemunicipalitie.post');
    });
    Route::prefix('barangay')->group(function () {
        Route::get('', 'BarangayController@index')->name('barangay.index');
        Route::get('barangay/store', 'BarangayController@store');
        Route::get('barangay/getList', 'BarangayController@getList')->name('barangay.getList');
        Route::post('getprofileProvcodeId', 'BarangayController@getprofileProvcodeId');
        Route::post('getDistrictCodes', 'BarangayController@getDistrictCodes');
        Route::post('barangay/ActiveInactive', 'BarangayController@ActiveInactive');
        Route::resource('barangay', 'BarangayController')->middleware(['auth','revalidate']);
        Route::post('barangay/formValidation', 'BarangayController@formValidation')->name('barangay.post');
    });
    Route::prefix('district')->group(function () {
        Route::get('', 'DistrictController@index')->name('district.index');
        Route::post('district/delete', 'DistrictController@Delete');
        Route::get('getLocalIdDetails', 'DistrictController@getLocalIdDetails');
        Route::post('district/districtActiveInactive', 'DistrictController@districtActiveInactive');
        Route::get('district/getList', 'DistrictController@getList')->name('district.getList');
        Route::get('district/store', 'DistrictController@store'); 
    });
    
    
    });
    /* address end */

    /* BAC Routes Start */
    Route::prefix('bac')->group(function () {
        /* Procurement Mode Routes */
        Route::prefix('procurement-modes')->group(function () {
            Route::get('', 'AdminBacProcurementModeController@index')->name('admin-bac.procurement-mode.index');
            Route::get('lists', 'AdminBacProcurementModeController@lists')->name('admin-bac.procurement-mode.lists');
            Route::post('store', 'AdminBacProcurementModeController@store')->name('admin-bac.procurement-mode.store');
            Route::get('edit/{id}', 'AdminBacProcurementModeController@find')->name('admin-bac.procurement-mode.find');
            Route::put('update/{id}', 'AdminBacProcurementModeController@update')->name('admin-bac.procurement-mode.update');
            Route::put('remove/{id}', 'AdminBacProcurementModeController@remove')->name('admin-bac.procurement-mode.remove');
            Route::put('restore/{id}', 'AdminBacProcurementModeController@restore')->name('admin-bac.procurement-mode.restore');
        });
        /* Procurement Mode Routes */
    });
    /* BAC Routes End */
});
// ------------------------------------- fees master ------------------------------
Route::middleware(['auth'])->prefix('fees-master')->group(function () {
     Route::prefix('business-permit-fee')->group(function () {
            Route::get('', 'BploBusinessPermitfeeController@index')->name('bplobusinesspermitfee.index');
            Route::get('getList', 'BploBusinessPermitfeeController@getList')->name('bplobusinesspermitfee.getList');
            Route::any('store', 'BploBusinessPermitfeeController@store');
            Route::post('ActiveInactive', 'BploBusinessPermitfeeController@ActiveInactive');
            Route::post('store/formValidation', 'BploBusinessPermitfeeController@formValidation')->name('bplobusinesspermitfee.post');
            Route::post('getactivityCodebyid', 'BploBusinessPermitfeeController@getactivityCodebyid');
            Route::post('getbussinessbyTaxtype', 'BploBusinessPermitfeeController@getbussinessbyTaxtype');
            Route::post('getbussinessbyTaxtypenew', 'BploBusinessPermitfeeController@getbussinessbyTaxtypenew');
            Route::get('getActivitybbaCode', 'BploBusinessPermitfeeController@getActivitybbaCode');
            Route::post('getCategoryDropdown', 'BploBusinessPermitfeeController@getCategoryDropdown');
            Route::post('getAreaDropdown', 'BploBusinessPermitfeeController@getAreaDropdown');
        });
        Route::prefix('business-sanitary-fee')->group(function () {
            Route::get('', 'BploBusinessSanitaryfeeController@index')->name('bplobusinesssanitaryfee.index');
            Route::get('getList', 'BploBusinessSanitaryfeeController@getList')->name('bplobusinesssanitaryfee.getList');
            Route::any('store', 'BploBusinessSanitaryfeeController@store');
            Route::post('ActiveInactive', 'BploBusinessSanitaryfeeController@ActiveInactive');
            Route::post('store/formValidation', 'BploBusinessSanitaryfeeController@formValidation')->name('bplobusinesssanitaryfee.post');
            Route::post('getSanitaryCategoryDropdown', 'BploBusinessSanitaryfeeController@getSanitaryCategoryDropdown');
            Route::post('getSanitaryAreaDropdown', 'BploBusinessSanitaryfeeController@getSanitaryAreaDropdown');

        });
        Route::prefix('business-garbage-fee')->group(function () {
            Route::get('', 'BploBusinessGarbagefeeController@index')->name('bplobusinessgarbagefee.index');
            Route::get('getList', 'BploBusinessGarbagefeeController@getList')->name('bplobusinessgarbagefee.getList');
            Route::get('store', 'BploBusinessGarbagefeeController@store');
            Route::post('ActiveInactive', 'BploBusinessGarbagefeeController@ActiveInactive');
            Route::any('store/formValidation', 'BploBusinessGarbagefeeController@formValidation')->name('bplobusinessgarbagefee.post');
            Route::post('getGarbageCategoryDropdown', 'BploBusinessGarbagefeeController@getGarbageCategoryDropdown');
            Route::post('getGarbageAreaDropdown', 'BploBusinessGarbagefeeController@getGarbageAreaDropdown');
        });
        Route::prefix('business-engineering-fee')->group(function () {
            Route::get('', 'BploBusinessEnggFeeController@index')->name('bplobusinessenggfee.index');
            Route::get('getList', 'BploBusinessEnggFeeController@getList')->name('bplobusinessenggfee.getList');
            Route::any('store', 'BploBusinessEnggFeeController@store');
            Route::post('ActiveInactive', 'BploBusinessEnggFeeController@ActiveInactive');
            Route::post('store/formValidation', 'BploBusinessEnggFeeController@formValidation')->name('bplobusinessenggfee.post');

        });
        Route::prefix('environmental-fee')->group(function () {
            Route::get('', 'BploBusinessenvfeesController@index')->name('bplobusinessenvfees.index');
            Route::get('getList', 'BploBusinessenvfeesController@getList')->name('bplobusinessenvfees.getList');
            Route::any('store', 'BploBusinessenvfeesController@store');
            Route::post('ActiveInactive', 'BploBusinessenvfeesController@ActiveInactive');
            Route::post('delete', 'BploBusinessenvfeesController@Delete');
            Route::post('store/formValidation', 'BploBusinessenvfeesController@formValidation')->name('bplobusinessenvfees.post');
        });
    
});


require __DIR__ . '/elements/gso.php';

require __DIR__ . '/elements/finance.php';

require __DIR__ . '/elements/accounting.php';

require __DIR__ . '/elements/treasury.php';

require __DIR__ . '/elements/for-approval.php';

require __DIR__ . '/elements/reports.php';

require __DIR__ . '/elements/component.php';

require __DIR__ . '/elements/economic.php';

require __DIR__ . '/elements/faq.php';

Route::get('sms/webhook', 'ComponentSMSNotificationController@webhook');
Route::get('sms/messaging', 'ComponentSMSNotificationController@messaging');
Route::get('hr/timecard', 'HR\PayrollCalculateController@timecard');
Route::get('flagship', 'ComponentSMSNotificationController@flagship');
Route::get('depreciate', 'CronController@depreciate');

// ------------------------------------- Administrative ------------------------------
Route::middleware(['auth'])->prefix('engneering')->group(function () {
    /* psic-libraries */
    Route::prefix('master-data')->group(function () {
       Route::prefix('applicationtype')->group(function () {
           Route::get('', 'Engneering\EngBldgAptypeController@index')->name('Engneering.engbldgaptype.index');
       });
       Route::prefix('applicationscope')->group(function () {
           Route::get('', 'Engneering\EngBldgScopeController@index')->name('Engneering.engbldgscope.index');
       });
       Route::prefix('engbldgpermitapp')->group(function () {
           Route::get('', 'Engneering\EngBldgPermitAppController@index')->name('engbldgpermitapp.index');
       });
       Route::prefix('engbldgoccupancytype')->group(function () {
           
           Route::get('', 'Engneering\EngBldgOccupancyTypeController@index')->name('Engneering.engbldgoccupancytype.index');
       });
       Route::prefix('engbldgoccupancysubtype')->group(function () {
           Route::get('', 'Engneering\EngBldgOccupancySubTypeController@index')->name('Engneering.engbldgoccupancysubtype.index');
       });
       Route::prefix('enginstalloperation')->group(function () {
           Route::get('', 'Engneering\EngInstallationOperationTypeController@index')->name('Engneering.enginstalloperation.index');
       });
       Route::prefix('engelectricequpment')->group(function () {
           Route::get('', 'Engneering\EngElectricalEquipmentTypeController@index')->name('Engneering.engelectricequpment.index');
       });
       Route::prefix('engfixturetype')->group(function () {
           Route::get('', 'Engneering\EngFixtureTypeController@index')->name('Engneering.engfixturetype.index');
       });
       Route::prefix('engwatersupplytype')->group(function () {
           Route::get('', 'Engneering\EngWaterSupplyTypeController@index')->name('Engneering.engwatersupplytype.index');
       });
       Route::prefix('engdisposalsystemtype')->group(function () {
           Route::get('', 'Engneering\EngDisposalSystemTypeController@index')->name('Engneering.engdisposalsystemtype.index');
       });
       Route::prefix('engarchitecturefeature')->group(function () {
           Route::get('', 'Engneering\EngArchitecturalFeaturesTypeController@index')->name('Engneering.engarchitecturefeature.index');
       });
       Route::prefix('excavationgroundtype')->group(function () {
           Route::get('', 'Engneering\EngExcavationGroundTypeController@index')->name('Engneering.excavationgroundtype.index');
       });
       Route::prefix('civilstructuretype')->group(function () {
           Route::get('', 'Engneering\EngCivilStructureTypeController@index')->name('Engneering.civilstructuretype.index');
       });
        Route::prefix('engsigndisplaytype')->group(function () {
           Route::get('', 'Engneering\EngSignDisplayTypeController@index')->name('Engneering.engsigndisplaytype.index');
       });
        Route::prefix('engprofessionaltype')->group(function () {
           Route::get('', 'Engneering\EngProfessionTypeController@index')->name('Engneering.engprofessionaltype.index');
       });
        Route::prefix('engsigninstllationtype')->group(function () {
           Route::get('', 'Engneering\EngSignInstallationTypeController@index')->name('Engneering.engsigninstllationtype.index');
       });
        Route::prefix('engfencingtype')->group(function () {
           Route::get('', 'Engneering\EngFencingTypeController@index')->name('Engneering.engfencingtype.index');
       });
        Route::prefix('engservice')->group(function () {
           Route::get('', 'Engneering\EngServiceController@index')->name('Engneering.engservice.index');
       }); 
         Route::prefix('servicerequirements')->group(function () {
           Route::get('', 'Engneering\EngServiceRequirementsController@index')->name('Engneering.servicerequirements.index');
       });
    });
    Route::prefix('engtfoc')->group(function () {
      Route::get('', 'Engneering\CtoTfocController@index')->name('engtfoc.index');
    });
     Route::prefix('clients')->group(function () {
      Route::get('', 'Engneering\EngClientsController@index')->name('engclients.index');
    });
    //Route::get('engclients/index', 'Engneering\EngClientsController@index')->name('engclients.index');
});

// ------------------------------------- Eng Building Permit App ------------------------------
//Route::get('engbldgpermitapp', 'Engneering\EngBldgPermitAppController@index')->name('engbldgpermitapp.index');
Route::get('engbldgpermitapp/getList', 'Engneering\EngBldgPermitAppController@getList')->name('bfpcertificate.getList');
Route::get('engbldgpermitapp/store', 'Engneering\EngBldgPermitAppController@store');
Route::resource('engbldgpermitapp', 'Engneering\EngBldgPermitAppController')->middleware(['auth','revalidate']);
Route::post('engbldgpermitapp/formValidation', 'Engneering\EngBldgPermitAppController@formValidation')->name('engbldgpermitapp.post');

// ------------------------------------- Eng Bldg Aptype ------------------------------
//Route::get('engbldgaptype', 'Engneering\EngBldgAptypeController@index')->name('Engneering.engbldgaptype.index');
Route::get('engbldgaptype/getList', 'Engneering\EngBldgAptypeController@getList')->name('Engneering.engbldgaptype.getList');
Route::post('engbldgaptype/ActiveInactive', 'Engneering\EngBldgAptypeController@ActiveInactive');
Route::get('engbldgaptype/store', 'Engneering\EngBldgAptypeController@store');
Route::resource('engbldgaptype', 'Engneering\EngBldgAptypeController')->middleware(['auth','revalidate']);
Route::post('engbldgaptype/formValidation', 'Engneering\EngBldgAptypeController@formValidation')->name('engbldgaptype.post');
// ------------------------------------- Eng Bldg Scope ------------------------------
//Route::get('engbldgscope', 'Engneering\EngBldgScopeController@index')->name('Engneering.engbldgscope.index');
Route::get('engbldgscope/getList', 'Engneering\EngBldgScopeController@getList')->name('Engneering.engbldgscope.getList');
Route::post('engbldgscope/ActiveInactive', 'Engneering\EngBldgScopeController@ActiveInactive');
Route::get('engbldgscope/store', 'Engneering\EngBldgScopeController@store');
Route::resource('engbldgscope', 'Engneering\EngBldgScopeController')->middleware(['auth','revalidate']);
Route::post('engbldgscope/formValidation', 'Engneering\EngBldgScopeController@formValidation')->name('engbldgscope.post');



// ------------------------------------- Eng Bldg Occupancy Type ------------------------------
//Route::get('engbldgoccupancytype', 'Engneering\EngBldgOccupancyTypeController@index')->name('Engneering.engbldgoccupancytype.index');
Route::get('engbldgoccupancytype/getList', 'Engneering\EngBldgOccupancyTypeController@getList')->name('Engneering.engbldgoccupancytype.getList');
Route::post('engbldgoccupancytype/ActiveInactive', 'Engneering\EngBldgOccupancyTypeController@ActiveInactive');
Route::get('engbldgoccupancytype/store', 'Engneering\EngBldgOccupancyTypeController@store');
Route::resource('engbldgoccupancytype', 'Engneering\EngBldgOccupancyTypeController')->middleware(['auth','revalidate']);
Route::post('engbldgoccupancytype/formValidation', 'Engneering\EngBldgOccupancyTypeController@formValidation')->name('engbldgoccupancytype.post');

// ------------------------------------- Eng Bldg Occupancy Sub Type ------------------------------
//Route::get('engbldgoccupancysubtype', 'Engneering\EngBldgOccupancySubTypeController@index')->name('Engneering.engbldgoccupancysubtype.index');
Route::get('engbldgoccupancysubtype/getList', 'Engneering\EngBldgOccupancySubTypeController@getList')->name('Engneering.engbldgoccupancysubtype.getList');
Route::post('engbldgoccupancysubtype/ActiveInactive', 'Engneering\EngBldgOccupancySubTypeController@ActiveInactive');
Route::get('engbldgoccupancysubtype/store', 'Engneering\EngBldgOccupancySubTypeController@store');
Route::resource('engbldgoccupancysubtype', 'Engneering\EngBldgOccupancySubTypeController')->middleware(['auth','revalidate']);
Route::post('engbldgoccupancysubtype/formValidation', 'Engneering\EngBldgOccupancySubTypeController@formValidation')->name('engbldgoccupancysubtype.post');


// ------------------------------------- Eng TFOC ------------------------------
// Route::get('engtfoc', 'Engneering\CtoTfocController@index')->name('engtfoc.index');
Route::get('engtfoc/getList', 'Engneering\CtoTfocController@getList')->name('Engneering.engtfoc.getList');
Route::post('engtfoc/ActiveInactive', 'Engneering\CtoTfocController@ActiveInactive');
Route::get('engtfoc/store', 'Engneering\CtoTfocController@store');
Route::post('getAccountDescription', 'Engneering\CtoTfocController@getAccountDescription');
Route::post('engtfoc/getTypeofchargesAjax', 'Engneering\CtoTfocController@getTypeofchargesAjax');
Route::post('engtfoc/getChartofaccountAjax', 'Engneering\CtoTfocController@getChartofaccountAjax');
Route::post('getEssestialvalue', 'Engneering\CtoTfocController@getEssestialvalue'); 
Route::post('engtfoc/deleteothertaxes', 'Engneering\CtoTfocController@deleteothertaxes');
Route::resource('engtfoc', 'Engneering\CtoTfocController')->middleware(['auth','revalidate']);
Route::post('engtfoc/formValidation', 'Engneering\CtoTfocController@formValidation')->name('engtfoc.post');

// ------------------------------------- Eng Module ------------------------------
Route::get('engmodule', 'Engneering\EngModuleController@index')->name('Engneering.engmodule.index');
Route::get('engmodule/getList', 'Engneering\EngModuleController@getList')->name('Engneering.engmodule.getList');
Route::post('engmodule/ActiveInactive', 'Engneering\EngModuleController@ActiveInactive');
Route::get('engmodule/store', 'Engneering\EngModuleController@store');
Route::resource('engmodule', 'Engneering\EngModuleController')->middleware(['auth','revalidate']);
Route::post('engmodule/formValidation', 'Engneering\EngModuleController@formValidation')->name('engmodule.post');

// ------------------------------------- Eng Bldg Assessment Fees ------------------------------
Route::get('engbldgassessmentfees', 'Engneering\EngBldgAssessmentFeesController@index')->name('Engneering.engbldgassessmentfees.index');
Route::get('engbldgassessmentfees/getList', 'Engneering\EngBldgAssessmentFeesController@getList')->name('Engneering.engbldgassessmentfees.getList');
Route::post('engbldgassessmentfees/ActiveInactive', 'Engneering\EngBldgAssessmentFeesController@ActiveInactive');
Route::get('engbldgassessmentfees/store', 'Engneering\EngBldgAssessmentFeesController@store');
Route::resource('engbldgassessmentfees', 'Engneering\EngBldgAssessmentFeesController')->middleware(['auth','revalidate']);
Route::post('engbldgassessmentfees/formValidation', 'Engneering\EngBldgAssessmentFeesController@formValidation')->name('engbldgassessmentfees.post');


// ------------------------------------- Eng Installation Fees ------------------------------
//Route::get('enginstalloperation', 'Engneering\EngInstallationOperationTypeController@index')->name('enginstalloperation.index');
Route::get('enginstalloperation/getList', 'Engneering\EngInstallationOperationTypeController@getList')->name('Engneering.enginstalloperation.getList');
Route::post('enginstalloperation/ActiveInactive', 'Engneering\EngInstallationOperationTypeController@ActiveInactive');
Route::get('enginstalloperation/store', 'Engneering\EngInstallationOperationTypeController@store');
Route::resource('enginstalloperation', 'Engneering\EngInstallationOperationTypeController')->middleware(['auth','revalidate']);
Route::post('enginstalloperation/formValidation', 'Engneering\EngInstallationOperationTypeController@formValidation')->name('enginstalloperation.post');

// ------------------------------------- Eng Bldg Architecturer Feature Type ------------------------------
//Route::get('engarchitecturefeature', 'Engneering\EngArchitecturalFeaturesTypeController@index')->name('engarchitecturefeature.index');
Route::get('engarchitecturefeature/getList', 'Engneering\EngArchitecturalFeaturesTypeController@getList')->name('Engneering.engarchitecturefeature.getList');
Route::post('engarchitecturefeature/ActiveInactive', 'Engneering\EngArchitecturalFeaturesTypeController@ActiveInactive');
Route::get('engarchitecturefeature/store', 'Engneering\EngArchitecturalFeaturesTypeController@store');
Route::resource('engarchitecturefeature', 'Engneering\EngArchitecturalFeaturesTypeController')->middleware(['auth','revalidate']);
Route::post('engarchitecturefeature/formValidation', 'Engneering\EngArchitecturalFeaturesTypeController@formValidation')->name('engarchitecturefeature.post');


// ------------------------------------- Eng Sign Display Type ------------------------------
//Route::get('engsigndisplaytype', 'Engneering\EngSignDisplayTypeController@index')->name('engsigndisplaytype.index');
Route::get('engsigndisplaytype/getList', 'Engneering\EngSignDisplayTypeController@getList')->name('Engneering.engsigndisplaytype.getList');
Route::post('engsigndisplaytype/ActiveInactive', 'Engneering\EngSignDisplayTypeController@ActiveInactive');
Route::get('engsigndisplaytype/store', 'Engneering\EngSignDisplayTypeController@store');
Route::resource('engsigndisplaytype', 'Engneering\EngSignDisplayTypeController')->middleware(['auth','revalidate']);
Route::post('engsigndisplaytype/formValidation', 'Engneering\EngSignDisplayTypeController@formValidation')->name('engsigndisplaytype.post');

// ------------------------------------- Eng Fixture Type ------------------------------
//Route::get('engfixturetype', 'Engneering\EngFixtureTypeController@index')->name('engfixturetype.index');
Route::get('engfixturetype/getList', 'Engneering\EngFixtureTypeController@getList')->name('Engneering.engfixturetype.getList');
Route::post('engfixturetype/ActiveInactive', 'Engneering\EngFixtureTypeController@ActiveInactive');
Route::get('engfixturetype/store', 'Engneering\EngFixtureTypeController@store');
Route::resource('engfixturetype', 'Engneering\EngFixtureTypeController')->middleware(['auth','revalidate']);
Route::post('engfixturetype/formValidation', 'Engneering\EngFixtureTypeController@formValidation')->name('engfixturetype.post');
// ------------------------------------- Eng Water Supply Type ------------------------------
//Route::get('engwatersupplytype', 'Engneering\EngWaterSupplyTypeController@index')->name('engwatersupplytype.index');
Route::get('engwatersupplytype/getList', 'Engneering\EngWaterSupplyTypeController@getList')->name('Engneering.engwatersupplytype.getList');
Route::post('engwatersupplytype/ActiveInactive', 'Engneering\EngWaterSupplyTypeController@ActiveInactive');
Route::get('engwatersupplytype/store', 'Engneering\EngWaterSupplyTypeController@store');
Route::resource('engwatersupplytype', 'Engneering\EngWaterSupplyTypeController')->middleware(['auth','revalidate']);
Route::post('engwatersupplytype/formValidation', 'Engneering\EngWaterSupplyTypeController@formValidation')->name('engwatersupplytype.post');

// ------------------------------------- Eng Disposal System Type ------------------------------
//Route::get('engdisposalsystemtype', 'Engneering\EngDisposalSystemTypeController@index')->name('engdisposalsystemtype.index');
Route::get('engdisposalsystemtype/getList', 'Engneering\EngDisposalSystemTypeController@getList')->name('Engneering.engdisposalsystemtype.getList');
Route::post('engdisposalsystemtype/ActiveInactive', 'Engneering\EngDisposalSystemTypeController@ActiveInactive');
Route::get('engdisposalsystemtype/store', 'Engneering\EngDisposalSystemTypeController@store');
Route::resource('engdisposalsystemtype', 'Engneering\EngDisposalSystemTypeController')->middleware(['auth','revalidate']);
Route::post('engdisposalsystemtype/formValidation', 'Engneering\EngDisposalSystemTypeController@formValidation')->name('engdisposalsystemtype.post');





// ------------------------------------- rpt property cert of  holding ------------------------------


Route::prefix('certificate-of-property-holding')->group(function () {
    Route::get('', 'CertificateOfPropertyHoldingsController@index')->name('rptpropertycertofpropertyholding.index');
  });
Route::get('rptproperty/rptpropertycertofpropertyholding', 'CertificateOfPropertyHoldingsController@index')->name('rptpropertycertofpropertyholding.index');
Route::get('rptpropertycertofpropertyholding/getList', 'CertificateOfPropertyHoldingsController@getList')->name('rptpropertycertofpropertyholding.getList');
Route::post('getClientLastName', 'CertificateOfPropertyHoldingsController@getClientLastName');
Route::post('getClientCodeAddressPropertyLand', 'CertificateOfPropertyHoldingsController@getClientCodeAddressPropertyLand');
Route::post('getClientsAppraisals', 'CertificateOfPropertyHoldingsController@getClientsAppraisals');
Route::post('getClientsAppraisalsMech', 'CertificateOfPropertyHoldingsController@getClientsAppraisalsMech');
Route::post('getClientsAppraisalsBuilding', 'CertificateOfPropertyHoldingsController@getClientsAppraisalsBuilding');
Route::get('getCertPositionDetails', 'CertificateOfPropertyHoldingsController@getCertPositionDetails');
Route::any('CertPholdingPrint', 'CertificateOfPropertyHoldingsController@CertPholdingPrint');
Route::get('rptpropertycertofpropertyholding/store', 'CertificateOfPropertyHoldingsController@store');
Route::post('rptpropertycertofpropertyholding/ActiveInactive', 'CertificateOfPropertyHoldingsController@ActiveInactive');
Route::post('rptpropertycertofpropertyholding/ApproveUnapprove', 'CertificateOfPropertyHoldingsController@ApproveUnapprove');
Route::post('rptpropertycertofpropertyholding/client', 'CertificateOfPropertyHoldingsController@storeClient');
Route::post('rptpropertycertofpropertyholding/delete', 'CertificateOfPropertyHoldingsController@Delete');
Route::resource('rptpropertycertofpropertyholding', 'CertificateOfPropertyHoldingsController')->middleware(['auth','revalidate']);
Route::post('rptpropertycertofpropertyholding/formValidation', 'CertificateOfPropertyHoldingsController@formValidation')->name('rptpropertycertofpropertyholding.post');
Route::post('certOfPropertyholdinggetOrNumberajax', 'CertificateOfPropertyHoldingsController@getOrNumberspropertyholdingAjax');
Route::post('getctoCashierDetailsPropertyHolding', 'CertificateOfPropertyHoldingsController@getctoCashierDetailsPropertyHolding');
Route::post('certOfNoLandgetIssuanceDetails', 'CertificateOfPropertyHoldingsController@getIssuanceDetails');


// ------------------------------------- rpt Certificate Of No Improvement ------------------------------

Route::prefix('certificate-of-no-improvement')->group(function () {
    Route::get('', 'CertificateOfNoImprovementController@index')->name('rptcertificateofnoImprovement.index');
  });
Route::get('rptcertificateofnoImprovement', 'CertificateOfNoImprovementController@index')->name('rptcertificateofnoImprovement.index');
Route::get('rptcertificateofnoImprovement/getList', 'CertificateOfNoImprovementController@getList')->name('rptcertificateofnoImprovement.getList');
Route::post('getClientLastNameImprovement', 'CertificateOfNoImprovementController@getClientLastNameImprovement');
Route::post('getClientCodeImprovement', 'CertificateOfNoImprovementController@getClientCodeImprovement');
Route::post('getClientsAppraisalsImprovement', 'CertificateOfNoImprovementController@getClientsAppraisalsImprovement');
Route::post('getClientsAppraisalsMechImprovement', 'CertificateOfNoImprovementController@getClientsAppraisalsMechImprovement');
Route::post('getClientsAppraisalsBuildingImprovement', 'CertificateOfNoImprovementController@getClientsAppraisalsBuildingImprovement');
Route::get('getCertPositionDetailsImprovement', 'CertificateOfNoImprovementController@getCertPositionDetailsImprovement');
Route::post('getctoCashierIdPropertyImprovment', 'CertificateOfNoImprovementController@getctoCashierIdPropertyImprovment');
Route::any('CertPholdingPrintImprovement', 'CertificateOfNoImprovementController@CertPholdingPrintImprovement');
Route::post('getClientLastNameImprovement', 'CertificateOfNoImprovementController@getClientLastNameImprovement');
Route::post('certOfNimprovementgetOrNumberajax', 'CertificateOfNoImprovementController@getOrNumbersAjax');


Route::post('getClientsAppraisalsImprovement', 'CertificateOfNoImprovementController@getClientsAppraisalsImprovement');
Route::get('rptcertificateofnoImprovement/store', 'CertificateOfNoImprovementController@store');
Route::post('rptcertificateofnoImprovement/ActiveInactive', 'CertificateOfNoImprovementController@ActiveInactive');
Route::post('rptcertificateofnoImprovement/client', 'CertificateOfNoImprovementController@storeClient');
Route::post('rptcertificateofnoImprovement/ApproveUnapprove', 'CertificateOfNoImprovementController@ApproveUnapprove');
Route::post('rptcertificateofnoImprovement/delete', 'CertificateOfNoImprovementController@Delete');
Route::resource('rptcertificateofnoImprovement', 'CertificateOfNoImprovementController')->middleware(['auth','revalidate']);
Route::post('rptcertificateofnoImprovement/formValidation', 'CertificateOfNoImprovementController@formValidation')->name('rptcertificateofnoImprovement.post');
// ------------------------------------- Rpt Certificate-Of-No-Land-Holding ------------------------------

Route::prefix('certificate-of-no-land-holding')->group(function () {
    Route::get('', 'CertificateOfNoLandHoldingController@index')->name('rptcertificateofnolandholding.index');
  });

Route::get('rptcertificateofnolandholding', 'CertificateOfNoLandHoldingController@index')->name('rptcertificateofnolandholding.index');
Route::post('getClientsAppraisalsNoLand', 'CertificateOfNoLandHoldingController@getClientsAppraisalsNoLand');
Route::post('getClientsAppraisalsMechNoLand', 'CertificateOfNoLandHoldingController@getClientsAppraisalsMechNoLand');
Route::post('getClientsAppraisalsBuildingNoLand', 'CertificateOfNoLandHoldingController@getClientsAppraisalsBuildingNoLand');
Route::any('rptcertificateofnolandholding/client', 'CertificateOfNoLandHoldingController@storeClient')->name('rptcertificateofnolandholding.client');
Route::get('rptcertificateofnolandholding/getList', 'CertificateOfNoLandHoldingController@getList')->name('rptcertificateofnolandholding.getList');
Route::post('getClientLastNameImprovement', 'CertificateOfNoLandHoldingController@getClientLastNameImprovement');
Route::post('rptcertificateofnolandholding/ActiveInactive', 'CertificateOfNoLandHoldingController@ActiveInactive');
Route::get('getAppraisersPositionDetails', 'CertificateOfNoLandHoldingController@getAppraisersPositionDetails');
Route::post('getClientCodeAddressnolandHolding', 'CertificateOfNoLandHoldingController@getClientCodeAddressnolandHolding');
Route::post('getClientsAppraisalsnolandHolding', 'CertificateOfNoLandHoldingController@getClientsAppraisalsImprovement');
Route::get('getCertPositionDetailsnolandHolding', 'CertificateOfNoLandHoldingController@getCertPositionDetailsnolandHolding');
Route::any('CertPholdingPrintNoLand', 'CertificateOfNoLandHoldingController@CertPholdingPrintNoLand');
Route::post('getClientLastNamenolandHolding', 'CertificateOfNoLandHoldingController@getClientLastNameImprovement');
Route::post('getClientsNameDetailsId', 'CertificateOfNoLandHoldingController@getClientsNameDetailsId');
Route::post('getClientsNameDetailsIdAll', 'CertificateOfNoLandHoldingController@getClientsNameDetailsIdAll');
Route::post('getClientsNameDetailsAjax', 'CertificateOfNoLandHoldingController@getClientsNameDetailsAjax');
Route::post('hrEmployeesAjax', 'CertificateOfNoLandHoldingController@hrEmployeesAjax');
Route::post('certOfNoLandgetOrNumberDetails', 'CertificateOfNoLandHoldingController@getOrNumberDetails');
Route::post('getctoCashierIdPropertyNoLandHoldingAjax', 'CertificateOfNoLandHoldingController@getctoCashierIdPropertyNoLandHoldingAjax');
Route::post('certOfNoLandgetIssuanceDetails', 'CertificateOfNoLandHoldingController@getIssuanceDetails');
Route::post('getClientsNameDetailsIdAllRequest', 'CertificateOfNoLandHoldingController@getClientsNameDetailsIdAllRequest');
Route::post('getClientsAppraisalsnolandHolding', 'CertificateOfNoLandHoldingController@getClientsAppraisalsnolandHolding');
Route::get('rptcertificateofnolandholding/store', 'CertificateOfNoLandHoldingController@store');
Route::post('rptcertificateofnolandholding/ActiveInactive', 'CertificateOfNoLandHoldingController@ActiveInactive');
Route::post('rptcertificateofnolandholding/ApproveUnapprove', 'CertificateOfNoLandHoldingController@ApproveUnapprove');
Route::post('rptcertificateofnolandholding/delete', 'CertificateOfNoLandHoldingController@Delete');
Route::resource('rptcertificateofnolandholding', 'CertificateOfNoLandHoldingController')->middleware(['auth','revalidate']);
Route::post('rptcertificateofnolandholding/formValidation', 'CertificateOfNoLandHoldingController@formValidation')->name('rptcertificateofnolandholding.post');

// ------------------------------------- rpt property cert of  Record ------------------------------

Route::prefix('certificate-record-listing')->group(function () {
    Route::get('', 'CertificateOfRecordController@index')->name('rptpropertycertofrecord.index');
  });
Route::get('rptproperty/rptpropertycertofrecord', 'CertificateOfRecordController@index')->name('rptpropertycertofrecord.index');
Route::get('rptpropertycertofrecord/getList', 'CertificateOfRecordController@getList')->name('rptpropertycertofrecord.getList');
// Route::post('getClientLastName', 'CertificateOfRecordController@getClientLastName');
// Route::post('getClientCodeAddressPropertyLand', 'CertificateOfRecordController@getClientCodeAddressPropertyLand');
// Route::post('getClientsAppraisals', 'CertificateOfRecordController@getClientsAppraisals');
// Route::get('getCertPositionDetails', 'CertificateOfRecordController@getCertPositionDetails');
// Route::any('CertPholdingPrint', 'CertificateOfRecordController@CertPholdingPrint');


Route::get('rptpropertycertofrecord/store', 'CertificateOfRecordController@store');
Route::post('rptpropertycertofrecord/ActiveInactive', 'CertificateOfRecordController@ActiveInactive');
Route::post('rptpropertycertofrecord/ApproveUnapprove', 'CertificateOfRecordController@ApproveUnapprove');
Route::post('rptpropertycertofrecord/delete', 'CertificateOfRecordController@Delete');
Route::resource('rptpropertycertofrecord', 'CertificateOfRecordController')->middleware(['auth','revalidate']);
Route::post('rptpropertycertofrecord/formValidation', 'CertificateOfRecordController@formValidation')->name('rptpropertycertofrecord.post');

// ------------------------------------- Bplo Business Cto Data Schedule ------------------------------
Route::get('business-schedule', 'Bplo\BploBusinessScheduleController@index')->name('bploschedule.index');
Route::get('BploBusinessSchedule/getList', 'Bplo\BploBusinessScheduleController@getList')->name('bplo.bploschedule.getList');
Route::post('BploBusinessSchedule/ActiveInactive', 'Bplo\BploBusinessScheduleController@ActiveInactive');
Route::get('BploBusinessSchedule/store', 'Bplo\BploBusinessScheduleController@store');
Route::resource('BploBusinessSchedule', 'Bplo\BploBusinessScheduleController')->middleware(['auth','revalidate']);
Route::post('BploBusinessSchedule/formValidation', 'Bplo\BploBusinessScheduleController@formValidation')->name('bploschedule.post');
// ------------------------------------- eng_conformance_to_fire_code ------------------------------
Route::get('engconformance', 'Engneering\EngConformanceToFireCode@index')->name('engconformance.index');
Route::get('engconformance/getList', 'Engneering\EngConformanceToFireCode@getList')->name('engconformance.getList');
Route::post('engconformance/ActiveInactive', 'Engneering\EngConformanceToFireCode@ActiveInactive');
Route::get('engconformance/store', 'Engneering\EngConformanceToFireCode@store');
Route::resource('engconformance', 'Engneering\EngConformanceToFireCode')->middleware(['auth','revalidate']);
Route::post('engconformance/formValidation', 'Engneering\EngConformanceToFireCode@formValidation')->name('engconformance.post');
// ------------------------------------- Bplo CTO data Formula ------------------------------
Route::get('formula', 'Bplo\CtoDataFormulaController@index')->name('bploFormula.index');
Route::get('CtoDataFormula/getList', 'Bplo\CtoDataFormulaController@getList')->name('bplo.bploFormula.getList');
Route::post('CtoDataFormula/ActiveInactive', 'Bplo\CtoDataFormulaController@ActiveInactive');
Route::get('CtoDataFormula/store', 'Bplo\CtoDataFormulaController@store');
Route::resource('CtoDataFormula', 'Bplo\CtoDataFormulaController')->middleware(['auth','revalidate']);
Route::post('CtoDataFormula/formValidation', 'Bplo\CtoDataFormulaController@formValidation')->name('bploFormula.post');
// ------------------------------------- Cpdo Data rout ------------------------------
Route::get('cpdo-module', 'CpdoModuleController@index')->name('cpdomodule.index');
Route::get('cpdo-module/getList', 'CpdoModuleController@getList');
Route::post('cpdomodule/ActiveInactive', 'CpdoModuleController@ActiveInactive');
Route::get('cpdo-module/store', 'CpdoModuleController@store');
Route::resource('cpdomodule', 'CpdoModuleController')->middleware(['auth','revalidate']);
Route::post('cpdomodule/formValidation', 'CpdoModuleController@formValidation')->name('cpdomodule.post');
// ------------------------------------- Bplo CTO data Compute Mode ------------------------------
Route::get('compute-mode', 'Bplo\CtoDataComputeModeController@index')->name('ComputeMode.index');
Route::get('CtoDataComputeMode/getList', 'Bplo\CtoDataComputeModeController@getList')->name('bplo.ComputeMode.getList');
Route::post('CtoDataComputeMode/ActiveInactive', 'Bplo\CtoDataComputeModeController@ActiveInactive');
Route::get('CtoDataComputeMode/store', 'Bplo\CtoDataComputeModeController@store');
Route::resource('CtoDataComputeMode', 'Bplo\CtoDataComputeModeController')->middleware(['auth','revalidate']);
Route::post('CtoDataComputeMode/formValidation', 'Bplo\CtoDataComputeModeController@formValidation')->name('ComputeMode.post');

// ------------------------------------- Bplo Application Type ------------------------------
Route::get('application-type', 'Bplo\PbloapptypeController@index')->name('applicationType.index');
Route::get('Pbloapplicationtype/getList', 'Bplo\PbloapptypeController@getList')->name('bplo.applicationType.getList');
Route::post('Pbloapplicationtype/ActiveInactive', 'Bplo\PbloapptypeController@ActiveInactive');
Route::get('Pbloapplicationtype/store', 'Bplo\PbloapptypeController@store');
Route::resource('Pbloapplicationtype', 'Bplo\PbloapptypeController')->middleware(['auth','revalidate']);
Route::post('Pbloapplicationtype/formValidation', 'Bplo\PbloapptypeController@formValidation')->name('applicationType.post');

// ------------------------------------- Bplo Charge Type ------------------------------
Route::get('business-data-type-of-charges', 'Bplo\CtoChargeTypesController@index')->name('ctoDatatype.index');
Route::get('CtoChargeTypes/getList', 'Bplo\CtoChargeTypesController@getList')->name('bplo.ctoDatatype.getList');
Route::post('CtoChargeTypes/ActiveInactive', 'Bplo\CtoChargeTypesController@ActiveInactive');
Route::get('CtoChargeTypes/store', 'Bplo\CtoChargeTypesController@store');
Route::resource('CtoChargeTypes', 'Bplo\CtoChargeTypesController')->middleware(['auth','revalidate']);
Route::post('CtoChargeTypes/formValidation', 'Bplo\CtoChargeTypesController@formValidation')->name('ctoDatatype.post');


// ------------------------------------- Bplo Charges Description ------------------------------
Route::get('business-data-charges-description', 'Bplo\CtoChargeDescriptionController@index')->name('chargesDesc.index');
Route::get('CtoChargeDescription/getList', 'Bplo\CtoChargeDescriptionController@getList')->name('bplo.chargesDesc.getList');
Route::post('CtoChargeDescription/ActiveInactive', 'Bplo\CtoChargeDescriptionController@ActiveInactive');
Route::get('CtoChargeDescription/store', 'Bplo\CtoChargeDescriptionController@store');
Route::resource('CtoChargeDescription', 'Bplo\CtoChargeDescriptionController')->middleware(['auth','revalidate']);
Route::post('CtoChargeDescription/formValidation', 'Bplo\CtoChargeDescriptionController@formValidation')->name('chargesDesc.post');

// ------------------------------------- Eng Electrical Equipment Type ------------------------------
//Route::get('engelectricequpment', 'Engneering\EngElectricalEquipmentTypeController@index')->name('engelectricequpment.index');
Route::get('engelectricequpment/getList', 'Engneering\EngElectricalEquipmentTypeController@getList')->name('Engneering.engelectricequpment.getList');
Route::post('engelectricequpment/ActiveInactive', 'Engneering\EngElectricalEquipmentTypeController@ActiveInactive');
Route::get('engelectricequpment/store', 'Engneering\EngElectricalEquipmentTypeController@store');
Route::resource('engelectricequpment', 'Engneering\EngElectricalEquipmentTypeController')->middleware(['auth','revalidate']);
Route::post('engelectricequpment/formValidation', 'Engneering\EngElectricalEquipmentTypeController@formValidation')->name('engelectricequpment.post');


// ------------------------------------- Eng Excavation Ground Type ------------------------------
//Route::get('excavationgroundtype', 'Engneering\EngExcavationGroundTypeController@index')->name('excavationgroundtype.index');

// ------------------------------------- Bplo Basis of Payment Extension ------------------------------
Route::get('bplo-basis-of-payment-extension', 'Bplo\CtoPaymentExtensionBasisController@index')->name('paymentExtension.index');
Route::get('CtoPaymentExtensionBasis/getList', 'Bplo\CtoPaymentExtensionBasisController@getList')->name('bplo.paymentExtension.getList');
Route::post('CtoPaymentExtensionBasis/deleteAttchment', 'Bplo\CtoPaymentExtensionBasisController@deleteAttchment');
Route::post('CtoPaymentExtensionBasis/ActiveInactive', 'Bplo\CtoPaymentExtensionBasisController@ActiveInactive');
Route::get('CtoPaymentExtensionBasis/store', 'Bplo\CtoPaymentExtensionBasisController@store');
Route::resource('CtoPaymentExtensionBasis', 'Bplo\CtoPaymentExtensionBasisController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentExtensionBasis/formValidation', 'Bplo\CtoPaymentExtensionBasisController@formValidation')->name('paymentExtension.post');

// ------------------------------------- tfoc-basis ------------------------------
Route::get('tfoc-basis', 'Bplo\CtoTfocBasisController@index')->name('ctoTfocBasis.index');
Route::get('bploCtoTfocBasis/getList', 'Bplo\CtoTfocBasisController@getList')->name('bplo.ctoTfocBasis.getList');
Route::post('bploCtoTfocBasis/ActiveInactive', 'Bplo\CtoTfocBasisController@ActiveInactive');
Route::get('bploCtoTfocBasis/store', 'Bplo\CtoTfocBasisController@store');
Route::resource('bploCtoTfocBasis', 'Bplo\CtoTfocBasisController')->middleware(['auth','revalidate']);
Route::post('bploCtoTfocBasis/formValidation', 'Bplo\CtoTfocBasisController@formValidation')->name('ctoTfocBasis.post');

// ------------------------------------- CTO PAYMENT MODE ------------------------------
Route::get('payment-mode', 'Bplo\CtoPaymentModeController@index')->name('bploCtoPaymentMode.index');
Route::get('CtoPaymentMode/getList', 'Bplo\CtoPaymentModeController@getList')->name('bplo.bploCtoPaymentMode.getList');
Route::post('CtoPaymentMode/ActiveInactive', 'Bplo\CtoPaymentModeController@ActiveInactive');
Route::get('CtoPaymentMode/store', 'Bplo\CtoPaymentModeController@store');
Route::resource('CtoPaymentMode', 'Bplo\CtoPaymentModeController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentMode/formValidation', 'Bplo\CtoPaymentModeController@formValidation')->name('bploCtoPaymentMode.post');

// ------------------------------------- BPLO BUSINESS TYPE ------------------------------
Route::get('business-type', 'Bplo\BploBusinessTypeController@index')->name('bploBusinessType.index');
Route::get('BploBusinessType/getList', 'Bplo\BploBusinessTypeController@getList')->name('bplo.bploBusinessType.getList');
Route::post('BploBusinessType/ActiveInactive', 'Bplo\BploBusinessTypeController@ActiveInactive');
Route::get('BploBusinessType/store', 'Bplo\BploBusinessTypeController@store');
Route::resource('BploBusinessType', 'Bplo\BploBusinessTypeController')->middleware(['auth','revalidate']);
Route::post('BploBusinessType/formValidation', 'Bplo\BploBusinessTypeController@formValidation')->name('bploBusinessType.post');

// ------------------------------------- BPLO BUSINESS Location ------------------------------
Route::get('business-activity', 'Bplo\BploBusinessLocationController@index')->name('bploBusinessLocation.index');
Route::get('BploBusinessLocation/getList', 'Bplo\BploBusinessLocationController@getList')->name('bplo.bploBusinessLocation.getList');
Route::post('BploBusinessLocation/ActiveInactive', 'Bplo\BploBusinessLocationController@ActiveInactive');
Route::get('BploBusinessLocation/store', 'Bplo\BploBusinessLocationController@store');
Route::resource('BploBusinessLocation', 'Bplo\BploBusinessLocationController')->middleware(['auth','revalidate']);
Route::post('BploBusinessLocation/formValidation', 'Bplo\BploBusinessLocationController@formValidation')->name('bploBusinessLocation.post');


// ------------------------------------- BPLO BUSINESS ENDORSING DEPARTMENT ------------------------------
Route::get('endorsing-department', 'Bplo\BploEndorsingDeptController@index')->name('bploEndorsingDept.index');
Route::get('BploEndorsingDept/getList', 'Bplo\BploEndorsingDeptController@getList')->name('bplo.bploEndorsingDept.getList');
Route::get('BploEndorsingDept/reload-fees/{id}', 'Bplo\BploEndorsingDeptController@reload_fees')->name('bplo.bploEndorsingDept.reload_fees');
Route::post('BploEndorsingDept/ActiveInactive', 'Bplo\BploEndorsingDeptController@ActiveInactive');
Route::get('BploEndorsingDept/store', 'Bplo\BploEndorsingDeptController@store');
Route::post('deleteBploEndorsingDept', 'Bplo\BploEndorsingDeptController@deleteBploEndorsingDept');
Route::resource('BploEndorsingDept', 'Bplo\BploEndorsingDeptController')->middleware(['auth','revalidate']);
Route::post('BploEndorsingDept/formValidation', 'Bplo\BploEndorsingDeptController@formValidation')->name('bploEndorsingDept.post');


// ------------------------------------- Bplo Payment Holiday Type ------------------------------
Route::get('bplo-holiday-type', 'Bplo\CtoPaymentHolidayTypeController@index')->name('bploHolidayType.index');
Route::get('CtoPaymentHolidayType/getList', 'Bplo\CtoPaymentHolidayTypeController@getList')->name('bplo.bploHolidayType.getList');
Route::post('CtoPaymentHolidayType/ActiveInactive', 'Bplo\CtoPaymentHolidayTypeController@ActiveInactive');
Route::get('CtoPaymentHolidayType/store', 'Bplo\CtoPaymentHolidayTypeController@store');
Route::resource('CtoPaymentHolidayType', 'Bplo\CtoPaymentHolidayTypeController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentHolidayType/formValidation', 'Bplo\CtoPaymentHolidayTypeController@formValidation')->name('bploHolidayType.post');

// ------------------------------------- Bplo CTO Payment OR Cancel Reason ------------------------------
Route::get('bplo-cancel-or-reason', 'Bplo\CtoPaymentOrCancelReasonController@index')->name('ORcancelReason.index');
Route::get('CtoPaymentOrCancelReason/getList', 'Bplo\CtoPaymentOrCancelReasonController@getList')->name('bplo.or_cancel_reason.getList');
Route::post('CtoPaymentOrCancelReason/ActiveInactive', 'Bplo\CtoPaymentOrCancelReasonController@ActiveInactive');
Route::get('CtoPaymentOrCancelReason/store', 'Bplo\CtoPaymentOrCancelReasonController@store');
Route::resource('CtoPaymentOrCancelReason', 'Bplo\CtoPaymentOrCancelReasonController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentOrCancelReason/formValidation', 'Bplo\CtoPaymentOrCancelReasonController@formValidation')->name('or_cancel_reason.post');

// ------------------------------------- Bplo CTO Cancel Business Permit Reason ------------------------------
Route::get('cancel-business-permit-reason', 'Bplo\CtoPaymentBuspermitCancelReController@index')->name('cabcelBusspermitReason.index');
Route::get('CtoPaymentBuspermitCancelRe/getList', 'Bplo\CtoPaymentBuspermitCancelReController@getList')->name('bplo.cabcelBusspermitReason.getList');
Route::post('CtoPaymentBuspermitCancelRe/ActiveInactive', 'Bplo\CtoPaymentBuspermitCancelReController@ActiveInactive');
Route::get('CtoPaymentBuspermitCancelRe/store', 'Bplo\CtoPaymentBuspermitCancelReController@store');
Route::resource('CtoPaymentBuspermitCancelRe', 'Bplo\CtoPaymentBuspermitCancelReController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentBuspermitCancelRe/formValidation', 'Bplo\CtoPaymentBuspermitCancelReController@formValidation')->name('cabcelBusspermitReason.post');

// ------------------------------------- BploClients------------------------------
Route::post('bploclients-uploadDocument', 'Bplo\BploClientsController@uploadDocument')->name('bploclients.uploadDocument');
Route::post('bploclients-deleteAttachment', 'Bplo\BploClientsController@deleteAttachment')->name('bploclients.deleteAttachment');
Route::get('bploclients/index', 'Bplo\BploClientsController@index')->name('bploclients.index');
Route::post('bploclients/delete', 'Bplo\BploClientsController@Delete');
Route::get('bploclients/getClientsDetails', 'Bplo\BploClientsController@getProfileDetails');
Route::post('bploclients/ActiveInactive', 'Bplo\BploClientsController@ActiveInactive');
Route::get('bploclients/getList', 'Bplo\BploClientsController@getList')->name('bploclients.getList');
Route::any('bploclients/store', 'Bplo\BploClientsController@store');
Route::any('bploclients/view', 'Bplo\BploClientsController@view');
Route::get('bploclients/getViewList', 'Bplo\BploClientsController@getViewList')->name('bploclients.getViewList');
Route::any('bploclients/bulkUpload', 'Bplo\BploClientsController@bulkUpload');
Route::any('bploclients/online_access', 'Bplo\BploClientsController@onlineAccess');
Route::post('bploclients/checkBusinessExist', 'Bplo\BploClientsController@checkBusinessExist');
Route::post('bploclients/AddBusnOnlineAccess', 'Bplo\BploClientsController@AddBusnOnlineAccess');
Route::resource('bploclients', 'Bplo\BploClientsController')->middleware(['auth','revalidate']);
Route::post('bploclients/formValidation', 'Bplo\BploClientsController@formValidation')->name('bploclients.post');
Route::post('bploclients/uploadBulkTaxpayers', 'Bplo\BploClientsController@uploadBulkTaxpayers')->name('bploclients.uploadBulkTaxpayers');
Route::post('bploclients/getBusniessOnlineAccess', 'Bplo\BploClientsController@getBusniessOnlineAccess');
Route::post('bploclients/getBusinessDtls', 'Bplo\BploClientsController@getBusinessDtls')->name('bploclients.getBusinessDtls');
Route::post('bploclients/deleteOnlineAccess', 'Bplo\BploClientsController@deleteOnlineAccess');
Route::post('bploclients/updateOnlineAccessRemoteServer', 'Bplo\BploClientsController@updateOnlineAccessRemoteServer');
Route::post('bploclients/getbusinssForOnlineAccess', 'Bplo\BploClientsController@getbusinssForOnlineAccess');





Route::post('getBarngayList', 'CommonController@getBarngayList');
Route::post('getClientList', 'CommonController@getClientList');
Route::post('getBarngayMunList', 'CommonController@getBarngayMunList');
Route::post('getBploRpt', 'CommonController@getBploRpt');
Route::post('getBploRptVar', 'CommonController@getBploRptVar');
Route::post('getBarngayNameList', 'CommonController@getBarngayNameList');
Route::post('getTaxDecration', 'CommonController@getTaxDecration');
Route::post('getPsicSubclass', 'CommonController@getPsicSubclass');
Route::post('getBarngayLisByRpt', 'CommonController@getBarngayLisByRpt');
Route::post('getBarngayLisByRptFlt', 'CommonController@getBarngayLisByRptFlt');
Route::post('getAllBusinessList', 'CommonController@getAllBusinessList');
Route::post('/getBrgyDetails', 'CommonController@getBrgyDetails')->name('getBrgyDetails');

// ------------------------------------- Bplo CTO Cancel Brgy Clearance Reason ------------------------------
Route::get('bplo-cancel-barangay-clearance-reason', 'Bplo\CtoPaymentBrgyClearCancelReController@index')->name('cancelBrgyclearReason.index');
Route::get('CtoPaymentBrgyClearCancelRe/getList', 'Bplo\CtoPaymentBrgyClearCancelReController@getList')->name('bplo.cancelBrgyclearReason.getList');
Route::post('CtoPaymentBrgyClearCancelRe/ActiveInactive', 'Bplo\CtoPaymentBrgyClearCancelReController@ActiveInactive');
Route::get('CtoPaymentBrgyClearCancelRe/store', 'Bplo\CtoPaymentBrgyClearCancelReController@store');
Route::resource('CtoPaymentBrgyClearCancelRe', 'Bplo\CtoPaymentBrgyClearCancelReController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentBrgyClearCancelRe/formValidation', 'Bplo\CtoPaymentBrgyClearCancelReController@formValidation')->name('cancelBrgyclearReason.post');

// ------------------------------------- Bplo CTO Computation type ------------------------------
Route::get('bplo-computation-type', 'Bplo\CtoComputationTypeController@index')->name('computationType.index');
Route::get('CtoComputationType/getList', 'Bplo\CtoComputationTypeController@getList')->name('bplo.computationType.getList');
Route::post('CtoComputationType/ActiveInactive', 'Bplo\CtoComputationTypeController@ActiveInactive');
Route::get('CtoComputationType/store', 'Bplo\CtoComputationTypeController@store');
Route::resource('CtoComputationType', 'Bplo\CtoComputationTypeController')->middleware(['auth','revalidate']);
Route::post('CtoComputationType/formValidation', 'Bplo\CtoComputationTypeController@formValidation')->name('computationType.post');

// ------------------------------------- Bplo TFOC - Application Computation ------------------------------
Route::get('business-data-tfoc-applicable-computation', 'Bplo\CtoTfocComputationBasisController@index')->name('appComputation.index');
Route::get('CtoTfocComputationBasis/getList', 'Bplo\CtoTfocComputationBasisController@getList')->name('bplo.appComputation.getList');
Route::post('CtoTfocComputationBasis/ActiveInactive', 'Bplo\CtoTfocComputationBasisController@ActiveInactive');
Route::get('CtoTfocComputationBasis/store', 'Bplo\CtoTfocComputationBasisController@store');
Route::resource('CtoTfocComputationBasis', 'Bplo\CtoTfocComputationBasisController')->middleware(['auth','revalidate']);
Route::post('CtoTfocComputationBasis/formValidation', 'Bplo\CtoTfocComputationBasisController@formValidation')->name('appComputation.post');


// ------------------------------------- Eng Electrical Equipment Type ------------------------------
Route::get('excavationgroundtype', 'Engneering\EngExcavationGroundTypeController@index')->name('excavationgroundtype.index');
Route::get('excavationgroundtype/getList', 'Engneering\EngExcavationGroundTypeController@getList')->name('Engneering.excavationgroundtype.getList');
Route::post('excavationgroundtype/ActiveInactive', 'Engneering\EngExcavationGroundTypeController@ActiveInactive');
Route::get('excavationgroundtype/store', 'Engneering\EngExcavationGroundTypeController@store');
Route::resource('excavationgroundtype', 'Engneering\EngExcavationGroundTypeController')->middleware(['auth','revalidate']);
Route::post('excavationgroundtype/formValidation', 'Engneering\EngExcavationGroundTypeController@formValidation')->name('excavationgroundtype.post');

// ------------------------------------- Eng Civil Structure Type ------------------------------
//Route::get('civilstructuretype', 'Engneering\EngCivilStructureTypeController@index')->name('civilstructuretype.index');
Route::get('civilstructuretype/getList', 'Engneering\EngCivilStructureTypeController@getList')->name('civilstructuretype.getList');
Route::post('civilstructuretype/ActiveInactive', 'Engneering\EngCivilStructureTypeController@ActiveInactive');
Route::get('civilstructuretype/store', 'Engneering\EngCivilStructureTypeController@store');
Route::resource('civilstructuretype', 'Engneering\EngCivilStructureTypeController')->middleware(['auth','revalidate']);
Route::post('civilstructuretype/formValidation', 'Engneering\EngCivilStructureTypeController@formValidation')->name('civilstructuretype.post');

// ------------------------------------- Eng Professional Type ------------------------------
//Route::get('engprofessionaltype', 'Engneering\EngProfessionTypeController@index')->name('engprofessionaltype.index');
Route::get('engprofessionaltype/getList', 'Engneering\EngProfessionTypeController@getList')->name('engprofessionaltype.getList');
Route::post('engprofessionaltype/ActiveInactive', 'Engneering\EngProfessionTypeController@ActiveInactive');
Route::get('engprofessionaltype/store', 'Engneering\EngProfessionTypeController@store');
Route::resource('engprofessionaltype', 'Engneering\EngProfessionTypeController')->middleware(['auth','revalidate']);
Route::post('engprofessionaltype/formValidation', 'Engneering\EngProfessionTypeController@formValidation')->name('engprofessionaltype.post');

// ------------------------------------- Eng Professional Sub Type ------------------------------
Route::get('engprofessionalsub', 'Engneering\EngSubProfessionController@index')->name('engprofessionalsub.index');
Route::get('engprofessionalsub/getList', 'Engneering\EngSubProfessionController@getList')->name('engprofessionalsub.getList');
Route::post('engprofessionalsub/ActiveInactive', 'Engneering\EngSubProfessionController@ActiveInactive');
Route::get('engprofessionalsub/store', 'Engneering\EngSubProfessionController@store');
Route::resource('engprofessionalsub', 'Engneering\EngSubProfessionController')->middleware(['auth','revalidate']);
Route::post('engprofessionalsub/formValidation', 'Engneering\EngSubProfessionController@formValidation')->name('engprofessionalsub.post');


// ------------------------------------- Eng Sign Installation  Type ------------------------------
//Route::get('engsigninstllationtype', 'Engneering\EngSignInstallationTypeController@index')->name('engsigninstllationtype.index');
Route::get('engsigninstllationtype/getList', 'Engneering\EngSignInstallationTypeController@getList')->name('engsigninstllationtype.getList');
Route::post('engsigninstllationtype/ActiveInactive', 'Engneering\EngSignInstallationTypeController@ActiveInactive');
Route::get('engsigninstllationtype/store', 'Engneering\EngSignInstallationTypeController@store');
Route::resource('engsigninstllationtype', 'Engneering\EngSignInstallationTypeController')->middleware(['auth','revalidate']);
Route::post('engsigninstllationtype/formValidation', 'Engneering\EngSignInstallationTypeController@formValidation')->name('engsigninstllationtype.post');

// ------------------------------------- Eng Fencing  Type ------------------------------
//Route::get('engfencingtype', 'Engneering\EngFencingTypeController@index')->name('engfencingtype.index');
Route::get('engfencingtype/getList', 'Engneering\EngFencingTypeController@getList')->name('engfencingtype.getList');
Route::post('engfencingtype/ActiveInactive', 'Engneering\EngFencingTypeController@ActiveInactive');
Route::get('engfencingtype/store', 'Engneering\EngFencingTypeController@store');
Route::resource('engfencingtype', 'Engneering\EngFencingTypeController')->middleware(['auth','revalidate']);
Route::post('engfencingtype/formValidation', 'Engneering\EngFencingTypeController@formValidation')->name('engfencingtype.post');

// ------------------------------------- Eng Service   ------------------------------
//Route::get('engservice', 'Engneering\EngServiceController@index')->name('engservice.index');
Route::get('engservice/getList', 'Engneering\EngServiceController@getList')->name('engservice.getList');
Route::post('engservice/ActiveInactive', 'Engneering\EngServiceController@ActiveInactive');
Route::get('engservice/store', 'Engneering\EngServiceController@store');
Route::resource('engservice', 'Engneering\EngServiceController')->middleware(['auth','revalidate']);
Route::post('engservice/viewrequiremets', 'Engneering\EngServiceController@viewrequiremets')->name('engservice.viewrequiremets');
Route::post('engservice/getserviceName', 'Engneering\EngServiceController@getServicefeename')->name('engservice.servicename');
Route::post('engservice/formValidation', 'Engneering\EngServiceController@formValidation')->name('engservice.post');

// ------------------------------------- Eng Service   ------------------------------
//Route::get('servicerequirements', 'Engneering\EngServiceRequirementsController@index')->name('servicerequirements.index');
Route::get('servicerequirements/getList', 'Engneering\EngServiceRequirementsController@getList')->name('servicerequirements.getList');
Route::post('servicerequirements/ActiveInactive', 'Engneering\EngServiceRequirementsController@ActiveInactive');
Route::get('servicerequirements/store', 'Engneering\EngServiceRequirementsController@store');
Route::resource('servicerequirements', 'Engneering\EngServiceRequirementsController')->middleware(['auth','revalidate']);
Route::post('servicerequirements/formValidation', 'Engneering\EngServiceRequirementsController@formValidation')->name('servicerequirements.post');

// ------------------------------------- Bplo CTO Payment Holiday ------------------------------


Route::get('bplo-holiday', 'Bplo\CtoPaymentHolidayController@index')->name('holiday.index');
Route::get('CtoPaymentHoliday/getList', 'Bplo\CtoPaymentHolidayController@getList')->name('bplo.holiday.getList');
Route::post('CtoPaymentHoliday/ActiveInactive', 'Bplo\CtoPaymentHolidayController@ActiveInactive');
Route::get('CtoPaymentHoliday/store', 'Bplo\CtoPaymentHolidayController@store');
Route::resource('CtoPaymentHoliday', 'Bplo\CtoPaymentHolidayController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentHoliday/formValidation', 'Bplo\CtoPaymentHolidayController@formValidation')->name('holiday.post');

// ------------------------------------- Bplo CTO OR Type ------------------------------
Route::post('ortype-uploadDocument', 'Bplo\CtoPaymentOrTypeController@uploadDocument')->name('ORType.uploadDocument');
Route::post('ortype-deleteAttachment', 'Bplo\CtoPaymentOrTypeController@deleteAttachment')->name('ORType.deleteAttachment');
Route::get('casherviewpay/casherview', 'Bplo\CtoPaymentOrTypeController@casherview');
Route::Post('ctopaymentortypedetails', 'Bplo\CtoPaymentOrTypeController@addpaymentdetils');
Route::Post('ctopaymentortypedetails-delete', 'Bplo\CtoPaymentOrTypeController@deletefuntion');
Route::get('bplo-or-type', 'Bplo\CtoPaymentOrTypeController@index')->name('ORType.index');
Route::get('CtoPaymentOrType/getList', 'Bplo\CtoPaymentOrTypeController@getList')->name('bplo.ORType.getList');
Route::post('CtoPaymentOrType/ActiveInactive', 'Bplo\CtoPaymentOrTypeController@ActiveInactive');
Route::get('CtoPaymentOrType/store', 'Bplo\CtoPaymentOrTypeController@store');
Route::resource('CtoPaymentOrType', 'Bplo\CtoPaymentOrTypeController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentOrType/formValidation', 'Bplo\CtoPaymentOrTypeController@formValidation')->name('ORType.post');

// ------------------------------------- Bplo CTO OR Assignment ------------------------------
Route::get('bplo-or-asssignment', 'Bplo\CtoPaymentOrAssignmentController@index')->name('ORAssignment.index');
Route::get('CtoPaymentOrAssignment/getList', 'Bplo\CtoPaymentOrAssignmentController@getList')->name('bplo.ORAssignment.getList');
Route::post('CtoPaymentOrAssignment/ActiveInactive', 'Bplo\CtoPaymentOrAssignmentController@ActiveInactive');
Route::post('CtoPaymentOrAssignment/getOrDescoption', 'Bplo\CtoPaymentOrAssignmentController@getOrDescoption');
Route::post('CtoPaymentOrAssignment/getOrDetails', 'Bplo\CtoPaymentOrAssignmentController@getOrDetails');
Route::get('CtoPaymentOrAssignment/store', 'Bplo\CtoPaymentOrAssignmentController@store');
Route::resource('CtoPaymentOrAssignment', 'Bplo\CtoPaymentOrAssignmentController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentOrAssignment/formValidation', 'Bplo\CtoPaymentOrAssignmentController@formValidation')->name('ORAssignment.post');

// ------------------------------------- Bplo Bank ------------------------------
Route::get('bplo-bank', 'Bplo\CtoPaymentBankController@index')->name('bank.index');
Route::get('CtoPaymentBank/getList', 'Bplo\CtoPaymentBankController@getList')->name('bplo.bank.getList');
Route::post('CtoPaymentBank/ActiveInactive', 'Bplo\CtoPaymentBankController@ActiveInactive');
Route::get('CtoPaymentBank/store', 'Bplo\CtoPaymentBankController@store');
Route::resource('CtoPaymentBank', 'Bplo\CtoPaymentBankController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentBank/formValidation', 'Bplo\CtoPaymentBankController@formValidation')->name('bank.post');



// ------------------------------------- Bplo Payment Due Date ------------------------------
Route::get('payment-data-due-date', 'Bplo\CtoPaymentDueDateController@index')->name('duedate.index');
Route::get('CtoPaymentDueDate/getList', 'Bplo\CtoPaymentDueDateController@getList')->name('bplo.duedate.getList');
Route::post('CtoPaymentDueDate/ActiveInactive', 'Bplo\CtoPaymentDueDateController@ActiveInactive');
Route::get('CtoPaymentDueDate/store', 'Bplo\CtoPaymentDueDateController@store');
Route::resource('CtoPaymentDueDate', 'Bplo\CtoPaymentDueDateController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentDueDate/formValidation', 'Bplo\CtoPaymentDueDateController@formValidation')->name('duedate.post');
Route::post('CtoPaymentDueDate/deleteAttchment', 'Bplo\CtoPaymentDueDateController@deleteAttchment');

// ------------------------------------- Bplo Interest/Surcharges ------------------------------
Route::get('business-data-interest-surcharges', 'Bplo\CtoTaxInterestSurchargeController@index')->name('interestSurcharge.index');
Route::any('CtoTaxInterestSurcharge/store', 'Bplo\CtoTaxInterestSurchargeController@store');
Route::resource('CtoTaxInterestSurcharge', 'Bplo\CtoTaxInterestSurchargeController')->middleware(['auth','revalidate']);
 
// ------------------------------------- Bplo CTO OR Setup ------------------------------
Route::get('bplo-or-setup', 'Bplo\CtoPaymentOrSetupController@index')->name('orSetup.index');
Route::get('bplo-or-setup-sample/{id}', 'Bplo\CtoPaymentOrSetupController@samplePrint')->name('bplo-or-setup-sample');
Route::get('copy-or-setups', 'Bplo\CtoPaymentOrSetupController@copyOrsetup')->name('copy-or-setups');
Route::get('CtoPaymentOrSetup/getList', 'Bplo\CtoPaymentOrSetupController@getList')->name('bplo.orSetup.getList');
Route::post('CtoPaymentOrSetup/ActiveInactive', 'Bplo\CtoPaymentOrSetupController@ActiveInactive');
Route::post('CtoPaymentOrSetup/saveDetails', 'Bplo\CtoPaymentOrSetupController@saveDetails');
Route::get('CtoPaymentOrSetup/store', 'Bplo\CtoPaymentOrSetupController@store');
Route::resource('CtoPaymentOrSetup', 'Bplo\CtoPaymentOrSetupController')->middleware(['auth','revalidate']);
Route::post('CtoPaymentOrSetup/formValidation', 'Bplo\CtoPaymentOrSetupController@formValidation')->name('orSetup.post');

// ------------------------------------- Bplo PSIC IFOC ------------------------------
Route::get('PsicTfoc', 'Bplo\PsicTfocController@index')->name('PsicTfoc.index');
Route::get('PsicTfoc/store', 'Bplo\PsicTfocController@store');
Route::get('PsicTfoc/getList', 'Bplo\PsicTfocController@getList')->name('bplo.PsicTfoc.getList');
Route::post('PsicTfoc/ActiveInactive', 'Bplo\PsicTfocController@ActiveInactive');
Route::post('PsicTfoc/formValidation', 'Bplo\PsicTfocController@formValidation')->name('PsicTfoc.post');
Route::resource('PsicTfoc', 'Bplo\PsicTfocController')->middleware(['auth','revalidate']);
Route::post('getChartAccount', 'Bplo\PsicTfocController@getChartAccount');
Route::post('getTfocBasis', 'Bplo\PsicTfocController@getTfocBasis');
Route::post('getTFOCDtlsAllList', 'Bplo\PsicTfocController@getTFOCDtlsList');
Route::post('getChargesAllList', 'Bplo\PsicTfocController@getChargesList');
Route::post('getTypeComputationAllList', 'Bplo\PsicTfocController@getTypeComputationList');


//--------------------------------Endorsement--------
Route::post('getBarangayAjax', 'Bplo\EndrosementController@getBarangayAjax');
Route::get('fire-protection/endorsement', 'Bplo\EndrosementController@index')->name('endorsement.index');
Route::get('fire-protection/print-summary/{id}', 'Bplo\EndrosementController@print_summary')->name('bplo.business.print_summary');
Route::get('fire-protection/getList', 'Bplo\EndrosementController@getList')->name('endorsement.getList');
Route::get('Endrosement/store', 'Bplo\EndrosementController@store');
Route::get('Endrosement/healthCertificate', 'Bplo\EndrosementController@healthCertificate');
Route::any('Endrosement/sanitaryPermit', 'Bplo\EndrosementController@sanitaryPermit');
Route::post('Endrosement/uploadSanitaryDoc', 'Bplo\EndrosementController@uploadSanitaryDoc');
Route::post('Endrosement/deleteSanitaryReq', 'Bplo\EndrosementController@deleteSanitaryReq');
Route::post('Endrosement/sanitaryPermit/formValidation', 'Bplo\EndrosementController@formValidation');

Route::get('Endrosement/getHealthCertificateList', 'Bplo\EndrosementController@getHealthCertificateList');
Route::post('Endrosement/getSelectHealthCert', 'Bplo\EndrosementController@getSelectHealthCert');
Route::get('Endrosement/removeHealthCert/{id}', 'Bplo\EndrosementController@removeHealthCert');
Route::get('Endrosement/approveHealthCert/{id}', 'Bplo\EndrosementController@approveHealthCert');
Route::get('Endrosement/apvRcmHealthCert/{id}', 'Bplo\EndrosementController@apvRcmHealthCert');
Route::get('Endrosement/addHealthCertificateDoc', 'Bplo\EndrosementController@addHealthCertificateDoc');


Route::post('Endrosement/uploadDocument', 'Bplo\EndrosementController@uploadDocument');
Route::post('Endrosement/assessmentDetails', 'Bplo\EndrosementController@assessmentDetails');
Route::post('Endrosement/deleteAttachment', 'Bplo\EndrosementController@deleteAttachment');
Route::post('Endrosement/updateEndorsementStatus', 'Bplo\EndrosementController@updateEndorsementStatus');
Route::get('Endrosement/addHealthCertificate', 'Bplo\EndrosementController@addHealthCertificate');
Route::get('Endrosement/storeHealthCert/{id}', 'Bplo\EndrosementController@storeHealthCert');
Route::get('pdo-endorsement', 'Bplo\EndrosementController@pdoIndex')->name('endorsement.pdoIndex');
Route::get('health-safety-endorsement-business-permit', 'Bplo\EndrosementController@healtSafetyEndrosementIndex')->name('endorsement.healtSafetyEndrosementIndex');
Route::get('environmental-endorsement', 'Bplo\EndrosementController@environmentalEndrosementIndex')->name('endorsement.environmentalEndrosementIndex');
Route::get('export-endrosement-lists','Bplo\EndrosementController@exportreportsEndrosementlists')->name('bplo.business.export');

//--------------------------------Endorsement Inspection--------

Route::any('firePrint', 'Bplo\EndrosementInspectionController@firePrint');
Route::get('Endrosement/application', 'Bplo\EndrosementInspectionController@application');
Route::post('Endrosement/deleteEndrosmentInspectionAttachment', 'Bplo\EndrosementInspectionController@deleteEndrosmentInspectionAttachment');
Route::post('Endrosement/uploadAttachmentInspection', 'Bplo\EndrosementInspectionController@uploadAttachmentInspection');

// ------------------------------------- Bplo Fee Master ------------------------------
Route::get('business-fee-master', 'Bplo\BfpFeesMasterController@index')->name('Bplo.feemaster.index');
Route::get('business-fee-master/getList', 'Bplo\BfpFeesMasterController@getList')->name('Bplo.feemaster.getList');
Route::post('BploBusinessType/FeesMasterActiveInactive', 'Bplo\BploBusinessTypeController@FeesMasterActiveInactive');
Route::get('business-fee-master/store', 'Bplo\BfpFeesMasterController@store');
Route::post('business-fee-master/getcheckboxes', 'Bplo\BfpFeesMasterController@getcheckboxes');
Route::resource('business-fee-master', 'Bplo\BfpFeesMasterController')->middleware(['auth','revalidate']);
Route::post('business-fee-master/formValidation', 'Bplo\BfpFeesMasterController@formValidation')->name('Bplo.feemaster.post');

// ------------------------------------- Eng Building Permit Service   ------------------------------
Route::get('engjobrequest', 'Engneering\EngJobRequestController@index')->name('engjobrequest.index');
Route::get('engjobrequest/getList', 'Engneering\EngJobRequestController@getList')->name('engjobrequest.getList');
Route::post('engjobrequest/getRptOwnersAjax', 'Engneering\EngJobRequestController@getEngOwnersAjax');
Route::post('engjobrequest/getbildOfficialAjax', 'Engneering\EngJobRequestController@getbildOfficialAjax');
Route::post('engjobrequest/getPermitnoAjax', 'Engneering\EngJobRequestController@getPermitnoAjax');
Route::post('engjobrequest/getExteranlsAjax', 'Engneering\EngJobRequestController@getExteranlsAjax');
Route::post('engjobrequest/ActiveInactive', 'Engneering\EngJobRequestController@ActiveInactive');
Route::post('engjobrequest/deleteFeedetails', 'Engneering\EngJobRequestController@deleteFeedetails');
Route::post('engjobrequest/deleteAttachment', 'Engneering\EngJobRequestController@deleteAttachment');
Route::get('engjobrequest/getClientsDetails', 'Engneering\EngJobRequestController@getProfileDetails');
Route::post('engjobrequest/storeEngbillSummary', 'Engneering\EngJobRequestController@storeEngbillSummary');
Route::get('engjobrequest/store', 'Engneering\EngJobRequestController@store');
Route::get('engjobrequest/getsuboccupancytype', 'Engneering\EngJobRequestController@getsuboccupancytype');
Route::post('engjobrequest/savejobreuest', 'Engneering\EngJobRequestController@savejobreuest');
Route::post('engjobrequest/permitvalidationBuilding', 'Engneering\EngJobRequestController@PermitValidationBuilding');
Route::post('engjobrequest/permitvalidationSanitary', 'Engneering\EngJobRequestController@permitvalidationSanitary');
Route::post('engjobrequest/permitvalidationElectric', 'Engneering\EngJobRequestController@permitvalidationElectric');
Route::post('engjobrequest/permitvalidationElectronic', 'Engneering\EngJobRequestController@permitvalidationElectronic');
Route::post('engjobrequest/permitvalidationMechanical', 'Engneering\EngJobRequestController@permitvalidationMechanical');
Route::post('engjobrequest/permitvalidationExcavation', 'Engneering\EngJobRequestController@permitvalidationExcavation');
Route::post('engjobrequest/permitvalidationCivil', 'Engneering\EngJobRequestController@permitvalidationCivil');
Route::post('engjobrequest/permitvalidationArchitectural', 'Engneering\EngJobRequestController@permitvalidationArchitectural');
Route::post('engjobrequest/permitvalidationFencing', 'Engneering\EngJobRequestController@permitvalidationFencing'); 
Route::post('engjobrequest/permitvalidationSign', 'Engneering\EngJobRequestController@permitvalidationSign');
Route::post('engjobrequest/permitvalidationDemolition', 'Engneering\EngJobRequestController@permitvalidationDemolition'); 
Route::get('engjobrequest/showserviceform', 'Engneering\EngJobRequestController@showserviceform');
Route::post('engjobrequest/saveorderofpayment', 'Engneering\EngJobRequestController@saveorderofpayment');
Route::post('engjobrequest/getApplicationType', 'Engneering\EngJobRequestController@getApplicationType');
Route::post('engjobrequest/getZoninginfo', 'Engneering\EngJobRequestController@getZoninginfo');
Route::post('engjobrequest/getFalcnobycleint', 'Engneering\EngJobRequestController@getFalcnobycleint');
Route::post('engjobrequest/getFalcnobyAjax', 'Engneering\EngJobRequestController@getFalcnobyAjax');
Route::post('engjobrequest/getbarngaybyfalcno', 'Engneering\EngJobRequestController@getbarngaybyfalcno');
Route::post('engjobrequest/showelectricrevisionform', 'Engneering\EngJobRequestController@showelectricrevisionform');
Route::post('engjobrequest/showbuildingrevisionform', 'Engneering\EngJobRequestController@showbuildingrevisionform');
Route::post('engjobrequest/checkloadrange', 'Engneering\EngJobRequestController@checkloadrange');
Route::post('engjobrequest/calculatebuildingfee', 'Engneering\EngJobRequestController@calculatebuildingfee');
Route::post('engjobrequest/calculatesanitaryfee', 'Engneering\EngJobRequestController@calculatesanitaryfee');
Route::post('engjobrequest/checkupsrange', 'Engneering\EngJobRequestController@checkupsrange');
Route::post('engjobrequest/getpoleamount', 'Engneering\EngJobRequestController@getpoleamount');
Route::post('engjobrequest/getmiscellaneousamount', 'Engneering\EngJobRequestController@getmiscellaneousamount');
Route::post('engjobrequest/getRequirements', 'Engneering\EngJobRequestController@getRequirements');
Route::post('jobrequest/storebuildingpermit', 'Engneering\EngJobRequestController@SaveBuildingPermit');
Route::post('jobrequest/saveelectricalcalculation', 'Engneering\EngJobRequestController@saveelectricalcalculation');
Route::post('jobrequest/savebuildingculation', 'Engneering\EngJobRequestController@savebuildingculation');
Route::post('jobrequest/storesanitarypermit', 'Engneering\EngJobRequestController@storesanitarypermit');
Route::post('jobrequest/storeelectricpermit', 'Engneering\EngJobRequestController@storeelectricpermit');
Route::post('jobrequest/storeelectrronicpermit', 'Engneering\EngJobRequestController@storeelectrronicpermit');
Route::post('jobrequest/storemechanicalpermit', 'Engneering\EngJobRequestController@storemechanicalpermit');
Route::post('jobrequest/storeexcavationpermit', 'Engneering\EngJobRequestController@storeexcavationpermit');
Route::post('jobrequest/storecivilpermit', 'Engneering\EngJobRequestController@storecivilpermit');
Route::post('jobrequest/storearchitecturalpermit', 'Engneering\EngJobRequestController@storearchitecturalpermit');
Route::post('jobrequest/storefencingpermit', 'Engneering\EngJobRequestController@storefencingpermit');
Route::post('jobrequest/storesigngpermit', 'Engneering\EngJobRequestController@storesigngpermit');
Route::post('jobrequest/storedemolitionpermit', 'Engneering\EngJobRequestController@storedemolitionpermit');
Route::post('jobrequest/MakeapprovePermit', 'Engneering\EngJobRequestController@MakeapprovePermit');
Route::post('jobrequest/UpdatePermitIssued', 'Engneering\EngJobRequestController@UpdatePermitIssued');
Route::post('engjobrequest/getBarngayList', 'Engneering\EngJobRequestController@getBarngayList');
Route::get('engjobrequest/showbuildingappfrom', 'Engneering\EngJobRequestController@showbuildingappfrom');
Route::get('engjobrequest/showelectricpermitform', 'Engneering\EngJobRequestController@showelectricpermitform');
Route::get('engjobrequest/showcivilpermitform', 'Engneering\EngJobRequestController@showcivilpermitform');
Route::get('engjobrequest/showelectronicspermitform', 'Engneering\EngJobRequestController@showelectronicspermitform');
Route::get('engjobrequest/showmechanicalpermitform', 'Engneering\EngJobRequestController@showmechanicalpermitform');
Route::get('engjobrequest/showexcavationpermitform', 'Engneering\EngJobRequestController@showexcavationpermitform');
Route::get('engjobrequest/showarchitecturalpermitform', 'Engneering\EngJobRequestController@showarchitecturalpermitform');
Route::get('engjobrequest/showfencingpermitform', 'Engneering\EngJobRequestController@showfencingpermitform');
Route::get('engjobrequest/showsignpermitform', 'Engneering\EngJobRequestController@showsignpermitform');
Route::get('engjobrequest/showdemolitionpermitform', 'Engneering\EngJobRequestController@showdemolitionpermitform');
//---------------------------------- Eng Sanitary Permit-------------------------------
Route::get('engjobrequest/showsanitarypermitform', 'Engneering\EngJobRequestController@showsanitarypermitform');
Route::get('engjobrequest/getConslutant', 'Engneering\EngJobRequestController@getConsultants');
Route::get('engjobrequest/printpermit', 'Engneering\EngJobRequestController@printpermit');
Route::get('engjobrequest/print-permit/{id}', 'Engneering\EngPrintController@print')->name('eng-permit-print');
Route::get('engjobrequest/print-sanitary/{id}', 'Engneering\EngPrintController@print_sanitary');//for ken
Route::get('engjobrequest/print-mechanical/{id}', 'Engneering\EngPrintController@print_mechanical');//for ken
Route::get('engjobrequest/print-order-of-payment/{id}', 'Engneering\EngPrintController@print_order_of_payment')->name('eng-print-order');//for ken)
Route::get('engjobrequest/print_order_of_paymentfile/{id}', 'Engneering\EngPrintController@print_order_of_paymentfile')->name('eng-print-orderfile');//for ken)
Route::post('jobrequest/Printorder', 'Engneering\EngJobRequestController@Printorder');
Route::get('engjobrequest/getSignDetails', 'Engneering\EngJobRequestController@getSignDetails');
Route::get('engjobrequest/getApplicant', 'Engneering\EngJobRequestController@getApplicant');
Route::get('engjobrequest/getRptClientDetails', 'Engneering\EngJobRequestController@getRptClientDetails');
Route::get('engjobrequest/getbuildingpermitdetails', 'Engneering\EngJobRequestController@getbuildingpermitdetails');
Route::resource('engjobrequest', 'Engneering\EngJobRequestController')->middleware(['auth','revalidate']);
Route::post('engjobrequest/formValidation', 'Engneering\EngJobRequestController@formValidation')->name('engjobrequest.post');

//--------------------------------occupancy app url--------
Route::get('engoccupancyapp', 'Engneering\EngOccupancyAppController@index')->name('engoccupancyapp.index');
Route::get('engoccupancyapp/getList', 'Engneering\EngOccupancyAppController@getList')->name('engoccupancyapp.getList');
Route::get('engoccupancyapp/Printorder', 'Engneering\EngOccupancyAppController@Printorder');
Route::get('engoccupancyapp/getbuidingdata', 'Engneering\EngOccupancyAppController@getbuidingdata');
Route::post('engoccupancyapp/GetBuildingpermitsAjax', 'Engneering\EngOccupancyAppController@GetBuildingpermitsAjax');
Route::post('engoccupancyapp/getSercviceRequirementsAjax', 'Engneering\EngOccupancyAppController@getSercviceRequirementsAjax');
Route::post('engoccupancyapp/ActiveInactive', 'Engneering\EngOccupancyAppController@ActiveInactive');
Route::post('engoccupancyapp/saveorderofpayment', 'Engneering\EngOccupancyAppController@saveorderofpayment');
Route::post('engoccupancyapp/deleteAttachment', 'Engneering\EngOccupancyAppController@deleteAttachment');
Route::post('engoccupancyapp/storeOccubillSummary', 'Engneering\EngOccupancyAppController@storeOccubillSummary');
Route::post('engoccupancyapp/MakeapprovePermit', 'Engneering\EngOccupancyAppController@MakeapprovePermit');
Route::post('engoccupancyapp/UpdatePermitIssued', 'Engneering\EngOccupancyAppController@UpdatePermitIssued');
Route::post('engoccupancyapp/getBarngayList', 'Engneering\EngOccupancyAppController@getBarngayList');
Route::post('engoccupancyapp/deleteFeedetails', 'Engneering\EngOccupancyAppController@deleteFeedetails');
Route::get('engoccupancyapp/getClientsDetails', 'Engneering\EngOccupancyAppController@getProfileDetails');
Route::get('engoccupancyapp/store', 'Engneering\EngOccupancyAppController@store');
Route::post('engoccupancyapp/savejobreuest', 'Engneering\EngOccupancyAppController@savejobreuest');
Route::get('engoccupancyapp/certificateoccupancyprint/{id}', 'Engneering\EngOccupancyAppController@PrintCertificateOfOccupancy')->name('engoccupancyapp.certificateoccupancyprint');
Route::resource('engoccupancyapp', 'Engneering\EngOccupancyAppController')->middleware(['auth','revalidate']);
Route::post('engoccupancyapp/formValidation', 'Engneering\EngOccupancyAppController@formValidation')->name('engoccupancyapp.post');

// ------------------------------------- Social Welfare ------------------------------
// citizens
Route::middleware(['auth'])->prefix('citizens')->group(function () {
    Route::get('/', 'SocialWelfare\CitizenController@index')->name('social.citizen.index');
    Route::get('getList', 'SocialWelfare\CitizenController@getList')->name('citizen.list');
    Route::post('getCitizens', 'SocialWelfare\CitizenController@getCitizens')->name('citizen.select');
    Route::post('getCitizenMunicipalOnly', 'SocialWelfare\CitizenController@getCitizenMunicipalOnly')->name('citizen.select');
    // Route::post('getCitizens2', 'SocialWelfare\CitizenController@getCitizens2')->name('citizen.select2');
    Route::post('getCitizen', 'SocialWelfare\CitizenController@getCitizen')->name('citizen.get');
    Route::post('getBrgy', 'SocialWelfare\CitizenController@getBrgy')->name('citizen.getBrgy');
    Route::post('getNationality', 'SocialWelfare\CitizenController@getNationality')->name('citizen.getNationality');
    Route::post('selectEmployee', 'SocialWelfare\AssistanceController@selectEmployee')->name('citizen.selectEmployee');
    Route::match(array('GET', 'POST'), 'store', 'SocialWelfare\CitizenController@store')->name('citizen.store');
    Route::post('/store/formValidation', 'SocialWelfare\CitizenController@formValidation')->name('citizen.post');
    Route::resource('Citizen', 'SocialWelfare\CitizenController')->middleware(['auth','revalidate']);
    Route::post('/ActiveInactive', 'SocialWelfare\CitizenController@ActiveInactive')->name('citizen.delete');
});
    Route::post('citizens/deleteAttachment', 'SocialWelfare\CitizenController@deleteAttachment');
    Route::post('citizens/uploadAttachment', 'SocialWelfare\CitizenController@uploadAttachment');
// health-safety-citizens
Route::middleware(['auth'])->prefix('health-safety-citizens')->group(function () {
    Route::get('/', 'SocialWelfare\CitizenController@index')->name('citizen.index');
});


Route::middleware(['auth'])->prefix('social-welfare')->group(function () {
    // social-welfare/assistance
    Route::prefix('assistance')->group(function () {
        Route::get('/', 'SocialWelfare\AssistanceController@index')->name('assistance.index');
        Route::match(array('GET', 'POST'), 'store', 'SocialWelfare\AssistanceController@store')->name('assistance.store');
        Route::get('getList', 'SocialWelfare\AssistanceController@getList')->name('assistance.list');
        Route::post('/store/formValidation', 'SocialWelfare\AssistanceController@formValidation')->name('assistance.post');
        Route::post('/ActiveInactive', 'SocialWelfare\AssistanceController@ActiveInactive')->name('assistance.activate');
        Route::post('/active', 'SocialWelfare\AssistanceController@active')->name('assistance.active');
        Route::post('/approve', 'SocialWelfare\AssistanceController@approve')->name('assistance.approve');
        Route::post('getRequirements', 'SocialWelfare\AssistanceController@getRequirements')->name('assistance.getRequirements');
        Route::post('getRequireList', 'SocialWelfare\AssistanceController@getRequireList')->name('assistance.getRequireList');
        Route::get('print-justification/{id}', 'SocialWelfare\AssistanceController@printJustification')->name('assistance.printJustification');
        Route::get('print-eligibility/{id}', 'SocialWelfare\AssistanceController@printEligibility')->name('assistance.printEligibility');
        Route::get('print-application/{id}', 'SocialWelfare\AssistanceController@printApplication')->name('assistance.printApplication');
        Route::get('print-request-letter/{id}', 'SocialWelfare\AssistanceController@printRequestLetter')->name('assistance.printRequestLetter');
        Route::get('print-case-study/{id}', 'SocialWelfare\AssistanceController@printCaseStudy')->name('assistance.printCaseStudy');

        Route::match(array('GET', 'POST'), 'request-letter/{id}', 'SocialWelfare\AssistanceController@requestLetter')->name('assistance.requestLetter');
        Route::post('/request-letter/{id}/formValidation', 'SocialWelfare\AssistanceController@letterValidation');
    });
    //social-welfare/solo-parent-id
    Route::prefix('solo-parent-id')->group(function () {
        Route::get('/', 'SocialWelfare\SoloParentIDController@index')->name('soloparent.index');
        Route::match(array('GET', 'POST'), 'store', 'SocialWelfare\SoloParentIDController@store')->name('soloparent.store');
        Route::get('getList', 'SocialWelfare\SoloParentIDController@getList')->name('soloparent.list');
        Route::post('/store/formValidation', 'SocialWelfare\SoloParentIDController@formValidation')->name('soloparent.post');
        Route::post('/ActiveInactive', 'SocialWelfare\SoloParentIDController@ActiveInactive')->name('soloparent.activate');
        Route::post('/active', 'SocialWelfare\SoloParentIDController@active')->name('soloparent.active');
        Route::post('/getLastID', 'SocialWelfare\SoloParentIDController@getLastID')->name('soloparent.getLastID');
        Route::get('/print/{id}', 'SocialWelfare\SoloParentIDController@print')->name('soloparent.print');
    });
    // social-welfare/senior-citizen-id
    Route::prefix('senior-citizen-id')->group(function () {
        Route::get('/', 'SocialWelfare\SeniorCitizenIDController@index')->name('senior.index');
        Route::match(array('GET', 'POST'), 'store', 'SocialWelfare\SeniorCitizenIDController@store')->name('senior.store');
        Route::get('getList', 'SocialWelfare\SeniorCitizenIDController@getList')->name('senior.list');
        Route::post('/store/formValidation', 'SocialWelfare\SeniorCitizenIDController@formValidation')->name('senior.post');
        Route::post('/ActiveInactive', 'SocialWelfare\SeniorCitizenIDController@ActiveInactive')->name('senior.activate');
        Route::post('/active', 'SocialWelfare\SeniorCitizenIDController@active')->name('senior.active');
        Route::post('/getLastID', 'SocialWelfare\SeniorCitizenIDController@getLastID')->name('senior.getLastID');
        Route::get('/print/{id}', 'SocialWelfare\SeniorCitizenIDController@print')->name('senior.print');
    });
    // social-welfare/pwd-id
    Route::prefix('pwd-id')->group(function () {
        Route::get('/', 'SocialWelfare\PWDController@index')->name('pwd.index');
        Route::match(array('GET', 'POST'), 'store', 'SocialWelfare\PWDController@store')->name('pwd.store');
        Route::get('getList', 'SocialWelfare\PWDController@getList')->name('pwd.list');
        Route::post('/store/formValidation', 'SocialWelfare\PWDController@formValidation')->name('pwd.post');
        Route::post('/ActiveInactive', 'SocialWelfare\PWDController@ActiveInactive')->name('pwd.activate');
        Route::post('/active', 'SocialWelfare\PWDController@active')->name('pwd.active');
        Route::post('/getLastID', 'SocialWelfare\PWDController@getLastID')->name('pwd.getLastID');
        Route::post('/getBrgyDetails', 'SocialWelfare\PWDController@getBrgyDetails')->name('pwd.getBrgyDetails');
        Route::get('/print/{id}', 'SocialWelfare\PWDController@print')->name('pwd.print');

        Route::prefix('setup-data')->group(function () {
            // social-welfare/pwd-id/setup-data/cause-disability-inborn
            Route::prefix('cause-disability-inborn')->group(function () {
                Route::get('/#', 'SocialWelfare\CauseDisabilityController@index')->name('cause-disability-inborn.index');
                Route::get('getList', 'SocialWelfare\CauseDisabilityController@getList')->name('setup-data-cause-disability.getList');
                Route::post('ActiveInactive', 'SocialWelfare\CauseDisabilityController@ActiveInactive');
                Route::get('store', 'SocialWelfare\CauseDisabilityController@store');
                Route::post('formValidation', 'SocialWelfare\CauseDisabilityController@formValidation')->name('setup-data-cause-disability.post');
                Route::resource('/', 'SocialWelfare\CauseDisabilityController')->middleware(['auth','revalidate']);
            });

            // social-welfare/pwd-id/setup-data/cause-disability-acquire
            Route::prefix('cause-disability-acquire')->group(function () {
                Route::get('/#', 'SocialWelfare\CauseDisabilityAcquireController@index')->name('cause-disability-aquire.index');
                Route::get('getList', 'SocialWelfare\CauseDisabilityAcquireController@getList')->name('cause-disability-aquire.getList');
                Route::post('ActiveInactive', 'SocialWelfare\CauseDisabilityAcquireController@ActiveInactive');
                Route::get('store', 'SocialWelfare\CauseDisabilityAcquireController@store');
                Route::post('formValidation', 'SocialWelfare\CauseDisabilityAcquireController@formValidation')->name('cause-disability-aquire.post');
                Route::resource('/', 'SocialWelfare\CauseDisabilityAcquireController')->middleware(['auth','revalidate']);
            });
        });

    });
    // social-welfare/travel-clearance-minor
    Route::prefix('travel-clearance-minor')->group(function () {
		Route::get('/', 'SocialWelfare\TravelClearanceMinorController@index')->name('tcm.index');
        Route::match(array('GET', 'POST'), 'store', 'SocialWelfare\TravelClearanceMinorController@store')->name('tcm.store');
        Route::get('getList', 'SocialWelfare\TravelClearanceMinorController@getList')->name('tcm.list');
        Route::post('/store/formValidation', 'SocialWelfare\TravelClearanceMinorController@formValidation')->name('tcm.post');
        Route::post('/ActiveInactive', 'SocialWelfare\TravelClearanceMinorController@ActiveInactive')->name('tcm.activate');
        Route::post('/active', 'SocialWelfare\TravelClearanceMinorController@active')->name('tcm.active');
        Route::get('/print/{id}', 'SocialWelfare\TravelClearanceMinorController@print')->name('tcm.print');
        Route::post('/approve', 'SocialWelfare\TravelClearanceMinorController@approve')->name('assistance.approve');
        Route::post('/getTransactionDetails', 'SocialWelfare\TravelClearanceMinorController@getTransactionDetails')->name('assistance.getTransactionDetails');
    });
	
    Route::prefix('setup-data')->group(function () {
        // social-welfare/setup-data/policy
        Route::prefix('policy')->group(function () {
            Route::get('/#', 'SocialWelfare\PolicyController@index')->name('sw_policy.index');
            Route::get('getList', 'SocialWelfare\PolicyController@getList')->name('sw_policy.getList');
            Route::post('ActiveInactive', 'SocialWelfare\PolicyController@ActiveInactive');
            Route::match(array('GET', 'POST'),'store', 'SocialWelfare\PolicyController@store');
            Route::post('formValidation', 'SocialWelfare\PolicyController@formValidation')->name('sw_policy.post');
            Route::resource('/', 'SocialWelfare\PolicyController')->middleware(['auth','revalidate']);
        });
    });


	Route::get('/assistance-type-requirements', 'SocialWelfare\AssistanceTypeRequirements@index')->name('assistance-type-requirements.index');
	Route::get('/assistance-type-requirements/getList', 'SocialWelfare\AssistanceTypeRequirements@getList')->name('assistance-type-requirements.getList');
	Route::post('/assistance-type-requirements/ActiveInactive', 'SocialWelfare\AssistanceTypeRequirements@ActiveInactive');
	Route::get('/assistance-type-requirements/store', 'SocialWelfare\AssistanceTypeRequirements@store');
	Route::resource('/assistance-type-requirements', 'SocialWelfare\AssistanceTypeRequirements')->middleware(['auth','revalidate']);
	Route::post('/assistance-type-requirements/formValidation', 'SocialWelfare\AssistanceTypeRequirements@formValidation')->name('assistance-type-requirements');
});

//--------------------------------Cpdo Service--------
Route::middleware(['auth'])->prefix('planning')->group(function () {
    /* Designation Routes */
    Route::prefix('setup-data')->group(function () {
        Route::prefix('services')->group(function () {
        Route::get('', 'Cpdo\CpdoServiceController@index')->name('cpdoservice.index');
         });
    });
});
//Route::get('cpdoservice', 'Cpdo\CpdoServiceController@index')->name('cpdoservice.index');
Route::get('cpdoservice/getList', 'Cpdo\CpdoServiceController@getList')->name('cpdoservice.getList');
Route::post('cpdoservice/ActiveInactive', 'Cpdo\CpdoServiceController@ActiveInactive');
Route::post('cpdoservice/getserviceName', 'Cpdo\CpdoServiceController@getserviceName')->name('cpdoservice.servicename');
Route::get('cpdoservice/getClientsDetails', 'Cpdo\CpdoServiceController@getProfileDetails');
Route::get('cpdoservice/store', 'Cpdo\CpdoServiceController@store');
Route::resource('cpdoservice', 'Cpdo\CpdoServiceController')->middleware(['auth','revalidate']);
Route::post('cpdoservice/viewrequiremets', 'Cpdo\CpdoServiceController@viewrequiremets')->name('cpdoservice.viewrequiremets');
Route::post('cpdoservice/formValidation', 'Cpdo\CpdoServiceController@formValidation')->name('cpdoservice.post');

Route::middleware(['auth'])->prefix('planning')->group(function () {
        Route::prefix('locationclearance')->group(function () {
        Route::get('', 'Cpdo\CpdoLocationClearanceController@index')->name('locationclearance.index');
         });
});
//Route::get('cpdoservice', 'Cpdo\CpdoServiceController@index')->name('cpdoservice.index');
Route::post('locationclearance/deleteEndrosmentInspectionAttachment', 'Bplo\PdoBploEndosementController@deleteEndrosmentInspectionAttachment');
Route::post('locationclearance/uploadAttachmentInspection', 'Bplo\PdoBploEndosementController@uploadAttachmentInspection');
Route::get('locationclearance/getList', 'Bplo\PdoBploEndosementController@getList')->name('locationclearance.getList');
Route::get('locationclearance/getClientsDetails', 'Bplo\PdoBploEndosementController@getClientsDetails');
Route::post('locationclearance-positionbyid', 'Bplo\PdoBploEndosementController@positionbyid');
Route::post('locationclearance/getordata', 'Bplo\PdoBploEndosementController@getordata');
Route::get('locationclearance/printreport', 'Bplo\PdoBploEndosementController@printreport');
Route::get('locationclearance/store', 'Bplo\PdoBploEndosementController@store');
Route::resource('locationclearance', 'Bplo\PdoBploEndosementController')->middleware(['auth','revalidate']);
Route::post('locationclearance/formValidation', 'Bplo\PdoBploEndosementController@formValidation')->name('locationclearance.post');

							/* welfare_swa_application-type */
/* Route::get('setup-data-application-type', 'SocialWelfare\ApplicationTypeController@index')->name('setup-data-application-type.index');
Route::get('setup-data-application-type/getList', 'SocialWelfare\ApplicationTypeController@getList')->name('setup-data-application-type.getList');
Route::post('setup-data-application-type/ActiveInactive', 'SocialWelfare\ApplicationTypeController@ActiveInactive');
Route::get('setup-data-application-type/store', 'SocialWelfare\ApplicationTypeController@store');
Route::resource('setup-data-application-type', 'SocialWelfare\ApplicationTypeController')->middleware(['auth','revalidate']);
Route::post('setup-data-application-type/formValidation', 'SocialWelfare\ApplicationTypeController@formValidation')->name('setup-data-application-type.post'); */

Route::get('setup-data-assistance-type', 'SocialWelfare\AssistanceTypeController@index')->name('setup-data-assistance-type.index');
Route::get('setup-data-assistance-type/getList', 'SocialWelfare\AssistanceTypeController@getList')->name('setup-data-assistance-type.getList');
Route::post('setup-data-assistance-type/ActiveInactive', 'SocialWelfare\AssistanceTypeController@ActiveInactive');
Route::get('setup-data-assistance-type/store', 'SocialWelfare\AssistanceTypeController@store');
Route::resource('setup-data-assistance-type', 'SocialWelfare\AssistanceTypeController')->middleware(['auth','revalidate']);
Route::post('setup-data-assistance-type/formValidation', 'SocialWelfare\AssistanceTypeController@formValidation')->name('setup-data-assistance-type.post');

Route::get('social-welfare/requirements', 'SocialWelfare\RequirementsController@index')->name('socialwelfare.index');
Route::get('social-welfare/requirements/getList', 'SocialWelfare\RequirementsController@getList')->name('socialwelfare.getList');
Route::post('social-welfare/requirements/ActiveInactive', 'SocialWelfare\RequirementsController@ActiveInactive');
Route::get('social-welfare/requirements/store', 'SocialWelfare\RequirementsController@store');
Route::resource('social-welfare/requirements', 'SocialWelfare\RequirementsController')->middleware(['auth','revalidate']);
Route::post('social-welfare/requirements/formValidation', 'SocialWelfare\RequirementsController@formValidation')->name('socialwelfare.post');
//--------------------------------Cpdo Application-------- 
Route::get('cpdoapplication', 'Cpdo\CpdoApplicationFormController@index')->name('cpdoapplication.index');
Route::get('online-cpdoapplication', 'Cpdo\CpdoApplicationFormController@onlineindex')->name('cpdoapplicationline.index');
Route::get('cpdoapplication/getList', 'Cpdo\CpdoApplicationFormController@getList')->name('cpdoapplication.getList');
Route::get('cpdoapplication/getListonline', 'Cpdo\CpdoApplicationFormController@getListonline')->name('cpdoapplicationonline.getList');
Route::post('cpdoapplication/ActiveInactive', 'Cpdo\CpdoApplicationFormController@ActiveInactive');
Route::post('getEngTaxpayersAutoSearchList', 'Cpdo\CpdoApplicationFormController@getEngTaxpayersAutoSearchList');
Route::post('cpdoapplication/deleteAttachment', 'Cpdo\CpdoApplicationFormController@deleteAttachment');
Route::post('cpdoapplication/ApproveCertificate', 'Cpdo\CpdoApplicationFormController@ApproveCertificate');
Route::post('cpdoapplication/getRequirements', 'Cpdo\CpdoApplicationFormController@getRequirements'); 
Route::post('cpdoapplication/uploadDocument','Cpdo\CpdoApplicationFormController@uploadDocument');
Route::post('cpdoapplication/savegeolocations','Cpdo\CpdoApplicationFormController@savegeolocations');
Route::post('cpdoapplication/insepectiondeleteAttachment','Cpdo\CpdoApplicationFormController@insepectiondeleteAttachment'); 
Route::get('cpdoapplication/store', 'Cpdo\CpdoApplicationFormController@store');
Route::get('cpdoapplication/getClientsDetails', 'Cpdo\CpdoApplicationFormController@getProfileDetails');
Route::get('cpdoapplication/printapplication', 'Cpdo\CpdoApplicationFormController@printapplication');
Route::get('cpdoapplication/printcertificate', 'Cpdo\CpdoApplicationFormController@printcertificate')->name('cpdoapplication.printcertificate'); 
Route::any('cpdoapplication/inspectionreport', 'Cpdo\CpdoApplicationFormController@inspectionreport');
Route::post('cpdoapplication/getBarngayList', 'Cpdo\CpdoApplicationFormController@getBarngayList');
Route::post('cpdoapplication/storeCpdobillSummary', 'Cpdo\CpdoApplicationFormController@storeCpdobillSummary');
Route::post('cpdoapplication/getEmployeeListAjax', 'Cpdo\CpdoApplicationFormController@getEmployeeListAjax');
Route::any('cpdoapplication/certification', 'Cpdo\CpdoApplicationFormController@certification');
Route::resource('cpdoapplication', 'Cpdo\CpdoApplicationFormController')->middleware(['auth','revalidate']);
Route::post('cpdoapplication/viewrequiremets', 'Cpdo\CpdoApplicationFormController@viewrequiremets')->name('cpdoapplication.viewrequiremets');
Route::post('cpdoapplication/saveorderofpayment', 'Cpdo\CpdoApplicationFormController@saveorderofpayment')->name('cpdoapplication.saveorderofpayment');
Route::post('cpdoapplication/ApproveInspection', 'Cpdo\CpdoApplicationFormController@ApproveInspection')->name('cpdoapplication.ApproveInspection');
Route::post('cpdoapplication/positionbyid', 'Cpdo\CpdoApplicationFormController@positionbyid')->name('cpdoapplication.positionbyid'); 
Route::post('cpdoapplication/printorderofpayment', 'Cpdo\CpdoApplicationFormController@printorderofpayment')->name('cpdoapplication.printorderofpayment'); 
Route::any('cpdoapplicationprintinspection', 'Cpdo\CpdoApplicationFormController@printinspection');
Route::post('cpdoapplication/formValidation', 'Cpdo\CpdoApplicationFormController@formValidation')->name('cpdoapplication.post');
Route::post('cpdoapplication/inspectionreport/formValidation', 'Cpdo\CpdoApplicationFormController@formValidationinspect')->name('cpdoapplication.post');
Route::post('cpdoapplication/certification/formValidation', 'Cpdo\CpdoApplicationFormController@formValidationcerti')->name('cpdoapplication.formValidationcerti');

Route::post('cpdoapplication/getServicetype', 'Cpdo\CpdoApplicationFormController@getServicetype')->name('cpdoapplication.getServicetype');


Route::get('social-welfare/status-type', 'SocialWelfare\StatusTypeController@index');
Route::get('social-welfare/status-type/getList', 'SocialWelfare\StatusTypeController@getList');
Route::post('social-welfare/status-type/ActiveInactive', 'SocialWelfare\StatusTypeController@ActiveInactive');
Route::get('social-welfare/status-type/store', 'SocialWelfare\StatusTypeController@store');
Route::resource('social-welfare/status-type', 'SocialWelfare\StatusTypeController')->middleware(['auth','revalidate']);
Route::post('social-welfare/status-type/formValidation', 'SocialWelfare\StatusTypeController@formValidation');

Route::get('setup-data-type-of-disability', 'SocialWelfare\TypeDisabilityController@index')->name('setup-data-type-of-disability.index');
Route::get('setup-data-type-of-disability/getList', 'SocialWelfare\TypeDisabilityController@getList')->name('setup-data-type-of-disability.getList');
Route::post('setup-data-type-of-disability/ActiveInactive', 'SocialWelfare\TypeDisabilityController@ActiveInactive');
Route::get('setup-data-type-of-disability/store', 'SocialWelfare\TypeDisabilityController@store');
Route::resource('setup-data-type-of-disability', 'SocialWelfare\TypeDisabilityController')->middleware(['auth','revalidate']);
Route::post('setup-data-type-of-disability/formValidation', 'SocialWelfare\TypeDisabilityController@formValidation')->name('setup-data-type-of-disability.post');



Route::get('setup-data-employment-type', 'SocialWelfare\EmploymentTypeController@index')->name('setup-data-employment-type.index');
Route::get('setup-data-employment-type/getList', 'SocialWelfare\EmploymentTypeController@getList')->name('setup-data-employment-type.getList');
Route::post('setup-data-employment-type/ActiveInactive', 'SocialWelfare\EmploymentTypeController@ActiveInactive');
Route::get('setup-data-employment-type/store', 'SocialWelfare\EmploymentTypeController@store');
Route::resource('setup-data-employment-type', 'SocialWelfare\EmploymentTypeController')->middleware(['auth','revalidate']);
Route::post('setup-data-employment-type/formValidation', 'SocialWelfare\EmploymentTypeController@formValidation')->name('setup-data-employment-type.post');


Route::get('setup-data-employment-status', 'SocialWelfare\StatusEmploymentController@index')->name('setup-data-employment-status.index');
Route::get('setup-data-employment-status/getList', 'SocialWelfare\StatusEmploymentController@getList')->name('setup-data-employment-status.getList');
Route::post('setup-data-employment-status/ActiveInactive', 'SocialWelfare\StatusEmploymentController@ActiveInactive');
Route::get('setup-data-employment-status/store', 'SocialWelfare\StatusEmploymentController@store');
Route::resource('setup-data-employment-status', 'SocialWelfare\StatusEmploymentController')->middleware(['auth','revalidate']);
Route::post('setup-data-employment-status/formValidation', 'SocialWelfare\StatusEmploymentController@formValidation')->name('setup-data-employment-status.post');

Route::get('setup-data-type-of-occupation', 'SocialWelfare\TypeOccupationController@index')->name('setup-data-type-of-occupation.index');
Route::get('setup-data-type-of-occupation/getList', 'SocialWelfare\TypeOccupationController@getList')->name('setup-data-type-of-occupation.getList');
Route::post('setup-data-type-of-occupation/ActiveInactive', 'SocialWelfare\TypeOccupationController@ActiveInactive');
Route::get('setup-data-type-of-occupation/store', 'SocialWelfare\TypeOccupationController@store');
Route::resource('setup-data-type-of-occupation', 'SocialWelfare\TypeOccupationController')->middleware(['auth','revalidate']);
Route::post('setup-data-type-of-occupation/formValidation', 'SocialWelfare\TypeOccupationController@formValidation')->name('setup-data-type-of-occupation.post');

Route::get('setup-data-employment-category', 'SocialWelfare\EmploymentCategoryController@index')->name('setup-data-employment-category.index');
Route::get('setup-data-employment-category/getList', 'SocialWelfare\EmploymentCategoryController@getList')->name('setup-data-employment-category.getList');
Route::post('setup-data-employment-category/ActiveInactive', 'SocialWelfare\EmploymentCategoryController@ActiveInactive');
Route::get('setup-data-employment-category/store', 'SocialWelfare\EmploymentCategoryController@store');
Route::resource('setup-data-employment-category', 'SocialWelfare\EmploymentCategoryController')->middleware(['auth','revalidate']);
Route::post('setup-data-employment-category/formValidation', 'SocialWelfare\EmploymentCategoryController@formValidation')->name('setup-data-employment-category.post');

Route::get('setup-data-education-attainment', 'SocialWelfare\EducationAttainmentController@index')->name('setup-data-education-attainment.index');
Route::get('setup-data-education-attainment/getList', 'SocialWelfare\EducationAttainmentController@getList')->name('setup-data-education-attainment.getList');
Route::post('setup-data-education-attainment/ActiveInactive', 'SocialWelfare\EducationAttainmentController@ActiveInactive');
Route::get('setup-data-education-attainment/store', 'SocialWelfare\EducationAttainmentController@store');
Route::resource('setup-data-education-attainment', 'SocialWelfare\EducationAttainmentController')->middleware(['auth','revalidate']);
Route::post('setup-data-education-attainment/formValidation', 'SocialWelfare\EducationAttainmentController@formValidation')->name('setup-data-education-attainment.post');

Route::get('setup-data-type-of-residency', 'SocialWelfare\TypeResidencyController@index')->name('setup-data-type-of-residency.index');
Route::get('setup-data-type-of-residency/getList', 'SocialWelfare\TypeResidencyController@getList')->name('setup-data-type-of-residency.getList');
Route::post('setup-data-type-of-residency/ActiveInactive', 'SocialWelfare\TypeResidencyController@ActiveInactive');
Route::get('setup-data-type-of-residency/store', 'SocialWelfare\TypeResidencyController@store');
Route::resource('setup-data-type-of-residency', 'SocialWelfare\TypeResidencyController')->middleware(['auth','revalidate']);
Route::post('setup-data-type-of-residency/formValidation', 'SocialWelfare\TypeResidencyController@formValidation')->name('setup-data-type-of-residency.post');

Route::get('engineering/consultantexternal', 'Engneering\EngConsultantExternalContt@index')->name('consultantexternal.index');
Route::get('engineering/consultantexternal/store', 'Engneering\EngConsultantExternalContt@store');
Route::get('engineering/consultantexternal/getList', 'Engneering\EngConsultantExternalContt@getList')->name('consultantexternal.getList');
Route::post('engineering/consultantexternal/ActiveInactive', 'Engneering\EngConsultantExternalContt@ActiveInactive');
Route::resource('engineering/consultantexternal/', 'Engneering\EngConsultantExternalContt')->middleware(['auth','revalidate']);
Route::post('engineering/consultantexternal/formValidation', 'Engneering\EngConsultantExternalContt@formValidation')->name('consultantexternal.post');

Route::get('eng/consultantexternal', 'Engneering\EngsConsultantExternalContt@index')->name('consultantexternals.index');
Route::get('eng/consultantexternal/store', 'Engneering\EngsConsultantExternalContt@store');
Route::get('eng/consultantexternal/getList', 'Engneering\EngsConsultantExternalContt@getList')->name('consultantexternals.getList');
Route::post('eng/consultantexternal/ActiveInactive', 'Engneering\EngsConsultantExternalContt@ActiveInactive');
Route::resource('eng/consultantexternal/', 'Engneering\EngsConsultantExternalContt')->middleware(['auth','revalidate']);
Route::post('eng/consultantexternal/formValidation', 'Engneering\EngsConsultantExternalContt@formValidation')->name('consultantexternals.post');

Route::get('engineering/engequipmenttype', 'Engneering\EngEquipmentSystemTypeController@index')->name('engequipmenttype.index');
Route::get('engineering/engequipmenttype/store', 'Engneering\EngEquipmentSystemTypeController@store');
Route::get('engineering/engequipmenttype/getList', 'Engneering\EngEquipmentSystemTypeController@getList')->name('engequipmenttype.getList');
Route::post('engineering/engequipmenttype/ActiveInactive', 'Engneering\EngEquipmentSystemTypeController@ActiveInactive');
Route::resource('engineering/engequipmenttype/', 'Engneering\EngEquipmentSystemTypeController')->middleware(['auth','revalidate']);
Route::post('engineering/engequipmenttype/formValidation', 'Engneering\EngEquipmentSystemTypeController@formValidation')->name('engequipmenttype.post');

//----eng staff routes----//
Route::get('engineeringstaff/index', 'Engneering\EngEngineeringstaffController@index')->name('engineeringstaffs.index');
Route::get('engineeringstaff/getList', 'Engneering\EngEngineeringstaffController@getList')->name('engineeringstaffs.getList');
Route::get('engineeringstaff/store', 'Engneering\EngEngineeringstaffController@store');
Route::post('engineeringstaff/delete', 'Engneering\EngEngineeringstaffController@Delete');
Route::post('engineeringstaff/ActiveInactive', 'Engneering\EngEngineeringstaffController@ActiveInactive');
Route::resource('engineeringstaff', 'Engneering\EngEngineeringstaffController')->middleware(['auth','revalidate']);
Route::post('engineeringstaff/formValidation', 'Engneering\EngEngineeringstaffController@formValidation')->name('engineeringstaffs.post');

//----end eng staff------//


Route::middleware(['auth'])->prefix('cashier')->group(function () {
    /* Designation Routes */
    Route::prefix('community-tax')->group(function () {
        Route::get('', 'CommunityTaxController@index')->name('community-tax.index');
        Route::get('printReceipt', 'CommunityTaxController@printCommunitytax');

    });
    // Business Permit Cashier
    Route::prefix('cashier-business-permit')->group(function () {
        Route::get('', 'Bplo\CashierBusinessPermitController@index')->name('bplocashier.index');
        Route::get('getList', 'Bplo\CashierBusinessPermitController@getList')->name('bplocashier.list');
        Route::get('store', 'Bplo\CashierBusinessPermitController@store');
        Route::post('store', 'Bplo\CashierBusinessPermitController@store');
        Route::post('cancelOr', 'Bplo\CashierBusinessPermitController@cancelOr');
        Route::post('getPaymentDetails', 'Bplo\CashierBusinessPermitController@getPaymentDetails');
        Route::post('getOrnumber', 'Bplo\CashierBusinessPermitController@getOrnumber');
        Route::post('creditAmountApply', 'Bplo\CashierBusinessPermitController@creditAmountApply');
        Route::post('checkOrUsedOrNot', 'Bplo\CashierBusinessPermitController@checkOrUsedOrNot');
        Route::get('printReceipt', 'Bplo\CashierBusinessPermitController@printReceipt');
        Route::any('updateCashierBillHistoryTaxpayers', 'Bplo\CashierBusinessPermitController@updateCashierBillHistoryTaxpayers');
        
    });


    Route::prefix('Miscellaneous')->group(function (){
        Route::get('', 'SocialWelfare\MiscellaneousCashieringController@index')->name('MiscellaneousCashiering.index');
        Route::get('getList', 'SocialWelfare\MiscellaneousCashieringController@getList')->name('MiscellaneousCashiering.list');
        Route::get('store', 'SocialWelfare\MiscellaneousCashieringController@store');
        Route::post('store', 'SocialWelfare\MiscellaneousCashieringController@store');
        Route::post('getOrnumber', 'SocialWelfare\MiscellaneousCashieringController@getOrnumber');
        Route::post('getOptionDetails', 'SocialWelfare\MiscellaneousCashieringController@getOptionDetails');
        Route::post('cancelOr', 'SocialWelfare\MiscellaneousCashieringController@cancelOr');
        Route::post('cancelNaturePaymentOption', 'SocialWelfare\MiscellaneousCashieringController@cancelNaturePaymentOption');
        Route::post('getUserList', 'SocialWelfare\MiscellaneousCashieringController@getUserList');
        Route::post('getTfocDropdown', 'SocialWelfare\MiscellaneousCashieringController@getTfocDropdown');
        Route::post('getAmountDetails', 'SocialWelfare\MiscellaneousCashieringController@getAmountDetails');
        Route::post('getUserDetails', 'SocialWelfare\MiscellaneousCashieringController@getUserDetails');
        Route::post('checkOrUsedOrNot', 'SocialWelfare\MiscellaneousCashieringController@checkOrUsedOrNot');
        Route::post('getUserbytoid', 'SocialWelfare\MiscellaneousCashieringController@getUserbytoid');
        Route::post('getallFees', 'SocialWelfare\MiscellaneousCashieringController@getallFees');
        Route::get('printReceipt', 'SocialWelfare\MiscellaneousCashieringController@printReceipt');
        Route::post('uploadDocument', 'SocialWelfare\MiscellaneousCashieringController@uploadDocument');
        Route::post('deleteAttachment', 'SocialWelfare\MiscellaneousCashieringController@deleteAttachment');
        Route::post('getTopNumbersAjax', 'SocialWelfare\MiscellaneousCashieringController@getTopNumbersAjax');
    });

    Route::prefix('burial-permit')->group(function (){
        Route::get('', 'BurialPermitCasheringController@index')->name('burialcashering.index');
        Route::get('getList', 'BurialPermitCasheringController@getList')->name('burialcashering.list');
        Route::get('store', 'BurialPermitCasheringController@store');
        Route::post('store', 'BurialPermitCasheringController@store');
        Route::post('getOrnumber', 'BurialPermitCasheringController@getOrnumber');
        Route::post('getOptionDetails', 'BurialPermitCasheringController@getOptionDetails');
        Route::post('cancelOr', 'BurialPermitCasheringController@cancelOr');
        Route::post('cancelNaturePaymentOption', 'BurialPermitCasheringController@cancelNaturePaymentOption');
        Route::post('getUserList', 'BurialPermitCasheringController@getUserList');
        Route::post('getTfocDropdown', 'BurialPermitCasheringController@getTfocDropdown');
        Route::post('getAmountDetails', 'BurialPermitCasheringController@getAmountDetails');
        Route::post('getUserDetails', 'BurialPermitCasheringController@getUserDetails');
        Route::post('checkOrUsedOrNot', 'BurialPermitCasheringController@checkOrUsedOrNot');
        Route::get('printReceipt', 'BurialPermitCasheringController@printReceipt');
        Route::post('getDeathcauses', 'BurialPermitCasheringController@getDeathcauses');
    });

     Route::prefix('HealthandSafety')->group(function (){
        Route::get('', 'HealthSafety\HealthandSafetyCasheringController@index')->name('HealthAndSafetyCashiering.index');
        Route::get('getList', 'HealthSafety\HealthandSafetyCasheringController@getList')->name('HealthAndSafetyCashiering.list');
        Route::get('store', 'HealthSafety\HealthandSafetyCasheringController@store');
        Route::post('store', 'HealthSafety\HealthandSafetyCasheringController@store');
        Route::post('getOrnumber', 'HealthSafety\HealthandSafetyCasheringController@getOrnumber');
        Route::post('getOptionDetails', 'HealthSafety\HealthandSafetyCasheringController@getOptionDetails');
        Route::post('getRefreshCitizen', 'HealthSafety\HealthandSafetyCasheringController@getRefreshCitizen');
        Route::post('cancelOr', 'HealthSafety\HealthandSafetyCasheringController@cancelOr');
        Route::post('cancelNaturePaymentOption', 'HealthSafety\HealthandSafetyCasheringController@cancelNaturePaymentOption');
        Route::post('getUserList', 'HealthSafety\HealthandSafetyCasheringController@getUserList');
        Route::post('getAmountDetails', 'HealthSafety\HealthandSafetyCasheringController@getAmountDetails');
        Route::post('getUserDetails', 'HealthSafety\HealthandSafetyCasheringController@getUserDetails');
        Route::post('getUserbytoid', 'HealthSafety\HealthandSafetyCasheringController@getUserbytoid');
        Route::post('getallFees', 'HealthSafety\HealthandSafetyCasheringController@getallFees');
        Route::post('checkOrUsedOrNot', 'HealthSafety\HealthandSafetyCasheringController@checkOrUsedOrNot');
        Route::get('printReceipt', 'HealthSafety\HealthandSafetyCasheringController@printReceipt');
        Route::post('deleteAttachment', 'HealthSafety\HealthandSafetyCasheringController@deleteAttachment');
    });

});

/*------- Community tax certificate routes---*/
Route::get('community-tax/getList', 'CommunityTaxController@getList')->name('community-tax.getList');
Route::post('community-tax/ActiveInactive', 'CommunityTaxController@ActiveInactive');
Route::post('community-tax/getOrnumber', 'CommunityTaxController@getOrnumber');
Route::post('community-tax/cancelorpayment', 'CommunityTaxController@cancelOrPayment');
Route::get('community-tax/store', 'CommunityTaxController@store');
Route::resource('community-tax', 'CommunityTaxController')->middleware(['auth','revalidate']);
Route::post('community-tax/getfeeamount', 'CommunityTaxController@getfeeamount');
Route::post('community-tax/getClientsDetails', 'CommunityTaxController@getProfileDetails');
Route::post('community-tax/getClientsbussiness', 'CommunityTaxController@getClientsbussiness');  
Route::post('community-tax/getClientsDropdown', 'CommunityTaxController@getClientsDropdown');
Route::post('community-tax/gettaxpayerssearch', 'CommunityTaxController@gettaxpayerssearch');
Route::post('community-tax/formValidation', 'CommunityTaxController@formValidation')->name('community-tax.post');



/*------- CPDO Cashering routes---*/
Route::get('cpdocashering', 'Cpdo\CpdoCasheringController@getList')->name('cpdocashering.index');
Route::get('cpdocashering/getList', 'Cpdo\CpdoCasheringController@getList')->name('cpdocashering.getList');
Route::post('cpdocashering/ActiveInactive', 'Cpdo\CpdoCasheringController@ActiveInactive');
Route::post('cpdocashering/getOrnumber', 'Cpdo\CpdoCasheringController@getOrnumber');
Route::post('cpdocashering/cancelorpayment', 'Cpdo\CpdoCasheringController@cancelOrPayment');
Route::post('cpdocashering/getamountinword', 'Cpdo\CpdoCasheringController@getamountinword');
Route::get('cpdocashering/updatecashierfullname', 'Cpdo\CpdoCasheringController@updatecashierfullname');
Route::post('cashering/checkOrInrange', 'CommonController@checkOrInrange');
Route::get('cpdocashering/store', 'Cpdo\CpdoCasheringController@store');
Route::get('cpdocashering/printReceipt', 'Cpdo\CpdoCasheringController@printReceipt');
Route::resource('cpdocashering', 'Cpdo\CpdoCasheringController')->middleware(['auth','revalidate']);
Route::post('cpdocashering/getfeeamount', 'Cpdo\CpdoCasheringController@getfeeamount');
Route::post('cpdocashering/getpenaltyfee', 'Cpdo\CpdoCasheringController@getpenaltyfee');
Route::post('cpdocashering/getTransactionid', 'Cpdo\CpdoCasheringController@getTransactionid');
Route::post('cpdocashering/getTransactionbytype', 'Cpdo\CpdoCasheringController@getTransactionbytype');
Route::post('cpdocashering/getClientsbussiness', 'Cpdo\CpdoCasheringController@getClientsbussiness');  
Route::post('cpdocashering/getClientsDropdown', 'Cpdo\CpdoCasheringController@getClientsDropdown');
Route::post('cpdocashering/formValidation', 'Cpdo\CpdoCasheringController@formValidation')->name('cpdocashering.post');

Route::get('Health-and-safety/registration', 'HealthRegistrationController@index')->name('healsaftregistration.index');
Route::get('Health-and-safety/registration/store', 'HealthRegistrationController@store');
Route::post('getRefreshHelSaf', 'HealthRegistrationController@getRefreshHelSaf');
Route::get('Health-and-safety/registration/getList', 'HealthRegistrationController@getList')->name('healsaftregistration.getList');
Route::post('Health-and-safety/registration/ActiveInactive', 'HealthRegistrationController@ActiveInactive');
Route::resource('Health-and-safety/registration/', 'HealthRegistrationController')->middleware(['auth','revalidate']);
Route::post('Health-and-safety/registration/formValidation', 'HealthRegistrationController@formValidation')->name('healsaftregistration.post');

/*----Occupancy Service ------*/
Route::get('occupancy-services', 'Engneering\OccupancyServicesController@index')->name('occupancy-services.index');
Route::get('occupancy-services/getList', 'Engneering\OccupancyServicesController@getList')->name('occupancy-services.getList');
Route::post('occupancy-services/ActiveInactive', 'Engneering\OccupancyServicesController@ActiveInactive');
Route::get('occupancy-services/store', 'Engneering\OccupancyServicesController@store');
Route::resource('occupancy-services', 'Engneering\OccupancyServicesController')->middleware(['auth','revalidate']);
Route::post('occupancy-services/viewrequiremets', 'Engneering\OccupancyServicesController@viewrequiremets')->name('occupancy-services.viewrequiremets');
Route::post('occupancy-services/getserviceName', 'Engneering\OccupancyServicesController@getServicefeename')->name('occupancy-services.servicename');
Route::post('occupancy-services/formValidation', 'Engneering\OccupancyServicesController@formValidation')->name('occupancy-services.post');

Route::get('bplo-reassessment-payment-mode', 'Bplo\ReassessmentPaymentController@index')->name('bplo-reassessment-payment-mode.index');
Route::post('bplo-reassessment-payment-mode/store', 'Bplo\ReassessmentPaymentController@store');

Route::get('health-safety-setup-data-service', 'HealthSafetySetupDataServiceController@index')->name('health-safety-setup-data-service.index');
Route::any('health-safety-setup-data-service/store', 'HealthSafetySetupDataServiceController@store');
Route::post('health-safety-setup-data-service/getserviceName', 'HealthSafetySetupDataServiceController@getServicefeename')->name('health-safety-setup-data-service.servicename');
Route::get('health-safety-setup-data-service/getList', 'HealthSafetySetupDataServiceController@getList')->name('health-safety-setup-data-service.getList');
Route::post('health-safety-setup-data-service/ActiveInactive', 'HealthSafetySetupDataServiceController@ActiveInactive');
Route::resource('health-safety-setup-data-service/', 'HealthSafetySetupDataServiceController')->middleware(['auth','revalidate']);
Route::post('health-safety-setup-data-service/formValidation', 'HealthSafetySetupDataServiceController@formValidation')->name('health-safety-setup-data-service.post');

Route::prefix('fire-safety-requirements')->group(function () {
    Route::get('', 'BfpRequirementController@index')->name('bfprequirement.index');
});

// Route::get('', 'BfpRequirementController@index')->name('bfprequirement.index');
Route::get('bfprequirement/getList', 'BfpRequirementController@getList')->name('bfprequirement.getList');
Route::get('bfprequirement/store', 'BfpRequirementController@store');
Route::post('bfprequirement/delete', 'BfpRequirementController@Delete');
Route::get('getEmployeeDetails', 'BfpRequirementController@getEmployeeDetails');
Route::post('bfprequirement/ActiveInactive', 'BfpRequirementController@appraisersActiveInactive');
Route::resource('bfprequirement', 'BfpRequirementController')->middleware(['auth','revalidate']);
Route::post('bfprequirement/formValidation', 'BfpRequirementController@formValidation')->name('bfprequirement.post');

/*------------------------------------- fire-safety-requirements ------------------------------*/

/* hematology */
Route::post('hematology-uploadDocument', 'HematologyController@uploadDocument')->name('hematology.uploadDocument');
Route::post('hematology-deleteAttachment', 'HematologyController@deleteAttachment')->name('hematology.deleteAttachment');
Route::get('hematology/designation/{employee_id}','HematologyController@getDesignation')->name('hematology/designation');
Route::get('hematology','HematologyController@index')->name('hematology.index');
Route::get('hematology/store','HematologyController@store')->name('hematology.store');
Route::post('hematology/getCitizensName', 'HematologyController@getCitizensname');
Route::post('hematology/getrangelists', 'HematologyController@getRangeListbase');
Route::get('hematology/getList','HematologyController@getList')->name('hematology.getList');
Route::post('hematology/ActiveInactive','HematologyController@ActiveInactive');
Route::resource('hematology/','HematologyController')->middleware(['auth','revalidate']);
Route::post('hematology/formValidation','HematologyController@formValidation')->name('hematology.post');
Route::get('hematology/print/{id}', 'LaboratoryReqController@hemaPrint')->name('hematology.print');
Route::get('hematology/submit/{id}', 'HematologyController@submit')->name('hematology.submit');

// TANGA DITO


/* serology */
Route::post('serology-uploadDocument', 'SerologyController@uploadDocument')->name('serology.uploadDocument');
Route::post('serology-deleteAttachment', 'SerologyController@deleteAttachment')->name('serology.deleteAttachment');
Route::get('serology/designation/{employee_id}','SerologyController@getDesignation')->name('serology/designation');
Route::post('serology-update','SerologyController@update')->name('serology.update');
Route::get('serology','SerologyController@index')->name('serology.index');
Route::get('serology/store','SerologyController@store')->name('serology.store');
Route::post('serology/getCitizensName', 'SerologyController@getCitizensname');
Route::get('serology/getList','SerologyController@getList')->name('serology.getList');
Route::post('serology/ActiveInactive','SerologyController@ActiveInactive');
Route::resource('serology/','SerologyController')->middleware(['auth','revalidate']);
Route::post('serology/formValidation','SerologyController@formValidation')->name('serology.post');
Route::get('serology/submit/{id}', 'SerologyController@submit')->name('serology.submit');

/* urinalysis */
Route::post('urinalysis-uploadDocument', 'UrinalysisController@uploadDocument')->name('urinalysis.uploadDocument');
Route::post('urinalysis-deleteAttachment', 'UrinalysisController@deleteAttachment')->name('urinalysis.deleteAttachment');
Route::get('urinalysis/designation/{employee_id}','UrinalysisController@getDesignation')->name('urinalysis/designation');
Route::get('urinalysis','UrinalysisController@index')->name('urinalysis.index');
Route::get('urinalysis/store','UrinalysisController@store')->name('urinalysis.store');
Route::post('urinalysis/getCitizensName', 'UrinalysisController@getCitizensname');
Route::get('urinalysis/getList','UrinalysisController@getList')->name('urinalysis.getList');
Route::post('urinalysis/ActiveInactive','UrinalysisController@ActiveInactive');
Route::resource('urinalysis/','UrinalysisController')->middleware(['auth','revalidate']);
Route::post('urinalysis/formValidation','UrinalysisController@formValidation')->name('urinalysis.post');
Route::get('urinalysis/submit/{id}', 'UrinalysisController@submit')->name('urinalysis.submit');

/* fecalysis */
Route::post('fecalysis-uploadDocument', 'FecalysisController@uploadDocument')->name('fecalysis.uploadDocument');
Route::post('fecalysis-deleteAttachment', 'FecalysisController@deleteAttachment')->name('fecalysis.deleteAttachment');
Route::get('fecalysis/designation/{employee_id}','FecalysisController@getDesignation')->name('fecalysis/designation');
Route::get('fecalysis','FecalysisController@index')->name('fecalysis.index');
Route::get('fecalysis/store','FecalysisController@store')->name('fecalysis.store');
Route::get('fecalysis/getList','FecalysisController@getList')->name('fecalysis.getList');
Route::post('fecalysis/getCitizensName', 'FecalysisController@getCitizensname');
Route::post('fecalysis/ActiveInactive','FecalysisController@ActiveInactive');
Route::resource('fecalysis/','FecalysisController')->middleware(['auth','revalidate']);
Route::post('fecalysis/formValidation','FecalysisController@formValidation')->name('fecalysis.post');
Route::get('fecalysis/submit/{id}', 'FecalysisController@submit')->name('fecalysis.submit');

/* pregnancy-test */
Route::post('pregnancy-test-uploadDocument', 'PregnancyTestController@uploadDocument')->name('pregnancy-test.uploadDocument');
Route::post('pregnancy-test-deleteAttachment', 'PregnancyTestController@deleteAttachment')->name('pregnancy-test.deleteAttachment');
Route::get('pregnancy-test/designation/{employee_id}','PregnancyTestController@getDesignation')->name('pregnancy-test/designation');
Route::get('pregnancy-test','PregnancyTestController@index')->name('pregnancy-test.index');
Route::get('pregnancy-test/store','PregnancyTestController@store')->name('pregnancy-test.store');
// Route::post('pregnancy-test/getCitizensName', 'PregnancyTestController@getCitizensname');
Route::get('pregnancy-test/getList','PregnancyTestController@getList')->name('pregnancy-test.getList');
Route::get('pregnancy-test/print/{id}', 'LaboratoryReqController@pregnancyPrint')->name('pregnancy-test.print');
Route::get('pregnancy-test/submit/{id}', 'PregnancyTestController@submit')->name('pregnancy-test.submit');
Route::post('pregnancy-test/ActiveInactive','PregnancyTestController@ActiveInactive');
Route::resource('pregnancy-test/','PregnancyTestController')->middleware(['auth','revalidate']);
Route::post('pregnancy-test/formValidation','PregnancyTestController@formValidation')->name('pregnancy-test.post');

// Blood Sugar Test
// Route::post('blood-sugar-test-uploadDocument', 'BloodSugarTestController@uploadDocument')->name('blood-sugar-test.uploadDocument');
// Route::post('blood-sugar-test-deleteAttachment', 'BloodSugarTestController@deleteAttachment')->name('blood-sugar-test.deleteAttachment');
Route::get('blood-sugar-test/designation/{employee_id}','BloodSugarTestController@getDesignation')->name('blood-sugar-test/designation');
Route::get('health-and-safety/laboratory/blood-sugar-test','BloodSugarTestController@index')->name('blood-sugar-test.index');
Route::get('blood-sugar-test/store','BloodSugarTestController@store')->name('blood-sugar-test.store');
Route::post('blood-sugar-test/getCitizensName', 'BloodSugarTestController@getCitizensname');
Route::get('blood-sugar-test/getList','BloodSugarTestController@getList')->name('blood-sugar-test.getList');
Route::get('blood-sugar-test/print/{id}', 'LaboratoryReqController@bloodSugarPrint')->name('blood-sugar-test.print');
Route::get('blood-sugar-test/submit/{id}', 'BloodSugarTestController@submit')->name('blood-sugar-test.submit');
Route::post('blood-sugar-test/ActiveInactive','BloodSugarTestController@ActiveInactive');
Route::resource('blood-sugar-test/','BloodSugarTestController')->middleware(['auth','revalidate']);
Route::post('blood-sugar-test/formValidation','BloodSugarTestController@formValidation')->name('blood-sugar-test.post');

// Gram Staining Test
// Route::post('blood-sugar-test-uploadDocument', 'BloodSugarTestController@uploadDocument')->name('blood-sugar-test.uploadDocument');
// Route::post('blood-sugar-test-deleteAttachment', 'BloodSugarTestController@deleteAttachment')->name('blood-sugar-test.deleteAttachment');
// Route::get('blood-sugar-test/designation/{employee_id}','BloodSugarTestController@getDesignation')->name('blood-sugar-test/designation');
Route::get('health-and-safety/laboratory/gram-staining-test','GramStainingTestController@index')->name('gram-staining-test.index');
Route::get('gram-staining-test/store','GramStainingTestController@store')->name('gram-staining-test.store');
Route::post('gram-staining-test/getCitizensName', 'GramStainingTestController@getCitizensname');
Route::get('gram-staining-test/getList','GramStainingTestController@getList')->name('gram-staining-test.getList');
Route::get('gram-staining-test/print/{id}', 'LaboratoryReqController@gramStainingPrint')->name('gram-staining-test.print');
Route::get('gram-staining-test/submit/{id}', 'GramStainingTestController@submit')->name('gram-staining-test.submit');
Route::post('gram-staining-test/ActiveInactive','GramStainingTestController@ActiveInactive');
Route::resource('gram-staining-test/','GramStainingTestController')->middleware(['auth','revalidate']);
Route::post('gram-staining-test/formValidation','GramStainingTestController@formValidation')->name('gram-staining-test.post');

/* health-safety-family-planning */
Route::post('health-safety-family-planning-uploadDocument', 'HeSaFamilyPlanning@uploadDocument')->name('health-safety-family-planning.uploadDocument');
Route::post('health-safety-family-planning-deleteAttachment', 'HeSaFamilyPlanning@deleteAttachment')->name('health-safety-family-planning.deleteAttachment');
Route::get('health-safety-family-planning','HeSaFamilyPlanning@index')->name('health-safety-family-planning.index');
Route::get('health-safety-family-planning/store','HeSaFamilyPlanning@store');
Route::post('health-safety-family-planning-getRefreshHelSaf', 'HeSaFamilyPlanning@getRefreshHelSaf');
Route::post('health-safety-family-planning/getCitizensName', 'HeSaFamilyPlanning@getCitizensname');
Route::get('health-safety-family-planning/getList','HeSaFamilyPlanning@getList')->name('health-safety-family-planning.getList');
Route::post('health-safety-family-planning/ActiveInactive','HeSaFamilyPlanning@ActiveInactive');
Route::resource('health-safety-family-planning/','HeSaFamilyPlanning')->middleware(['auth','revalidate']);
Route::post('health-safety-family-planning/formValidation','HeSaFamilyPlanning@formValidation')->name('health-safety-family-planning.post');

/* Inventory */
Route::get('Medicine-supplies-inventory','InventoryController@index')->name('Medicine-supplies-inventory');
Route::get('get-breakdowns','InventoryController@getBreakDowns')->name('get-breakdowns');
Route::get('Medicine-supplies-inventory/getList','InventoryController@InventoryGetList')->name('Medicine-supplies-inventory/getList');
Route::get('Medicine-supplies-inventory/store','InventoryController@store')->name('Medicine-supplies-inventory/store');
Route::get('get-item-details-by-control-number','InventoryController@getItemDetailsByControl')->name('get-item-details-by-control-number');
Route::post('Medicine-supplies-inventory','InventoryController@addInventory')->name('Medicine-supplies-inventory');
Route::post('Medicine-supplies-inventory/formValidation','InventoryController@InventoryformValidation')->name('Medicine-supplies-inventory/formValidation');
Route::get('get-item-details-external','InventoryController@getItemDetailsExternal')->name('get-item-details-external');
Route::get('get-single-item-details/{id}','InventoryController@getSingleItemDetails')->name('get-single-item-details');
Route::get('get-item-details-by-item-id','InventoryController@getItemDetailsByItemId')->name('get-item-details-by-item-id');
Route::get('Medicine-supplies-inventory/edit/{id}','InventoryController@editInventory')->name('Medicine-supplies-inventory/edit');
Route::post('Medicine-supplies-inventory/update/formValidation','InventoryController@InventoryformValidation')->name('Medicine-supplies-inventory/edit');
Route::post('Medicine-supplies-inventory/update','InventoryController@updateInventory')->name('Medicine-supplies-inventory/edit');
Route::get('get-all-suppliers-inventory','InventoryController@getAllSuppliersInventory')->name('get-all-suppliers-inventory');
Route::get('get-all-items-inventory','InventoryController@getAllItemsInventory')->name('get-all-items-inventory');
Route::post('Medicine-supplies-inventory/ActiveInactive','InventoryController@ActiveInactive')->name('Medicine-supplies-inventory/ActiveInactive');

/* Inventory Category */
Route::get('healthy-and-safety/setup-data/inventory-category','InventoryController@InventoryCategory')->name('healthy-and-safety/setup-data/inventory-category');
Route::get('inventory-category/getList','InventoryController@InventoryCategoryGetList')->name('inventory-category/getList');
Route::get('inventory-category/store','InventoryController@InventoryCategoryStore')->name('inventory-category/store');
Route::post('inventory-category/store/formValidation','InventoryController@formValidation')->name('inventory-category/store/formValidation');
Route::post('inventory-category/update/formValidation','InventoryController@formValidation')->name('inventory-category/store/formValidation');
Route::post('inventory-category/store','InventoryController@addInventoryCategory')->name('inventory-category/store');
Route::get('inventory-category/edit/{id}','InventoryController@InventoryCategoryEdit')->name('inventory-category/edit');
Route::post('inventory-category/update','InventoryController@InventoryCategoryUpdate')->name('inventory-category/update');
Route::get('inventory-category/delete','InventoryController@InventoryCategoryDelete')->name('inventory-category/delete');
Route::post('inventory-category/ActiveInactive','InventoryController@ActiveInactiveCategory')->name('inventory-category/ActiveInactive');

// Ho Issuance
Route::get('medicine-supplies-issuance','HoIssuanceController@index')->name('medicine-supplies-issuance');
Route::get('medicine-supplies-issuance/getList','HoIssuanceController@getList')->name('medicine-supplies-issuance/getList');
Route::get('medicine-supplies-issuance/getList/{cit_id}','HoIssuanceController@getListSpecific')->name('medicine-supplies-issuance/getListSpecific');
 // For Adding 
Route::get('medicine-supplies-issuance/add','HoIssuanceController@add')->name('medicine-supplies-issuance/add');
Route::post('medicine-supplies-issuance/add/formValidation','HoIssuanceController@formValidation')->name('medicine-supplies-issuance/add/formValidation');
Route::post('medicine-supplies-issuance/add','HoIssuanceController@addData')->name('medicine-supplies-issuance/add');
// For editting
Route::get('medicine-supplies-issuance/edit/{id}','HoIssuanceController@edit')->name('medicine-supplies-issuance/edit');
Route::post('medicine-supplies-issuance/update','HoIssuanceController@update')->name('medicine-supplies-issuance/add');
Route::post('medicine-supplies-issuance/update/formValidation','HoIssuanceController@formValidation')->name('medicine-supplies-issuance/update/formValidation');
Route::post('medicine-supplies-issuance/update','HoIssuanceController@updateData')->name('medicine-supplies-issuance/update');

Route::get('medicine-supplies-issuance/designation/{employee_id}','HoIssuanceController@getDesignation')->name('medicine-supplies-issuance/designation');
Route::get('medicine-supplies-issuance/citizeninfo/{citizen_id}','HoIssuanceController@getCitizenInfo')->name('medicine-supplies-issuance/barangay');
Route::get('medicine-supplies-issuance/employeeinfo/{employee_id}','HoIssuanceController@getEmployeeInfo')->name('medicine-supplies-issuance/barangay');
Route::get('medicine-supplies-issuance/get-items','HoIssuanceController@getItems')->name('medicine-supplies-issuance/citizeninfo');
Route::post('medicine-supplies-issuance/ActiveInactive','HoIssuanceController@ActiveInactive')->name('medicine-supplies-issuance/ActiveInactive');
Route::get('medicine-supplies-issuance/calculate-conversion','HoIssuanceController@calculateConversions')->name('medicine-supplies-issuance/calculate-conversion');


// Adjustments
Route::get('medicine-supplies-sdjustment','HoInventoryAdjustments@index')->name('medicine-supplies-sdjustment');
Route::get('medicine-supplies-sdjustment/getList','HoInventoryAdjustments@getList')->name('medicine-supplies-sdjustment/getList');
Route::get('medicine-supplies-sdjustment/get-items','HoInventoryAdjustments@getItems')->name('medicine-supplies-sdjustment/get-items');
Route::get('medicine-supplies-sdjustment/add','HoInventoryAdjustments@add')->name('medicine-supplies-sdjustment/add');
Route::post('medicine-supplies-sdjustment/add/formValidation','HoInventoryAdjustments@formValidation')->name('medicine-supplies-sdjustment/add');
Route::post('medicine-supplies-sdjustment/add','HoInventoryAdjustments@addData')->name('medicine-supplies-sdjustment/add');
Route::post('medicine-supplies-sdjustment/ActiveInactive','HoInventoryAdjustments@ActiveInactive')->name('medicine-supplies-sdjustment/ActiveInactive');
Route::get('medicine-supplies-sdjustment/edit/{id}','HoInventoryAdjustments@edit')->name('medicine-supplies-sdjustment/edit');
Route::post('medicine-supplies-sdjustment/update/formValidation','HoInventoryAdjustments@formValidation')->name('medicine-supplies-sdjustment/update');
Route::post('medicine-supplies-sdjustment/update','HoInventoryAdjustments@updateData')->name('medicine-supplies-sdjustment/update');

// Medical Certificate 
Route::post('medical-certificate-uploadDocument', 'MedicalCertificateController@uploadDocument')->name('medical-certificate.uploadDocument');
Route::post('medical-certificate-deleteAttachment', 'MedicalCertificateController@deleteAttachment')->name('medical-certificate.deleteAttachment');
Route::get('medical-certificate','MedicalCertificateController@index')->name('medical-certificate');
Route::get('medical-certificate/getList','MedicalCertificateController@getList')->name('medical-certificate/getList');
Route::get('medical-certificate/store','MedicalCertificateController@store')->name('add-medical-certificate/store');
Route::post('medical-certificate/store/formValidation','MedicalCertificateController@formValidation')->name('validation-add-medical-certificate/store');
Route::post('medical-certificate/store','MedicalCertificateController@store')->name('edit-medical-certificate/store');
Route::post('medical-certificate/ActiveInactive','MedicalCertificateController@ActiveInactive')->name('edit-medical-certificate/ActiveInactive');
Route::get('medical-certificate/get-or-no/{id}','MedicalCertificateController@getORNoDetails')->name('medical-certificate/get-or-no');
Route::post('medical-certificate/get-or-list/{id}','MedicalCertificateController@getOr')->name('medical-certificate/get-or-list');


// Imperial System
Route::get('imperial-system','ImperialSystemController@index')->name('imperial-system');
Route::get('imperial-system/getList','ImperialSystemController@getList')->name('imperial-system/getList');
Route::get('imperial-system/store','ImperialSystemController@store')->name('add-imperial-system/store');
Route::post('imperial-system/store/formValidation','ImperialSystemController@formValidation')->name('validation-add-imperial-system/store');
Route::post('imperial-system/store','ImperialSystemController@store')->name('edit-imperial-system/store');
Route::post('imperial-system/ActiveInactive','ImperialSystemController@ActiveInactive')->name('edit-imperial-system/ActiveInactive');

// Development Permit Computation
Route::get('development-permit-computation','DevelopmentPermit@index')->name('development-permit-computation');
Route::get('development-permit-computation/getList','DevelopmentPermit@getList')->name('development-permit-computation/getList');
Route::get('development-permit-computation/store','DevelopmentPermit@store')->name('add-development-permit-computation/store');
Route::post('development-permit-computation/store/formValidation','DevelopmentPermit@formValidation')->name('validation-add-development-permit-computation/store');
Route::post('development-permit-computation/store','DevelopmentPermit@store')->name('edit-development-permit-computation/store');
Route::post('development-permit-computation/ActiveInactive','DevelopmentPermit@ActiveInactive')->name('edit-development-permit-computation/ActiveInactive');
Route::get('development-permit-computation/get-imperials','DevelopmentPermit@getImperials')->name('edit-development-permit-computation/get-imperials');

// Zoning Clearance Computation
Route::get('zoning-clearance-computation','ZoningClearanceController@index')->name('zoning-clearance-computation');
Route::get('zoning-clearance-computation/getList','ZoningClearanceController@getList')->name('zoning-clearance-computation/getList');
Route::get('zoning-clearance-computation/store','ZoningClearanceController@store')->name('add-zoning-clearance-computation/store');
Route::post('zoning-clearance-computation/store/formValidation','ZoningClearanceController@formValidation')->name('validation-add-zoning-clearance-computation/store');
Route::post('zoning-clearance-computation/store','ZoningClearanceController@store')->name('edit-zoning-clearance-computation/store');
Route::post('zoning-clearance-computation/ActiveInactive','ZoningClearanceController@ActiveInactive')->name('edit-zoning-clearance-computation/ActiveInactive');
Route::get('zoning-clearance-computation/get-imperials','ZoningClearanceController@getImperials')->name('edit-zoning-clearance-computation/get-imperials');


// Medical Utilization 
Route::get('reports-inventory-utilization','HoUtilization@index')->name('reports-utilization-report');
Route::get('reports-inventory-utilization/getList','HoUtilization@getList')->name('reports-utilization-report/getList');
Route::get('reports-inventory-utilization/store','HoUtilization@store')->name('add-reports-utilization-report/store');
Route::post('reports-inventory-utilization/store/formValidation','HoUtilization@formValidation')->name('validation-add-reports-utilization-report/store');
Route::post('reports-inventory-utilization/store','HoUtilization@store')->name('edit-reports-utilization-report/store');
Route::post('reports-inventory-utilization/ActiveInactive','HoUtilization@ActiveInactive')->name('edit-reports-utilization-report/ActiveInactive');

// Utilization Report
Route::get('reports-utilization','UtilizationReport@index')->name('utilization-report');
Route::get('reports-utilization/getList','UtilizationReport@getList')->name('Report-report/getList');
Route::get('reports-utilization/store','UtilizationReport@store')->name('add-utilization-report/store');
// Route::post('reports-utilization/store/formValidation','HoUtilization@formValidation')->name('validation-add-utilization-report/store');
// Route::post('reports-utilization/store','HoUtilization@store')->name('edit-utilization-report/store');
// Route::post('reports-utilization/ActiveInactive','HoUtilization@ActiveInactive')->name('edit-utilization-report/ActiveInactive');

/*treasurer-tax-credit */
Route::get('treasurer-tax-credit','TreasurerTaxCreditCont@index')->name('treasurertaxcredit.index');
Route::get('treasurer-tax-credit/store','TreasurerTaxCreditCont@store');
Route::post('arr-depaertments-check','TreasurerTaxCreditCont@arrdepaertmentscheck');
Route::get('treasurer-tax-credit/getList','TreasurerTaxCreditCont@getList')->name('treasurertaxcredit.getList');
Route::post('treasurer-tax-credit/ActiveInactive','TreasurerTaxCreditCont@ActiveInactive');
Route::resource('treasurer-tax-credit/','TreasurerTaxCreditCont')->middleware(['auth','revalidate']);
Route::post('treasurer-tax-credit/formValidation','TreasurerTaxCreditCont@formValidation')->name('treasurertaxcredit.post');

/// TANGA DITO

/*application-form-payment-services */
Route::get('application-form-payment-services','PaymentServicesController@index')->name('ApplicationPaymentServices.index');
Route::get('ApplicationPaymentServices/store','PaymentServicesController@store');
Route::post('ApplicationPaymentServices-getmodulename','PaymentServicesController@getmodulename');
Route::get('ApplicationPaymentServices/getList','PaymentServicesController@getList')->name('ApplicationPaymentServices.getList');
Route::post('ApplicationPaymentServices/ActiveInactive','PaymentServicesController@ActiveInactive');
Route::resource('ApplicationPaymentServices/','PaymentServicesController')->middleware(['auth','revalidate']);
Route::post('ApplicationPaymentServices/formValidation','PaymentServicesController@formValidation')->name('ApplicationPaymentServices.post');

/*------- Engneering Cashering routes---*/
Route::get('engcashering', 'Engneering\EngCasheringController@getList')->name('engcashering.index');
Route::get('engcashering/getList', 'Engneering\EngCasheringController@getList')->name('engcashering.getList');
Route::post('engcashering/ActiveInactive', 'Engneering\EngCasheringController@ActiveInactive');
Route::post('engcashering/getOrnumber', 'Engneering\EngCasheringController@getOrnumber');
Route::post('engcashering/cancelorpayment', 'Engneering\EngCasheringController@cancelOrPayment');
Route::post('engcashering/getamountinword', 'Engneering\EngCasheringController@getamountinword');
Route::get('engcashering/store', 'Engneering\EngCasheringController@store');
// Route::get('engcashering/printengCasheringtax', 'Engneering\EngCasheringController@printengCasheringtax');
Route::get('engcashering/printengCasheringtax', 'Engneering\EngCasheringController@printReceipt');
Route::resource('engcashering', 'Engneering\EngCasheringController')->middleware(['auth','revalidate']);
Route::post('engcashering/getfeeamount', 'Engneering\EngCasheringController@getfeeamount');
Route::post('engcashering/getTransactionid', 'Engneering\EngCasheringController@getTransactionid');
Route::post('engcashering/getallFeesejr', 'Engneering\EngCasheringController@getallFeesejr');
Route::post('engcashering/getallFeesejredit', 'Engneering\EngCasheringController@getallFeesejredit');
Route::post('engcashering/getClientsbussiness', 'Engneering\EngCasheringController@getClientsbussiness');  
Route::post('engcashering/getClientsDropdown', 'Engneering\EngCasheringController@getClientsDropdown');
Route::post('engcashering/formValidation', 'Engneering\EngCasheringController@formValidation')->name('engcashering.post');


/*------- Occupancy Cashering routes---*/
Route::get('occupancycashering', 'Engneering\OccupancyCasheringController@getList')->name('occupancycashering.index');
Route::get('occupancycashering/getList', 'Engneering\OccupancyCasheringController@getList')->name('occupancycashering.getList');
Route::post('occupancycashering/ActiveInactive', 'Engneering\OccupancyCasheringController@ActiveInactive');
Route::post('occupancycashering/getOrnumber', 'Engneering\OccupancyCasheringController@getOrnumber');
Route::post('occupancycashering/getamountinword', 'Engneering\OccupancyCasheringController@getamountinword');
Route::get('occupancycashering/store', 'Engneering\OccupancyCasheringController@store');
Route::get('occupancycashering/printoccupancyCasheringtax', 'Engneering\OccupancyCasheringController@printoccupancyCasheringtax');
Route::resource('occupancycashering', 'Engneering\OccupancyCasheringController')->middleware(['auth','revalidate']);
Route::post('occupancycashering/getfeeamount', 'Engneering\OccupancyCasheringController@getfeeamount');
Route::post('occupancycashering/getTransactionid', 'Engneering\OccupancyCasheringController@getTransactionid');
Route::post('occupancycashering/getallFeeseeoa', 'Engneering\OccupancyCasheringController@getallFeeseeoa');
Route::post('occupancycashering/getallFeeseoaedit', 'Engneering\OccupancyCasheringController@getallFeeseoaedit');
Route::post('occupancycashering/getTopNumbersAjax', 'Engneering\OccupancyCasheringController@getTopNumbersAjax');
Route::post('occupancycashering/getClientsbussiness', 'Engneering\OccupancyCasheringController@getClientsbussiness');  
Route::post('occupancycashering/getClientsDropdown', 'Engneering\OccupancyCasheringController@getClientsDropdown');
Route::post('occupancycashering/formValidation', 'Engneering\OccupancyCasheringController@formValidation')->name('occupancycashering.post');

/*------- Real Property Tax Payers File---*/
Route::get('real-property-taxpayers-file', 'RptPropertyOwnerController@taxpayersindex')->name('realpropertytaxpayerfile.index');
Route::get('rptop/getlist', 'RptPropertyOwnerController@rptopGetList')->name('realpropertytaxpayerfile.getlist');
Route::post('rptop/loadHistory', 'RptPropertyOwnerController@rptopLoadHistory')->name('realpropertytaxpayerfile.loadHistory');
Route::get('rptop/printbill/{id}', 'RptPropertyOwnerController@printBill')->name('realpropertytaxpayerfile.printbill');


// ------------------------------------- EngClients------------------------------
Route::post('cpdoclients-uploadDocument', 'Cpdo\CpdoClientsController@uploadDocument')->name('cpdoclients.uploadDocument');
Route::post('cpdoclients-deleteAttachment', 'Cpdo\CpdoClientsController@deleteAttachment')->name('cpdoclients.deleteAttachment');
Route::get('cpdoclients/index', 'Cpdo\CpdoClientsController@index')->name('cpdoclients.index');
Route::post('cpdoclients/delete', 'Cpdo\CpdoClientsController@Delete');
Route::get('cpdoclients/getClientsDetails', 'Cpdo\CpdoClientsController@getProfileDetails');
Route::post('cpdoclients/ActiveInactive', 'Cpdo\CpdoClientsController@ActiveInactive');
Route::get('cpdoclients/getList', 'Cpdo\CpdoClientsController@getList')->name('cpdoclients.getList');
Route::any('cpdoclients/store', 'Cpdo\CpdoClientsController@store');
Route::resource('cpdoclients', 'Cpdo\CpdoClientsController')->middleware(['auth','revalidate']);
Route::post('cpdoclients/formValidation', 'Cpdo\CpdoClientsController@formValidation')->name('cpdoclients.post');

/* reports-masterlists-business-establishments */
Route::get('reports-masterlists-business-establishments','ReportsMasterlistsController@index')->name('reportsmasterlists.index');
Route::post('reportsmasterlists/getList','ReportsMasterlistsController@getList')->name('reportsmasterlists.getList');
Route::get('export-reportsmasterlists','ReportsMasterlistsController@exportreportsmasterlists1')->name('reportsmasterlists.export-reportsmasterlists');

/* reports-masterlists-assessed-fees */
Route::get('reports-masterlists-assessed-fees','ReportAssessedFeesController@index')->name('reportAssessedFees.index');
Route::get('reports-masterlists-assessed-fees/getList','ReportAssessedFeesController@getList')->name('reportAssessedFees.getList');
Route::get('export-assessed-fees','ReportAssessedFeesController@exportreportsmasterlists')->name('reportAssessedFees.export-assessed-fees');

/* reports-masterlists-Registered-business */
Route::get('reports-masterlists-registered-business','ReportsMasterlistsRegBusnController@index')->name('reportsmasterlistsregbusn.index');
Route::post('report-registered-business/getList','ReportsMasterlistsRegBusnController@getList')->name('reportsmasterlistsregbusn.getList');
Route::get('export-registered-business','ReportsMasterlistsRegBusnController@exportreportsmasterlists')->name('reportsmasterlistsregbusn.export-reportsmasterlistsbusnrpt');

/* reports-masterlists-declined-business */
Route::get('reports-masterlists-declined-applications-business','ReportsMasterlistsDeclinedBusnController@index')->name('reportsmasterlistsdeclinedbusn.index');
Route::post('report-declined-business/getList','ReportsMasterlistsDeclinedBusnController@getList')->name('reportsmasterlistsdeclinedbusn.getList');
Route::get('export-declined-business','ReportsMasterlistsDeclinedBusnController@exportreportsmasterlists')->name('reportsmasterlistsdeclinedbusn.export-declined-business');

/* reports-masterlists-establishment-line-of-business */
Route::get('reports-masterlists-lines-of-business-of-registered-establishment','ReportsMasterlistEstLineOfbusinessController@index')->name('report-est-LineOfbusiness.index');
Route::post('report-est-LineOfbusiness/getList','ReportsMasterlistEstLineOfbusinessController@getList')->name('report-est-LineOfbusiness.getList');
Route::get('export-est-LineOfbusiness','ReportsMasterlistEstLineOfbusinessController@exportreportsmasterlists')->name('report-est-LineOfbusiness.export-LineOfbusiness');

/* reports-masterlists-percentage-ofbusiness-owned-bysex */
Route::get('reports-masterlists-percentage-ofbusiness-owned-bysex','PercentageOfBusinessController@index')->name('percentageofbusiness.index');
Route::get('export-percentage-ofbusiness','PercentageOfBusinessController@exportpercentage')->name('percentageofbusiness.export-percentage-ofbusiness');
/* bfp-clients-list */
Route::get('bfp-clients-list','Report\BfpClientsListController@index')->name('bfpclientslist.index');
Route::get('bfp-clients-list/getList', 'Report\BfpClientsListController@getList')->name('bfpclientslist.getList');
Route::get('export-bfp-client-lists','Report\BfpClientsListController@exportreportsBfpCilentlists')->name('bplo.business.export');
/* business-tax-collection */
Route::get('business-tax-collection','Report\BusinessTaxCollectionController@index')->name('businesstaxcollection.index');
Route::get('business-tax-collection/getList', 'Report\BusinessTaxCollectionController@getList')->name('businesstaxcollection.getList');
Route::get('export-business-tax-collection','Report\BusinessTaxCollectionController@exportreportsBusinessTaxCollection')->name('bplo.business.export');
/* reports-masterlists-noof-business-permit-issued */
Route::get('reports-masterlists-noof-business-permit-issued','NoBusinessPermitIssuedCont@index')->name('nobusinesspermitissued.index');

/* reports-masterlists-listof-business-per-barangay */
Route::get('reports-masterlists-listof-business-per-barangay','Report\ListofBusinessperBarangay@index')->name('listofbusinessper.index');
Route::get('reportsmasterlistslistofbusines/getList','Report\ListofBusinessperBarangay@getList')->name('listofbusinessper.getList');
Route::get('export-listof-business-per-barangay','Report\ListofBusinessperBarangay@exportlistofbusiness')->name('listofbusinessper.export');

/* Total Business per barangay */
Route::get('total-of-business-per-barangay','Report\TotalofBusinessperBarangay@index')->name('totalofbusinessper.index');
Route::get('reporttotalofbusines/getList','Report\TotalofBusinessperBarangay@getList')->name('totalofbusinessper.getList');
Route::get('export-total-of-business-per-barangay','Report\TotalofBusinessperBarangay@exportlistofbusiness')->name('totalofbusinessper.export');

/* Composition (of LGU's Fees)*/
Route::get('reports-masterlists-composition-of-lgu-fees','Report\CompositionOfLguFeesContt@index')->name('compositionfees.index');
Route::get('compositionoflgufees/getList','Report\CompositionOfLguFeesContt@getList')->name('compositionfees.getList');

/* List of Business Not Operational */
Route::get('reports-masterlists-listof-business-not-operational','Report\ListofBusinessNotOperational@index')->name('notoperational.index');
Route::get('listofbusinessnotoperational/getList','Report\ListofBusinessNotOperational@getList')->name('notoperational.getList');
Route::get('export-ListofBusinessNotOperational-lists','Report\ListofBusinessNotOperational@exportreportsListofBusinessNotOperational')->name('report.notoperational.export_view');


/* bank-description */
Route::post('bankdescription-uploadDocument', 'BankDescriptionController@uploadDocument')->name('bankdescription.uploadDocument');
Route::post('bankdescription-deleteAttachment', 'BankDescriptionController@deleteAttachment')->name('bankdescription.deleteAttachment');
Route::get('bank-description', 'BankDescriptionController@index')->name('bankdescription.index');
Route::get('bankdescription/store', 'BankDescriptionController@store');
Route::get('bankdescription/getList', 'BankDescriptionController@getList')->name('bankdescription.getList');
Route::post('bankdescription/ActiveInactive', 'BankDescriptionController@ActiveInactive');
Route::resource('bankdescription/', 'BankDescriptionController')->middleware(['auth','revalidate']);
Route::post('bankdescription/formValidation', 'BankDescriptionController@formValidation')->name('bankdescription.post');
// ------------------------------------- NoOfBusiness ------------------------------
 Route::prefix('reports-masterlists-noof-business-byline-business')->group(function () {
        Route::get('', 'NoOfBusinessController@index')->name('noofbusiness.index');
    });
 
Route::get('noofbusiness/index', 'NoOfBusinessController@index')->name('noofbusiness.index');
Route::get('noofbusiness/getList', 'NoOfBusinessController@getList')->name('noofbusiness.getList');
Route::resource('noofbusiness', 'NoOfBusinessController')->middleware(['auth','revalidate']);
Route::post('noofbusiness/formValidation', 'NoOfBusinessController@formValidation')->name('noofbusiness.post');
//------------------------------------Business Permit Retire---------------------------
Route::prefix('business-permit-retire')->group(function () {
    Route::get('', 'Bplo\BusinessPermitRetireController@index')->name('BusinessPermitRetire.index');
    Route::get('getList', 'Bplo\BusinessPermitRetireController@getList')->name('BusinessPermitRetire.list');
    Route::get('store', 'Bplo\BusinessPermitRetireController@store');
    Route::post('store', 'Bplo\BusinessPermitRetireController@store');
    Route::post('uploadDocument', 'Bplo\BusinessPermitRetireController@uploadDocument');
    Route::post('deleteAttachment', 'Bplo\BusinessPermitRetireController@deleteAttachment');
    Route::post('getBusinessDetails', 'Bplo\BusinessPermitRetireController@getBusinessDetails');
    Route::post('store/formValidation', 'Bplo\BusinessPermitRetireController@formValidation')->name('BusinessPermitRetire.post');
    Route::post('getRequirementList', 'Bplo\BusinessPermitRetireController@getRequirementList');
    Route::post('deleteRetirementApplication', 'Bplo\BusinessPermitRetireController@deleteRetirementApplication');
    Route::post('checkPreviousPendingAmt', 'Bplo\BusinessPermitRetireController@checkPreviousPendingAmt');
    
    
});
//------------------------------------Business Permit Assessment Retire---------------------------
Route::prefix('treasury-business-retirement-assessment')->group(function () {
    Route::get('', 'Bplo\RetireAssessmentController@index')->name('RetireAssessment.index');
    Route::get('getList', 'Bplo\RetireAssessmentController@getList')->name('RetireAssessment.list');
    Route::get('store', 'Bplo\RetireAssessmentController@store');
    Route::post('store', 'Bplo\RetireAssessmentController@store');
    Route::post('getAssessmentDetails', 'Bplo\TreasurerAssessmentController@getAssessmentDetails');
    Route::post('saveFinalAssessDtls', 'Bplo\TreasurerAssessmentController@saveFinalAssessDtls');
    Route::post('displayTaxOrderOfPayment', 'Bplo\TreasurerAssessmentController@displayTaxOrderOfPayment');
    Route::get('generatePaymentPdf', 'Bplo\TreasurerAssessmentController@generatePaymentPdf');
});
//------------------------------------Business Permit Delinquency---------------------------
 Route::prefix('treasury-business-delinquent')->group(function () {
    Route::get('/', 'Bplo\DelinquencyController@index')->name('Delinquency.index');
    Route::get('getList', 'Bplo\DelinquencyController@getList')->name('Delinquency.list');
    Route::any('store', 'Bplo\DelinquencyController@store');
    Route::any('sendEmail', 'Bplo\DelinquencyController@sendEmail');
});

//------------------------------------Business Permit Outstanding Details---------------------------
Route::middleware(['auth'])->prefix('business-outstanding-payment')->group(function () {
    Route::get('/', 'Bplo\OutstandingPaymentController@index')->name('OutstandingPayment.index');
    Route::get('getList', 'Bplo\OutstandingPaymentController@getList')->name('OutstandingPayment.list');
    Route::any('store', 'Bplo\OutstandingPaymentController@store');
    Route::any('sendEmail', 'Bplo\OutstandingPaymentController@sendEmail');
    Route::post('getOutstandingDetails', 'Bplo\OutstandingPaymentController@getOutstandingDetails');
});

/*departmental collection*/
Route::get('reports-departmental-collection/index', 'Report\DepartmentalCollectionController@index')->name('departmentcollection.index');
Route::get('reports-departmental-collection/getList', 'Report\DepartmentalCollectionController@getList')->name('departmentcollection.getList');
Route::resource('reports-departmental-collection', 'Report\DepartmentalCollectionController')->middleware(['auth','revalidate']);
Route::get('export-departmentalcollection','Report\DepartmentalCollectionController@exportdepartmentalcollection')->name('departmentalcollection.export-reportlist');
Route::post('reports-departmental-collection/viewdetails','Report\DepartmentalCollectionController@viewdetails')->name('departmentalcollection.viewdetails');

Route::get('reports-composition-lgu-fees/index', 'Report\CompositionOflguController@index')->name('compositionoflgu.index');
Route::get('reports-composition-lgu-fees/getList', 'Report\CompositionOflguController@getList')->name('compositionoflgu.getList');
Route::resource('reports-composition-lgu-fees', 'Report\CompositionOflguController')->middleware(['auth','revalidate']);
Route::post('reports-composition-lgu-fees/viewdetails','Report\CompositionOflguController@viewdetails')->name('compositionoflgu.viewdetails');

Route::get('reports-masterlists-retired-business', 'Report\ReportsMasterlistsRetired@index')->name('retiredbusiness.index');
Route::get('retiredbusiness/getList', 'Report\ReportsMasterlistsRetired@getList')->name('retiredbusiness.getList');
Route::resource('retiredbusiness', 'Report\ReportsMasterlistsRetired')->middleware(['auth','revalidate']);
Route::get('export-retiredbusiness','Report\ReportsMasterlistsRetired@exportlistsRetired')->name('reportAssessedFees.export-retiredbusiness');

/*health-and-safety/setup-data/serology-method */
Route::get('health-and-safety/setup-data/serology-method','SerologyMethodContt@index')->name('serologymethod.index');
Route::get('serologymethod/store','SerologyMethodContt@store');
Route::get('serologymethod/getList','SerologyMethodContt@getList')->name('serologymethod.getList');
Route::post('serologymethod/ActiveInactive','SerologyMethodContt@ActiveInactive');
Route::resource('serologymethod/','SerologyMethodContt')->middleware(['auth','revalidate']);
Route::post('serologymethod/formValidation','SerologyMethodContt@formValidation')->name('serologymethod.post');

Route::get('/send-departmental-request/{id}', 'NotificationController@departmental_request')->name('send.departmental.request');
Route::get('/departmental-request/approve/{id}', 'NotificationController@approve_departmental')->name('approve.departmental.request');
Route::get('/departmental-request/disapprove/{id}', 'NotificationController@disapprove_departmental')->name('disapprove.departmental.request');

/*health-and-safety/medicine-and-supplies/Expirable */
Route::get('health-and-safety/medicine-supplies/expirable-inventory','ExpirableController@index')->name('expirable.index');
Route::get('health-and-safety/medicine-supplies/expirable-inventory/getList','ExpirableController@getList')->name('expirable.getList');
Route::post('health-and-safety/medicine-supplies/expirable-inventory/ActiveInactive','ExpirableController@ActiveInactive');
Route::resource('health-and-safety/medicine-supplies/expirable-inventory/','ExpirableController')->middleware(['auth','revalidate']);
Route::post('health-and-safety/medicine-supplies/expirable-inventory/formValidation','ExpirableController@formValidation')->name('expirable.post');

/*health-and-safety/setup-data/ExpirableItem */
Route::get('health-and-safety/setup-data/expirable-item','ExpirableItemController@index')->name('expirableitem.index');
Route::get('expirableitem/getList','ExpirableItemController@getList')->name('expirableitem.getList');
Route::post('expirableitem/ActiveInactive','ExpirableItemController@ActiveInactive');
Route::resource('expirableitem/','ExpirableItemController')->middleware(['auth','revalidate']);
Route::post('expirableitem/formValidation','ExpirableItemController@formValidation')->name('expirableitem.post');


/*Bussiness tax credit*/
Route::get('treasury-business-taxcreditfile/index', 'Bplo\BplotaxcreditController@index')->name('treasury-business.index');
Route::get('treasury-business-taxcreditfile/getList', 'Bplo\BplotaxcreditController@getList')->name('treasury-business.getList');
Route::resource('treasury-business-taxcreditfile', 'Bplo\BplotaxcreditController')->middleware(['auth','revalidate']);
Route::post('treasury-business-taxcreditfile/viewdetails','Bplo\BplotaxcreditController@viewdetails')->name('treasury-business.viewdetails');


/*Bplo-retirement-Certificate */
Route::get('bplo-retirementcertificate','Bplo\CertificateIssuanceController@index')->name('retirecertificate.index');
Route::get('bplo-retirementcertificate/store','Bplo\CertificateIssuanceController@store');
Route::get('bplo-retirementcertificate/getList','Bplo\CertificateIssuanceController@getList')->name('retirecertificate.getList');
Route::post('bplo-retirementcertificate/ActiveInactive','Bplo\CertificateIssuanceController@ActiveInactive');
Route::post('bplo-retirementcertificate/uploadDocument','Bplo\CertificateIssuanceController@uploadDocument');
Route::post('bplo-retirementcertificate/deleteAttachment','Bplo\CertificateIssuanceController@deleteAttachment'); 
Route::post('bplo-retirementcertificate/updateremark','Bplo\CertificateIssuanceController@updateremark');
Route::post('bplo-retirementcertificate/updatestatus','Bplo\CertificateIssuanceController@updatestatus');
Route::resource('bplo-retirementcertificate/','Bplo\CertificateIssuanceController')->middleware(['auth','revalidate']);
Route::post('bplo-retirementcertificate/formValidation','Bplo\CertificateIssuanceController@formValidation')->name('retirecertificate.post');

/*Monthly collection*/
Route::get('reports-monthly-collection/index', 'Report\MonthlyCollectionController@index')->name('departmentcollection.index');
Route::get('reports-monthly-collection/getList', 'Report\MonthlyCollectionController@getList')->name('departmentcollection.getList');
Route::resource('reports-monthly-collection', 'Report\MonthlyCollectionController')->middleware(['auth','revalidate']);
Route::get('export-monthlycollection','Report\MonthlyCollectionController@exportmonthlycollection')->name('monthlycollection.export-reportlist');
Route::post('reports-monthly-collection/viewdetails','Report\MonthlyCollectionController@viewdetails')->name('departmentalcollection.viewdetails');

/* hrsalarygrade */
Route::any('hr-salary-grade','HR\HrSalaryGradeController@index')->name('hrsalarygrade.index');
Route::get('hr-salary-grade/store','HR\HrSalaryGradeController@store')->name('hrsalarygrade.store');
Route::get('hr-salary-grade/getList','HR\HrSalaryGradeController@getList')->name('hrsalarygrade.getList');
Route::post('hr-salary-grade/ActiveInactive','HR\HrSalaryGradeController@ActiveInactive');
Route::resource('hr-salary-grade','HR\HrSalaryGradeController')->middleware(['auth','revalidate']);
Route::post('hr-salary-grade/formValidation','HR\HrSalaryGradeController@formValidation')->name('hrsalarygrade.post');

/* hr-missed-logs */
Route::any('hr-missed-logs','HR\HrMissedLogsController@index')->name('hr-missed-logs.index');
Route::get('hr-missed-logs/store','HR\HrMissedLogsController@store')->name('hr-missed-logs.store');
Route::get('hr-missed-logs/getList','HR\HrMissedLogsController@getList')->name('hr-missed-logs.getList');
Route::post('hr-missed-logs/ActiveInactive','HR\HrMissedLogsController@ActiveInactive');
Route::resource('hr-missed-logs','HR\HrMissedLogsController')->middleware(['auth','revalidate']);
Route::post('hr-missed-logs/formValidation','HR\HrMissedLogsController@formValidation')->name('hr-missed-logs.post');
Route::post('hr-missed-logs/disapprove', 'HR\HrMissedLogsController@disapprove')->name('hr-missed-logs.disapprove');

/* hr Appointment Routes */
Route::any('hr-appointment','HR\HrAppointmentController@index')->name('hr-appointment.index');
Route::get('hr-appointment/store','HR\HrAppointmentController@store')->name('hr-appointment.store');
Route::get('hr-appointment/getList','HR\HrAppointmentController@getList')->name('hr-appointment.getList');
Route::post('hr-appointment/ActiveInactive','HR\HrAppointmentController@ActiveInactive');
Route::resource('hr-appointment','HR\HrAppointmentController')->middleware(['auth','revalidate']);
Route::post('hr-appointment/formValidation','HR\HrAppointmentController@formValidation')->name('hr-appointment.post');

Route::post('hr-appointment/getDivByDept','HR\HrAppointmentController@getDivByDept');
Route::post('hr-appointment/getEmpByDiv','HR\HrAppointmentController@getEmpByDiv');
Route::post('hr-appointment/getEmpdetById','HR\HrAppointmentController@getEmpdetById');
Route::post('hr-appointment/getSalaryDet','HR\HrAppointmentController@getSalaryDet');
Route::post('hr-appointment/getEmployees','HR\HrAppointmentController@getEmployees');
Route::post('hr-appointment/getEmployees/{div}','HR\HrAppointmentController@getEmployeesByDiv');


/* hrleavetype */
Route::any('hr-leave-type','HR\HrLeavetypeController@index')->name('hrleavetype.index');
Route::get('hr-leave-type/store','HR\HrLeavetypeController@store')->name('hrleavetype.store');
Route::get('hr-leave-type/getList','HR\HrLeavetypeController@getList')->name('hrleavetype.getList');
Route::post('hr-leave-type/ActiveInactive','HR\HrLeavetypeController@ActiveInactive');
Route::resource('hr-leave-type','HR\HrLeavetypeController')->middleware(['auth','revalidate']);
Route::post('hr-leave-type/formValidation','HR\HrLeavetypeController@formValidation')->name('hrleavetype.post');

/* hrleaveapplication */
Route::any('hr-leave-application','HR\HrLeaveApplicationController@index')->name('hrleaveapplication.index');
Route::get('hr-leave-application/store','HR\HrLeaveApplicationController@store')->name('hrleaveapplication.store');
Route::get('hr-leave-application/getList','HR\HrLeaveApplicationController@getList')->name('hrleaveapplication.getList');
Route::post('hr-leave-application/ActiveInactive','HR\HrLeaveApplicationController@ActiveInactive');
Route::resource('hr-leave-application','HR\HrLeaveApplicationController')->middleware(['auth','revalidate']);
Route::post('hr-leave-application/formValidation','HR\HrLeaveApplicationController@formValidation')->name('hrleaveapplication.post');

/* hrdefaultSchedules */
Route::any('hr-default-schedules','HR\HrDefaultScheduleController@index')->name('hrdefaultschedule.index');
Route::get('hr-default-schedules/store','HR\HrDefaultScheduleController@store')->name('hrdefaultschedule.store');
Route::get('hr-default-schedules/getList','HR\HrDefaultScheduleController@getList')->name('hrdefaultschedule.getList');
Route::post('hr-default-schedules/ActiveInactive','HR\HrDefaultScheduleController@ActiveInactive');
Route::resource('hr-default-schedules','HR\HrDefaultScheduleController')->middleware(['auth','revalidate']);
Route::post('hr-default-schedules/formValidation','HR\HrDefaultScheduleController@formValidation')->name('hrdefaultschedule.post');
// employee-biometric-record
Route::any('employee-biometric-record','HR\HrBiometricsRecordController@index')->name('HrBiometricsRecord.index');
Route::get('employee-biometric-record/getList','HR\HrBiometricsRecordController@getList')->name('HrBiometricsRecord.getList');
Route::resource('employee-biometric-record','HR\HrBiometricsRecordController')->middleware(['auth','revalidate']);
Route::post('employee-biometric-record/getDivByDept','HR\HrBiometricsRecordController@getDivByDept');
//hr-timekeeping
Route::any('hr-timekeeping','HR\HrTimekeepingController@index')->name('HrTimekeeping.index');
Route::get('hr-timekeeping/getList','HR\HrTimekeepingController@getList')->name('HrTimekeeping.getList');
Route::resource('hr-timekeeping','HR\HrTimekeepingController')->middleware(['auth','revalidate']);
Route::post('hr-timekeeping/getDivByDept','HR\HrTimekeepingController@getDivByDept');
Route::post('hr-timekeeping/getEmpByDiv','HR\HrTimekeepingController@getEmpByDiv');
Route::post('hr-timekeeping/process-timekeeping','HR\HrTimekeepingController@process_timekeeping');

/* hrholidays */
Route::any('hr-holidays','HR\HrHolidaysController@index')->name('hrholiday.index');
Route::get('hr-holidays/store','HR\HrHolidaysController@store')->name('hrholiday.store');
Route::get('hr-holidays/getList','HR\HrHolidaysController@getList')->name('hrholiday.getList');
Route::post('hr-holidays/ActiveInactive','HR\HrHolidaysController@ActiveInactive');
Route::resource('hr-holidays','HR\HrHolidaysController')->middleware(['auth','revalidate']);
Route::post('hr-holidays/formValidation','HR\HrHolidaysController@formValidation')->name('hrholiday.post');

/* hrworkSchedules */
Route::any('hr-work-schedule','HR\HrWorkScheduleController@index')->name('hrworkschedule.index');
Route::get('hr-work-schedule/store','HR\HrWorkScheduleController@store')->name('hrworkschedule.store');
Route::get('hr-work-schedule/getList','HR\HrWorkScheduleController@getList')->name('hrworkschedule.getList');
Route::post('hr-work-schedule/ActiveInactive','HR\HrWorkScheduleController@ActiveInactive');
Route::resource('hr-work-schedule','HR\HrWorkScheduleController')->middleware(['auth','revalidate']);
Route::post('hr-work-schedule-employee/formValidation','HR\HrWorkScheduleController@formValidation')->name('hrworkschedule.post');
Route::post('hr-work-schedule-employee','HR\HrWorkScheduleController@storeEmployee')->name('hrworkschedule.storeEmployee');
Route::post('hr-work-schedule/formValidation','HR\HrWorkScheduleController@formValidationEmployee')->name('hrworkschedule.formValidationEmployee');
Route::post('getEmployeeSched','HR\HrWorkScheduleController@getEmployeeSched')->name('hrworkschedule.getEmployeeSched');

/* hrworkSchedules */
Route::get('hr-my-calender','HR\HrMyCalenderController@index')->name('HrMyCalenderController.index');
Route::get('/events', 'HR\HrMyCalenderController@getEvents')->name('HrMyCalenderController.getEvents');
Route::post('/event/update', 'HR\HrMyCalenderController@updateEvent')->name('HrMyCalenderController.updateEvent');

// HR Policy 
Route::prefix('hr')->group(function () {
    Route::prefix('policy')->group(function () {
        $slugs = ['leave-deduction', 'work-days'];
        foreach ($slugs as $value) {
            Route::prefix($value)->group(function () use ($value){
                $controller = str_replace('-','',$value);
                Route::get('/',"HR\PolicyController@".$controller)->name('hr.policy.'.$value.'.index');
            });
        }
        Route::get('/edit/{policy}',"HR\PolicyController@edit")->name('hr.policy.edit');
        Route::post('/update',"HR\PolicyController@update")->name('hr.policy.update');
        Route::post('/update/formValidation','HR\PolicyController@formValidation')->name('hr.policy.validation');
        
    });
});

// Real Property Cashier
    Route::prefix('cashier-real-property')->group(function () {
        Route::get('', 'CashierRealPropertyController@index')->name('rptcashier.index');
        Route::get('getList', 'CashierRealPropertyController@getList')->name('rptcashier.list');
        Route::get('store', 'CashierRealPropertyController@store');
        Route::post('store', 'CashierRealPropertyController@store');
        Route::post('cancelOr', 'CashierRealPropertyController@cancelOr');
        Route::get('loadacceptedtds', 'CashierRealPropertyController@loadAcceptedTds');
        Route::post('getPaymentDetails', 'CashierRealPropertyController@getPaymentDetails');
        Route::post('getOrnumber', 'CashierRealPropertyController@getOrnumber');
        Route::post('accepttd', 'CashierRealPropertyController@acceptTd');
        Route::post('removetd', 'CashierRealPropertyController@removeTd');
        Route::get('loadcasheringinfo', 'CashierRealPropertyController@loadCasheringInfo');
        Route::post('checkOrUsedOrNot', 'CashierRealPropertyController@checkOrUsedOrNot');
        Route::get('printReceipt', 'CashierRealPropertyController@printReceipt');
        Route::post('creditAmountApply', 'CashierRealPropertyController@creditAmountApply');
        Route::post('updateCashierBillHistoryTaxpayers', 'CashierRealPropertyController@updateCashierBillHistoryTaxpayers');
        Route::post('updateRptOnlineAccessTaxpayers', 'CashierRealPropertyController@updateRptOnlineAccessTaxpayers');
        Route::get('geAjaxfundselectlist', 'CashierRealPropertyController@geAjaxfundselectlist');
        Route::get('geAjaxbankselectlist', 'CashierRealPropertyController@geAjaxbankselectlist');
        Route::get('geAjaxchequeselectlist', 'CashierRealPropertyController@geAjaxchequeselectlist');
        Route::get('geAjaxtopnoselectlist', 'CashierRealPropertyController@geAjaxtopnoselectlist');
        
    });

Route::prefix('rpt-tax-collection')->group(function () {
        Route::get('', 'CashierRealPropertyController@taxCollectionIndex')->name('taxcollection.index');
        Route::get('getList', 'CashierRealPropertyController@taxCollectiongetList')->name('taxcollection.list');
        Route::get('download-excel', 'CashierRealPropertyController@downloadExcel')->name('taxcollection.downloadexcel');
    });

/*Real Property tax credit*/
Route::get('rpt-tax-credit-file', 'RealPropTaxCreditController@index')->name('realproptaxcredit.index');
Route::get('rpt-tax-credit-file/getList', 'RealPropTaxCreditController@getList')->name('realproptaxcredit.getList');
Route::resource('rpt-tax-credit-file', 'RealPropTaxCreditController')->middleware(['auth','revalidate']);
Route::post('rpt-tax-credit-file/viewdetails','RealPropTaxCreditController@viewdetails')->name('realproptaxcredit.viewdetails');    

/* ChnageofSchedules */
Route::any('hr-change-schedule','HR\ChangeofScheduleController@index')->name('hrchnageschedule.index');
Route::get('hr-change-schedule/store','HR\ChangeofScheduleController@store')->name('hrchnageschedule.store');
Route::get('hr-change-schedule/getList','HR\ChangeofScheduleController@getList')->name('hrchnageschedule.getList');
Route::get('hr-change-schedule/validate-approver/{id}/{sequence}', 'HR\ChangeofScheduleController@validate_approver')->name('hrchnageschedule.validate-approver');
Route::post('hr-change-schedule/approve', 'HR\ChangeofScheduleController@approve')->name('hrchnageschedule.approve');
Route::post('change-schedule/deleteAttachment', 'HR\ChangeofScheduleController@deleteAttachment')->name('hrchnageschedule.deleteAttachment');
Route::post('hr-change-schedule/disapprove', 'HR\ChangeofScheduleController@disapprove')->name('hrchnageschedule.disapprove');
Route::post('hr-change-schedule/ActiveInactive','HR\ChangeofScheduleController@ActiveInactive');
Route::resource('hr-change-schedule','HR\ChangeofScheduleController')->middleware(['auth','revalidate']);
Route::post('hr-change-schedule/formValidation','HR\ChangeofScheduleController@formValidation')->name('hrchnageschedule.post');

/* pagibig */
Route::any('hr-pagibig','HR\HrPagibigController@index')->name('hrpagibig.index');
Route::get('hr-pagibig/store','HR\HrPagibigController@store')->name('hrpagibig.store');
Route::get('hr-pagibig/getList','HR\HrPagibigController@getList')->name('hrpagibig.getList');
Route::post('hr-pagibig/ActiveInactive','HR\HrPagibigController@ActiveInactive');
Route::resource('hr-pagibig','HR\HrPagibigController')->middleware(['auth','revalidate']);
Route::post('hr-pagibig/formValidation','HR\HrPagibigController@formValidation')->name('hrpagibig.post');

//-------------Email Approval Without Authentication ---------------------------
Route::get('approveDelinquency/{id}', 'Bplo\DelinquencyController@approveDelinquency');
Route::get('approveOutstandingPayment/{id}', 'Bplo\OutstandingPaymentController@approveOutstandingPayment');
Route::get('rpt-deliquency/approveDelinquency/{id}', 'RptDeliquentsController@approveDelinquency');

//----------------------------------- Section Requirement---------------------------------------
Route::middleware(['auth'])->prefix('bplo-section-requirements')->group(function () {
    Route::get('', 'Bplo\SectionRequirementController@index')->name('SectionRequirement.index');
    Route::get('getList', 'Bplo\SectionRequirementController@getList')->name('SectionRequirement.getList');
    Route::get('view', 'Bplo\SectionRequirementController@view');
    Route::resource('','Bplo\SectionRequirementController')->middleware(['auth','revalidate']);
    Route::any('store', 'Bplo\SectionRequirementController@store');
    
    Route::post('formValidation', 'Bplo\SectionRequirementController@formValidation')->name('SectionRequirement.post');
});

Route::post('deleteSectionRequirement', 'Bplo\SectionRequirementController@deleteSectionRequirement');

/* Hr Leaves Applications */
Route::any('hr-leaves','HR\HrLeaveController@index')->name('hrleaves.index');
Route::get('hr-leaves/store','HR\HrLeaveController@store')->name('hrleaves.store');
Route::get('hr-leaves/getList','HR\HrLeaveController@getList')->name('hrleaves.getList');
Route::get('hr-leaves/validate-approver/{id}/{sequence}', 'HR\HrLeaveController@validate_approver')->name('hrleaves.validate-approver');
Route::post('hr-leaves/approve', 'HR\HrLeaveController@approve')->name('hrleaves.approve');
Route::post('hr-leaves/deleteAttachment', 'HR\HrLeaveController@deleteAttachment')->name('hrleaves.deleteAttachment');
Route::post('hr-leaves/disapprove', 'HR\HrLeaveController@disapprove')->name('hrleaves.disapprove');
Route::post('hr-leaves/ActiveInactive','HR\HrLeaveController@ActiveInactive');
Route::get('hr-leaves/automate-leave','HR\HrLeaveController@automateLeaves')->name('hrleaves.automateLeaves');
Route::resource('hr-leaves','HR\HrLeaveController')->middleware(['auth','revalidate']);
Route::post('hr-leaves/formValidation','HR\HrLeaveController@formValidation')->name('hrleaves.post');
Route::get('hr-leaves/print/{id}','HR\HrLeaveController@print')->name('hrleaves.print');

/* Hr Official Work */
Route::any('hr-official-work','HR\HrOfficialWorkController@index')->name('hrofficialwork.index');
Route::get('hr-official-work/store','HR\HrOfficialWorkController@store')->name('hrofficialwork.store');
Route::get('hr-official-work/getList','HR\HrOfficialWorkController@getList')->name('hrofficialwork.getList');
Route::get('hr-official-work/validate-approver/{id}/{sequence}', 'HR\HrOfficialWorkController@validate_approver')->name('hrleaves.validate-approver');
Route::post('hr-official-work/approve', 'HR\HrOfficialWorkController@approve')->name('hrofficialwork.approve');
Route::post('hr-official-work/deleteAttachment', 'HR\HrOfficialWorkController@deleteAttachment')->name('hrofficialwork.deleteAttachment');
Route::post('hr-official-work/disapprove', 'HR\HrOfficialWorkController@disapprove')->name('hrofficialwork.disapprove');
Route::post('hr-official-work/ActiveInactive','HR\HrOfficialWorkController@ActiveInactive');
Route::resource('hr-official-work','HR\HrOfficialWorkController')->middleware(['auth','revalidate']);
Route::post('hr-official-work/formValidation','HR\HrOfficialWorkController@formValidation')->name('hrofficialwork.post');

/*change-of-schedule-approval */
Route::any('change-of-schedule-approval','HR\ChangeofScheduleApprovalController@index')->name('changeofscheduleapproval.index');
Route::get('change-of-schedule-approval/store','HR\ChangeofScheduleApprovalController@store')->name('changeofscheduleapproval.store');
Route::get('change-of-schedule-approval/getList','HR\ChangeofScheduleApprovalController@getList')->name('changeofscheduleapproval.getList');
Route::post('change-of-schedule-approval/ActiveInactive','HR\ChangeofScheduleApprovalController@ActiveInactive');
Route::resource('change-of-schedule-approval','HR\ChangeofScheduleApprovalController')->middleware(['auth','revalidate']);
Route::post('change-of-schedule-approval/formValidation','HR\ChangeofScheduleApprovalController@formValidation')->name('changeofscheduleapproval.post');

/* Hr Official Work */
Route::any('hr-overtime','HR\HrOverTimeController@index')->name('hrovertime.index');
Route::get('hr-overtime/store','HR\HrOverTimeController@store')->name('hrovertime.store');
Route::get('hr-overtime/getList','HR\HrOverTimeController@getList')->name('hrovertime.getList');
Route::get('hr-overtime/validate-approver/{id}/{sequence}', 'HR\HrOverTimeController@validate_approver')->name('hrovertime.validate-approver');
Route::post('hr-overtime/approve', 'HR\HrOverTimeController@approve')->name('hrovertime.approve');
Route::post('hr-overtime/deleteAttachment', 'HR\HrOverTimeController@deleteAttachment')->name('hrovertime.deleteAttachment');
Route::post('hr-overtime/disapprove', 'HR\HrOverTimeController@disapprove')->name('hrovertime.disapprove');
Route::post('hr-overtime/ActiveInactive','HR\HrOverTimeController@ActiveInactive');
Route::resource('hr-overtime','HR\HrOverTimeController')->middleware(['auth','revalidate']);
Route::post('hr-overtime/formValidation','HR\HrOverTimeController@formValidation')->name('hrovertime.post');
Route::get('hr-overtime-approval','HR\HrOverTimeController@approvalIndex')->name('hrovertime.approval');
Route::get('hr-overtime-approval/store','HR\HrOverTimeController@approvalCreate')->name('hrovertime.approval');


/* Hr Offset */
Route::any('hr-offset','HR\HrOffsetController@index')->name('hroffset.index');
Route::get('hr-offset/store','HR\HrOffsetController@store')->name('hroffset.store');
Route::get('hr-offset/getList','HR\HrOffsetController@getList')->name('hroffset.getList');
Route::get('hr-offset/validate-approver/{id}/{sequence}', 'HR\HrOffsetController@validate_approver')->name('hroffset.validate-approver');
Route::post('hr-offset/approve', 'HR\HrOffsetController@approve')->name('hroffset.approve');
Route::post('hr-offset/deleteAttachment', 'HR\HrOffsetController@deleteAttachment')->name('hroffset.deleteAttachment');
Route::post('hr-offset/disapprove', 'HR\HrOffsetController@disapprove')->name('hroffset.disapprove');
Route::post('hr-offset/checkremainoffsethour', 'HR\HrOffsetController@chkOffsetHour')->name('hroffset.chkoffsethour');
Route::post('hr-offset/ActiveInactive','HR\HrOffsetController@ActiveInactive');
Route::resource('hr-offset','HR\HrOffsetController')->middleware(['auth','revalidate']);
Route::post('hr-offset/formValidation','HR\HrOffsetController@formValidation')->name('hroffset.post');

/* Hr Offset  Approval*/
Route::any('hr-offset-approval','HR\HrOffsetApprovalController@index')->name('hroffsetapproval.index');
Route::get('hr-offset-approval/store','HR\HrOffsetApprovalController@store')->name('hroffsetapproval.store');
Route::get('hr-offset-approval/getList','HR\HrOffsetApprovalController@getList')->name('hroffsetapproval.getList');
Route::get('hr-offset-approval/validate-approver/{id}/{sequence}', 'HR\HrOffsetApprovalController@validate_approver')->name('hroffsetapproval.validate-approver');
Route::post('hr-offset-approval/approve', 'HR\HrOffsetApprovalController@approve')->name('hroffsetapproval.approve');
Route::post('hr-offset-approval/deleteAttachment', 'HR\HrOffsetApprovalController@deleteAttachment')->name('hroffsetapproval.deleteAttachment');
Route::post('hr-offset-approval/disapprove', 'HR\HrOffsetApprovalController@disapprove')->name('hroffsetapproval.disapprove');
Route::post('hr-offset-approval/checkremainoffsethour', 'HR\HrOffsetApprovalController@chkOffsetHour')->name('hroffsetapproval.chkoffsethour');
Route::post('hr-offset-approval/ActiveInactive','HR\HrOffsetApprovalController@ActiveInactive');
Route::resource('hr-offset-approval','HR\HrOffsetApprovalController')->middleware(['auth','revalidate']);
Route::post('hr-offset-approval/formValidation','HR\HrOffsetApprovalController@formValidation')->name('hroffsetapproval.post');

/* Hr Leaves Applications Approval*/
Route::any('hr-leaves-approval','HR\HrLeaveApprovalController@index')->name('hrleavesapproval.index');
Route::get('hr-leaves-approval/store','HR\HrLeaveApprovalController@store')->name('hrleavesapproval.store');
Route::get('hr-leaves-approval/getList','HR\HrLeaveApprovalController@getList')->name('hrleavesapproval.getList');
Route::get('hr-leaves-approval/validate-approver/{id}/{sequence}', 'HR\HrLeaveApprovalController@validate_approver')->name('hrleavesapproval.validate-approver');
Route::post('hr-leaves-approval/approve', 'HR\HrLeaveApprovalController@approve')->name('hrleavesapproval.approve');
Route::post('hr-leaves-approval/deleteAttachment', 'HR\HrLeaveApprovalController@deleteAttachment')->name('hrleavesapproval.deleteAttachment');
Route::post('hr-leaves-approval/disapprove', 'HR\HrLeaveApprovalController@disapprove')->name('hrleavesapproval.disapprove');
Route::post('hr-leaves-approval/ActiveInactive','HR\HrLeaveApprovalController@ActiveInactive');
Route::resource('hr-leaves-approval','HR\HrLeaveApprovalController')->middleware(['auth','revalidate']);
Route::post('hr-leaves-approval/formValidation','HR\HrLeaveApprovalController@formValidation')->name('hrleavesapproval.post');

/* hr-missed-logs */
Route::any('hr-missed-logapproval','HR\HrMissedLogsApprovalController@index')->name('hr-missed-logapproval.index');
Route::get('hr-missed-logapproval/store','HR\HrMissedLogsApprovalController@store')->name('hr-missed-logapproval.store');
Route::get('hr-missed-logapproval/getList','HR\HrMissedLogsApprovalController@getList')->name('hr-missed-logapproval.getList');
Route::get('hr-missed-logapproval/validate-approver/{id}/{sequence}', 'HR\HrMissedLogsApprovalController@validate_approver')->name('hr-missed-logapproval.validate-approver');
Route::post('hr-missed-logapproval/approve', 'HR\HrMissedLogsApprovalController@approve')->name('hr-missed-logapproval.approve');
Route::post('hr-missed-logapproval/disapprove', 'HR\HrMissedLogsApprovalController@disapprove')->name('hr-missed-logapproval.disapprove');


Route::post('hr-missed-logapproval/ActiveInactive','HR\HrMissedLogsApprovalController@ActiveInactive');
Route::resource('hr-missed-logapproval','HR\HrMissedLogsApprovalController')->middleware(['auth','revalidate']);
Route::post('hr-missed-logapproval/formValidation','HR\HrMissedLogsApprovalController@formValidation')->name('hr-missed-logapproval.post');

/* Hr Official Work Approval*/
Route::any('hr-offwork-approval','HR\HrOfficialWorkApprovalController@index')->name('hroffworkapproval.index');
Route::get('hr-offwork-approval/store','HR\HrOfficialWorkApprovalController@store')->name('hroffworkapproval.store');
Route::get('hr-offwork-approval/getList','HR\HrOfficialWorkApprovalController@getList')->name('hroffworkapproval.getList');
Route::get('hr-offwork-approval/validate-approver/{id}/{sequence}', 'HR\HrOfficialWorkApprovalController@validate_approver')->name('hrleaves.validate-approver');
Route::post('hr-offwork-approval/approve', 'HR\HrOfficialWorkApprovalController@approve')->name('hroffworkapproval.approve');
Route::post('hr-offwork-approval/deleteAttachment', 'HR\HrOfficialWorkApprovalController@deleteAttachment')->name('hroffworkapproval.deleteAttachment');
Route::post('hr-offwork-approval/disapprove', 'HR\HrOfficialWorkApprovalController@disapprove')->name('hroffworkapproval.disapprove');
Route::post('hr-offwork-approval/ActiveInactive','HR\HrOfficialWorkApprovalController@ActiveInactive');
Route::resource('hr-offwork-approval','HR\HrOfficialWorkApprovalController')->middleware(['auth','revalidate']);
Route::post('hr-offwork-approval/formValidation','HR\HrOfficialWorkApprovalController@formValidation')->name('hroffworkapproval.post');

/* Hr Leave Adjustment*/
Route::any('hr-leaveearn-adjustment','HR\LeaveAdjustmentController@index')->name('hrleaveadjustment.index');
Route::any('my-leaves','HR\LeaveAdjustmentController@empleaves')->name('my.leaves');
Route::any('my-leaves/getList','HR\LeaveAdjustmentController@empleaveslist')->name('my.leaves');
Route::get('hr-leaveearn-adjustment/store','HR\LeaveAdjustmentController@store')->name('hrleaveadjustment.store');
Route::get('hr-leaveearn-adjustment/getList','HR\LeaveAdjustmentController@getList')->name('hrleaveadjustment.getList');
Route::post('hr-leaveearn-adjustment/getAdjustmentdetails', 'HR\LeaveAdjustmentController@getAdjustmentdetails');
Route::post('hr-leaveearn-adjustment/approve', 'HR\LeaveAdjustmentController@approve')->name('hrleaveadjustment.approve');
Route::post('hr-leaveearn-adjustment/ActiveInactive','HR\LeaveAdjustmentController@ActiveInactive');
Route::get('hr-leaveearn-adjustment/trigger-accural-monthly','HR\LeaveAdjustmentController@triggerAccuralMonthly')->name('hrleaveadjustment.triggerAccuralMonthly');
Route::get('hr-leaveearn-adjustment/trigger-accural-annually','HR\LeaveAdjustmentController@triggerAccuralAnnual')->name('hrleaveadjustment.triggerAccuralAnnual');
Route::resource('hr-leaveearn-adjustment','HR\LeaveAdjustmentController')->middleware(['auth','revalidate']);
Route::post('hr-leaveearn-adjustment/formValidation','HR\LeaveAdjustmentController@formValidation')->name('hrleaveadjustment.post');
Route::post('hr-leaveearn-adjustment/getRemaining','HR\LeaveAdjustmentController@getRemaining')->name('hrleaveadjustment.getRemaining');

// ------------------------------------- hr-leave-parameter ------------------------------
Route::get('hr-leave-parameter', 'HR\HrleaveParameterController@index')->name('leaveparameter.index');
Route::get('HrleaveParameter/getList', 'HR\HrleaveParameterController@getList')->name('HR.leaveparameter.getList');
Route::post('HrleaveParameter/ActiveInactive', 'HR\HrleaveParameterController@ActiveInactive');
Route::get('HrleaveParameter/store', 'HR\HrleaveParameterController@store');
Route::resource('HrleaveParameter', 'HR\HrleaveParameterController')->middleware(['auth','revalidate']);
Route::post('HrleaveParameter/formValidation', 'HR\HrleaveParameterController@formValidation')->name('leaveparameter.post');
Route::post('HrleaveParameter/remove-params', 'HR\HrleaveParameterController@removeParams')->name('leaveparameter.post');

// ------------------------------------- hr-leave-parameter detail ------------------------------
Route::get('hr-leave-parameter-detail', 'HR\HrleaveParameterDetailController@index')->name('leaveparameterdetail.index');
Route::get('HrleaveParameterDetail/getList', 'HR\HrleaveParameterDetailController@getList')->name('HR.leaveparameterdetail.getList');
Route::post('HrleaveParameterDetail/ActiveInactive', 'HR\HrleaveParameterDetailController@ActiveInactive');
Route::get('HrleaveParameterDetail/store', 'HR\HrleaveParameterDetailController@store');
Route::resource('HrleaveParameterDetail', 'HR\HrleaveParameterDetailController')->middleware(['auth','revalidate']);
Route::post('HrleaveParameterDetail/formValidation', 'HR\HrleaveParameterDetailController@formValidation')->name('leaveparameterdetail.post');


/* HR Employee Status */
Route::any('hr-employee-status','HR\HrEmployeeStatusController@index')->name('hremployeestatus.index');
Route::get('hr-employee-status/store','HR\HrEmployeeStatusController@store')->name('hremployeestatus.store');
Route::get('hr-employee-status/getList','HR\HrEmployeeStatusController@getList')->name('hremployeestatus.getList');
Route::post('hr-employee-status/ActiveInactive','HR\HrEmployeeStatusController@ActiveInactive');
Route::resource('hr-employee-status','HR\HrEmployeeStatusController')->middleware(['auth','revalidate']);
Route::post('hr-employee-status/formValidation','HR\HrEmployeeStatusController@formValidation')->name('hrleavetype.post');


/* hroccupational level */
Route::any('hr-occupation-level','HR\HrOccupationLevelController@index')->name('hroccupationlevel.index');
Route::get('hr-occupation-level/store','HR\HrOccupationLevelController@store')->name('hroccupationlevel.store');
Route::get('hr-occupation-level/getList','HR\HrOccupationLevelController@getList')->name('hroccupationlevel.getList');
Route::post('hr-occupation-level/ActiveInactive','HR\HrOccupationLevelController@ActiveInactive');
Route::resource('hr-occupation-level','HR\HrOccupationLevelController')->middleware(['auth','revalidate']);
Route::post('hr-occupation-level/formValidation','HR\HrOccupationLevelController@formValidation')->name('hroccupationlevel.post');

/* HR Salary Grade step */
Route::any('hr-salary-grade-step','HR\HrSalaryGradeStepController@index')->name('hrsalarygradestep.index');
Route::get('hr-salary-grade-step/store','HR\HrSalaryGradeStepController@store')->name('hrsalarygradestep.store');
Route::get('hr-salary-grade-step/getList','HR\HrSalaryGradeStepController@getList')->name('hrsalarygradestep.getList');
Route::post('hr-salary-grade-step/ActiveInactive','HR\HrSalaryGradeStepController@ActiveInactive');
Route::resource('hr-salary-grade-step','HR\HrSalaryGradeStepController')->middleware(['auth','revalidate']);
Route::post('hr-salary-grade-step/formValidation','HR\HrSalaryGradeStepController@formValidation')->name('hrsalarygradestep.post');

//------------------------------------Real Property Delinquency---------------------------
 Route::middleware(['auth'])->prefix('rpt-deliquency')->group(function () {
    Route::get('/', 'RptDeliquentsController@index')->name('rptdelinquency.index');
    Route::get('getList', 'RptDeliquentsController@getList')->name('rptdelinquency.list');
    Route::get('getdetailslist','RptDeliquentsController@getDetailsList');
    Route::any('store', 'RptDeliquentsController@store');
    Route::any('sendEmail', 'RptDeliquentsController@sendEmail');
    Route::any('sendSMS', 'RptDeliquentsController@sendDeliquentSMS');
    
});
 
Route::get('rpt-deliquency/generatePaymentPdf', 'RptDeliquentsController@generatePaymentPdf');
 //------------------------------------Real Property Delinquency---------------------------
 Route::prefix('rpt-payments-file')->group(function () {
    Route::get('/', 'RptPaymentFileController@index')->name('rptpaymentfile.index');
    Route::get('getList', 'RptPaymentFileController@getList')->name('rptpaymentfile.list');
    Route::get('getalltds', 'RptPaymentFileController@getAllTds')->name('getalltds.list');
    Route::any('store', 'RptPaymentFileController@store');
    Route::any('viewhistory', 'RptPaymentFileController@viewhistory');
    Route::post('uploadDocument', 'RptPaymentFileController@uploadDocument');
    Route::get('loadpaymentfiles', 'RptPaymentFileController@loadPaymentFiles');
    Route::get('deletepaymentfile', 'RptPaymentFileController@deletepaymentfile');
    Route::get('maketdremoteselectlist', 'RptPaymentFileController@maketdremoteselectlist');
});

 /* HR Pay Code */
Route::any('hr-pay-code','HR\HrPayCodeController@index')->name('hrpaycode.index');
Route::get('hr-pay-code/store','HR\HrPayCodeController@store')->name('hrpaycode.store');
Route::get('hr-pay-code/getList','HR\HrPayCodeController@getList')->name('hrpaycode.getList');
Route::post('hr-pay-code/ActiveInactive','HR\HrPayCodeController@ActiveInactive');
Route::resource('hr-pay-code','HR\HrPayCodeController')->middleware(['auth','revalidate']);
Route::post('hr-pay-code/formValidation','HR\HrPayCodeController@formValidation')->name('hrpaycode.post');

 /* HR Loan Type */
Route::any('hr-loan-type','HR\HrLoanTypeController@index')->name('hrloantype.index');
Route::get('hr-loan-type/store','HR\HrLoanTypeController@store')->name('hrloantype.store');
Route::get('hr-loan-type/getList','HR\HrLoanTypeController@getList')->name('hrloantype.getList');
Route::post('hr-loan-type/ActiveInactive','HR\HrLoanTypeController@ActiveInactive');
Route::resource('hr-loan-type','HR\HrLoanTypeController')->middleware(['auth','revalidate']);
Route::post('hr-loan-type/formValidation','HR\HrLoanTypeController@formValidation')->name('hrloantype.post');

/* HR Phil Health */
Route::any('hr-philhealth','HR\HrPhilHealthController@index')->name('hrphilhealth.index');
Route::get('hr-philhealth/store','HR\HrPhilHealthController@store')->name('hrphilhealth.store');
Route::get('hr-philhealth/getList','HR\HrPhilHealthController@getList')->name('hrphilhealth.getList');
Route::post('hr-philhealth/ActiveInactive','HR\HrPhilHealthController@ActiveInactive');
Route::resource('hr-philhealth','HR\HrPhilHealthController')->middleware(['auth','revalidate']);
Route::post('hr-philhealth/formValidation','HR\HrPhilHealthController@formValidation')->name('hrphilhealth.post');

// ------------------------------------- AllClients------------------------------
//Route::get('engclients/index', 'Engneering\EngClientsController@index')->name('engclients.index');
Route::post('allclients/delete', 'AllclientsController@Delete');
Route::get('allclients/getClientsDetails', 'AllclientsController@getProfileDetails');
Route::post('allclients/ActiveInactive', 'AllclientsController@ActiveInactive');
Route::get('allclients/getList', 'AllclientsController@getList')->name('allclients.getList');
Route::any('allclients/store', 'AllclientsController@store');
Route::resource('allclients', 'AllclientsController')->middleware(['auth','revalidate']);
Route::post('allclients/formValidation', 'AllclientsController@formValidation')->name('allclients.post');

/* HR tax Type */
Route::any('hr-tax','HR\HrTaxController@index')->name('hrtax.index');
Route::get('hr-tax/store','HR\HrTaxController@store')->name('hrtax.store');
Route::get('hr-tax/getList','HR\HrTaxController@getList')->name('hrtax.getList');
Route::post('hr-tax/ActiveInactive','HR\HrTaxController@ActiveInactive');
Route::resource('hr-tax','HR\HrTaxController')->middleware(['auth','revalidate']);
Route::post('hr-tax/formValidation','HR\HrTaxController@formValidation')->name('hrtax.post');

/* HR tax Card */
Route::get('hr-timecard/update-timecards','HR\PayrollCalculateController@updateTimecards')->name('update.timecards');
Route::any('hr-timecard','HR\HrTimecardController@index')->name('hrtimecard.index');
Route::any('my-timecard','HR\HrTimecardController@employee')->name('hrtimecard.employee');
Route::get('hr-timecard/store','HR\HrTimecardController@store')->name('hrtimecard.store');
Route::get('hr-timecard/getList','HR\HrTimecardController@getList')->name('hrtimecard.getList');
Route::get('hr-timecard/getList/{id}','HR\HrTimecardController@getList')->name('hrtimecard.getList.id');
Route::post('hr-timecard/ActiveInactive','HR\HrTimecardController@ActiveInactive');
Route::resource('hr-timecard','HR\HrTimecardController')->middleware(['auth','revalidate']);
Route::post('hr-timecard/formValidation','HR\HrTimecardController@formValidation')->name('hrtimecard.post');
Route::post('hr-timecard/refresh','HR\HrTimecardController@timecardRefresh')->name('hrtimecard.refresh');

/* HR tax Type */
Route::any('hr-cuttoff-period','HR\CuttoffPeriodController@index')->name('cuttoffperiod.index');
Route::get('hr-cuttoff-period/store','HR\CuttoffPeriodController@store')->name('cuttoffperiod.store');
Route::get('hr-cuttoff-period/getList','HR\CuttoffPeriodController@getList')->name('cuttoffperiod.getList');
Route::post('hr-cuttoff-period/ActiveInactive','HR\CuttoffPeriodController@ActiveInactive');
Route::resource('hr-cuttoff-period','HR\CuttoffPeriodController')->middleware(['auth','revalidate']);
Route::post('hr-cuttoff-period/formValidation','HR\CuttoffPeriodController@formValidation')->name('cuttoffperiod.post');

/* Hr gsis table */
Route::any('hr-gsis-benefits','HR\HrGsisController@index')->name('hrgsis.index');
Route::get('hr-gsis-benefits/store','HR\HrGsisController@store')->name('hrgsis.store');
Route::get('hr-gsis-benefits/getList','HR\HrGsisController@getList')->name('hrgsis.getList');
Route::post('hr-gsis-benefits/ActiveInactive','HR\HrGsisController@ActiveInactive');
Route::resource('hr-gsis-benefits','HR\HrGsisController')->middleware(['auth','revalidate']);
Route::post('hr-gsis-benefits/formValidation','HR\HrGsisController@formValidation')->name('hrgsis.post');


/* Hr Loan Application */
Route::post('loan-application/lone-ledeger', 'HR\HrLoanApplicationController@loneledegerhtml');
Route::get('loan-application/department-division/{employee_id}','HR\HrLoanApplicationController@getDesignation')->name('hrloanapplication/department-division');
Route::any('loan-application','HR\HrLoanApplicationController@index')->name('hrloanapplication.index');
Route::get('loan-application/store','HR\HrLoanApplicationController@store')->name('hrloanapplication.store');
Route::get('loan-application/getList','HR\HrLoanApplicationController@getList')->name('hrloanapplication.getList');
Route::post('loan-application/ActiveInactive','HR\HrLoanApplicationController@ActiveInactive');
Route::resource('loan-application','HR\HrLoanApplicationController')->middleware(['auth','revalidate']);
Route::post('loan-application/formValidation','HR\HrLoanApplicationController@formValidation')->name('hrgsis.post');

/* hr loan cycle */
Route::any('hr-loan-cycle','HR\HrLoanCycleController@index')->name('hrloancycle.index');
Route::get('hr-loan-cycle/store','HR\HrLoanCycleController@store')->name('hrloancycle.store');
Route::get('hr-loan-cycle/getList','HR\HrLoanCycleController@getList')->name('hrloancycle.getList');
Route::post('hr-loan-cycle/ActiveInactive','HR\HrLoanCycleController@ActiveInactive');
Route::resource('hr-loan-cycle','HR\HrLoanCycleController')->middleware(['auth','revalidate']);
Route::post('hr-loan-cycle/formValidation','HR\HrLoanCycleController@formValidation')->name('hrloancycle.post');

/* hr Income deduction*/
Route::any('hr-income-deduction','HR\HrIncomeDeductionController@index')->name('hrIncomeDeduction.index');
Route::get('hr-income-deduction/store','HR\HrIncomeDeductionController@store')->name('hrIncomeDeduction.store');
Route::get('hr-income-deduction/getList','HR\HrIncomeDeductionController@getList')->name('hrIncomeDeduction.getList');
Route::get('hr-income-deduction/getEmpList','HR\HrIncomeDeductionController@getEmpList')->name('hrIncomeDeduction.getEmpList');
Route::post('hr-income-deduction/getSelEmpList','HR\HrIncomeDeductionController@getSelEmpList')->name('hrIncomeDeduction.getSelEmpList');
Route::post('hr-income-deduction/ActiveInactive','HR\HrIncomeDeductionController@ActiveInactive');
Route::resource('hr-income-deduction','HR\HrIncomeDeductionController')->middleware(['auth','revalidate']);
Route::post('hr-income-deduction/formValidation','HR\HrIncomeDeductionController@formValidation')->name('hrIncomeDeduction.post');
Route::post('hr-income-deduction/getGovDeduction','HR\HrIncomeDeductionController@getGovDeduction')->name('hrIncomeDeduction.getGovDeduction');

//------------------------------------Real Property Short Collection---------------------------
 Route::middleware(['auth'])->prefix('rpt-short-collection')->group(function () {
    Route::get('/', 'RptShortCollectionController@index')->name('rptshortcollection.index');
    Route::get('getList', 'RptShortCollectionController@getList')->name('rptshortcollection.list');
    Route::get('getalltds', 'RptShortCollectionController@getAllTds')->name('rptshortcollection.list');
    Route::any('store', 'RptShortCollectionController@store');
    /*Route::any('sendEmail', 'RptPaymentFileController@sendEmail');*/
});
//------------------------------------Business Permit Payment File---------------------------
Route::middleware(['auth'])->prefix('business-payment-file')->group(function () {
    Route::get('/', 'Bplo\PaymentFileController@index')->name('paymentFile.index');
    Route::get('getList', 'Bplo\PaymentFileController@getList')->name('paymentFile.list');
    Route::any('store', 'Bplo\PaymentFileController@store');
});
 
//------------------------------------Biometricts---------------------------
Route::middleware(['auth'])->prefix('hr-biometrics')->group(function () {
    Route::get('','HR\BiometricsController@index')->name('hr.biometrics');
    Route::post('','HR\BiometricsController@store')->name('hr.biometrics.post');
    Route::get( 'store', 'HR\BiometricsController@store')->name('hr.biometrics.store');
    Route::post('formValidation','HR\BiometricsController@formValidation');
    Route::get('getList','HR\BiometricsController@getList');
    Route::post('import','HR\BiometricsController@import')->name('hr.biometrics.import');
    Route::post('import/formValidation','HR\BiometricsController@importValidation')->name('hr.biometrics.import.validate');
    Route::post('connect','HR\BiometricsController@connection')->name('hr.biometrics.connection');
    Route::get('tester','HR\BiometricsController@tester')->name('hr.biometrics.test');
});
// API Biometrics
Route::prefix('api')->group(function () {
    Route::prefix('biometrics')->group(function () {
        Route::get('getBiometrics','HR\BiometricsApiController@biometrics');
        Route::post('confirmBiometric','HR\BiometricsApiController@confirmBiometric');
        Route::post('recieveAttendance','HR\BiometricsApiController@recieveAttendance');
    });
});
/* HR Income and deduction Type */
Route::any('hr-income-deduction-type','HR\HrIncomeDeductionTypeController@index')->name('HrIncomeDeductionType.index');
Route::get('hr-income-deduction-type/store','HR\HrIncomeDeductionTypeController@store')->name('HrIncomeDeductionType.store');
Route::get('hr-income-deduction-type/getList','HR\HrIncomeDeductionTypeController@getList')->name('HrIncomeDeductionType.getList');
Route::post('hr-income-deduction-type/ActiveInactive','HR\HrIncomeDeductionTypeController@ActiveInactive');
Route::resource('hr-income-deduction-type','HR\HrIncomeDeductionTypeController')->middleware(['auth','revalidate']);
Route::post('hr-income-deduction-type/formValidation','HR\HrIncomeDeductionTypeController@formValidation')->name('HrIncomeDeductionType.post');
/* HR Loan Cycle */
Route::any('hr-appointment-status','HR\HrAppointmentStatusController@index')->name('HrAppointmentStatus.index');
Route::get('hr-appointment-status/store','HR\HrAppointmentStatusController@store')->name('HrAppointmentStatus.store');
Route::get('hr-appointment-status/getList','HR\HrAppointmentStatusController@getList')->name('HrAppointmentStatus.getList');
Route::post('hr-appointment-status/ActiveInactive','HR\HrAppointmentStatusController@ActiveInactive');
Route::resource('hr-appointment-status','HR\HrAppointmentStatusController')->middleware(['auth','revalidate']);
Route::post('hr-appointment-status/formValidation','HR\HrAppointmentStatusController@formValidation')->name('HrAppointmentStatus.post');

//get sl ajax
Route::post('subsidiary-ledgers/getSL','HR\HrAppointmentStatusController@getSL')->name('getSL');
Route::post('subsidiary-ledgers/getSL/{gl}','HR\HrAppointmentStatusController@getSL')->name('getSL');
Route::post('general-ledgers/getGL','HR\HrAppointmentStatusController@getGL')->name('getGL');

/* HR Holiday Type */
Route::any('hr-holiday-types','HR\HrHolidayTypeController@index')->name('HrHolidayType.index');
Route::get('hr-holiday-types/store','HR\HrHolidayTypeController@store')->name('HrHolidayType.store');
Route::get('hr-holiday-types/getList','HR\HrHolidayTypeController@getList')->name('HrHolidayType.getList');
Route::post('hr-holiday-types/ActiveInactive','HR\HrHolidayTypeController@ActiveInactive');
Route::resource('hr-holiday-types','HR\HrHolidayTypeController')->middleware(['auth','revalidate']);
Route::post('hr-holiday-types/formValidation','HR\HrHolidayTypeController@formValidation')->name('HrHolidayType.post');

Route::get('rpt-assessment-list', 'RptAssessmentListController@index')->name('rptassessment.index');
Route::get('rpt-assessment-list/getList', 'RptAssessmentListController@getList');
Route::post('rpt-assessment-list/ActiveInactive', 'RptAssessmentListController@ActiveInactive');
Route::get('rpt-assessment-list/computebillingdata', 'RptAssessmentListController@computeBillingData');
Route::get('rpt-assessment-list/printbill/{id}', 'RptAssessmentListController@printBill');

/* HR Work Type */
Route::any('hr-work-type','HR\HrWorkTypeController@index')->name('HrWorkType.index');
Route::get('hr-work-type/store','HR\HrWorkTypeController@store')->name('HrWorkType.store');
Route::get('hr-work-type/getList','HR\HrWorkTypeController@getList')->name('HrWorkType.getList');
Route::post('hr-work-type/ActiveInactive','HR\HrWorkTypeController@ActiveInactive');
Route::resource('hr-work-type','HR\HrWorkTypeController')->middleware(['auth','revalidate']);
Route::post('hr-work-type/formValidation','HR\HrWorkTypeController@formValidation')->name('HrWorkType.post');

/*Real Prop Partial Payment*/
Route::get('rpt-partial-payment', 'RptPartialPaymentController@index')->name('rptpartilapayment.index');
Route::get('rpt-partial-payment/getList', 'RptPartialPaymentController@getList')->name('rptpartilapayment.getList');
Route::get('rpt-partial-payment/show', 'RptPartialPaymentController@show');
Route::resource('rpt-partial-payment', 'RptPartialPaymentController')->middleware(['auth','revalidate']);
Route::post('rpt-partial-payment/viewdetails','RptPartialPaymentController@viewdetails')->name('rptpartilapayment.viewdetails'); 

/* HR cpdo Development Permit */
Route::any('cpdodevelopmentapp','Cpdo\CpdoDevelopmentPermitController@index')->name('cpdodevelopment.index');
Route::get('cpdodevelopmentapp/store','Cpdo\CpdoDevelopmentPermitController@store')->name('cpdodevelopment.store');
Route::get('cpdodevelopmentapp/getList','Cpdo\CpdoDevelopmentPermitController@getList')->name('cpdodevelopment.getList');
Route::post('cpdodevelopmentapp/getRequirements', 'Cpdo\CpdoDevelopmentPermitController@getRequirements'); 
Route::post('cpdodevelopmentapp/ActiveInactive','Cpdo\CpdoDevelopmentPermitController@ActiveInactive');
Route::post('cpdodevelopmentapp/storeDevelopbillSummary', 'Cpdo\CpdoDevelopmentPermitController@storeDevelopbillSummary');
Route::post('cpdodevelopmentapp/uploadDocument','Cpdo\CpdoDevelopmentPermitController@uploadDocument');
Route::post('cpdodevelopmentapp/insepectiondeleteAttachment','Cpdo\CpdoDevelopmentPermitController@insepectiondeleteAttachment'); 
Route::post('cpdodevelopmentapp/formValidation','Cpdo\CpdoDevelopmentPermitController@formValidation')->name('cpdodevelopment.post');
Route::post('cpdodevelopmentapp/getServicetype', 'Cpdo\CpdoDevelopmentPermitController@getServicetype')->name('cpdodevelopment.getServicetype');
Route::post('cpdodevelopmentapp/getpaymentlinedit', 'Cpdo\CpdoDevelopmentPermitController@getpaymentlinedit')->name('cpdodevelopment.getpaymentlinedit');
Route::post('getRequirementsAjax','Cpdo\CpdoDevelopmentPermitController@getRequirementsAjax');


Route::get('online-cpdoapplication', 'Cpdo\CpdoDevelopmentPermitController@onlineindex')->name('cpdoapplicationline.index');
Route::get('cpdodevelopmentapp/getListonline', 'Cpdo\CpdoDevelopmentPermitController@getListonline')->name('cpdoapplicationonline.getList');
Route::post('cpdodevelopmentapp/deleteAttachment', 'Cpdo\CpdoDevelopmentPermitController@deleteAttachment');
Route::post('cpdodevelopmentapp/ApproveCertificate', 'Cpdo\CpdoDevelopmentPermitController@ApproveCertificate');
Route::get('cpdodevelopmentapp/getClientsDetails', 'Cpdo\CpdoDevelopmentPermitController@getProfileDetails');
Route::get('cpdodevelopmentapp/printapplication', 'Cpdo\CpdoDevelopmentPermitController@printapplication');
Route::get('cpdodevelopmentapp/printcertificate', 'Cpdo\CpdoDevelopmentPermitController@printcertificate')->name('cpdodevelopment.printcertificate'); 
Route::any('cpdodevelopmentapp/inspectionreport', 'Cpdo\CpdoDevelopmentPermitController@inspectionreport');
Route::any('cpdodevelopmentapp/certification', 'Cpdo\CpdoDevelopmentPermitController@certification');
Route::post('cpdodevelopmentapp/viewrequiremets', 'Cpdo\CpdoDevelopmentPermitController@viewrequiremets')->name('cpdodevelopment.viewrequiremets');
Route::post('cpdodevelopmentapp/saveorderofpayment', 'Cpdo\CpdoDevelopmentPermitController@saveorderofpayment')->name('cpdodevelopment.saveorderofpayment');
Route::post('cpdodevelopmentapp/ApproveInspection', 'Cpdo\CpdoDevelopmentPermitController@ApproveInspection')->name('cpdodevelopment.ApproveInspection');
Route::post('cpdodevelopmentapp/positionbyid', 'Cpdo\CpdoDevelopmentPermitController@positionbyid')->name('cpdodevelopment.positionbyid'); 
Route::post('cpdodevelopmentapp/printorderofpayment', 'Cpdo\CpdoDevelopmentPermitController@printorderofpayment')->name('cpdodevelopment.printorderofpayment'); 
Route::any('cpdodevelopmentapp/printinspection', 'Cpdo\CpdoDevelopmentPermitController@printinspection');

Route::post('cpdodevelopmentapp/formValidation', 'Cpdo\CpdoDevelopmentPermitController@formValidation')->name('cpdodevelopment.post');
Route::post('cpdodevelopmentapp/inspectionreport/formValidation', 'Cpdo\CpdoDevelopmentPermitController@formValidationinspect')->name('cpdodevelopment.post');
Route::resource('cpdodevelopmentapp','Cpdo\CpdoDevelopmentPermitController')->middleware(['auth','revalidate']);
Route::post('cpdodevelopmentapp/certification/formValidation', 'Cpdo\CpdoDevelopmentPermitController@formValidationcerti')->name('cpdodevelopment.formValidationcerti');

// Eng Electrical Fees Pole
Route::any('eng_electricalpole', 'Engneering\EngElectricalFeessPoleAttachmentController@index')->name('Engneering.engelectricalfees.index');
Route::get('eng_electricalpole/getList', 'Engneering\EngElectricalFeessPoleAttachmentController@getList')->name('Engneering.engelectricalfees.getList');
Route::post('eng_electricalpole/ActiveInactive', 'Engneering\EngElectricalFeessPoleAttachmentController@ActiveInactive');
Route::get('eng_electricalpole/store', 'Engneering\EngElectricalFeessPoleAttachmentController@store');
Route::resource('eng_electricalpole', 'Engneering\EngElectricalFeessPoleAttachmentController')->middleware(['auth','revalidate']);
Route::post('eng_electricalpole/formValidation', 'Engneering\EngElectricalFeessPoleAttachmentController@formValidation')->name('engelectricalfees.post');

// Eng Electrical Fees misc
Route::any('eng_electricalmisc', 'Engneering\EngElectricalFeessMiscellaneosController@index')->name('Engneering.engelectricalmisc.index');
Route::get('eng_electricalmisc/getList', 'Engneering\EngElectricalFeessMiscellaneosController@getList')->name('Engneering.engelectricalmisc.getList');
Route::post('eng_electricalmisc/ActiveInactive', 'Engneering\EngElectricalFeessMiscellaneosController@ActiveInactive');
Route::get('eng_electricalmisc/store', 'Engneering\EngElectricalFeessMiscellaneosController@store');
Route::resource('eng_electricalmisc', 'Engneering\EngElectricalFeessMiscellaneosController')->middleware(['auth','revalidate']);
Route::post('eng_electricalmisc/formValidation', 'Engneering\EngElectricalFeessMiscellaneosController@formValidation')->name('engelectricalmisc.post');

/* eng-electrical-fees-load */
Route::any('eng-electrical-fees-load','Engneering\EngElectricalFeesLoadController@index')->name('engelectricalfeesload.index');
Route::get('engelectricalfeesload/store','Engneering\EngElectricalFeesLoadController@store')->name('engelectricalfeesload.store');
Route::get('engelectricalfeesload/getList','Engneering\EngElectricalFeesLoadController@getList')->name('engelectricalfeesload.getList');
Route::post('engelectricalfeesload/ActiveInactive','Engneering\EngElectricalFeesLoadController@ActiveInactive');
Route::resource('engelectricalfeesload','Engneering\EngElectricalFeesLoadController')->middleware(['auth','revalidate']);
Route::post('engelectricalfeesload/formValidation','Engneering\EngElectricalFeesLoadController@formValidation')->name('engelectricalfeesload.post');

/* eng_electrical_fees_ups */
Route::any('eng-electrical-fees-ups','Engneering\EngElectricalFeesupsController@index')->name('engelectricalfeesups.index');
Route::get('engelectricalfeesups/store','Engneering\EngElectricalFeesupsController@store')->name('engelectricalfeesups.store');
Route::get('engelectricalfeesups/getList','Engneering\EngElectricalFeesupsController@getList')->name('engelectricalfeesups.getList');
Route::post('engelectricalfeesups/ActiveInactive','Engneering\EngElectricalFeesupsController@ActiveInactive');
Route::resource('engelectricalfeesups','Engneering\EngElectricalFeesupsController')->middleware(['auth','revalidate']);
Route::post('engelectricalfeesups/formValidation','Engneering\EngElectricalFeesupsController@formValidation')->name('engelectricalfeesups.post');

/* eng-building-permit-fees-category */
Route::any('buildingfeescategory','Engneering\BuildingFeesCategoryController@index')->name('buildingfeescategory.index');
Route::get('buildingfeescategory/store','Engneering\BuildingFeesCategoryController@store')->name('buildingfeescategory.store');
Route::get('buildingfeescategory/getList','Engneering\BuildingFeesCategoryController@getList')->name('buildingfeescategory.getList');
Route::post('buildingfeescategory/ActiveInactive','Engneering\BuildingFeesCategoryController@ActiveInactive');
Route::resource('buildingfeescategory','Engneering\BuildingFeesCategoryController')->middleware(['auth','revalidate']);
Route::post('buildingfeescategory/formValidation','Engneering\BuildingFeesCategoryController@formValidation')->name('buildingfeescategory.post');

/* eng_building_permit_fees_division */
Route::any('buildingfeesdivision','Engneering\BuildingFeesDivisionController@index')->name('buildingfeesdivision.index');
Route::get('buildingfeesdivision/store','Engneering\BuildingFeesDivisionController@store')->name('buildingfeesdivision.store');
Route::get('buildingfeesdivision/getList','Engneering\BuildingFeesDivisionController@getList')->name('buildingfeesdivision.getList');
Route::post('buildingfeesdivision/ActiveInactive','Engneering\BuildingFeesDivisionController@ActiveInactive');
Route::resource('buildingfeesdivision','Engneering\BuildingFeesDivisionController')->middleware(['auth','revalidate']);
Route::post('buildingfeesdivision/formValidation','Engneering\BuildingFeesDivisionController@formValidation')->name('buildingfeesdivision.post');

/* eng_building_permit_fees_set1 */
Route::any('engbuildingpermitfeesset1','Engneering\PermitFeesSet1@index')->name('permitfeesset1.index');
Route::get('engbuildingpermitfeesset1/store','Engneering\EngbuildingPermitFeesSet1Controller@store')->name('permitfeesset1.store');
Route::get('engbuildingpermitfeesset1/getList','Engneering\EngbuildingPermitFeesSet1Controller@getList')->name('permitfeesset1.getList');
Route::post('engbuildingpermitfeesset1/ActiveInactive','Engneering\EngbuildingPermitFeesSet1Controller@ActiveInactive');
Route::resource('engbuildingpermitfeesset1','Engneering\EngbuildingPermitFeesSet1Controller')->middleware(['auth','revalidate']);
Route::post('engbuildingpermitfeesset1/formValidation','Engneering\EngbuildingPermitFeesSet1Controller@formValidation')->name('permitfeesset1.post');

/* eng_building_permit_fees_set2 */
Route::any('engbuildingfeesset2','Engneering\EngPermitFeesSet2Controller@index')->name('permitfeesset2.index');
Route::get('engbuildingfeesset2/store','Engneering\EngPermitFeesSet2Controller@store')->name('permitfeesset2.store');
Route::get('engbuildingfeesset2/getList','Engneering\EngPermitFeesSet2Controller@getList')->name('permitfeesset2.getList');
Route::post('engbuildingfeesset2/ActiveInactive','Engneering\EngPermitFeesSet2Controller@ActiveInactive');
Route::resource('engbuildingfeesset2','Engneering\EngPermitFeesSet2Controller')->middleware(['auth','revalidate']);
Route::post('engbuildingfeesset2/formValidation','Engneering\EngPermitFeesSet2Controller@formValidation')->name('permitfeesset2.post');

/* eng_building_permit_fees_set3 */
Route::any('engbuildingfeesset3','Engneering\EngbuildingPermitFeesSet3Controller@index')->name('permitfeesset3.index');
Route::get('engbuildingfeesset3/store','Engneering\EngbuildingPermitFeesSet3Controller@store')->name('permitfeesset3.store');
Route::get('engbuildingfeesset3/getList','Engneering\EngbuildingPermitFeesSet3Controller@getList')->name('permitfeesset3.getList');
Route::post('engbuildingfeesset3/ActiveInactive','Engneering\EngbuildingPermitFeesSet3Controller@ActiveInactive');
Route::resource('engbuildingfeesset3','Engneering\EngbuildingPermitFeesSet3Controller')->middleware(['auth','revalidate']);
Route::post('engbuildingfeesset3/formValidation','Engneering\EngbuildingPermitFeesSet3Controller@formValidation')->name('permitfeesset3.post');

/* eng_building_permit_fees_set4 */
Route::any('engbuildingfeesset4','Engneering\EngbuildingPermitFeesSet4Controller@index')->name('permitfeesset4.index');
Route::get('engbuildingfeesset4/store','Engneering\EngbuildingPermitFeesSet4Controller@store')->name('permitfeesset4.store');
Route::get('engbuildingfeesset4/getList','Engneering\EngbuildingPermitFeesSet4Controller@getList')->name('permitfeesset4.getList');
Route::post('engbuildingfeesset4/ActiveInactive','Engneering\EngbuildingPermitFeesSet4Controller@ActiveInactive');
Route::resource('engbuildingfeesset4','Engneering\EngbuildingPermitFeesSet4Controller')->middleware(['auth','revalidate']);
Route::post('engbuildingfeesset4/formValidation','Engneering\EngbuildingPermitFeesSet4Controller@formValidation')->name('permitfeesset4.post');

Route::middleware(['auth'])->prefix('hr-payroll-calculate')->group(function () {
    Route::get('', 'HR\PayrollCalculateController@index')->name('hr.payroll.calculate');
    Route::get('store', 'HR\PayrollCalculateController@store')->name('hr.payroll.calculate.store');
    Route::get('view/{id}', 'HR\PayrollCalculateController@view')->name('hr.payroll.calculate.view');
    Route::post('store', 'HR\PayrollCalculateController@store')->name('hr.payroll.calculate.save');
    Route::post('selectEmployees/{division}/{type}', 'HR\PayrollCalculateController@selectEmployees')->name('hr.payroll.calculate.selectEmployees');
    Route::post('getPayroll', 'HR\PayrollCalculateController@getPayroll')->name('hr.payroll.calculate.getPayroll');
    Route::post('selectCutoff', 'HR\PayrollCalculateController@selectCutoff')->name('hr.payroll.calculate.selectCutoff');
    Route::get('getList', 'HR\PayrollCalculateController@getList')->name('hr.payroll.calculate.getList');
    Route::get('view-by-gl/{payroll_no}/{gl_id}', 'HR\PayrollCalculateController@viewByGL')->name('hr.payroll.calculate.view.gl');
    Route::get('printGeneral/{payroll}', 'HR\PayrollCalculateController@printGeneral')->name('hr.payroll.calculate.print.general');
    Route::post('store/formValidation', 'HR\PayrollCalculateController@formValidation')->name('hr.payroll.calculate.formValidation');
});

Route::middleware(['auth'])->prefix('my-payroll')->group(function () {
    Route::get('', 'HR\PayrollCalculateController@employee')->name('my.payroll');
    Route::get('getList', 'HR\PayrollCalculateController@getEmployeeList')->name('my.payroll.getlist');
    Route::get('view', 'HR\PayrollCalculateController@payrollView')->name('my.payroll.getlist');

});

Route::any('ctoorregister', 'Bplo\CtoPaymnetOrRegisterController@index')->name('bplo.orregister.index');
Route::get('ctoorregister/getList', 'Bplo\CtoPaymnetOrRegisterController@getList')->name('bplo.orregister.getList');
Route::post('ctoorregister/ActiveInactive', 'Bplo\CtoPaymnetOrRegisterController@ActiveInactive');
Route::post('ctoorregister/uploadDocument', 'Bplo\CtoPaymnetOrRegisterController@uploadDocument');
Route::post('ctoorregister/deleteAttachment', 'Bplo\CtoPaymnetOrRegisterController@deleteAttachment');

Route::post('ctoorregister/getShortname', 'Bplo\CtoPaymnetOrRegisterController@getShortname');
Route::get('ctoorregister/store', 'Bplo\CtoPaymnetOrRegisterController@store');
Route::resource('ctoorregister', 'Bplo\CtoPaymnetOrRegisterController')->middleware(['auth','revalidate']);
Route::post('ctoorregister/formValidation', 'Bplo\CtoPaymnetOrRegisterController@formValidation')->name('orregister.post');

//------------------------------------Real Property Short Collection---------------------------
 Route::middleware(['auth'])->prefix('report-on-real-property')->group(function () {
    Route::get('/', 'RptAssessmentQuarterlyController@index')->name('rptassquartly.index');
    Route::get('getList', 'RptAssessmentQuarterlyController@getList')->name('rptassquartly.list');
    /*Route::get('getalltds', 'RptAssessmentQuarterlyController@getAllTds')->name('rptassquartly.list');
    Route::any('store', 'RptAssessmentQuarterlyController@store');*/
    /*Route::any('sendEmail', 'RptPaymentFileController@sendEmail');*/
});


/*DTI LIST*/
Route::get('reportsnationalgovdti-lists','Report\BusinessGovDtiListsController@index')->name('reportsnationalgovdti.index');
Route::any('reportsnationalgovdti-lists/getList','Report\BusinessGovDtiListsController@getList')->name('reportsnationalgovdti.getList');
Route::get('export-nationalgovdti-lists','Report\BusinessGovDtiListsController@exportreportsmasterlists')->name('reportsnationalgovdti.export');


/*BIR LIST*/
Route::get('reportsnationalgovbir-lists','Report\BusinessGovBriListsController@index')->name('reportsnationalgovdti.index');
Route::any('reportsnationalgovbir-lists/getList','Report\BusinessGovBriListsController@getList')->name('reportsnationalgovdti.getList');
Route::get('export-reportsnationalgovbir-lists','Report\BusinessGovBriListsController@exportreportsbrilists')->name('reportsnationalgovbri.export');

//---------------------Online bplo appliacatoons-----------------------------------------------
Route::prefix('business-online-application')->group(function () {
    Route::get('', 'OnlineApplicantController@index')->name('bplo.business.index');
    Route::get('lists', 'OnlineApplicantController@getList')->name('bplo.business.lists');
    Route::get('edit/{bus_id}', 'OnlineApplicantController@find')->name('bplo.business.find');
    Route::get('busn_psic_lists/{bus_id}', 'OnlineApplicantController@busn_psic_lists')->name('bplo.business.busn_psic_lists');
    Route::get('requirment_doc_list/{bus_id}', 'OnlineApplicantController@requirment_doc_list')->name('bplo.business.requirment_doc_list');
    Route::get('busn_measure_lists/{bus_id}', 'OnlineApplicantController@busn_measure_lists')->name('bplo.business.busn_measure_lists');//used
    Route::get('refresh_client', 'OnlineApplicantController@refresh_client')->name('bplo.business.refresh_client');
    Route::get('reload_client_det/{id}', 'OnlineApplicantController@reload_client_det')->name('bplo.business.reload_client_det');//used
    Route::get('checkMuncByBrgy/{id}', 'OnlineApplicantController@checkMuncByBrgy')->name('bplo.business.checkMuncByBrgy');
    Route::get('approve/{id}', 'OnlineApplicantController@approve')->name('bplo.business.approve');
    Route::get('decline/{id}', 'OnlineApplicantController@decline')->name('bplo.business.decline');
    Route::get('print-summary/{id}', 'OnlineApplicantController@print_summary')->name('bplo.business.print_summary');
       
});

/*PSA LIST*/
Route::get('reportsnationalgovpsa-lists','Report\BusinessGovPsaListsController@index')->name('reportsnationalgovdti.index');
Route::any('reportsnationalgovpsa-lists/getList','Report\BusinessGovPsaListsController@getList')->name('reportsnationalgovpsa.getList');
Route::get('export-reportsnationalgovpsa-lists','Report\BusinessGovPsaListsController@exportreportsbrilists')->name('reportsnationalgovpsa.export');

/*COLUMN HEADER */
Route::any('setupcolumn-header', 'ReportColumnHeaderController@index')->name('columnheader.index');
Route::get('setupcolumn-header/getList', 'ReportColumnHeaderController@getList')->name('columnheader.getList');
Route::post('setupcolumn-header/ActiveInactive', 'ReportColumnHeaderController@ActiveInactive');
Route::post('setupcolumn-header/getserviceName', 'ReportColumnHeaderController@getserviceName')->name('columnheader.servicename');
Route::post('setupcolumn-header/getTaxfessoption', 'ReportColumnHeaderController@getTaxfessoption')->name('columnheader.getTaxfessoption');
Route::get('setupcolumn-header/store', 'ReportColumnHeaderController@store');
Route::resource('setupcolumn-header', 'ReportColumnHeaderController')->middleware(['auth','revalidate']);
Route::post('setupcolumn-header/formValidation', 'ReportColumnHeaderController@formValidation')->name('columnheader.post');
/*set srs data */
Route::prefix('setup/srs')->group(function () {
    Route::any('', 'ReportSRSController@index')->name('setup.srs.index');
    Route::get('/getList', 'ReportSRSController@getList')->name('setup.srs.getList');
    Route::post('/ActiveInactive', 'ReportSRSController@ActiveInactive');
    Route::post('/getserviceName', 'ReportSRSController@getserviceName')->name('setup.srs.servicename');
    Route::post('/getTaxfessoption', 'ReportSRSController@getTaxfessoption')->name('setup.srs.getTaxfessoption');
    Route::get('/store', 'ReportSRSController@store');
    Route::resource('', 'ReportSRSController')->middleware(['auth','revalidate']);
    Route::post('/formValidation', 'ReportSRSController@formValidation')->name('setup.srs.post');
});


//  --------------------account-receivables-property -----------------------------
Route::get('account-receivables-property','AccountRreceivablesPropertyController@index')->name('accountrreceivablesproperty.index');
Route::get('account-receivables-property/show','AccountRreceivablesPropertyController@show')->name('accountrreceivablesproperty.show');
Route::any('account-receivables-property/getList','AccountRreceivablesPropertyController@getList')->name('accountrreceivablesproperty.getList');
//Route::get('cron-jon/add-update-data-receivables','AccountRreceivablesPropertyController@addUpdateDataReceivables');
Route::get('account-receivables-property/getdetailslist','AccountRreceivablesPropertyController@getDetailsList');
Route::any('account-receivables-property/sendEmail','AccountRreceivablesPropertyController@sendEmail');
Route::get('print-emailPdf/{id}', 'AccountRreceivablesPropertyController@print_emailPdf')->name('mails.business.print_summary');
Route::get('account-receivables-property/generatePaymentPdf', 'AccountRreceivablesPropertyController@generatePaymentPdf');
//http://127.0.0.1:8000/cron-jon/add-update-data-receivables

// ------------------------------------- Online RptPropertyOwner------------------------------
Route::any('taxpayer-online-registration', 'OnlineRptPropertyOwnerController@index')->name('bp.property-owners.index');
// Route::post('taxpayer-online-registration-uploadDocument', 'OnlineRptPropertyOwnerController@uploadDocument')->name('rptpropertyowner.uploadDocument');
// Route::post('taxpayer-online-registration-deleteAttachment', 'OnlineRptPropertyOwnerController@deleteAttachment')->name('rptpropertyowner.deleteAttachment');
//Route::get('rptpropertyowner/index', 'RptPropertyOwnerController@index')->name('rptpropertyowner.index');
// Route::post('taxpayer-online-registration/delete', 'OnlineRptPropertyOwnerController@Delete');
Route::get('taxpayer-online-registration/getClientsDetails', 'OnlineRptPropertyOwnerController@getClientsDetails');
Route::get('taxpayer-online-registration-getProfileDetails', 'OnlineRptPropertyOwnerController@getProfileDetails');
Route::get('taxpayer-online-registration/getClientsDetails', 'OnlineRptPropertyOwnerController@getClientsDetails');
// Route::post('taxpayer-online-registration/ActiveInactive', 'OnlineRptPropertyOwnerController@ActiveInactive');
Route::get('taxpayer-online-registration/getList', 'OnlineRptPropertyOwnerController@getList')->name('rptpropertyowner.getList');
Route::any('taxpayer-online-registration/store', 'OnlineRptPropertyOwnerController@store');
// Route::resource('taxpayer-online-registration', 'OnlineRptPropertyOwnerController')->middleware(['auth','revalidate']);
Route::post('taxpayer-online-registration/approve/{id}', 'OnlineRptPropertyOwnerController@approve')->name('rptpropertyowner.approve');
Route::post('taxpayer-online-registration/decline/{id}', 'OnlineRptPropertyOwnerController@decline')->name('rptpropertyowner.decline');
Route::get('taxpayer-online-registration/get-client-details/{id}', 'OnlineRptPropertyOwnerController@getClientDetails')->name('rptpropertyowner.getClientDetails');
Route::post('taxpayer-online-registration/getBploBusinessList', 'OnlineRptPropertyOwnerController@getBploBusinessList')->name('rptpropertyowner.getBploList');
Route::post('taxpayer-online-registration/engjobrequestList', 'OnlineRptPropertyOwnerController@engjobrequestList')->name('rptpropertyowner.engjobrequestList');
Route::post('taxpayer-online-registration/cpdodevelopmentappList', 'OnlineRptPropertyOwnerController@cpdodevelopmentappList')->name('rptpropertyowner.cpdodevelopmentappList');
Route::post('taxpayer-online-registration/engoccupancyappList', 'OnlineRptPropertyOwnerController@engoccupancyappList')->name('rptpropertyowner.engoccupancyappList');
Route::post('taxpayer-online-registration/realPropertyList', 'OnlineRptPropertyOwnerController@realPropertyList')->name('rptpropertyowner.realPropertyList');

/*Fanancial Report Tax fees Other Charges */
Route::get('reportfanancetaxfeecharges','Report\BploTaxFeeOtherChargesController@index')->name('reporttaxfeecharges.index');
Route::any('reportfanancetaxfeecharges/getList','Report\BploTaxFeeOtherChargesController@getList')->name('reporttaxfeecharges.getList');
Route::get('export-reportfanancetaxfeecharges','Report\BploTaxFeeOtherChargesController@exportreportstfoclists')->name('reporttaxfeecharges.export');

//  ---------------econ-data-cemetery -------------------
Route::prefix('econ-data-cemetery')->group(function () {
        Route::get('', 'EconDataCemeteryController@index')->name('EcoDataCemetery.index');
}); 
Route::get('EcoDataCemetery/index', 'EconDataCemeteryController@index')->name('EcoDataCemetery.index');
Route::get('EcoDataCemetery/getList', 'EconDataCemeteryController@getList')->name('EcoDataCemetery.getList');
Route::get('EcoDataCemetery/store', 'EconDataCemeteryController@store');
Route::post('EcoDataCemetery/ActiveInactive', 'EconDataCemeteryController@ActiveInactive');
Route::post('EcoDataCemetery/delete', 'EconDataCemeteryController@Delete');
Route::post('EcoDataCemetery/defaultUpdateCode', 'EconDataCemeteryController@defaultUpdateCode');
Route::resource('EcoDataCemetery', 'EconDataCemeteryController')->middleware(['auth','revalidate']);
Route::post('EcoDataCemetery/formValidation', 'EconDataCemeteryController@formValidation')->name('EcoDataCemetery.post');

//  ---------------econ-data-cemetery -------------------
Route::prefix('burial-cause-of-death')->group(function () {
        Route::get('', 'EcoCauseOfDeathController@index')->name('ecocauseofdeath.index');
}); 
Route::get('ecocauseofdeath/index', 'EcoCauseOfDeathController@index')->name('ecocauseofdeath.index');
Route::get('ecocauseofdeath/getList', 'EcoCauseOfDeathController@getList')->name('ecocauseofdeath.getList');
Route::get('ecocauseofdeath/store', 'EcoCauseOfDeathController@store');
Route::post('ecocauseofdeath/ActiveInactive', 'EcoCauseOfDeathController@ActiveInactive');
Route::post('ecocauseofdeath/delete', 'EcoCauseOfDeathController@Delete');
Route::post('ecocauseofdeath/defaultUpdateCode', 'EcoCauseOfDeathController@defaultUpdateCode');
Route::resource('ecocauseofdeath', 'EcoCauseOfDeathController')->middleware(['auth','revalidate']);
Route::post('ecocauseofdeath/formValidation', 'EcoCauseOfDeathController@formValidation')->name('ecocauseofdeath.post');



//  ---------------econ-data-cemetery -------------------
Route::prefix('rpt-online-taxpayers')->group(function () {
        Route::get('', 'RptPropertyOnlineAccessController@index')->name('rptpropertyonlineaccess.index');
}); 
Route::get('rptpropertyonlineaccess/index', 'RptPropertyOnlineAccessController@index')->name('rptpropertyonlineaccess.index');
Route::get('rptpropertyonlineaccess/getList', 'RptPropertyOnlineAccessController@getList')->name('rptpropertyonlineaccess.getList');
Route::get('rptpropertyonlineaccess/store', 'RptPropertyOnlineAccessController@store');
Route::post('rptpropertyonlineaccess/ActiveInactive', 'RptPropertyOnlineAccessController@ActiveInactive');
Route::post('rptpropertyonlineaccess/delete', 'RptPropertyOnlineAccessController@Delete');
Route::post('rptpropertyonlineaccess/defaultUpdateCode', 'RptPropertyOnlineAccessController@defaultUpdateCode');
Route::resource('rptpropertyonlineaccess', 'RptPropertyOnlineAccessController')->middleware(['auth','revalidate']);
Route::post('getTaxDeclaresionOnlineDetails', 'RptPropertyOnlineAccessController@getTaxDeclaresionOnlineDetails');
Route::post('getTaxDeclaresionOnlineDetailsAll', 'RptPropertyOnlineAccessController@getTaxDeclaresionOnlineDetailsAll');
Route::post('AddOnlineAccess', 'RptPropertyOnlineAccessController@AddOnlineAccess');
Route::post('deleteOnlineAccess', 'RptPropertyOnlineAccessController@deleteOnlineAccess');
Route::get('rpt-online-taxpayers/view', 'RptPropertyOnlineAccessController@viewDetails');
Route::post('getClientOnlineAccess', 'RptPropertyOnlineAccessController@getClientOnlineAccess');
Route::post('checkExit', 'RptPropertyOnlineAccessController@checkExit');
Route::post('getClientOnlineAccessView', 'RptPropertyOnlineAccessController@getClientOnlineAccessView');
Route::post('rptpropertyonlineaccess/formValidation', 'RptPropertyOnlineAccessController@formValidation')->name('rptpropertyonlineaccess.post');
Route::post('rptpropertyonlineaccess/gettddetails', 'RptPropertyOnlineAccessController@getTdDetails')->name('rptpropertyonlineaccess.gettddetails');

//------------------------------------Business Permit Delinquency And Outstanding Payment---------------------------
 Route::prefix('business-ar')->group(function () {
    Route::get('/', 'Bplo\DelinquencyOutstandingController@index')->name('DelinquencyOutstanding.index');
    Route::get('getList', 'Bplo\DelinquencyOutstandingController@getList')->name('DelinquencyOutstanding.list');
    Route::any('store', 'Bplo\DelinquencyOutstandingController@store');
});
Route::post('getBploTaxpayersAutoSearchList', 'CommonController@getBploTaxpayersAutoSearchList');

//------------------Engineering Online--------------//
// ------------------------------------- Eng Building Permit Service   ------------------------------
Route::get('engjobrequestonline', 'Engneering\EngJobRequestController@index')->name('engjobrequestonline.index');
Route::get('engjobrequestonline/getList', 'Engneering\EngJobRequestOnlineController@getList')->name('engjobrequestonline.getList');
Route::post('engjobrequestonline/ActiveInactive', 'Engneering\EngJobRequestOnlineController@ActiveInactive');
Route::post('engjobrequestonline/deleteFeedetails', 'Engneering\EngJobRequestOnlineController@deleteFeedetails');
Route::post('engjobrequestonline/deleteAttachment', 'Engneering\EngJobRequestOnlineController@deleteAttachment');
Route::get('engjobrequestonline/getClientsDetails', 'Engneering\EngJobRequestOnlineController@getProfileDetails');
Route::get('engjobrequestonline/store', 'Engneering\EngJobRequestOnlineController@store');
Route::get('engjobrequestonline/getsuboccupancytype', 'Engneering\EngJobRequestOnlineController@getsuboccupancytype');
Route::post('engjobrequestonline/savejobreuest', 'Engneering\EngJobRequestOnlineController@savejobreuest');
Route::post('engjobrequestonline/Declineapplication', 'Engneering\EngJobRequestOnlineController@Declineapplication');
Route::post('engjobrequestonline/permitvalidationBuilding', 'Engneering\EngJobRequestOnlineController@PermitValidationBuilding');
Route::post('engjobrequestonline/permitvalidationSanitary', 'Engneering\EngJobRequestOnlineController@permitvalidationSanitary');
Route::post('engjobrequestonline/permitvalidationElectric', 'Engneering\EngJobRequestOnlineController@permitvalidationElectric');
Route::post('engjobrequestonline/permitvalidationElectronic', 'Engneering\EngJobRequestOnlineController@permitvalidationElectronic');
Route::post('engjobrequestonline/permitvalidationMechanical', 'Engneering\EngJobRequestOnlineController@permitvalidationMechanical');
Route::post('engjobrequestonline/permitvalidationExcavation', 'Engneering\EngJobRequestOnlineController@permitvalidationExcavation');
Route::post('engjobrequestonline/permitvalidationCivil', 'Engneering\EngJobRequestOnlineController@permitvalidationCivil');
Route::post('engjobrequestonline/permitvalidationArchitectural', 'Engneering\EngJobRequestOnlineController@permitvalidationArchitectural');
Route::post('engjobrequestonline/permitvalidationFencing', 'Engneering\EngJobRequestOnlineController@permitvalidationFencing'); 
Route::post('engjobrequestonline/permitvalidationSign', 'Engneering\EngJobRequestOnlineController@permitvalidationSign');
Route::post('engjobrequestonline/permitvalidationDemolition', 'Engneering\EngJobRequestOnlineController@permitvalidationDemolition'); 
Route::get('engjobrequestonline/showserviceform', 'Engneering\EngJobRequestOnlineController@showserviceform');
Route::post('engjobrequestonline/saveorderofpayment', 'Engneering\EngJobRequestOnlineController@saveorderofpayment');
Route::post('engjobrequestonline/getApplicationType', 'Engneering\EngJobRequestOnlineController@getApplicationType');
Route::post('engjobrequestonline/showelectricrevisionform', 'Engneering\EngJobRequestOnlineController@showelectricrevisionform');
Route::post('engjobrequestonline/showbuildingrevisionform', 'Engneering\EngJobRequestOnlineController@showbuildingrevisionform');
Route::post('engjobrequestonline/getRequirements', 'Engneering\EngJobRequestOnlineController@getRequirements');
Route::post('engjobrequestonline/MakeapprovePermit', 'Engneering\EngJobRequestOnlineController@MakeapprovePermit');
Route::get('engjobrequestonline/showbuildingappfrom', 'Engneering\EngJobRequestOnlineController@showbuildingappfrom');
Route::get('engjobrequestonline/showelectricpermitform', 'Engneering\EngJobRequestOnlineController@showelectricpermitform');
Route::get('engjobrequestonline/showcivilpermitform', 'Engneering\EngJobRequestOnlineController@showcivilpermitform');
Route::get('engjobrequestonline/showelectronicspermitform', 'Engneering\EngJobRequestOnlineController@showelectronicspermitform');
Route::post('engjobrequestonline/Declineapplication', 'Engneering\EngJobRequestOnlineController@Declineapplication'); 
Route::post('engjobrequestonline/approve', 'Engneering\EngJobRequestOnlineController@approve'); 
Route::post('engjobrequestonline/syncapptoremote', 'Engneering\EngJobRequestOnlineController@syncapptoremote');
Route::post('engjobrequestonline/syncreqtoremote', 'Engneering\EngJobRequestOnlineController@syncreqtoremote'); 
Route::get('engjobrequestonline/showmechanicalpermitform', 'Engneering\EngJobRequestOnlineController@showmechanicalpermitform');
Route::get('engjobrequestonline/showexcavationpermitform', 'Engneering\EngJobRequestOnlineController@showexcavationpermitform');
Route::get('engjobrequestonline/showarchitecturalpermitform', 'Engneering\EngJobRequestOnlineController@showarchitecturalpermitform');
Route::get('engjobrequestonline/showfencingpermitform', 'Engneering\EngJobRequestOnlineController@showfencingpermitform');
Route::get('engjobrequestonline/showsignpermitform', 'Engneering\EngJobRequestOnlineController@showsignpermitform');
Route::get('engjobrequestonline/showdemolitionpermitform', 'Engneering\EngJobRequestOnlineController@showdemolitionpermitform');
//---------------------------------- Eng Sanitary Permit-------------------------------
Route::get('engjobrequestonline/showsanitarypermitform', 'Engneering\EngJobRequestOnlineController@showsanitarypermitform');
Route::get('engjobrequestonline/getConslutant', 'Engneering\EngJobRequestOnlineController@getConsultants');
Route::get('engjobrequestonline/printpermit', 'Engneering\EngJobRequestOnlineController@printpermit');
Route::get('engjobrequestonline/print-permit/{id}', 'Engneering\EngPrintController@print')->name('eng-permit-print');
Route::get('engjobrequestonline/print-sanitary/{id}', 'Engneering\EngPrintController@print_sanitary');//for ken
Route::get('engjobrequestonline/print-mechanical/{id}', 'Engneering\EngPrintController@print_mechanical');//for ken
Route::get('engjobrequestonline/print-order-of-payment/{id}', 'Engneering\EngPrintController@print_order_of_payment')->name('eng-print-order');//for ken)
Route::post('engjobrequestonline/Printorder', 'Engneering\EngJobRequestOnlineController@Printorder');
Route::get('engjobrequestonline/getSignDetails', 'Engneering\EngJobRequestOnlineController@getSignDetails');
Route::get('engjobrequestonline/getApplicant', 'Engneering\EngJobRequestOnlineController@getApplicant');
Route::get('engjobrequestonline/getRptClientDetails', 'Engneering\EngJobRequestOnlineController@getRptClientDetails');
Route::resource('engjobrequestonline', 'Engneering\EngJobRequestOnlineController')->middleware(['auth','revalidate']);
Route::post('engjobrequestonline/formValidation', 'Engneering\EngJobRequestOnlineController@formValidation')->name('engjobrequest.post');

//------------Cpdo Online Application----------//
Route::get('online-cpdoapplication', 'Cpdo\CpdoApplicationOnlineController@index')->name('cpdoapplication.index');
Route::get('online-cpdoapplication', 'Cpdo\CpdoApplicationOnlineController@onlineindex')->name('cpdoapplicationline.index');
Route::post('online-cpdoapplication/getList', 'Cpdo\CpdoApplicationOnlineController@getList')->name('cpdoapplication.getList1');
Route::post('online-cpdoapplication/getRequirements', 'Cpdo\CpdoApplicationOnlineController@getRequirements');
Route::post('online-cpdoapplication/Declineapplication', 'Cpdo\CpdoApplicationOnlineController@Declineapplication'); 
Route::post('online-cpdoapplication/approve', 'Cpdo\CpdoApplicationOnlineController@approve'); 
Route::post('online-cpdoapplication/syncapptoremote', 'Cpdo\CpdoApplicationOnlineController@syncapptoremote'); 
Route::get('online-cpdoapplication/store', 'Cpdo\CpdoApplicationOnlineController@store');
Route::get('online-cpdoapplication/getClientsDetails', 'Cpdo\CpdoApplicationOnlineController@getProfileDetails');
Route::resource('online-cpdoapplication', 'Cpdo\CpdoApplicationOnlineController')->middleware(['auth','revalidate']);
Route::post('online-cpdoapplication/viewrequiremets', 'Cpdo\CpdoApplicationOnlineController@viewrequiremets')->name('onlinecpdoapplication.viewrequiremets');
Route::post('online-cpdoapplication/positionbyid', 'Cpdo\CpdoApplicationOnlineController@positionbyid')->name('onlinecpdoapplication.positionbyid');
Route::post('online-cpdoapplication/getServicetype', 'Cpdo\CpdoApplicationOnlineController@getServicetype')->name('onlinecpdoapplication.getServicetype');


// ------------------------------------- cemetery-style ------------------------------
Route::get('cemetery-style', 'CemeteryStyleController@index')->name('cemeterystyle.index');
Route::get('cemeterystyle/getList', 'CemeteryStyleController@getList')->name('cemeterystyle.getList');
Route::post('cemeterystyle/ActiveInactive', 'CemeteryStyleController@ActiveInactive');
Route::get('cemeterystyle/store', 'CemeteryStyleController@store');
Route::resource('cemeterystyle', 'CemeteryStyleController')->middleware(['auth','revalidate']);
Route::post('cemeterystyle/formValidation', 'CemeteryStyleController@formValidation')->name('cemeterystyle.post');

// ------------------------------------- cemetery-list ------------------------------
Route::post('cemeterylist-lotnolist','CemeteryListController@lotnolist');
Route::post('cemeterylist-getbrgyid','CemeteryListController@getbrgyid');
Route::get('cemetery-list', 'CemeteryListController@index')->name('cemeterylist.index');
Route::get('cemeterylist/getList', 'CemeteryListController@getList')->name('cemeterylist.getList');
Route::post('cemeterylist/ActiveInactive', 'CemeteryListController@ActiveInactive');
Route::get('cemeterylist/store', 'CemeteryListController@store');
Route::resource('cemeterylist', 'CemeteryListController')->middleware(['auth','revalidate']);
Route::post('cemeterylist/formValidation', 'CemeteryListController@formValidation')->name('cemeterylist.post');
Route::post('cemeterystyle/formValidation', 'CemeteryStyleController@formValidation')->name('cemeterystyle.post');

// ------------------------------------- cemeteries-list-details ------------------------------
Route::post('cemeterieslistdetails/savenumberoflot', 'CemeteriesListDetailsController@saveornumberoflot')->name('cemeterieslistdetails.saveornumberoflot');
Route::get('cemeteries-list-details', 'CemeteriesListDetailsController@index')->name('cemeterieslistdetails.index');
Route::get('cemeterieslistdetails/getList', 'CemeteriesListDetailsController@getList')->name('cemeterieslistdetails.getList');
Route::post('cemeterieslistdetails/ActiveInactive', 'CemeteriesListDetailsController@ActiveInactive');
Route::post('cemeterieslistdetails/ActiveInactives', 'CemeteriesListDetailsController@ActiveInactives');
Route::get('cemeterieslistdetails/store', 'CemeteriesListDetailsController@store');
Route::resource('cemeterieslistdetails', 'CemeteriesListDetailsController')->middleware(['auth','revalidate']);
Route::post('cemeterieslistdetails/formValidation', 'CemeteriesListDetailsController@formValidation')->name('cemeterieslistdetails.post');


//--------------------------------occupancy app Online--------
Route::get('engoccupancyapponline', 'Engneering\OccupancyAppOnlineController@index')->name('engoccupancyapponline.index');
Route::get('engoccupancyapponline/getList', 'Engneering\OccupancyAppOnlineController@getList')->name('engoccupancyapponline.getList');
Route::get('engoccupancyapponline/getbuidingdata', 'Engneering\OccupancyAppOnlineController@getbuidingdata');
Route::post('engoccupancyapponline/Declineapplication', 'Engneering\OccupancyAppOnlineController@Declineapplication'); 
Route::post('engoccupancyapponline/approve', 'Engneering\OccupancyAppOnlineController@approve'); 
Route::post('engoccupancyapponline/syncapptoremote', 'Engneering\OccupancyAppOnlineController@syncapptoremote'); 
Route::get('engoccupancyapponline/getClientsDetails', 'Engneering\OccupancyAppOnlineController@getProfileDetails');
Route::get('engoccupancyapponline/store', 'Engneering\OccupancyAppOnlineController@store');
Route::resource('engoccupancyapponline', 'Engneering\OccupancyAppOnlineController')->middleware(['auth','revalidate']);
Route::post('engoccupancyapponline/formValidation', 'Engneering\OccupancyAppOnlineController@formValidation')->name('engoccupancyapponline.post');

// ------------------------------------- eco-application-type ------------------------------
Route::get('eco-application-type', 'EcoapplicationtypeController@index')->name('ecoapplicationtype.index');
Route::get('ecoapplicationtype/getList', 'EcoapplicationtypeController@getList')->name('ecoapplicationtype.getList');
Route::post('ecoapplicationtype/ActiveInactive', 'EcoapplicationtypeController@ActiveInactive');
Route::post('ecoapplicationtype/ActiveInactives', 'EcoapplicationtypeController@ActiveInactives');
Route::get('ecoapplicationtype/store', 'EcoapplicationtypeController@store');
Route::resource('ecoapplicationtype', 'EcoapplicationtypeController')->middleware(['auth','revalidate']);
Route::post('ecoapplicationtype/formValidation', 'EcoapplicationtypeController@formValidation')->name('ecoapplicationtype.post');

// ------------------------------------- receptions ------------------------------
Route::get('receptions', 'ReceptionsController@index')->name('receptions.index');
Route::get('receptions/getList', 'ReceptionsController@getList')->name('receptions.getList');
Route::post('receptions/ActiveInactive', 'ReceptionsController@ActiveInactive');
Route::post('receptions/ActiveInactives', 'ReceptionsController@ActiveInactives');
Route::get('receptions/store', 'ReceptionsController@store');
Route::resource('receptions', 'ReceptionsController')->middleware(['auth','revalidate']);
Route::post('receptions/formValidation', 'ReceptionsController@formValidation')->name('receptions.post');


Route::any('cemetery-cashering', 'EconAndInvestment\CemeteryCasheringController@getList')->name('cemeterycashering.index');
Route::get('cemetery-cashering/getList', 'EconAndInvestment\CemeteryCasheringController@getList')->name('cemeterycashering.getList');
Route::post('cemetery-cashering/ActiveInactive', 'EconAndInvestment\CemeteryCasheringController@ActiveInactive');
Route::post('cemetery-cashering/getOrnumber', 'EconAndInvestment\CemeteryCasheringController@getOrnumber');
Route::post('cemetery-cashering/cancelorpayment', 'EconAndInvestment\CemeteryCasheringController@cancelOrPayment');
Route::post('cemetery-cashering/cancelOr', 'EconAndInvestment\CemeteryCasheringController@cancelOr');
Route::post('cemetery-cashering/getamountinword', 'EconAndInvestment\CemeteryCasheringController@getamountinword');
Route::get('cemetery-cashering/updatecashierfullname', 'EconAndInvestment\CemeteryCasheringController@updatecashierfullname');
Route::post('cemetery-cashering/checkOrInrange', 'CommonController@checkOrInrange');
Route::post('cemetery-cashering/checkOrUsedOrNot', 'EconAndInvestment\CemeteryCasheringController@checkOrUsedOrNot');
Route::get('cemetery-cashering/store', 'EconAndInvestment\CemeteryCasheringController@store');
Route::get('cemetery-cashering/printReceipt', 'EconAndInvestment\CemeteryCasheringController@printReceipt');
Route::resource('cemetery-cashering', 'EconAndInvestment\CemeteryCasheringController')->middleware(['auth','revalidate']);
Route::post('cemetery-cashering/getallFees', 'EconAndInvestment\CemeteryCasheringController@getallFees');
Route::post('cemetery-cashering/getbillingdetails', 'EconAndInvestment\CemeteryCasheringController@getbillingdetails');
Route::post('cemetery-cashering/getUserbytoid', 'EconAndInvestment\CemeteryCasheringController@getUserbytoid');
Route::post('cemetery-cashering/getTransactionbytype', 'EconAndInvestment\CemeteryCasheringController@getTransactionbytype');
Route::post('cemetery-cashering/getClientsbussiness', 'EconAndInvestment\CemeteryCasheringController@getClientsbussiness');  
Route::post('cemetery-cashering/getClientsDropdown', 'EconAndInvestment\CemeteryCasheringController@getClientsDropdown');
Route::post('cemetery-cashering/formValidation', 'EconAndInvestment\CemeteryCasheringController@formValidation')->name('cemeterycashering.post');

 /* Accounting trans Type */
 Route::any('type-of-transaction','Accounting\AcctgTypeOfTransactionController@index')->name('LegaltypeTransaction.index');
 Route::get('type-of-transaction/store','Accounting\AcctgTypeOfTransactionController@store')->name('LegaltypeTransaction.store');
 Route::get('type-of-transaction/getList','Accounting\AcctgTypeOfTransactionController@getList')->name('LegaltypeTransaction.getList');
 Route::post('type-of-transaction/ActiveInactive','Accounting\AcctgTypeOfTransactionController@ActiveInactive');
 Route::resource('type-of-transaction','Accounting\AcctgTypeOfTransactionController')->middleware(['auth','revalidate']);
 Route::post('type-of-transaction/formValidation','Accounting\AcctgTypeOfTransactionController@formValidation')->name('LegaltypeTransaction.post');
 /* Accounting resdential name */
 Route::any('residential-name','Accounting\AcctgResidentialController@index')->name('LegalResidentialName.index');
 Route::get('residential-name/store','Accounting\AcctgResidentialController@store')->name('LegalResidentialName.store');
 Route::get('residential-name/getList','Accounting\AcctgResidentialController@getList')->name('LegalResidentialName.getList');
 Route::post('residential-name/ActiveInactive','Accounting\AcctgResidentialController@ActiveInactive');
 Route::resource('residential-name','Accounting\AcctgResidentialController')->middleware(['auth','revalidate']);
 Route::post('residential-name/formValidation','Accounting\AcctgResidentialController@formValidation')->name('LegalResidentialName.post');
// ------------------------------------- residential housing list ------------------------------
Route::any('residential-housing-list', 'Accounting\ResidentialHousingListController@index')->name('LegalResidentialLocation.index');
Route::get('residential-housing-list/getList', 'Accounting\ResidentialHousingListController@getList')->name('LegalResidentialLocation.getList');
Route::post('residential-housing-list/ActiveInactive', 'Accounting\ResidentialHousingListController@ActiveInactive');
Route::get('residential-housing-list/store', 'Accounting\ResidentialHousingListController@store');
Route::resource('residential-housing-list', 'Accounting\ResidentialHousingListController')->middleware(['auth','revalidate']);
Route::post('residential-housing-list/formValidation', 'Accounting\ResidentialHousingListController@formValidation')->name('LegalResidentialLocation.post');
Route::post('residential-housing-list/lotnolist','Accounting\ResidentialHousingListController@lotnolist');
Route::post('residential-housing-list/getbrgyid','Accounting\ResidentialHousingListController@getbrgyid');
Route::post('residential-housing-list/savenumberoflot', 'Accounting\ResidentialHousingListController@saveornumberoflot')->name('LegalResidentialLocation.saveornumberoflot');
// ------------------------------------- residential housing-list-details ------------------------------
Route::get('LegalResidentialLocDetails/getList', 'Accounting\LegalResidentialLocDetailsController@getList')->name('cemeterieslistdetails.getList');
Route::post('LegalResidentialLocDetails/ActiveInactive', 'Accounting\LegalResidentialLocDetailsController@ActiveInactive');
// ------------------------------------- legal housing application ------------------------------
Route::prefix('legal-housing-application')->group(function () {
    Route::any('/','Accounting\AcctgLegalHousingAppController@index')->name('LegalHousingApplication.index');
    Route::get('store','Accounting\AcctgLegalHousingAppController@store')->name('LegalHousingApplication.store');
    Route::get('getList','Accounting\AcctgLegalHousingAppController@getList')->name('LegalHousingApplication.getList');
    Route::post('ActiveInactive','Accounting\AcctgLegalHousingAppController@ActiveInactive');
    Route::resource('/','Accounting\AcctgLegalHousingAppController')->middleware(['auth','revalidate']);
    Route::post('formValidation','Accounting\AcctgLegalHousingAppController@formValidation')->name('LegalHousingApplication.post');
    Route::get('getClientDetails/{id}','Accounting\AcctgLegalHousingAppController@getClientDetails')->name('LegalHousingApplication.getClientDetails');
    Route::post('getPhase/{residential_id}', 'Accounting\AcctgLegalHousingAppController@getPhase')->name('LegalResidentialLocation.getPhase');
    Route::post('getBlk/{phase_id}', 'Accounting\AcctgLegalHousingAppController@getBlk')->name('LegalResidentialLocation.getBlk');
    Route::get('printOrderPayment/{data}','AcctgCollectionReportController@housingPrint')->name('LegalHousingApplication.getClientDetails');
    Route::get('printBreakdown/{data}','Accounting\AcctgLegalHousingAppController@breakdownPrint')->name('LegalHousingApplication.printBreakdown');
    Route::get('triggerPenalties','Accounting\AcctgLegalHousingAppController@triggerPenalties')->name('LegalHousingApplication.triggerPenalties');
    // Route::get('printOrderPayment/{id}','AcctgCollectionReportController@housingPrint')->name('LegalHousingApplication.getClientDetails');
});
//dashboard
Route::get('/bplo-dashboard','DashboardController@bplo_dashboard_index')->name('bplo-dashboard');
Route::get('/engineering-dashboard','DashboardController@engineering_dashboard_index')->name('engineering-dashboard');
Route::get('/rpt-dashboard','DashboardController@rpt_dashboard_index')->name('rpt-dashboard');
Route::get('/occupancy-dashboard','DashboardController@occupancy_dashboard_index')->name('occupancy-dashboard');
Route::get('/cpdo-dashboard','DashboardController@cpdo_dashboard_index')->name('cpdo-dashboard');
Route::get('/load-dashboard', 'DashboardController@load_dashboard_index')->name('load.dashboard');

// ------------------------------------- dashboard-group-menu ------------------------------
Route::get('dashboard-group-menu', 'DashboardGroupMenuController@index')->name('dashboardgroupmenu.index');
Route::get('dashboardgroupmenu/getList', 'DashboardGroupMenuController@getList')->name('dashboardgroupmenu.getList');
Route::post('dashboardgroupmenu/ActiveInactive', 'DashboardGroupMenuController@ActiveInactive');
Route::get('dashboardgroupmenu/store', 'DashboardGroupMenuController@store');
Route::resource('dashboardgroupmenu', 'DashboardGroupMenuController')->middleware(['auth','revalidate']);
Route::post('dashboardgroupmenu/formValidation', 'DashboardGroupMenuController@formValidation')->name('dashboardgroupmenu.post');

// ------------------------------------- Cemetary Account Receivable ------------------------------
Route::get('cemetery-ar', 'Treasury\AccountReceivableCemeteryController@index')->name('cemeteryar.index');
Route::get('cemetery-ar/getList', 'Treasury\AccountReceivableCemeteryController@getList')->name('cemeteryar.getList');
Route::get('cemetery-ar/exportarcemetery', 'Treasury\AccountReceivableCemeteryController@exportarcemetery');
Route::get('cemetery-ar/getpaymentlist', 'Treasury\AccountReceivableCemeteryController@getpaymentlist');
Route::get('cemetery-ar/store', 'Treasury\AccountReceivableCemeteryController@store');
Route::resource('cemetery-ar', 'Treasury\AccountReceivableCemeteryController')->middleware(['auth','revalidate']);
Route::post('cemetery-ar/formValidation', 'Treasury\AccountReceivableCemeteryController@formValidation')->name('cemeteryar.post');

// ------------------------------------- Rental Account Receivable ------------------------------
Route::get('rental-ar', 'Treasury\AccountReceivableRentalController@index')->name('rentalar.index');
Route::get('rental-ar/getList', 'Treasury\AccountReceivableRentalController@getList')->name('rentalar.getList');
Route::post('rental-ar/ActiveInactive', 'Treasury\AccountReceivableRentalController@ActiveInactive');
Route::get('rental-ar/getpaymentlist', 'Treasury\AccountReceivableRentalController@getpaymentlist');
Route::get('rental-ar/store', 'Treasury\AccountReceivableRentalController@store');
Route::resource('rental-ar', 'Treasury\AccountReceivableRentalController')->middleware(['auth','revalidate']);
Route::post('rental-ar/formValidation', 'Treasury\AccountReceivableRentalController@formValidation')->name('rentalar.post');

// ------------------------------------- Housing Account Receivable ------------------------------
Route::get('housing-ar', 'Treasury\AccountReceivableHousingController@index')->name('housingar.index');
Route::get('housing-ar/getList', 'Treasury\AccountReceivableHousingController@getList')->name('housingar.getList');
Route::post('housing-ar/ActiveInactive', 'Treasury\AccountReceivableHousingController@ActiveInactive');
Route::get('housing-ar/getpaymentlist', 'Treasury\AccountReceivableHousingController@getpaymentlist');
Route::get('housing-ar/store', 'Treasury\AccountReceivableHousingController@store');
Route::resource('housing-ar', 'Treasury\AccountReceivableHousingController')->middleware(['auth','revalidate']);
Route::post('housing-ar/formValidation', 'Treasury\AccountReceivableHousingController@formValidation')->name('housingar.post');

// ------------------------------------- dashboard-group-menu ------------------------------
Route::get('userloginlogs','UserLoginLogsController@index')->name('userloginlogs.index');
Route::get('userloginlogs/getList','UserLoginLogsController@getList')->name('userloginlogs.getList');
Route::post('userloginlogs/ActiveInactive','UserLoginLogsController@ActiveInactive');
Route::get('userloginlogs/store','UserLoginLogsController@store');
Route::resource('userloginlogs','UserLoginLogsController')->middleware(['auth','revalidate']);
Route::post('userloginlogs/formValidation','UserLoginLogsController@formValidation')->name('userloginlogs.post');
// ------------------------------------- cron-job ------------------------------
Route::get('cron-job', 'CronJobController@index')->name('cron-job.index');
Route::get('cron-job/getList', 'CronJobController@getList')->name('cron-job.getList');
Route::post('cron-job/ActiveInactive', 'CronJobController@ActiveInactive');
Route::get('cron-job/store', 'CronJobController@store');
Route::resource('cron-job', 'CronJobController')->middleware(['auth','revalidate']);
Route::post('cron-job/formValidation', 'CronJobController@formValidation')->name('cron-job.post');
Route::post('cron-job/getScheduleVal','CronJobController@getScheduleVal');
Route::post('cron-job/quickRunCron','CronJobController@quickRunCron');
//-------------------------------------- online-development-permit --------------------------
Route::get('online-development-permit', 'Cpdo\CpdoDevelopmentPermitController@onlineindex')->name('cpdoDevelopmentPermitOnline.index');
Route::get('online-development-permit/getList','Cpdo\CpdoDevelopmentPermitController@onlineGetList')->name('cpdoDevelopmentPermitOnline.getList');
Route::get('online-development-permit/store','Cpdo\CpdoDevelopmentPermitController@onlineStore')->name('cpdoDevelopmentPermitOnline.store');
Route::post('online-development-permit/approve/{id}','Cpdo\CpdoDevelopmentPermitController@approve')->name('cpdoDevelopmentPermitOnline.approve');
Route::post('online-development-permit/decline/{id}','Cpdo\CpdoDevelopmentPermitController@decline')->name('cpdoDevelopmentPermitOnline.decline');

// ------------------------------------- dashboard-group-menu ------------------------------
Route::get('cpdopenalties', 'Cpdo\CpdoPenaltiesController@index')->name('cpdopenalties.index');
Route::get('cpdopenalties/getList', 'Cpdo\CpdoPenaltiesController@getList')->name('cpdopenalties.getList');
Route::post('cpdopenalties/ActiveInactive', 'Cpdo\CpdoPenaltiesController@ActiveInactive');
Route::get('cpdopenalties/store', 'Cpdo\CpdoPenaltiesController@store');
Route::resource('cpdopenalties', 'Cpdo\CpdoPenaltiesController')->middleware(['auth','revalidate']);
Route::post('cpdopenalties/formValidation', 'Cpdo\CpdoPenaltiesController@formValidation')->name('cpdopenalties.post');
/* IP Security Manage */
Route::any('ip-security-manage','IpSecurityManageController@index')->name('IpSecurityManage.index');
Route::get('ip-security-manage/store','IpSecurityManageController@store')->name('IpSecurityManage.store');
Route::get('ip-security-manage/getList','IpSecurityManageController@getList')->name('IpSecurityManage.getList');
Route::post('ip-security-manage/ActiveInactive','IpSecurityManageController@ActiveInactive');
Route::resource('ip-security-manage','IpSecurityManageController')->middleware(['auth','revalidate']);
Route::post('ip-security-manage/formValidation','IpSecurityManageController@formValidation')->name('IpSecurityManage.post');
Route::get('get_current_ip_address','IpSecurityManageController@get_current_ip_address')->name('IpSecurityManage.CurrentIpAddress');
Route::post('ip-security-manage/update-ip-settings','IpSecurityManageController@updateIpSettings')->name('IpSecurityManage.updateIpSettings');

/* IP Security Exclusions */
Route::any('ip-security-exclusion','IpSecurityExclusionController@index')->name('IpSecurityExclusion.index');
Route::get('ip-security-exclusion/store','IpSecurityExclusionController@store')->name('IpSecurityExclusion.store');
Route::get('ip-security-exclusion/getList','IpSecurityExclusionController@getList')->name('IpSecurityExclusion.getList');
Route::post('ip-security-exclusion/ActiveInactive','IpSecurityExclusionController@ActiveInactive');
Route::resource('ip-security-exclusion','IpSecurityExclusionController')->middleware(['auth','revalidate']);
Route::post('ip-security-exclusion/formValidation','IpSecurityExclusionController@formValidation')->name('IpSecurityExclusion.post');
Route::get('ip-security-exclusion/get_employee_details/{id}','IpSecurityExclusionController@get_employee_details')->name('IpSecurityExclusion.getEmployeeDetails');

// ------------------------------------- Real Property Account Receiveables Setup ------------------------------
Route::middleware(['auth'])->prefix('real-property-ar-setup')->group(function () {
          Route::get('', 'CtoAccountsReceivableSetupsController@index')->name('realpropertyarsetup.index');
});
Route::get('realpropertyarsetup/getList', 'CtoAccountsReceivableSetupsController@getList')->name('realpropertyarsetup.getList');
Route::get('realpropertyarsetup/store', 'CtoAccountsReceivableSetupsController@store');
Route::resource('realpropertyarsetup', 'CtoAccountsReceivableSetupsController')->middleware(['auth','revalidate']);

Route::post('realpropertyarsetup/formValidation', 'CtoAccountsReceivableSetupsController@formValidation');
//widget template
Route::any('widgets-template','WidgetsTemplateController@index')->name('WidgetsTemplate.index');
/* Signing settings */
Route::any('signing-settings','SigningSettingsController@index')->name('SigningSettings.index');
Route::get('signing-settings/store','SigningSettingsController@store')->name('SigningSettings.store');
Route::get('signing-settings/getList','SigningSettingsController@getList')->name('SigningSettings.getList');
Route::post('signing-settings/ActiveInactive','SigningSettingsController@ActiveInactive');
Route::resource('signing-settings','SigningSettingsController')->middleware(['auth','revalidate']);
Route::post('signing-settings/formValidation','SigningSettingsController@formValidation')->name('SigningSettings.post');
Route::post('signing-settings/update-signing-settings','SigningSettingsController@updateSigningSettings')->name('SigningSettings.updateSigningSettings');
Route::get('get_sub_module','SigningSettingsController@get_sub_module')->name('SigningSettings.get_sub_module');

/* BAC Designation */
Route::any('bac-designations','GsoBacDesignationsController@index')->name('GsoBacDesignations.index');
Route::get('bac-designations/store','GsoBacDesignationsController@store')->name('GsoBacDesignations.store');
Route::get('bac-designations/getList','GsoBacDesignationsController@getList')->name('GsoBacDesignations.getList');
Route::post('bac-designations/ActiveInactive','GsoBacDesignationsController@ActiveInactive');
Route::resource('bac-designations','GsoBacDesignationsController')->middleware(['auth','revalidate']);
Route::post('bac-designations/formValidation','GsoBacDesignationsController@formValidation')->name('GsoBacDesignations.post');
Route::get('get_emp_dept','GsoBacDesignationsController@get_emp_dept')->name('GsoBacDesignations.get_emp_dept');

Route::get('online-payment-history', 'onlinePaymentHistoryController@index')->name('onlinePaymentHistory.index');
Route::get('online-payment-history/getList','onlinePaymentHistoryController@getList')->name('onlinePaymentHistory.getList');
Route::get('online-payment-history/store','onlinePaymentHistoryController@store')->name('onlinePaymentHistory.store');
Route::get('online-payment-history/showcpdo','onlinePaymentHistoryController@showcpdo')->name('onlinePaymentHistory.showcpdo');

Route::get('online-payment-history/showbplo','onlinePaymentHistoryController@showbplo')->name('onlinePaymentHistory.showbplo');
Route::get('online-payment-history/showeng','onlinePaymentHistoryController@showeng')->name('onlinePaymentHistory.showeng');
Route::get('online-payment-history/showoccu','onlinePaymentHistoryController@showoccu')->name('onlinePaymentHistory.showoccu');
Route::get('online-payment-history/showrealproperty','onlinePaymentHistoryController@showrealproperty')->name('onlinePaymentHistory.showrealproperty');
Route::get('online-payment-history/loadacceptedtds','onlinePaymentHistoryController@loadacceptedtds');
Route::get('online-payment-history/loadcasheringinfo','onlinePaymentHistoryController@loadcasheringinfo');
Route::get('online-payment-history/approveView','onlinePaymentHistoryController@approveView')->name('onlinePaymentHistory.approveView');

Route::post('online-payment-history/approve','onlinePaymentHistoryController@approve')->name('onlinePaymentHistory.approve');
Route::post('online-payment-history/decline','onlinePaymentHistoryController@decline')->name('onlinePaymentHistory.decline');

// ------------------------------------- dashboard-group-menu ------------------------------
Route::get('special-access-for-apps', 'SpecialAccessforAppsController@index')->name('special-access-for-apps.index');
Route::get('special-access-for-apps/getList', 'SpecialAccessforAppsController@getList')->name('special-access-for-apps.getList');
Route::post('special-access-for-apps/ActiveInactive', 'SpecialAccessforAppsController@ActiveInactive');
Route::get('special-access-for-apps/store', 'SpecialAccessforAppsController@store');
Route::resource('special-access-for-apps', 'SpecialAccessforAppsController')->middleware(['auth','revalidate']);
Route::post('special-access-for-apps/formValidation', 'SpecialAccessforAppsController@formValidation')->name('special-access-for-apps.post');
Route::get('get_sub_module_list','SpecialAccessforAppsController@getsubmodule')->name('special-access-for-apps.get_sub_module_list');
Route::get('getmoduleid','SpecialAccessforAppsController@get_module_id')->name('special-access-for-apps.getmoduleid');