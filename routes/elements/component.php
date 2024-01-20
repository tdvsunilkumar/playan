<?php 


use Illuminate\Support\Facades\Route;

// --------------------------- Components Routes -----------------------------
Route::middleware(['auth'])->prefix('components')->group(function () {
    /* FAQ Routes */
    Route::prefix('faqs')->group(function () {
        Route::get('', 'ComponentFAQController@index')->name('component.faq.index');
        Route::get('lists', 'ComponentFAQController@lists')->name('component.faq.lists');
        Route::post('uploads', 'ComponentFAQController@uploads')->name('component.faq.uploads');
        Route::post('store', 'ComponentFAQController@store')->name('component.faq.store');
        Route::get('edit/{id}', 'ComponentFAQController@find')->name('component.faq.find');
        Route::post('update/{id}', 'ComponentFAQController@update')->name('component.faq.update');
        Route::put('remove/{id}', 'ComponentFAQController@remove')->name('component.faq.remove');
        Route::put('restore/{id}', 'ComponentFAQController@restore')->name('component.faq.restore');
        Route::post('update-order', 'ComponentFAQController@update_order')->name('component.faq.update-order');
    });

    /* Menu Routes */
    Route::prefix('menus')->group(function () {
        /* Groups Routes */
        Route::prefix('groups')->group(function () {
            Route::get('', 'ComponentMenuGroupController@index')->name('component.menu-group.index');
            Route::get('lists', 'ComponentMenuGroupController@lists')->name('component.menu-group.lists');
            Route::post('store', 'ComponentMenuGroupController@store')->name('component.menu-group.store');
            Route::get('edit/{id}', 'ComponentMenuGroupController@find')->name('component.menu-group.find');
            Route::put('update/{id}', 'ComponentMenuGroupController@update')->name('component.menu-group.update');
            Route::put('remove/{id}', 'ComponentMenuGroupController@remove')->name('component.menu-group.remove');
            Route::put('restore/{id}', 'ComponentMenuGroupController@restore')->name('component.menu-group.restore');
            Route::put('order/{order}/{id}', 'ComponentMenuGroupController@order')->name('component.menu-group.order');
            Route::post('update-order', 'ComponentMenuGroupController@update_order')->name('component.menu-group.update-order');
        });

        /* Modules Routes */
        Route::prefix('modules')->group(function () {
            Route::get('', 'ComponentMenuModuleController@index')->name('component.menu-module.index');
            Route::get('lists', 'ComponentMenuModuleController@lists')->name('component.menu-module.lists');
            Route::post('store', 'ComponentMenuModuleController@store')->name('component.menu-module.store');
            Route::get('edit/{id}', 'ComponentMenuModuleController@find')->name('component.menu-module.find');
            Route::put('update/{id}', 'ComponentMenuModuleController@update')->name('component.menu-module.update');
            Route::put('remove/{id}', 'ComponentMenuModuleController@remove')->name('component.menu-module.remove');
            Route::put('restore/{id}', 'ComponentMenuModuleController@restore')->name('component.menu-module.restore');
            Route::put('order/{order}/{id}', 'ComponentMenuModuleController@order')->name('component.menu-module.order');
            Route::post('update-order', 'ComponentMenuModuleController@update_order')->name('component.menu-module.update-order');
        });

        /* Sub Module Routes */
        Route::prefix('sub-modules')->group(function () {
            Route::get('', 'ComponentMenuSubModuleController@index')->name('component.menu-sub-module.index');
            Route::get('lists', 'ComponentMenuSubModuleController@lists')->name('component.menu-sub-module.lists');
            Route::post('store', 'ComponentMenuSubModuleController@store')->name('component.menu-sub-module.store');
            Route::get('edit/{id}', 'ComponentMenuSubModuleController@find')->name('component.menu-sub-module.find');
            Route::put('update/{id}', 'ComponentMenuSubModuleController@update')->name('component.menu-sub-module.update');
            Route::put('remove/{id}', 'ComponentMenuSubModuleController@remove')->name('component.menu-sub-module.remove');
            Route::put('restore/{id}', 'ComponentMenuSubModuleController@restore')->name('component.menu-sub-module.restore');
            Route::put('order/{order}/{id}', 'ComponentMenuSubModuleController@order')->name('component.menu-sub-module.order');
            Route::post('update-order', 'ComponentMenuSubModuleController@update_order')->name('component.menu-sub-module.update-order');
        });
    });

    /* Permission Routes */
    Route::prefix('permissions')->group(function () {
        Route::get('', 'ComponentPermissionController@index')->name('components.permissions.index');
        Route::get('lists', 'ComponentPermissionController@lists')->name('components.permissions.lists');
        Route::post('store', 'ComponentPermissionController@store')->name('components.permissions.store');
        Route::get('edit/{id}', 'ComponentPermissionController@find')->name('components.permissions.find');
        Route::put('update/{id}', 'ComponentPermissionController@update')->name('components.permissions.update');
        Route::put('remove/{id}', 'ComponentPermissionController@remove')->name('components.permissions.remove');
        Route::put('restore/{id}', 'ComponentPermissionController@restore')->name('components.permissions.restore');
        Route::put('order/{order}/{id}', 'ComponentPermissionController@order')->name('components.permissions.order');    
    });

    /* Users Routes */
    Route::prefix('users')->group(function () {
        /* Account Routes */
        Route::prefix('accounts')->group(function () {
            Route::get('', 'ComponentUserAccountController@index')->name('components.users.accounts.index');
            Route::get('lists', 'ComponentUserAccountController@lists')->name('components.users.accounts.lists');
            Route::post('store', 'ComponentUserAccountController@store')->name('components.users.accounts.store');
            Route::get('edit/{id}', 'ComponentUserAccountController@find')->name('components.users.accounts.find');
            Route::get('editDeshPermission/{id}', 'ComponentUserAccountController@editDeshPermission')->name('components.users.accounts.editDeshPermission');
            Route::put('update/{id}', 'ComponentUserAccountController@update')->name('components.users.accounts.update');
            Route::put('remove/{id}', 'ComponentUserAccountController@remove')->name('components.users.accounts.remove');
            Route::put('restore/{id}', 'ComponentUserAccountController@restore')->name('components.users.accounts.restore');
            Route::put('updateDash/{id}', 'ComponentUserAccountController@updateDash')->name('components.users.accounts.updateDash');
            Route::post('storeDash', 'ComponentUserAccountController@storeDash')->name('components.users.accounts.storeDash');
        });

        /* Role Routes */
        Route::prefix('roles')->group(function () {
            Route::get('', 'ComponentUserRoleController@index')->name('components.users.roles.index');
            Route::get('lists', 'ComponentUserRoleController@lists')->name('components.users.roles.lists');
            Route::post('store', 'ComponentUserRoleController@store')->name('components.users.roles.store');
            Route::get('edit/{id}', 'ComponentUserRoleController@find')->name('components.users.roles.find');
            Route::put('update/{id}', 'ComponentUserRoleController@update')->name('components.users.roles.update');
            Route::put('remove/{id}', 'ComponentUserRoleController@remove')->name('components.users.roles.remove');
            Route::put('restore/{id}', 'ComponentUserRoleController@restore')->name('components.users.roles.restore');
            Route::get('load-menus/{id}/{user}', 'ComponentUserRoleController@load_menus')->name('components.users.roles.load_menus');
            Route::get('load-menus-dash/{id}/{user}', 'ComponentUserRoleController@load_menus_dash')->name('components.users.roles.load_menus_dash');
        });
    });

    /* Approval Setting Routes */
    Route::prefix('approval-settings')->group(function () {
        Route::get('', 'ComponentApprovalSettingController@index')->name('components.approval-setting.index');
        Route::get('lists', 'ComponentApprovalSettingController@lists')->name('components.approval-setting.lists');
        Route::post('store', 'ComponentApprovalSettingController@store')->name('components.approval-setting.store');
        Route::get('edit/{id}', 'ComponentApprovalSettingController@find')->name('components.approval-setting.find');
        Route::post('update/{id}', 'ComponentApprovalSettingController@modify')->name('components.approval-setting.modify');
        Route::put('remove/{id}', 'ComponentApprovalSettingController@remove')->name('components.approval-setting.remove');
        Route::put('restore/{id}', 'ComponentApprovalSettingController@restore')->name('components.approval-setting.restore');
        Route::get('reload-sub-module/{module}', 'ComponentApprovalSettingController@reload_sub_module')->name('components.approval-setting.reload-sub-module');
    });

    /* Groups Routes */
    Route::prefix('sms-notifications')->group(function () {
        Route::get('', 'ComponentSMSNotificationController@index')->name('component.sms-notification.index');
        Route::get('new', 'ComponentSMSNotificationController@new')->name('component.sms-notification.index');
        Route::get('settings', 'ComponentSMSNotificationController@settings')->name('component.sms-notification.settings');
        Route::get('settings/edit/{id}', 'ComponentSMSNotificationController@find_setting')->name('component.sms-notification.find-setting');
        Route::post('settings/store', 'ComponentSMSNotificationController@store_setting')->name('component.sms-notification.store-setting');
        Route::put('settings/update/{id}', 'ComponentSMSNotificationController@update_setting')->name('component.sms-notification.update-setting');
        Route::put('settings/remove/{id}', 'ComponentSMSNotificationController@remove_setting')->name('component.sms-notification.remove-setting');
        Route::put('settings/restore/{id}', 'ComponentSMSNotificationController@restore_setting')->name('component.sms-notification.restore-setting');
        Route::put('settings/update', 'ComponentSMSNotificationController@update_server_settings')->name('component.sms-notification.update-server-settings');
        Route::get('settings-lists', 'ComponentSMSNotificationController@settings_lists')->name('component.sms-notification.settings-list');
        Route::get('templates', 'ComponentSMSNotificationController@sms_templates')->name('component.sms-notification.sms-templates');
        Route::get('templates/lists', 'ComponentSMSNotificationController@sms_templates_lists')->name('component.sms-notification.sms-templates-lists');
        Route::get('templates/reload-module', 'ComponentSMSNotificationController@reload_module')->name('component.sms-notification.templates-reload-module');
        Route::get('templates/reload-sub-module', 'ComponentSMSNotificationController@reload_sub_module')->name('component.sms-notification.templates-reload-sub-module');
        Route::post('templates/store', 'ComponentSMSNotificationController@store_template')->name('component.sms-notification.templates.store');
        Route::get('templates/edit/{id}', 'ComponentSMSNotificationController@find_template')->name('component.sms-notification.templates.find');
        Route::put('templates/update/{id}', 'ComponentSMSNotificationController@update_template')->name('component.sms-notification.templates.update');
        Route::put('templates/remove/{id}', 'ComponentSMSNotificationController@remove_template')->name('component.sms-notification.templates.remove');
        Route::put('templates/restore/{id}', 'ComponentSMSNotificationController@restore_template')->name('component.sms-notification.templates.restore');
        Route::get('templates/group-lists', 'ComponentSMSNotificationController@group_lists')->name('component.sms-notification.group-lists');
        Route::get('templates/fetch-codex', 'ComponentSMSNotificationController@fetch_codex')->name('component.sms-notification.fetch-codex');
        Route::get('lists', 'ComponentSMSNotificationController@lists')->name('component.sms-notification.lists');
        Route::post('send', 'ComponentSMSNotificationController@send')->name('component.sms-notification.store');
        Route::post('send-later', 'ComponentSMSNotificationController@send_later')->name('component.sms-notification.send-later');
        Route::get('search-user', 'ComponentSMSNotificationController@search_user')->name('component.sms-notification.search-user');
        Route::get('search-employee', 'ComponentSMSNotificationController@search_employee')->name('component.sms-notification.search-employee');
        Route::get('search-taxpayer', 'ComponentSMSNotificationController@search_taxpayer')->name('component.sms-notification.search-taxpayer');
        Route::get('search-citizen', 'ComponentSMSNotificationController@search_citizen')->name('component.sms-notification.search-citizen');
        Route::get('tracking', 'ComponentSMSNotificationController@tracking')->name('component.sms-notification.tracking');
        Route::get('tracking-lists', 'ComponentSMSNotificationController@tracking_lists')->name('component.sms-notification.tracking-lists');
        Route::put('resend/{id}', 'ComponentSMSNotificationController@resend')->name('component.sms-notification.resend');
        Route::get('outbox', 'ComponentSMSNotificationController@outbox')->name('component.sms-notification.sms-outbox');
        Route::get('outbox/lists', 'ComponentSMSNotificationController@outbox_lists')->name('component.sms-notification.sms-outbox-lists');
    });
});
