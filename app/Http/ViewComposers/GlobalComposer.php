<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserRole;
use App\Models\Utility;

class GlobalComposer {

    public function compose(View $view)
    {   
        $logo          = asset(Storage::url('uploads/logo/'));
        $lang          = (new Utility)->getValByName('default_language'); \App::setLocale($lang);        
        $settings      = (new Utility)->settings();
        $company_logo  = (new Utility)->getValByName('company_logo_dark');
        $company_logos = (new Utility)->getValByName('company_logo_light');
        $mode_setting  = (new Utility)->mode_layout();
        $menus = [];
        if (Auth::check()) {
            $menus = (new UserRole)->load_user_menus(Auth::user()->id);
        }
        // $permissions  = \App\Http\Controllers\Controller::load_privileges();
        $view->with(compact('lang', 'settings', 'menus', 'logo', 'company_logo', 'company_logos', 'mode_setting'));
    }

}