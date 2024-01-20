<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\AccountList;
use App\Models\Announcement;
use App\Models\AttendanceEmployee;
use App\Models\BalanceSheet;
use App\Models\BankAccount;
use App\Models\Bill;
use App\Models\Bug;
use App\Models\BugStatus;
use App\Models\DealTask;
use App\Models\Employee;
use App\Models\Event;
use App\Models\Expense;
use App\Models\Goal;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\LandingPageSection;
use App\Models\Meeting;
use App\Models\Order;
use App\Models\Payees;
use App\Models\Payer;
use App\Models\Payment;
use App\Models\ProductServiceCategory;
use App\Models\ProductServiceUnit;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Revenue;
use App\Models\Tax;
use App\Models\Ticket;
use App\Models\Timesheet;
use App\Models\TimeTracker;
use App\Models\Trainer;
use App\Models\Training;
use App\Models\User;
use App\Models\BploBusiness;
use App\Models\RptProperty;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{   
    private $slugs;

    public function __construct()
    {
        $this->slugs = 'dashboard';
        $this->_BploBusiness = new BploBusiness(); 
        $this->rptProperty = new RptProperty(); 
    }

    public function index()
    {
        // dd(User::find(Auth::user()->id)->widgets);
        $widgets = strlen(User::find(Auth::user()->id)->widgets) ? explode(',', User::find(Auth::user()->id)->widgets) : [];
        // dd($widgets);
        $dashboard_menu = (new User)->dashboard_menu(Auth::user()->id); 
        return view('dashboard.index')->with(compact('widgets', 'dashboard_menu'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function account_dashboard_index()
    {
        if(Auth::check())
        {
            
            $this->is_permitted($this->slugs, 'read');
            if(Auth::user()->type == 'super admin')
            {
                return redirect()->route('client.dashboard.view');
            }
            elseif(Auth::user()->type == 'client')
            {
                return redirect()->route('client.dashboard.view');
            }
            else
            {
                $data['latestIncome']  = array();//Revenue::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                $data['latestExpense'] = array();//Payment::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();


                $incomeCategory =  array();//ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 1)->get();
                $inColor        = array();
                $inCategory     = array();
                $inAmount       = array();
                for($i = 0; $i < count($incomeCategory); $i++)
                {
                    $inColor[]    = '#' . $incomeCategory[$i]->color;
                    $inCategory[] = $incomeCategory[$i]->name;
                    $inAmount[]   = $incomeCategory[$i]->incomeCategoryRevenueAmount();
                }


                $data['incomeCategoryColor'] = $inColor;
                $data['incomeCategory']      = $inCategory;
                $data['incomeCatAmount']     = $inAmount;


                $expenseCategory = array(); //ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 2)->get();
                $exColor         = array();
                $exCategory      = array();
                $exAmount        = array();
                for($i = 0; $i < count($expenseCategory); $i++)
                {
                    $exColor[]    = '#' . $expenseCategory[$i]->color;
                    $exCategory[] = $expenseCategory[$i]->name;
                    $exAmount[]   = $expenseCategory[$i]->expenseCategoryAmount();
                }

                $data['expenseCategoryColor'] = $exColor;
                $data['expenseCategory']      = $exCategory;
                $data['expenseCatAmount']     = $exAmount;

                $data['incExpBarChartData']  = \Auth::user()->getincExpBarChartData();
                $data['incExpLineChartData'] = \Auth::user()->getIncExpLineChartDate();

                $data['currentYear']  = date('Y');
                $data['currentMonth'] = date('M');

                $constant['taxes']         = array(); //Tax::where('created_by', \Auth::user()->creatorId())->count();
                $constant['category']      = array(); //ProductServiceCategory::where('created_by', \Auth::user()->creatorId())->count();
                $constant['units']         = array(); //ProductServiceUnit::where('created_by', \Auth::user()->creatorId())->count();
                $constant['bankAccount']   = array(); //BankAccount::where('created_by', \Auth::user()->creatorId())->count();
                $data['constant']          = $constant;
                $data['bankAccountDetail'] = array(); //BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get();
                $data['recentInvoice']     = array(); //Invoice::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                $data['weeklyInvoice']     = \Auth::user()->weeklyInvoice();
                $data['monthlyInvoice']    = \Auth::user()->monthlyInvoice();
                $data['recentBill']        = array(); //Bill::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                $data['weeklyBill']        = \Auth::user()->weeklyBill();
                $data['monthlyBill']       = \Auth::user()->monthlyBill();
                $data['goals']             = array(); //Goal::where('created_by', '=', \Auth::user()->creatorId())->where('is_display', 1)->get();
                return view('dashboard.account-dashboard', $data);
            }
        }
        else
        {
            return redirect('login');
            // if(!file_exists(storage_path() . "/installed"))
            // {
            //     header('location:install');
            //     die;
            // }
            // else
            // {
            //     $settings = Utility::settings();
            //     if($settings['display_landing_page'] == 'on')
            //     {
            //         return view('layouts.landing', compact('settings'));
            //     }
            //     else
            //     {
            //         return redirect('login');
            //     }

            // }
        }
    }
    public function dashboard_index(){
		
        $dash=array();
        $dash_menu = DB::table('users_role_groups AS urg')
                     ->Leftjoin('menu_groups AS mg', 'mg.id', '=', 'urg.menu_group_id')
                     ->where('urg.user_id',\Auth::user()->id)
                     ->where('mg.is_dashboard',1)
                     ->select('mg.slug','mg.name')->get();
        foreach ($dash_menu as $val) {
            $dash[$val->slug]=$val->name;
        }
        return view('dashboard.main-dashboard',compact('dash'));
    }
    public function load_dashboard_index(Request $request)
    {
                $data['latestIncome']  = array();//Revenue::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                $data['latestExpense'] = array();//Payment::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                $incomeCategory =  array();//ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 1)->get();
                $inColor        = array();
                $inCategory     = array();
                $inAmount       = array();
                for($i = 0; $i < count($incomeCategory); $i++)
                {
                    $inColor[]    = '#' . $incomeCategory[$i]->color;
                    $inCategory[] = $incomeCategory[$i]->name;
                    $inAmount[]   = $incomeCategory[$i]->incomeCategoryRevenueAmount();
                }


                $data['incomeCategoryColor'] = $inColor;
                $data['incomeCategory']      = $inCategory;
                $data['incomeCatAmount']     = $inAmount;


                $expenseCategory = array(); //ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 2)->get();
                $exColor         = array();
                $exCategory      = array();
                $exAmount        = array();
                for($i = 0; $i < count($expenseCategory); $i++)
                {
                    $exColor[]    = '#' . $expenseCategory[$i]->color;
                    $exCategory[] = $expenseCategory[$i]->name;
                    $exAmount[]   = $expenseCategory[$i]->expenseCategoryAmount();
                }

                $data['expenseCategoryColor'] = $exColor;
                $data['expenseCategory']      = $exCategory;
                $data['expenseCatAmount']     = $exAmount;

                $data['incExpBarChartData']  = \Auth::user()->getincExpBarChartData();
                $data['incExpLineChartData'] = \Auth::user()->getIncExpLineChartDate();

                $data['currentYear']  = date('Y');
                $data['currentMonth'] = date('M');

                $constant['taxes']         = array(); //Tax::where('created_by', \Auth::user()->creatorId())->count();
                $constant['category']      = array(); //ProductServiceCategory::where('created_by', \Auth::user()->creatorId())->count();
                $constant['units']         = array(); //ProductServiceUnit::where('created_by', \Auth::user()->creatorId())->count();
                $constant['bankAccount']   = array(); //BankAccount::where('created_by', \Auth::user()->creatorId())->count();
                $data['constant']          = $constant;
                $data['bankAccountDetail'] = array(); //BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get();
                $data['recentInvoice']     = array(); //Invoice::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                $data['weeklyInvoice']     = \Auth::user()->weeklyInvoice();
                $data['monthlyInvoice']    = \Auth::user()->monthlyInvoice();
                $data['recentBill']        = array(); //Bill::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                $data['weeklyBill']        = \Auth::user()->weeklyBill();
                $data['monthlyBill']       = \Auth::user()->monthlyBill();
                $data['goals']             = array(); //Goal::where('created_by', '=', \Auth::user()->creatorId())->where('is_display', 1)->get();
                return $this->redirect_dashboard($request->selectedOption,$data);
    }  
    public function redirect_dashboard($selectedOption,$data)
    {
        $currentYear = now()->year;
        switch ($selectedOption) {
            case 'business-permit':
                $data['topBarangays'] =$this->_BploBusiness->getTop5Data("busn_office_barangay_id");
                $data['topBusinessType'] =$this->_BploBusiness->getTop5Data("btype_id");
                $data['topBusinessEstablishment'] =$this->_BploBusiness->getTop5BusnIdsByYear($currentYear);
                $data['bplo_business'] =$this->_BploBusiness->getAllBoloBusiness($currentYear);
                $data['bplo_business_endorsment'] =$this->_BploBusiness->getAllBploBusnEnd($currentYear);
                $data['bplo_endorsing_dept'] =$this->_BploBusiness->getAllEndDept();
                $data['bplo_business_location'] = DB::table('bplo_business as a')
                                            ->select(DB::raw('b.busloc_desc as LOCATION'), DB::raw('count(DISTINCT a.id) as TOTAL'))
                                            ->join('bplo_business_locations as b', 'a.busloc_id', '=', 'b.id')
                                            ->where('a.busn_tax_year', 2023)
                                            ->where('a.is_active', 1)
                                            ->groupBy('b.busloc_desc')
                                            ->get();
                $data['no_health_certificate'] = DB::table('bplo_business_endorsement as a')
                                                ->selectRaw('count(a.busn_id) as `No Health Certificate`')
                                                ->join('bplo_endorsing_dept as b', function ($join) {
                                                    $join->on('a.endorsing_dept_id', '=', 'b.id')
                                                        ->where('b.id', 3);
                                                })
                                                ->join('bplo_business as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.id')
                                                        ->where('c.app_code', '<', 3)
                                                        ->where(DB::raw('(c.busn_employee_no_female + c.busn_employee_no_male)'), '>', 0)
                                                        ->where('c.is_active', 1);
                                                })
                                                ->where('a.bend_year', 2023)
                                                ->whereNotExists(function ($query) {
                                                    $query->select('x.busn_id')
                                                        ->from('ho_app_health_certs as x')
                                                        ->whereColumn('x.busn_id', 'a.busn_id')
                                                        ->whereColumn('x.hahc_app_year', 'a.bend_year')
                                                        ->whereColumn('x.bend_id', 'a.id');
                                                })
                                                ->first();
                $data['no_sanitary_certificate'] = DB::table('bplo_business_endorsement as a')
                                                ->selectRaw('count(a.busn_id) as `No Sanitary Permit`')
                                                ->join('bplo_endorsing_dept as b', function ($join) {
                                                    $join->on('a.endorsing_dept_id', '=', 'b.id')
                                                        ->where('b.id', 3);
                                                })
                                                ->join('bplo_business as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.id')
                                                        ->where('c.app_code', '<', 3)
                                                        ->where(DB::raw('(c.busn_employee_no_female + c.busn_employee_no_male)'), '>', 0)
                                                        ->where('c.is_active', 1);
                                                })
                                                ->where('a.bend_year', 2023)
                                                ->whereNotExists(function ($query) {
                                                    $query->select('x.busn_id')
                                                        ->from('ho_application_sanitaries as x')
                                                        ->whereColumn('x.busn_id', 'a.busn_id')
                                                        ->whereColumn('x.has_app_year', 'a.bend_year')
                                                        ->whereColumn('x.bend_id', 'a.id');
                                                })
                                                ->first();    
                $data['business_owned_male'] = DB::table('bplo_business as x')
                                                ->selectRaw('COUNT(DISTINCT x.id) as `Business Owned by Male`')
                                                ->join('bplo_business_type as y', function ($join) {
                                                    $join->on('x.btype_id', '=', 'y.id')
                                                        ->where('y.btype_status', 1);
                                                })
                                                ->join('clients as z', function ($join) {
                                                    $join->on('x.client_id', '=', 'z.id')
                                                        ->where('z.gender', 1);
                                                })
                                                ->where('x.busn_tax_year', 2023)
                                                ->where('x.app_code', '<', 3)
                                                ->where('x.is_individual', 1)
                                                ->whereNotIn('x.busn_app_status', [0, 7, 8])
                                                ->where('x.is_active', 1)
                                                ->first(); 
                $data['business_owned_female'] = DB::table('bplo_business as x')
                                                ->selectRaw('COUNT(DISTINCT x.id) as `Business Owned by Female`')
                                                ->join('bplo_business_type as y', function ($join) {
                                                    $join->on('x.btype_id', '=', 'y.id')
                                                        ->where('y.btype_status', 1);
                                                })
                                                ->join('clients as z', function ($join) {
                                                    $join->on('x.client_id', '=', 'z.id')
                                                        ->where('z.gender', 0);
                                                })
                                                ->where('x.busn_tax_year', 2023)
                                                ->where('x.app_code', '<', 3)
                                                ->where('x.is_individual', 1)
                                                ->whereNotIn('x.busn_app_status', [0, 7, 8])
                                                ->where('x.is_active', 1)
                                                ->first();                                                                                                 
                                                //dd($data['no_health_certificate']->{'No Health Certificate'});
                return view('dashboard.bploDashboard',$data);
            case 'engneering':
                $data['new'] = DB::table('eng_job_requests as ejr')
                ->whereDate('created_at', Carbon::today())
                ->where('ejr.is_active', 1)
                ->count();
                
                 $data['pending'] = DB::table('eng_job_requests as ejr')
                 ->where('ejr.is_active', 1)
                 ->where('is_approve', 0)
                 ->count();

                 $data['payment'] = DB::table('eng_job_requests as ejr')
                 ->where('ejr.is_active', 1)
                 ->where('is_approve', 1)
                 ->where('orno','=', NULL)
                 ->count();

                 $data['permit'] = DB::table('eng_job_requests as ejr')
                 ->where('ejr.is_active', 1)
                 ->where('ejr_is_permit_released', 1)
                 ->count();

                  $data['active'] = DB::table('eng_job_requests as ejr')
                 ->where('ejr.is_active', 1)
                 ->count();
                  $data['cancelled'] = DB::table('eng_job_requests as ejr')
                 ->where('ejr.is_active', 0)
                 ->count();
                  $data['online'] = DB::table('eng_job_requests as ejr')
                 ->where('ejr.is_active', 1)->where('ejr.is_online', 1) 
                 ->count();
                  $data['walkin'] = DB::table('eng_job_requests as ejr')
                 ->where('ejr.is_active', 1)->where('ejr.is_online', 0) 
                 ->count();

                 $data['service_status'] =  DB::table('eng_job_requests')
                ->leftJoin('eng_services', 'eng_job_requests.es_id', '=', 'eng_services.id')
                ->leftJoin('eng_application_type', 'eng_services.eat_id', '=', 'eng_application_type.id')
                ->select(
                    'eng_application_type.eat_module_desc as SERVICES',
                    DB::raw('(SELECT COUNT(eng.es_id) FROM eng_job_requests as eng WHERE eng.topno IS NULL AND eng.es_id = eng_job_requests.es_id) as Draft'),
                    DB::raw('(SELECT COUNT(eng.es_id) FROM eng_job_requests as eng WHERE eng.topno IS NOT NULL AND eng.es_id = eng_job_requests.es_id) as Submitted'),
                    DB::raw('(SELECT COUNT(eng.es_id) FROM eng_job_requests as eng WHERE eng.is_approve = 1 AND eng.es_id = eng_job_requests.es_id) as Approved'),
                    DB::raw('(SELECT COUNT(eng.es_id) FROM eng_job_requests as eng WHERE eng.orno IS NOT NULL AND eng.es_id = eng_job_requests.es_id) as Paid'),
                    DB::raw('(SELECT COUNT(eng.es_id) FROM eng_job_requests as eng WHERE eng.ejr_is_permit_released =1 AND eng.es_id = eng_job_requests.es_id) as Released'),
                    DB::raw('COUNT(eng_job_requests.id) as Total')
                )
                ->where('eng_job_requests.is_active', 1)
                ->groupBy('eng_job_requests.es_id')
                ->orderByDesc(DB::raw('COUNT(eng_job_requests.es_id)'))
                ->get();
                //echo "<pre>"; print_r($data['service_status']); exit;
                 $data['top_fiveservices'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name  as Barangays', DB::raw('count(ejr.id) as TotalCount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.es_id)'))
                ->limit(5)
                ->get();
                $data['top_taxpayers'] = DB::table('eng_job_requests as ejr')
                ->select('c.full_name as name', DB::raw('SUM(ejr.ejr_totalfees) as totalfee'))
                ->join('clients as c', 'ejr.client_id', '=', 'c.id')
                ->where('ejr.is_active', 1)
                ->groupBy('c.id')
                ->orderByDesc(DB::raw('SUM(ejr.ejr_totalfees)'))
                ->limit(3)
                ->get();
                 $data['top_services'] = DB::table('eng_job_requests as ejr')
                ->select('eat.eat_module_desc as name', DB::raw('SUM(ejr.ejr_totalfees) as totalfee'))
                ->join('eng_services as es', 'ejr.es_id', '=', 'es.id')
                ->join('eng_application_type as eat', 'es.eat_id', '=', 'eat.id')
                ->where('ejr.is_active', 1)
                ->groupBy('ejr.es_id')
                ->orderByDesc(DB::raw('COUNT(ejr.es_id)'))
                ->limit(3)
                ->get();
                 $data['top_barangaysbuilding'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 1)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                 $data['top_barangaysdemolition'] =  DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 2)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                 $data['top_barangaysanitary'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 3)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                $data['top_barangayfencing'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 4)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                 $data['top_barangayexcavation'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 5)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                 $data['top_barangayelectric'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 6)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                 $data['top_barangaywater'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 7)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                 $data['top_barangaysign'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 8)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                $data['top_barangayelectronic'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 9)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                $data['top_barangaymechanical'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 10)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                $data['top_barangaycivil'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 11)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                 $data['top_barangayarchitect'] = DB::table('eng_job_requests as ejr')
                ->select('b.brgy_name as barangay', DB::raw('count(ejr.id) as totalcount'))
                ->join('barangays as b', 'ejr.location_brgy_id', '=', 'b.id')
                ->where('ejr.es_id', 13)->where('ejr.is_active', 1)
                ->groupBy('ejr.location_brgy_id')
                ->orderByDesc(DB::raw('COUNT(ejr.id)'))
                ->limit(5)
                ->get();
                return view('dashboard.engDashboard',$data);
            case 'real-property':
                $data = $this->rptProperty->loadDashboardData($data);
                return view('dashboard.rptDashboard',$data);
            case 'planning-and-development':
                $data['cpdo_application_forms'] =DB::table('cpdo_application_forms')->where('is_active',1)->get();
                $data['cpdo_development_permits'] =DB::table('cpdo_development_permits')->where('is_active',1)->get();
                $data['top_barangays'] = DB::table('cpdo_application_forms as a')
                ->select('b.brgy_name as barangay', DB::raw('count(DISTINCT a.id) as total_zoning_clearance'))
                ->join('barangays as b', 'a.caf_brgy_id', '=', 'b.id')
                ->where('a.is_active', 1)
                ->groupBy('a.caf_brgy_id')
                ->orderByDesc(DB::raw('count(a.id)'))
                ->limit(5)
                ->get();
                $data['bplo_business_endorsment'] =$this->_BploBusiness->getAllBploBusnEnd($currentYear);
                $data['top_business_endorsment'] =  DB::table('bplo_business_endorsement as a')
                                                    ->join('bplo_endorsing_dept as b', 'a.endorsing_dept_id', '=', 'b.id')
                                                    ->join('bplo_business as c', 'a.busn_id', '=', 'c.id')
                                                    ->join('barangays as d', 'c.busn_office_barangay_id', '=', 'd.id')
                                                    ->where('a.bend_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', '=', 2)
                                                    ->groupBy('d.id')
                                                    ->select('d.brgy_name as barangay','d.id as barangay_id')
                                                    ->get();
                $data['top_barangay_dev_permit'] = DB::table('cpdo_development_permits as a')
                                                    ->join('barangays as b', 'a.locationofproject', '=', 'b.id')
                                                    ->select('b.brgy_name as barangay_name', DB::raw('COUNT(a.id) as total'))
                                                    ->where('a.is_active', 1)
                                                    ->groupBy('a.locationofproject')
                                                    ->orderByDesc(DB::raw('COUNT(a.id)'))
                                                    ->limit(5)
                                                    ->get();
                        //dd($data['top_business_endorsment']);
                return view('dashboard.planDevtDashboard',$data);
            case 'occupancy':
                 $data['new'] = DB::table('eng_occupancy_apps as eoa')
                ->whereDate('created_at', Carbon::today())
                ->where('eoa.is_active', 1)
                ->count();
                
                 $data['pending'] = DB::table('eng_occupancy_apps as eoa')
                 ->where('eoa.is_active', 1)
                 ->where('is_approve', 0)
                 ->count();

                 $data['payment'] = DB::table('eng_occupancy_apps as eoa')
                 ->where('eoa.is_active', 1)
                 ->where('is_approve', 1)
                 ->where('orno','=', NULL)
                 ->count();

                 $data['permit'] = DB::table('eng_occupancy_apps as eoa')
                 ->where('eoa.is_active', 1)
                 ->where('is_approve', 1)
                 ->where('orno','<>', NULL)
                 ->where('eoa_is_permit_released', 1)
                 ->count();

                 $data['draft'] = DB::table('eng_occupancy_apps as eoa')
                 ->where('eoa.is_active', 1)
                 ->where('is_approve', 0)
                 ->where('topno','=', NULL)
                 ->count();
                  $data['submited'] = DB::table('eng_occupancy_apps as eoa')
                 ->where('eoa.is_active', 1)
                 ->where('is_approve', 0)
                 ->where('topno','<>', NULL)
                 ->count();
                  $data['approved'] = DB::table('eng_occupancy_apps as eoa')
                 ->where('eoa.is_active', 1)
                 ->where('is_approve', 1)
                 ->count();
                  $data['paid'] = DB::table('eng_occupancy_apps as eoa')
                 ->where('eoa.is_active', 1)
                 ->where('is_approve', 1)
                 ->where('orno','<>', NULL)
                 ->count();
                  $data['release'] = DB::table('eng_occupancy_apps as eoa')
                 ->where('eoa.is_active', 1)
                 ->where('is_approve', 1)
                 ->where('orno','<>', NULL)
                 ->where('eoa_is_permit_released', 1)
                 ->count();

                  $data['currentyear'] = DB::table('eng_occupancy_apps as eoa')
                ->select(DB::raw('SUM(eoa.cashieramount) as totalamt'))
                ->where('eoa.is_active', 1)
                ->where(DB::raw("year(eoa.ordate)"), date('Y'))
                ->where('eoa.cashieramount','<>',NULL)
                ->first();
                 $dateTo = date('Y-m-d');  $dateFrom = date('Y-m-d', strtotime('-3 month'));
                  $data['currentquater'] = DB::table('eng_occupancy_apps as eoa')
                ->select(DB::raw('SUM(eoa.cashieramount) as totalamt'))
                ->where('eoa.is_active', 1)
                ->whereDate('eoa.ordate', '>=', $dateFrom)->whereDate('eoa.ordate', '<=', $dateTo)
                ->where('eoa.cashieramount','<>',NULL)
                ->first();

                 $data['currentmonth'] = DB::table('eng_occupancy_apps as eoa')
                ->select(DB::raw('SUM(eoa.cashieramount) as totalamt'))
                ->where('eoa.is_active', 1)
                ->whereMonth('eoa.ordate', date('m'))
                ->where('eoa.cashieramount','<>',NULL)
                ->first();
                
                return view('dashboard.occupancyDashboard',$data);
            case 'social-welfare':

                $year = 2023;
                // assistance
                $data['assistance_new'] =  DB::table('welfare_social_welfare_assistance as a')
                ->whereDate('created_date', Carbon::today())
                ->where('wswa_approve_status', 0)
                ->where('wswa_is_active', 1)
                ->count();
                $data['assistance_draft'] =  DB::table('welfare_social_welfare_assistance as a')
                ->where('wswa_approve_status', 0)
                ->where('wswa_is_active', 1)
                ->count();
                $data['assistance_submitted'] =  DB::table('welfare_social_welfare_assistance as a')
                ->where('wswa_approve_status', 1)
                ->where('wswa_is_active', 1)
                ->count();
                $data['assistance_approved'] =  DB::table('welfare_social_welfare_assistance as a')
                ->where('wswa_approve_status', 2)
                ->where('wswa_is_active', 1)
                ->count();
                $data['assistance_monthly'] =  DB::table('welfare_social_welfare_assistance as a')
                ->where('wswa_is_active', 1)
                ->whereYear('created_date',$year)
                ->groupBy(
                    DB::raw('MONTH(created_date)'),
                    DB::raw('YEAR(created_date)')
                )
                ->select(
                    DB::raw('DATE_FORMAT(created_date, "%M") as month'),
                    DB::raw('count(id) as count') 
                )
                ->get();

                $data['assistance_type_amount_tbl'] =  DB::table('welfare_social_welfare_assistance as a')
                ->join('welfare_swa_assistance_type as wsat', 'a.wsat_id', 'wsat.id')
                ->where('a.wswa_is_active', 1)
                ->groupBy('a.wsat_id')
                ->select(
                    'wsat.wsat_description as type',
                    DB::raw('sum(a.wswa_amount) as total'),
                )
                ->orderBy('total','DESC')
                ->limit(3)
                ->get();
                $data['assistance_active'] =  DB::table('welfare_social_welfare_assistance as a')
                ->where('wswa_is_active', 1)
                ->count();
                $data['assistance_cancelled'] =  DB::table('welfare_social_welfare_assistance as a')
                ->where('wswa_is_active', 0)
                ->count();
                $data['assistance_released'] =  DB::table('cbo_allotment_obligations as obr')
                ->join('cbo_obligation_types as type', 'type.id', 'obr.obligation_type_id')
                ->where([
                    ['type.code', 'AICS'],
                    ['approved_counter', 3],
                    ])
                ->count();
                $data['assistance_pending'] =  DB::table('cbo_allotment_obligations as obr')
                ->join('cbo_obligation_types as type', 'type.id', 'obr.obligation_type_id')
                ->where([
                    ['type.code', 'AICS'],
                    ['approved_counter','<', 3],
                    ])
                ->count();
                $data['assistance_type_count_tbl'] =  DB::table('welfare_social_welfare_assistance as a')
                ->join('welfare_swa_assistance_type as wsat', 'a.wsat_id', 'wsat.id')
                ->where('a.wswa_is_active', 1)
                ->groupBy('a.wsat_id')
                ->select(
                    'wsat.wsat_description as type',
                    DB::raw('count(a.id) as total'),
                )
                ->orderBy('total','DESC')
                ->limit(3)
                ->get();
                $data['assistance_brgy_amount_tbl'] =  DB::table('welfare_social_welfare_assistance as a')
                ->join('citizens as cit', 'a.cit_id', 'cit.id')
                ->join('barangays as brgy', 'cit.brgy_id', 'brgy.id')
                ->where('a.wswa_is_active', 1)
                ->groupBy('cit.brgy_id')
                ->select(
                    'brgy.brgy_name as brgy',
                    DB::raw('sum(a.wswa_amount) as total'),
                )
                ->orderBy('total','DESC')
                ->limit(5)
                ->get();
                $data['assistance_brgy_count_tbl'] =  DB::table('welfare_social_welfare_assistance as a')
                ->join('citizens as cit', 'a.cit_id', 'cit.id')
                ->join('barangays as brgy', 'cit.brgy_id', 'brgy.id')
                ->where('a.wswa_is_active', 1)
                ->groupBy('cit.brgy_id')
                ->select(
                    'brgy.brgy_name as brgy',
                    DB::raw('count(a.id) as total'),
                )
                ->orderBy('total','DESC')
                ->limit(5)
                ->get();
                // PWD
                $data['pwd_new'] =  DB::table('welfare_pwd_application_form as pwd')
                // ->whereDate('created_date', Carbon::today())
                ->where('wpaf_application_type', 0)
                ->where('wpaf_is_active', 1)
                ->count();
                $data['pwd_renewal'] =  DB::table('welfare_pwd_application_form as pwd')
                // ->whereDate('created_date', Carbon::today())
                ->where('wpaf_application_type', 1)
                ->where('wpaf_is_active', 1)
                ->count();
                $data['pwd_gov'] =  DB::table('welfare_pwd_application_form as pwd')
                ->where('wpcoe_id', 1)
                ->where('wpaf_is_active', 1)
                ->count();
                $data['pwd_priv'] =  DB::table('welfare_pwd_application_form as pwd')
                ->where('wpcoe_id', 2)
                ->where('wpaf_is_active', 1)
                ->count();
                $data['pwd_type_tbl'] =  DB::table('welfare_pwd_application_form as pwd')
                ->join('citizens as cit', 'pwd.cit_id', 'cit.id')
                ->join('welfare_pwd_type_of_disability as wptod', 'pwd.wptod_id', 'wptod.id')
                ->where('pwd.wpaf_is_active', 1)
                ->groupBy('pwd.wptod_id')
                ->select(
                    'wptod.wptod_description as disability',
                    DB::raw('sum(IF (cit.cit_gender = 1,1,0)) as female'),
                    DB::raw('sum(IF (cit.cit_gender = 0,1,0)) as male'),
                    DB::raw('count(pwd.id) as total'),
                )
                ->get();
                $data['pwd_monthly'] =  DB::table('welfare_pwd_application_form as pwd')
                ->where('wpaf_is_active', 1)
                ->whereYear('created_date',$year)
                ->groupBy(
                    DB::raw('MONTH(created_date)'),
                    // DB::raw('YEAR(created_date)')
                )
                ->select(
                    DB::raw('DATE_FORMAT(created_date, "%M") as month'),
                    DB::raw('COUNT(id) as count') 
                )
                ->get();

                $data['pwd_active'] =  DB::table('welfare_pwd_application_form as pwd')
                ->where('wpaf_is_active', 1)
                ->count();

                $first_row = DB::table('welfare_pwd_application_form as pwd')->where('id',1)->first();
                $cancel_query = DB::table('welfare_pwd_application_form as pwd')
                ->where('wpaf_is_active', 0);
                if ($first_row->wpaf_control_no === 'Test Data') {
                    $cancel_query->where(function ($query)  {
                        $query->where('id','!=',1);
                        // $query->where('wpaf_control_no','!=','Test Data');
                    });
                }
                $data['pwd_cancelled'] =  $cancel_query->count();

                $data['pwd_inborn'] =  DB::table('welfare_pwd_application_form as pwd')
                ->where('pwd_cause_type', 0)
                ->where('wpaf_is_active', 1)
                ->count();
                $data['pwd_aquired'] =  DB::table('welfare_pwd_application_form as pwd')
                ->where('pwd_cause_type', 1)
                ->where('wpaf_is_active', 1)
                ->count();
                $data['pwd_brgy_tbl'] =  DB::table('welfare_pwd_application_form as pwd')
                ->join('citizens as cit', 'pwd.cit_id', 'cit.id')
                ->join('barangays as brgy', 'pwd.wpaf_brgy_id', 'brgy.id')
                ->where('pwd.wpaf_is_active', 1)
                ->groupBy('pwd.wpaf_brgy_id')
                ->select(
                    'brgy.brgy_name as brgy',
                    DB::raw('sum(IF (cit.cit_gender = 1,1,0)) as female'),
                    DB::raw('sum(IF (cit.cit_gender = 0,1,0)) as male'),
                    DB::raw('count(pwd.id) as total'),
                )
                ->get();
                $data['pwd_age_tbl'] =  DB::table(DB::raw('
                (SELECT TIMESTAMPDIFF(YEAR, cit_date_of_birth, CURDATE()) AS age, pwd.id as pwd_id, cit.cit_gender as gender
FROM welfare_pwd_application_form as pwd
join citizens as cit on pwd.cit_id = cit.id 
where pwd.wpaf_is_active = 1) as tbl
                '))
                ->groupBy('age_range')
                ->orderBy('ordinal')
                ->select(
                    DB::raw("CASE
                    WHEN age < 20 THEN 'Under 20'
                    WHEN age BETWEEN 20 and 29 THEN '20 - 29'
                    WHEN age BETWEEN 30 and 39 THEN '30 - 39'
                    WHEN age BETWEEN 40 and 49 THEN '40 - 49'
                    WHEN age BETWEEN 50 and 59 THEN '50 - 59'
                    WHEN age BETWEEN 60 and 69 THEN '60 - 69'
                    WHEN age BETWEEN 70 and 79 THEN '70 - 79'
                    WHEN age >= 80 THEN 'Over 80'
                    WHEN age IS NULL THEN 'Not Filled In (NULL)'
                END as age_range"),
                    DB::raw("COUNT(*) AS count"),
                    DB::raw("CASE
                    WHEN age < 20 THEN 1
                    WHEN age BETWEEN 20 and 29 THEN 2
                    WHEN age BETWEEN 30 and 39 THEN 3
                    WHEN age BETWEEN 40 and 49 THEN 4
                    WHEN age BETWEEN 50 and 59 THEN 5
                    WHEN age BETWEEN 60 and 69 THEN 6
                    WHEN age BETWEEN 70 and 79 THEN 7
                    WHEN age >= 80 THEN 8
                    WHEN age IS NULL THEN 9
                END as ordinal"),
                DB::raw('sum(IF (gender = 1,1,0)) as female'),
                DB::raw('sum(IF (gender = 0,1,0)) as male'),
                )
                ->get();
                // dd($data['pwd_age_tbl']);

                // Senior 
                $data['senior_new'] =  DB::table('welfare_seniors_citizen_application as senior')
                ->where('wsca_is_renewal', 0)
                ->where('wsca_is_active', 1)
                ->count();
                $data['senior_renew'] =  DB::table('welfare_seniors_citizen_application as senior')
                ->where('wsca_is_renewal', 1)
                ->where('wsca_is_active', 1)
                ->count();
                $data['senior_pension'] =  DB::table('welfare_seniors_citizen_application as senior')
                ->where('wsca_pension_amount', '>',0)
                ->where('wsca_is_active', 1)
                ->count();
                $data['senior_nopension'] =  DB::table('welfare_seniors_citizen_application as senior')
                ->where('wsca_pension_amount', '<=',0)
                ->where('wsca_is_active', 1)
                ->count();
                $data['senior_monthly'] =  DB::table('welfare_seniors_citizen_application as senior')
                ->where('wsca_is_active', 1)
                ->whereYear('created_date',$year)
                ->groupBy(
                    DB::raw('MONTH(created_date)'),
                    // DB::raw('YEAR(created_date)')
                )
                ->select(
                    DB::raw('DATE_FORMAT(created_date, "%M") as month'),
                    DB::raw('COUNT(id) as count') 
                )
                ->get();

                $data['senior_active'] =  DB::table('welfare_seniors_citizen_application as senior')
                ->where('wsca_is_active', 1)
                ->count();
                $first_row = DB::table('welfare_seniors_citizen_application as senior')->where('id',1)->first();
                $cancel_query = DB::table('welfare_seniors_citizen_application as senior')
                ->where('wsca_is_active', 0);
                if ($first_row->wsca_remarks === 'Test Data') {
                    $cancel_query->where(function ($query)  {
                        $query->where('id','!=',1);
                        // $query->where('wsca_remarks','!=','Test Data');
                    });
                }
                $data['senior_cancelled'] =  $cancel_query->count();
                $data['senior_male'] =  DB::table('welfare_seniors_citizen_application as senior')
                ->join('citizens as cit', 'senior.cit_id', 'cit.id')
                ->where('senior.wsca_is_active', 1)
                ->where('cit.cit_gender', 0)
                ->count();
                $data['senior_female'] =  DB::table('welfare_seniors_citizen_application as senior')
                ->join('citizens as cit', 'senior.cit_id', 'cit.id')
                ->where('senior.wsca_is_active', 1)
                ->where('cit.cit_gender', 1)
                ->count();
                $data['senior_brgy_count_tbl'] =  DB::table('welfare_seniors_citizen_application as senior')
                ->join('citizens as cit', 'senior.cit_id', 'cit.id')
                ->join('barangays as brgy', 'cit.brgy_id', 'brgy.id')
                ->where('senior.wsca_is_active', 1)
                ->groupBy('cit.brgy_id')
                ->select(
                    'brgy.brgy_name as brgy',
                    DB::raw('count(senior.id) as total'),
                    DB::raw('sum(IF (cit.cit_gender = 1,1,0)) as female'),
                    DB::raw('sum(IF (cit.cit_gender = 0,1,0)) as male'),
                )
                ->orderBy('total','DESC')
                ->limit(5)
                ->get();

                // solo parent
                $data['solo_parent_new'] =  DB::table('welfare_solo_parent_application as solo_parent')
                ->where('wspa_is_renewal', 0)
                ->where('wspa_is_active', 1)
                ->count();
                $data['solo_parent_renew'] =  DB::table('welfare_solo_parent_application as solo_parent')
                ->where('wspa_is_renewal', 1)
                ->where('wspa_is_active', 1)
                ->count();
                $data['solo_parent_income'] =  DB::table('welfare_solo_parent_application as solo_parent')
                ->where('wspa_total_income', '>',0)
                ->where('wspa_is_active', 1)
                ->count();
                $data['solo_parent_noincome'] =  DB::table('welfare_solo_parent_application as solo_parent')
                ->where('wspa_total_income', '<=',0)
                ->where('wspa_is_active', 1)
                ->count();
                $data['solo_parent_monthly'] =  DB::table('welfare_solo_parent_application as solo_parent')
                ->where('wspa_is_active', 1)
                ->whereYear('created_date',$year)
                ->groupBy(
                    DB::raw('MONTH(created_date)'),
                    // DB::raw('YEAR(created_date)')
                )
                ->select(
                    DB::raw('DATE_FORMAT(created_date, "%M") as month'),
                    DB::raw('COUNT(id) as count') 
                )
                ->get();

                $data['solo_parent_active'] =  DB::table('welfare_solo_parent_application as solo_parent')
                ->where('wspa_is_active', 1)
                ->count();
                $first_row = DB::table('welfare_solo_parent_application as solo_parent')->where('id',1)->first();
                $cancel_query = DB::table('welfare_solo_parent_application as solo_parent')
                ->where('wspa_is_active', 0);
                if ($first_row->wspa_occupation === 'Test Data') {
                    $cancel_query->where(function ($query)  {
                        $query->where('id','!=',1);
                        // $query->where('wspa_occupation','!=','Test Data');
                    });
                }
                $data['solo_parent_cancelled'] =  $cancel_query->count();
                $data['solo_parent_male'] =  DB::table('welfare_solo_parent_application as solo_parent')
                ->join('citizens as cit', 'solo_parent.cit_id', 'cit.id')
                ->where('solo_parent.wspa_is_active', 1)
                ->where('cit.cit_gender', 0)
                ->count();
                $data['solo_parent_female'] =  DB::table('welfare_solo_parent_application as solo_parent')
                ->join('citizens as cit', 'solo_parent.cit_id', 'cit.id')
                ->where('solo_parent.wspa_is_active', 1)
                ->where('cit.cit_gender', 1)
                ->count();
                $data['solo_parent_brgy_count_tbl'] =  DB::table('welfare_solo_parent_application as solo_parent')
                ->join('citizens as cit', 'solo_parent.cit_id', 'cit.id')
                ->join('barangays as brgy', 'cit.brgy_id', 'brgy.id')
                ->where('solo_parent.wspa_is_active', 1)
                ->groupBy('cit.brgy_id')
                ->select(
                    'brgy.brgy_name as brgy',
                    DB::raw('count(solo_parent.id) as total'),
                    DB::raw('sum(IF (cit.cit_gender = 1,1,0)) as female'),
                    DB::raw('sum(IF (cit.cit_gender = 0,1,0)) as male'),
                )
                ->orderBy('total','DESC')
                ->limit(5)
                ->get();
                return view('dashboard.socialWalfareDashboard',$data);
            case 'Cashier':
                 $data['total_transaction_today'] = DB::table('cto_cashier as a')
                ->whereDate('a.cashier_or_date', Carbon::today())
                ->where('a.status', 1)->where('a.ocr_id', 0)
                ->count();
                
                 $data['walkin_transaction_today'] = DB::table('cto_cashier as a')
                ->whereDate('a.cashier_or_date', Carbon::today())
                ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','<>',5)
                ->count();

                $data['online_transaction_today'] = DB::table('cto_cashier as a')
                ->whereDate('a.cashier_or_date', Carbon::today())
                ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','=',5)
                ->count();

                 $data['cancelled_transaction_today'] = DB::table('cto_cashier as a')
                 ->whereDate('a.cashier_or_date', Carbon::today())
                 ->where('a.status', 1)->where('a.ocr_id','>',0)
                 ->count();

                 $data['total_transaction_this_month'] = DB::table('cto_cashier as a')
                 ->whereMonth('a.cashier_or_date',date('m'))
                 ->where('a.status', 1)->where('a.ocr_id', 0)
                 ->count();
                
                 $data['walkin_transaction_this_month'] = DB::table('cto_cashier as a')
                 ->whereMonth('a.cashier_or_date', date('m'))
                 ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','<>',5)
                 ->count();

                 $data['online_transaction_this_month'] = DB::table('cto_cashier as a')
                 ->whereMonth('a.cashier_or_date', date('m'))
                 ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','=',5)
                 ->count();

                 $data['cancelled_transaction_this_month'] = DB::table('cto_cashier as a')
                 ->whereMonth('a.cashier_or_date', date('m'))
                 ->where('a.status', 1)->where('a.ocr_id','>',0)
                 ->count();

                 $data['top_taxpayers'] = DB::table('cto_cashier as a')
                 ->select('a.taxpayers_name as name', DB::raw('SUM(a.net_tax_due_amount) as totalfee'))
                 ->where('a.status', 1)->where('a.ocr_id', 0)
                 ->whereYear('a.cashier_or_date', $currentYear) 
                 ->groupBy('a.client_citizen_id','a.payee_type')
                 ->orderByDesc(DB::raw('SUM(a.net_tax_due_amount)'))
                 ->limit(5)
                 ->get();
                 
                 $data['TodayWalkin'] = DB::table('cto_cashier as a')
                  ->select(DB::raw('SUM(a.net_tax_due_amount) as total'))
                 ->whereDate('a.cashier_or_date', Carbon::today())
                 ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','<>',5)
                 ->get();

                 $data['TodayOnline'] = DB::table('cto_cashier as a')
                  ->select(DB::raw('SUM(a.net_tax_due_amount) as total'))
                 ->whereDate('a.cashier_or_date', Carbon::today())
                 ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','=',5)
                 ->get();

                  $data['MonthWalkin'] = DB::table('cto_cashier as a')
                  ->select(DB::raw('SUM(a.net_tax_due_amount) as total'))
                 ->whereMonth('a.cashier_or_date', date('m'))
                 ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','<>',5)
                 ->get();

                  $data['MonthOnline'] = DB::table('cto_cashier as a')
                  ->select(DB::raw('SUM(a.net_tax_due_amount) as total'))
                 ->whereMonth('a.cashier_or_date', date('m'))
                 ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','=',5)
                 ->get();

                  $data['YearWalkin'] = DB::table('cto_cashier as a')
                  ->select(DB::raw('SUM(a.net_tax_due_amount) as total'))
                 ->whereYear('a.cashier_or_date', date('Y'))
                 ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','<>',5)
                 ->get();

                  $data['YearOnline'] = DB::table('cto_cashier as a')
                  ->select(DB::raw('SUM(a.net_tax_due_amount) as total'))
                 ->whereYear('a.cashier_or_date', date('Y'))
                 ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','=',5)
                 ->get();

                //  $data['CollOfficer'] = DB::table('cto_cashier as a')
                // ->leftJoin('hr_employees as b', 'b.user_id', '=', 'a.created_by')
                // ->leftJoin('cto_payment_or_types as cpt', 'cpt.id', '=', 'a.ortype_id')
                // ->select('b.fullnamed as Name', 'a.or_no', 'cpt.ortype_name')
                // ->where('a.status', 1)->where('a.ocr_id', 0)
                // ->whereYear('a.cashier_or_date', $currentYear)
                // ->groupBy('a.created_by') // Use whereYear to filter by year
                // ->orderByDesc('a.id')
                // ->get();
                $data['CollOfficer'] = DB::table('cto_cashier as a')->select(
                        'b.fullname as Name',
                        DB::raw('(SELECT x.or_no FROM cto_cashier x WHERE x.created_by=a.created_by AND x.ocr_id = 0 ORDER BY x.id DESC LIMIT 1) as Last_OR_No'),
                        DB::raw('(SELECT CONCAT(cpr.cpor_series, ",", cpt.ortype_name) FROM cto_cashier x 
                            left join cto_payment_or_registers as cpr on cpr.id = x.or_register_id
                            left join cto_payment_or_types as cpt on cpt.id = x.ortype_id
                            WHERE x.created_by=a.created_by AND x.ocr_id = 0 ORDER BY x.id DESC LIMIT 1) as Accountable_Forms')
                    )
                    ->leftJoin('hr_employees as b', 'b.user_id', '=', 'a.created_by')
                    ->where('a.status', 1)
                    ->where('a.ocr_id', 0)
                    ->whereYear('a.cashier_or_date', $currentYear)
                    ->groupBy('a.created_by')
                    ->orderByDesc('a.id')
                    ->get();

                  $top_collector = DB::table('cto_cashier as a')->leftJoin('hr_employees as b','b.user_id','=','a.created_by')
                  ->select('a.created_by','a.id','b.fullname as Name',DB::raw('SUM(a.net_tax_due_amount) as totalamt'),DB::raw('count(a.id) as totalcount'))
                 ->where('a.status', 1)->groupBy('a.created_by')
                 ->whereYear('a.cashier_or_date', $currentYear) 
                 ->orderByDesc(DB::raw('SUM(a.net_tax_due_amount)'))->limit(3)
                 ->get();
                
                 $data['top_collector']=$top_collector; 

                 $section = DB::table('cto_cashier as a')->leftjoin('cto_payment_cashier_system as b','a.tfoc_is_applicable','=','b.id')->select('b.id','b.pcs_name')
                 ->whereDate('a.cashier_or_date', Carbon::today())
                 ->where('a.status', 1)->where('a.ocr_id', 0)
                 ->groupBy('a.tfoc_is_applicable')->get();
                 $sectionarray = array(); 
                 foreach ($section as $key => $val) {
                     $transactions =  DB::table('cto_cashier as a')
                        ->whereDate('a.cashier_or_date', Carbon::today())
                        ->where('a.tfoc_is_applicable', $val->id)
                        ->where('a.status', 1)->where('a.ocr_id', 0)
                        ->count();
                     $walkin =  DB::table('cto_cashier as a')
                     ->select(DB::raw('SUM(a.net_tax_due_amount) as totalamt'))
                        ->whereDate('a.cashier_or_date', Carbon::today())
                        ->where('a.tfoc_is_applicable', $val->id)
                        ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','<>',5)
                        ->get();
                     $online =  DB::table('cto_cashier as a')
                        ->select( DB::raw('SUM(a.net_tax_due_amount) as totalamt'))
                        ->whereDate('a.cashier_or_date', Carbon::today())
                        ->where('a.tfoc_is_applicable', $val->id)
                        ->where('a.status', 1)->where('a.ocr_id', 0)->where('a.payment_terms','=',5)
                        ->get();
                        $total = ($walkin[0]->totalamt + $online[0]->totalamt);
                        $sectionarray[$key]['section'] = $val->pcs_name; 
                        $sectionarray[$key]['transaction'] = $transactions;  
                        $sectionarray[$key]['walkin'] = ($walkin[0]->totalamt) ? number_format($walkin[0]->totalamt, 2, '.', ','): '0.00';
                        $sectionarray[$key]['online'] = ($online[0]->totalamt) ? number_format($online[0]->totalamt, 2, '.', ','): '0.00';
                        $sectionarray[$key]['total'] = number_format($total, 2, '.', ',');
                 }
                 $data['sections']=$sectionarray; 

                return view('dashboard.cashierDashboard',$data);
                
            case 'health-and-safety':

            $data['Laboratory'] = DB::table('ho_lab_requests')
                ->whereDate('lab_reg_date', Carbon::today())
                ->where('is_active',1)
                ->where('lab_is_posted',1)
                ->select('cit_id')->count();
            
            $data['Hematology'] = DB::table('ho_hematology')
                        ->whereDate('hema_date', Carbon::today())
                        ->where('hema_is_active',1)
                        ->where('hema_is_posted',1)
                        ->select('cit_id')->count();

            $data['Serology'] = DB::table('ho_serology')
                        ->whereDate('ser_date', Carbon::today())
                        ->where('ser_is_active',1)
                        ->where('ser_is_posted',1)
                        ->select('cit_id')->count();

            $data['Urinalysis'] = DB::table('ho_urinalysis')
                        ->whereDate('urin_date', Carbon::today())
                        ->where('urin_is_active',1)
                        ->where('urin_is_posted',1)
                        ->select('cit_id')->count();

            $data['Fecalysis'] = DB::table('ho_fecalysis')
                        ->whereDate('fec_date', Carbon::today())
                        ->where('fec_is_active',1)
                        ->where('fec_is_posted',1)
                        ->select('cit_id')->count();

            $data['Pregnancy_test'] = DB::table('ho_pregnancy')
                        ->whereDate('pt_date', Carbon::today())
                        ->where('pt_is_active',1)
                        ->where('pt_is_posted',1)
                        ->select('cit_id')->count();

            $data['blood_sugar_test'] = DB::table('ho_blood_sugar_tests')
                        ->whereDate('bs_date', Carbon::today())
                        ->where('is_active',1)
                        ->where('is_posted',1)
                        ->select('cit_id')->count();

            $data['gram_staining_test'] = DB::table('ho_gram_stainings')
                        ->whereDate('gs_date', Carbon::today())
                        ->where('is_active',1)
                        ->where('is_posted',1)
                        ->select('cit_id')->count();

            $data['FamilyPlanning'] = DB::table('ho_fam_plan')
						->whereDate('fam_date', Carbon::today())
                        ->where('fam_is_active',1)
                        ->select('fam_ref_id')->count(); 

            $data['MedicalCertificate'] = DB::table('ho_medical_certificates')
						->whereDate('med_cert_date', Carbon::today())
                        ->where('is_active',1)
                        ->select('cit_id')->count(); 

            $data['MedicalRecords'] = DB::table('ho_medical_records')
						->whereDate('med_rec_date', Carbon::today())
                        ->where('med_rec_status',1)
                        ->select('cit_id')->count(); 

            $data['HealthCertificate'] = DB::table('ho_app_health_certs')
						->whereDate('hahc_issuance_date', Carbon::today())
						->where('hahc_status',1)
                        ->where('hahc_approver_status',1)
						->where('hahc_recommending_approver_status',1)
                        ->where('hahc_approver_status',1)
                        ->where('hahc_recommending_approver_status',1)
                        ->select('citizen_id')->count(); 

            $data['sanitory_permit'] = DB::table('ho_application_sanitaries')
                        ->whereDate('has_issuance_date', Carbon::today())
                        ->where('has_status',1)
                        ->where('has_approver_status',1)
                        ->where('has_recommending_approver_status',1)
                        ->select('busn_id')->count();
						
			 $data['Medicinestoday'] = DB::table('ho_issuance_details  as issued_item')
                        ->leftjoin('gso_items as items','items.id','=', 'issued_item.item_id')
						->leftjoin('ho_issuances as issuance_parent', 'issuance_parent.id', '=', 'issued_item.issuance_id')
                        ->leftjoin('ho_medical_item_categories as med_item_category', 'med_item_category.id', '=', 'items.medical_category_id')
                       	->whereDate('issuance_parent.issuance_date', Carbon::today())
                        ->where('med_item_category.code','=','Medicine')
                        ->distinct('issued_item.item_id')->count();
						
			$data['MedicalSupplies'] = DB::table('ho_issuance_details  as issued_item')
                        ->leftjoin('gso_items as items','items.id','=', 'issued_item.item_id')
						->leftjoin('ho_issuances as issuance_parent', 'issuance_parent.id', '=', 'issued_item.issuance_id')
                        ->leftjoin('ho_medical_item_categories as med_item_category', 'med_item_category.id', '=', 'items.medical_category_id')
                       	->whereDate('issuance_parent.issuance_date', Carbon::today())
                        ->where('med_item_category.code','=','Medical Supplies')
                        ->distinct('issued_item.item_id')->count();

            $data['Expirable'] = DB::table(DB::raw('(
                        SELECT COUNT(ho_inventory_posting.item_id) AS tableCOunt
                        FROM ho_inventory_posting
                        WHERE ho_inventory_posting.cip_expiry_date = CURDATE()
                        AND ho_inventory_posting.cip_balance_qty > 0
                        UNION ALL
                        SELECT COUNT(ho_inventory_breakdowns.item_id) AS tableCOunt
                        FROM ho_inventory_breakdowns
                        WHERE ho_inventory_breakdowns.hrb_expiry_date < CURDATE()
                        AND ho_inventory_breakdowns.hrb_balance_qty > 0
                        ) AS tbl'))
                        ->sum('tbl.tableCOunt');
            $data['bplo_business_endorsment'] =$this->_BploBusiness->getAllBploBusnEnd($currentYear);
            $data['top_business_endorsment'] =  DB::table('bplo_business_endorsement as a')
                                                                ->join('bplo_endorsing_dept as b', 'a.endorsing_dept_id', '=', 'b.id')
                                                                ->join('bplo_business as c', 'a.busn_id', '=', 'c.id')
                                                                ->join('barangays as d', 'c.busn_office_barangay_id', '=', 'd.id')
                                                                ->where('a.bend_year', '=', DB::raw('YEAR(CURDATE())'))
                                                                ->where('b.id', '=', 3)
                                                                ->groupBy('d.id')
                                                                ->select('d.brgy_name as barangay','d.id as barangay_id')
                                                                ->get();

                       
        
            case 'statistics':
        
                $data['record_card'] = DB::table('ho_record_cards')
                            ->where('rec_card_status',1)
                            ->select('cit_id')->count(); 
                            
                $data['lab_request'] = DB::table('ho_lab_requests')
                            ->where('is_active',1)
                            ->where('lab_is_posted',1)
                            ->select('cit_id')->count(); 
    
                $data['medical_records'] = DB::table('ho_medical_records')
                            ->where('med_rec_status',1)
                            ->select('med_rec_id')->count(); 
    
                $data['medical_certificate'] = DB::table('ho_medical_certificates')
                            ->where('is_active',1)
                            ->select('cit_id')->count(); 
    
                $data['Health_certificate'] = DB::table('ho_app_health_certs')
                            ->where('hahc_status',1)
                            ->where('hahc_approver_status',1)
                            ->where('hahc_recommending_approver_status',1)
                            ->select('citizen_id')->count();
    
                $data['Sanitory_permit'] = DB::table('ho_application_sanitaries')
                            ->where('has_status',1)
                            ->where('has_approver_status',1)
                            ->where('has_recommending_approver_status',1)
                            ->select('busn_id')->count();

                $data['NearlyExpired'] = DB::table(DB::raw('(
                            SELECT items.name, inventory.cip_balance_qty as QTY, inventory.cip_expiry_date as expiry_date
                            FROM ho_inventory_posting AS inventory
                            LEFT JOIN gso_items AS items
                            ON inventory.item_id = items.id
                            WHERE DATEDIFF(inventory.cip_expiry_date, curdate()) <= 45
                            AND inventory.cip_balance_qty > 0
							AND inventory.cip_status = 1
							
                            UNION 
                            SELECT items.name, inventoryBreakdown.hrb_balance_qty as QTY, inventoryBreakdown.hrb_expiry_date as expiry_date
                            FROM ho_inventory_breakdowns AS inventoryBreakdown
                            LEFT JOIN gso_items AS items
                            ON inventoryBreakdown.item_id = items.id
                            WHERE DATEDIFF(inventoryBreakdown.hrb_expiry_date, curdate()) <= 45
                            AND inventoryBreakdown.hrb_balance_qty > 0
							AND inventoryBreakdown.hrb_status = 1
                            ORDER BY expiry_date ASC
                            ) AS tbl'))
                            ->get();
    
                case 'top5 barangays': 
                    $data['top5_barangays'] = DB::table('barangays as b')
                    ->select('b.brgy_name as barangay', DB::raw('count(hlr.cit_id) as citizen_count'))
                    ->leftjoin('citizens as c', 'c.brgy_id', '=', 'b.id')
                    ->leftjoin('ho_lab_requests as hlr', 'hlr.cit_id', '=', 'c.id')
                    ->where('hlr.lab_req_year', '=', DB::raw('YEAR(CURDATE())'))
                    ->groupBy('b.brgy_name')
                    ->orderByDesc(DB::raw('count(hlr.cit_id)'))
                    ->limit(5)
                    ->get();
                            
                        return view('dashboard.healthSafetyDashboard',$data);
            case 'fire-protection':
                $data['afPendingVerificationCount'] = DB::table('bfp_application_forms as a')
                                                    ->select(DB::raw('COUNT(DISTINCT a.id) as af_pending_verification'))
                                                    ->whereNull('a.bff_verified_status')
                                                    ->orWhere('a.bff_verified_status', 0)
                                                    ->first();
                $data['bplo_business_endorsment'] =$this->_BploBusiness->getAllBploBusnEnd($currentYear);
                $data['top_business_endorsment'] =  DB::table('bplo_business_endorsement as a')
                                                    ->join('bplo_endorsing_dept as b', 'a.endorsing_dept_id', '=', 'b.id')
                                                    ->join('bplo_business as c', 'a.busn_id', '=', 'c.id')
                                                    ->join('barangays as d', 'c.busn_office_barangay_id', '=', 'd.id')
                                                    ->where('a.bend_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', '=', 1)
                                                    ->groupBy('d.id')
                                                    ->select('d.brgy_name as barangay','d.id as barangay_id')
                                                    ->get();                                   
                $data['afPendingApprovalCount'] = DB::table('bfp_application_forms as a')
                                                    ->select(DB::raw('COUNT(DISTINCT a.id) as af_pending_approval'))
                                                    ->whereNull('a.bff_certified_status')
                                                    ->orWhere('a.bff_certified_status', 0)
                                                    ->first();
                $pendingForAssessment=DB::table('bfp_application_forms as a')
                                                    ->join('bplo_business_endorsement as c', function ($join) {
                                                        $join->on('a.bend_id', '=', 'c.id')
                                                            ->where('c.bend_status', '<>', 3);
                                                    })
                                                    ->where('a.bff_year', '=', DB::raw('year(curdate())'))
                                                    ->whereNotExists(function ($query) {
                                                        $query->select(DB::raw(1))
                                                            ->from('bfp_application_assessments as b')
                                                            ->whereRaw('b.bff_id = a.id');
                                                    })
                                                    ->select(DB::raw('COUNT(DISTINCT a.id) as count'))
                                                    ->first();
                                                    $data['pendingForAssessment'] =  $pendingForAssessment->count;
                $data['applicationFormApprovedCount'] = DB::table('bfp_application_forms as a')
                                                    ->select(DB::raw('COUNT(DISTINCT a.id) as application_form_approved'))
                                                    ->where('a.bff_certified_by', '>', 0)
                                                    ->where('a.bff_certified_status', 1)
                                                    ->first(); 
                $result = DB::table('bfp_application_forms as a')
                                                ->join('bplo_business_endorsement as c', 'a.bend_id', '=', 'c.id')
                                                ->where('a.bff_year', '=', DB::raw('year(curdate())'))
                                                ->select(DB::raw('COUNT(DISTINCT a.id) as count'))
                                                ->first();                                  
                $data['applicationFormsCount'] =  $result->count;
                $data['assessmentCount'] = DB::table('bfp_application_assessments as a')
                                                    ->join('bplo_business_endorsement as b', function ($join) {
                                                        $join->on('a.bend_id', '=', 'b.id')
                                                            ->where('b.bend_status', '<>', 3);
                                                    })
                                                    ->where('a.bfpas_ops_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('a.bfpas_is_fully_paid', 0)
                                                    ->count();
                $data['assessmentCountPending'] =  DB::table('bfp_application_assessments as a')
                                                    ->join('bplo_business_endorsement as b', function ($join) {
                                                        $join->on('a.bend_id', '=', 'b.id')
                                                            ->where('b.bend_status', '<>', 3);
                                                    })
                                                    ->whereYear('a.bfpas_ops_year', now()->year)
                                                    ->where('a.bfpas_total_amount', '>', 0)
                                                    ->where('a.bfpas_is_fully_paid', 1)
                                                    ->whereRaw('LENGTH(a.bfpas_payment_or_no) > 0')
                                                    ->where('a.payment_status', 1)
                                                    ->where('a.ocr_id', 0)
                                                    ->selectRaw('COUNT(DISTINCT a.id) as count')
                                                    ->count();
                $data['applicationFormsIndusCount'] = DB::table('bfp_application_forms as a')
                                                    ->join('bplo_business_endorsement as c', 'a.bend_id', '=', 'c.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->count(); 
                $data['applicationEduFormsCount'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 2)
                                                    ->count(); 
                $data['occupancyDetan'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 3)
                                                    ->count();
                                                
                $data['occupancyMercantile'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 4)
                                                    ->count();
                $data['occupancyTypeBusiness'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 5)
                                                    ->count(); 
                $data['occupancyTypeHealth'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 6)
                                                    ->count();
                $data['occupancyTypeStorage'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 7)
                                                    ->count();
                $data['occupancyTypeSingle'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 8)
                                                    ->count(); 
                $data['occupancyTypeMiscellaneous'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 9)
                                                    ->count(); 
                $data['occupancyTypeTheather'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 10)
                                                    ->count(); 
                $data['occupancyTypeResidential'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 12)
                                                    ->count(); 
                $data['occupancyTypeSmall'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 11)
                                                    ->count(); 
                $data['occupancyTypeGasoline'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 13)
                                                    ->count(); 
                $data['occupancyTypeAssembly'] = DB::table('bfp_application_forms as a')
                                                    ->join('bfp_occupancy_types as b', 'a.bot_id', '=', 'b.id')
                                                    ->where('a.bff_year', '=', DB::raw('YEAR(CURDATE())'))
                                                    ->where('b.id', 14)
                                                    ->count();
                $data['top5Barangay'] = DB::table('bfp_application_assessments as a')
                                                    ->select('c.brgy_name as Barangay', DB::raw('SUM(a.bfpas_total_amount) as Total'))
                                                    ->join('bplo_business as b', 'a.busn_id', '=', 'b.id')
                                                    ->join('barangays as c', 'b.busn_office_barangay_id', '=', 'c.id')
                                                    ->where('a.bfpas_total_amount', '>', 0)
                                                    ->where('a.bfpas_is_fully_paid', '=', 1)
                                                    ->where('a.payment_status', '=', 1)
                                                    ->groupBy('b.busn_office_barangay_id')
                                                    ->orderByDesc(DB::raw('SUM(a.bfpas_total_amount)'))
                                                    ->limit(5)
                                                    ->get();
                $data['businessAppStatusToday'] = DB::table('bplo_business_endorsement as a')
                                                    ->select(DB::raw('COUNT(DISTINCT a.id) as today'))
                                                    ->join('bplo_business as b', function ($join) {
                                                        $join->on('a.busn_id', '=', 'b.id')
                                                            ->where('b.app_code', '=', 1)
                                                            ->where('b.is_active', '=', 1);
                                                    })
                                                    ->join('bplo_endorsing_dept as c', function ($join) {
                                                        $join->on('a.endorsing_dept_id', '=', 'c.id')
                                                            ->where('c.id', '=', 1);
                                                    })
                                                    ->whereRaw('DATE_FORMAT(a.created_at, "%Y-%m-%d") = DATE_FORMAT(curdate(), "%Y-%m-%d")')
                                                    ->first();  
            $data['businessAppStatusMonth'] = DB::table('bplo_business_endorsement as a')
                                                    ->select(DB::raw('COUNT(DISTINCT a.id) as this_month'))
                                                    ->join('bplo_business as b', function ($join) {
                                                        $join->on('a.busn_id', '=', 'b.id')
                                                            ->where('b.app_code', '=', 1)
                                                            ->where('b.is_active', '=', 1);
                                                    })
                                                    ->join('bplo_endorsing_dept as c', function ($join) {
                                                        $join->on('a.endorsing_dept_id', '=', 'c.id')
                                                            ->where('c.id', '=', 1);
                                                    })
                                                    ->whereRaw('DATE_FORMAT(a.created_at, "%Y-%m") = DATE_FORMAT(curdate(), "%Y-%m")')
                                                    ->first(); 
            $data['businessAppStatusYear'] = DB::table('bplo_business_endorsement as a')
                                                    ->select(DB::raw('COUNT(DISTINCT a.id) as year'))
                                                    ->join('bplo_business as b', function ($join) {
                                                        $join->on('a.busn_id', '=', 'b.id')
                                                            ->where('b.app_code', '=', 1)
                                                            ->where('b.is_active', '=', 1);
                                                    })
                                                    ->join('bplo_endorsing_dept as c', function ($join) {
                                                        $join->on('a.endorsing_dept_id', '=', 'c.id')
                                                            ->where('c.id', '=', 1);
                                                    })
                                                    ->whereRaw('DATE_FORMAT(a.created_at, "%Y") = YEAR(curdate())')
                                                    ->first();
            $data['businessAppStatusRenewToday'] = DB::table('bplo_business_endorsement as a')
                                                    ->select(DB::raw('COUNT(DISTINCT a.id) as today'))
                                                    ->join('bplo_business as b', function ($join) {
                                                        $join->on('a.busn_id', '=', 'b.id')
                                                            ->where('b.app_code', '=', 2)
                                                            ->where('b.is_active', '=', 1);
                                                    })
                                                    ->join('bplo_endorsing_dept as c', function ($join) {
                                                        $join->on('a.endorsing_dept_id', '=', 'c.id')
                                                            ->where('c.id', '=', 1);
                                                    })
                                                    ->whereRaw('DATE_FORMAT(a.created_at, "%Y-%m-%d") = DATE_FORMAT(curdate(), "%Y-%m-%d")')
                                                    ->first();
            $data['businessAppStatusRenewMonth'] = DB::table('bplo_business_endorsement as a')
                                                    ->select(DB::raw('COUNT(DISTINCT a.id) as this_month'))
                                                    ->join('bplo_business as b', function ($join) {
                                                        $join->on('a.busn_id', '=', 'b.id')
                                                            ->where('b.app_code', '=', 2)
                                                            ->where('b.is_active', '=', 1);
                                                    })
                                                    ->join('bplo_endorsing_dept as c', function ($join) {
                                                        $join->on('a.endorsing_dept_id', '=', 'c.id')
                                                            ->where('c.id', '=', 1);
                                                    })
                                                    ->whereRaw('DATE_FORMAT(a.created_at, "%Y-%m") = DATE_FORMAT(curdate(), "%Y-%m")')
                                                    ->first();
            $data['businessAppStatusRenewYear'] = DB::table('bplo_business_endorsement as a')
                                                    ->select(DB::raw('COUNT(DISTINCT a.id) as year'))
                                                    ->join('bplo_business as b', function ($join) {
                                                        $join->on('a.busn_id', '=', 'b.id')
                                                            ->where('b.app_code', '=', 2)
                                                            ->where('b.is_active', '=', 1);
                                                    })
                                                    ->join('bplo_endorsing_dept as c', function ($join) {
                                                        $join->on('a.endorsing_dept_id', '=', 'c.id')
                                                            ->where('c.id', '=', 1);
                                                    })
                                                    ->whereRaw('DATE_FORMAT(a.created_at, "%Y") = YEAR(curdate())')
                                                    ->first();
            $data['top5Taxpayer'] = DB::table('bfp_application_assessments as a')
                                                    ->select('c.full_name as taxpayer_name', DB::raw('SUM(a.bfpas_total_amount) as total_amt'))
                                                    ->join('bplo_business as b', 'a.busn_id', '=', 'b.id')
                                                    ->join('clients as c', 'b.client_id', '=', 'c.id')
                                                    ->where('a.bfpas_total_amount', '>', 0)
                                                    ->where('a.bfpas_is_fully_paid', '=', 1)
                                                    ->where('a.bfpas_payment_or_no', '!=', '')
                                                    ->where('a.payment_status', '=', 1)
                                                    ->where('a.ocr_id', '=', 0)
                                                    ->groupBy('c.id')
                                                    ->orderByDesc(DB::raw('SUM(a.bfpas_total_amount)'))
                                                    ->limit(5)
                                                    ->get();
                $data['incomeToday'] = DB::table('bfp_application_assessments as a')
                                                    ->select(DB::raw('SUM(a.bfpas_total_amount) as today'))
                                                    ->where('a.bfpas_total_amount', '>', 0)
                                                    ->where('a.bfpas_is_fully_paid', '=', 1)
                                                    ->where('a.bfpas_payment_or_no', '!=', '')
                                                    ->where('a.payment_status', '=', 1)
                                                    ->where('a.ocr_id', '=', 0)
                                                    ->whereDate('a.bfpas_date_paid', '=', now()->format('Y-m-d'))
                                                    ->first();
                $data['incomeThisMonth'] = DB::table('bfp_application_assessments as a')
                                                    ->select(DB::raw('SUM(a.bfpas_total_amount) as this_month'))
                                                    ->where('a.bfpas_total_amount', '>', 0)
                                                    ->where('a.bfpas_is_fully_paid', '=', 1)
                                                    ->where('a.bfpas_payment_or_no', '!=', '')
                                                    ->where('a.payment_status', '=', 1)
                                                    ->where('a.ocr_id', '=', 0)
                                                    ->whereRaw('DATE_FORMAT(a.bfpas_date_paid, "%Y-%m") = DATE_FORMAT(curdate(), "%Y-%m")')
                                                    ->first();
                $data['incomeThisYear']  = DB::table('bfp_application_assessments as a')
                                                    ->select(DB::raw('SUM(a.bfpas_total_amount) as this_year'))
                                                    ->where('a.bfpas_total_amount', '>', 0)
                                                    ->where('a.bfpas_is_fully_paid', '=', 1)
                                                    ->where('a.bfpas_payment_or_no', '!=', '')
                                                    ->where('a.payment_status', '=', 1)
                                                    ->where('a.ocr_id', '=', 0)
                                                    ->whereRaw('DATE_FORMAT(a.bfpas_date_paid, "%Y") = DATE_FORMAT(curdate(), "%Y")')
                                                    ->first();
                $data['receiptToday'] = DB::table('bfp_application_assessments as a')
                                                    ->select(DB::raw('COUNT(a.id) as today'))
                                                    ->where('a.bfpas_total_amount', '>', 0)
                                                    ->where('a.bfpas_is_fully_paid', '=', 1)
                                                    ->where('a.bfpas_payment_or_no', '!=', '')
                                                    ->where('a.payment_status', '=', 1)
                                                    ->where('a.ocr_id', '=', 0)
                                                    ->whereDate('a.bfpas_date_paid', '=', now()->format('Y-m-d'))
                                                    ->first();
                $data['receiptThisMonth'] = DB::table('bfp_application_assessments as a')
                                                    ->select(DB::raw('COUNT(a.id) as this_month'))
                                                    ->where('a.bfpas_total_amount', '>', 0)
                                                    ->where('a.bfpas_is_fully_paid', '=', 1)
                                                    ->where('a.bfpas_payment_or_no', '!=', '')
                                                    ->where('a.payment_status', '=', 1)
                                                    ->where('a.ocr_id', '=', 0)
                                                    ->whereRaw('DATE_FORMAT(a.bfpas_date_paid, "%Y-%m") = DATE_FORMAT(curdate(), "%Y-%m")')
                                                    ->first();
                $data['receiptThisYear']  = DB::table('bfp_application_assessments as a')
                                                    ->select(DB::raw('COUNT(a.id) as this_year'))
                                                    ->where('a.bfpas_total_amount', '>', 0)
                                                    ->where('a.bfpas_is_fully_paid', '=', 1)
                                                    ->where('a.bfpas_payment_or_no', '!=', '')
                                                    ->where('a.payment_status', '=', 1)
                                                    ->where('a.ocr_id', '=', 0)
                                                    ->whereRaw('DATE_FORMAT(a.bfpas_date_paid, "%Y") = DATE_FORMAT(curdate(), "%Y")')
                                                    ->first();
                                                                                      
                return view('dashboard.fireProtectionDashboard',$data);  
            case 'environmental':
                $data['bplo_business_endorsment'] =$this->_BploBusiness->getAllBploBusnEnd($currentYear);
                $data['top_business_endorsment'] =  DB::table('bplo_business_endorsement as a')
                                                                ->join('bplo_endorsing_dept as b', 'a.endorsing_dept_id', '=', 'b.id')
                                                                ->join('bplo_business as c', 'a.busn_id', '=', 'c.id')
                                                                ->join('barangays as d', 'c.busn_office_barangay_id', '=', 'd.id')
                                                                ->where('a.bend_year', '=', DB::raw('YEAR(CURDATE())'))
                                                                ->where('b.id', '=', 4)
                                                                ->groupBy('d.id')
                                                                ->select('d.brgy_name as barangay','d.id as barangay_id')
                                                                ->get();
                $data['inspectionRptPending'] = DB::table('bplo_business_endorsement as a')
                                                                ->select(DB::raw('COUNT(a.id) as Pending For Inspection Report'))
                                                                ->join('bplo_business as c', function ($join) {
                                                                    $join->on('a.busn_id', '=', 'c.id')
                                                                        ->where('c.is_active', '=', 1);
                                                                })
                                                                ->where('a.bend_year', '=', now()->year)
                                                                ->where('a.endorsing_dept_id', '=', 4)
                                                                ->where('a.bend_status', '<>', 3)
                                                                ->whereNotExists(function ($query) {
                                                                    $query->select('b.id')
                                                                        ->from('enro_bplo_inspection_report as b')
                                                                        ->whereRaw('b.busn_id = a.busn_id')
                                                                        ->whereRaw('b.bend_id = a.id')
                                                                        ->where('b.ebir_status', '=', 1);
                                                                })
                                                                ->count();  
                $data['inspectionRptDraft'] = DB::table('enro_bplo_inspection_report as a')
                                                                ->select(DB::raw('COUNT(a.id) as Draft Inspection Report'))
                                                                ->join('bplo_business as b', function ($join) {
                                                                    $join->on('a.busn_id', '=', 'b.id')
                                                                        ->where('b.is_active', '=', 1);
                                                                })
                                                                ->join('bplo_business_endorsement as c', function ($join) {
                                                                    $join->on('a.busn_id', '=', 'c.busn_id')
                                                                        ->on('a.bend_id', '=', 'c.id')
                                                                        ->where('c.bend_status', '<>', 3);
                                                                })
                                                                ->where('a.ebir_year', '=', now()->year)
                                                                ->where('a.ebir_status', '=', 1)
                                                                ->where('a.ebir_inspected_status', '=', 0)
                                                                ->where('a.ebir_approved_status', '=', 0)
                                                                ->count();  
                $data['inspectionRptInspected'] = DB::table('enro_bplo_inspection_report as a')
                                            ->select(DB::raw('COUNT(a.id) as Inspected Inspection Report'))
                                            ->join('bplo_business as b', function ($join) {
                                                $join->on('a.busn_id', '=', 'b.id')
                                                    ->where('b.is_active', '=', 1);
                                            })
                                            ->join('bplo_business_endorsement as c', function ($join) {
                                                $join->on('a.busn_id', '=', 'c.busn_id')
                                                    ->on('a.bend_id', '=', 'c.id')
                                                    ->where('c.bend_status', '<>', 3);
                                            })
                                            ->where('a.ebir_year', '=', now()->year)
                                            ->where('a.ebir_status', '=', 1)
                                            ->where('a.ebir_inspected_status', '=', 1)
                                            ->where('a.ebir_inspected_by', '>', 0)
                                            ->where('a.ebir_approved_status', '=', 0)
                                            ->count();
                $data['inspectionRptApproved'] = DB::table('enro_bplo_inspection_report as a')
                                                ->select(DB::raw('COUNT(a.id) as Approved Inspection Report'))
                                                ->join('bplo_business as b', function ($join) {
                                                    $join->on('a.busn_id', '=', 'b.id')
                                                        ->where('b.is_active', '=', 1);
                                                })
                                                ->join('bplo_business_endorsement as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.busn_id')
                                                        ->on('a.bend_id', '=', 'c.id')
                                                        ->where('c.bend_status', '<>', 3);
                                                })
                                                ->where('a.ebir_year', '=', now()->year)
                                                ->where('a.ebir_status', '=', 1)
                                                ->where('a.ebir_inspected_status', '=', 1)
                                                ->where('a.ebir_inspected_by', '>', 0)
                                                ->where('a.ebir_approved_status', '=', 1)
                                                ->where('a.ebir_approved_by', '>', 0)
                                                ->count(); 
                $data['envClearancePending'] = DB::table('bplo_business_endorsement as a')
                                                ->select(DB::raw('COUNT(a.id) as Pending For Environmental Clearance'))
                                                ->join('bplo_business as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.id')
                                                        ->where('c.is_active', '=', 1);
                                                })
                                                ->where('a.bend_year', '=', now()->year)
                                                ->where('a.endorsing_dept_id', '=', 4)
                                                ->where('a.bend_status', '<>', 3)
                                                ->whereNotExists(function ($query) {
                                                    $query->select('b.id')
                                                        ->from('enro_bplo_app_clearances as b')
                                                        ->whereRaw('b.busn_id = a.busn_id')
                                                        ->whereRaw('b.bend_id = a.id');
                                                })
                                                ->count();  
                $data['envClearanceDraft'] = DB::table('enro_bplo_app_clearances as a')
                                            ->select(DB::raw('COUNT(a.id) as Draft For Environmental Clearance'))
                                            ->join('bplo_business as b', function ($join) {
                                                $join->on('a.busn_id', '=', 'b.id')
                                                    ->where('b.is_active', '=', 1);
                                            })
                                            ->join('bplo_business_endorsement as c', function ($join) {
                                                $join->on('a.busn_id', '=', 'c.busn_id')
                                                    ->on('a.bend_id', '=', 'c.id')
                                                    ->where('c.bend_status', '<>', 3);
                                            })
                                            ->where('a.ebac_app_year', '=', now()->year)
                                            ->where('a.ebac_status', '=', 1)
                                            ->where('a.ebac_approved_by_status', '=', 0)
                                            ->count();
                $data['envClearanceCancelled'] = DB::table('enro_bplo_app_clearances as a')
                                                ->select(DB::raw('COUNT(a.id) as Draft For Environmental Clearance'))
                                                ->join('bplo_business as b', function ($join) {
                                                    $join->on('a.busn_id', '=', 'b.id')
                                                        ->where('b.is_active', '=', 1);
                                                })
                                                ->join('bplo_business_endorsement as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.busn_id')
                                                        ->on('a.bend_id', '=', 'c.id')
                                                        ->where('c.bend_status', '<>', 3);
                                                })
                                                ->where('a.ebac_app_year', '=', now()->year)
                                                ->where('a.ebac_status', '=', 0)
                                                ->count(); 
                $data['envClearanceApproved'] = DB::table('enro_bplo_app_clearances as a')
                                                ->select(DB::raw('COUNT(a.id) as Draft For Environmental Clearance'))
                                                ->join('bplo_business as b', function ($join) {
                                                    $join->on('a.busn_id', '=', 'b.id')
                                                        ->where('b.is_active', '=', 1);
                                                })
                                                ->join('bplo_business_endorsement as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.busn_id')
                                                        ->on('a.bend_id', '=', 'c.id')
                                                        ->where('c.bend_status', '<>', 3);
                                                })
                                                ->where('a.ebac_app_year', '=', now()->year)
                                                ->where('a.ebac_status', '=', 1)
                                                ->where('a.ebac_approved_by_status', '>', 0)
                                                ->count();
                $data['top5Barangay'] = DB::table('enro_bplo_inspection_report as a')
                                                ->select('d.brgy_name as barangay', DB::raw('COUNT(a.id) as total'))
                                                ->join('bplo_business as b', function ($join) {
                                                    $join->on('a.busn_id', '=', 'b.id')
                                                        ->where('b.is_active', '=', 1);
                                                })
                                                ->join('bplo_business_endorsement as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.busn_id')
                                                        ->on('a.bend_id', '=', 'c.id')
                                                        ->where('c.bend_status', '<>', 3);
                                                })
                                                ->join('barangays as d', 'b.busn_office_barangay_id', '=', 'd.id')
                                                ->where('a.ebir_year', '=', now()->year)
                                                ->groupBy('d.id')
                                                ->orderByDesc(DB::raw('COUNT(a.id)'))
                                                ->limit(5)
                                                ->get();
                $data['env_clearance_active'] = DB::table('enro_bplo_app_clearances as a')
                                                ->select(DB::raw('COUNT(a.id) as Active Environmental Clearance'))
                                                ->join('bplo_business as b', function ($join) {
                                                    $join->on('a.busn_id', '=', 'b.id')
                                                        ->where('b.is_active', '=', 1);
                                                })
                                                ->join('bplo_business_endorsement as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.busn_id')
                                                        ->on('a.bend_id', '=', 'c.id')
                                                        ->where('c.bend_status', '<>', 3);
                                                })
                                                ->where('a.ebac_app_year', '=', now()->year)
                                                ->where('a.ebac_status', '=', 1)
                                                ->count();
                $data['env_clearance_cancelled'] =  DB::table('enro_bplo_app_clearances as a')
                                                ->select(DB::raw('COUNT(a.id) as Active Environmental Clearance'))
                                                ->join('bplo_business as b', function ($join) {
                                                    $join->on('a.busn_id', '=', 'b.id')
                                                        ->where('b.is_active', '=', 1);
                                                })
                                                ->join('bplo_business_endorsement as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.busn_id')
                                                        ->on('a.bend_id', '=', 'c.id')
                                                        ->where('c.bend_status', '<>', 3);
                                                })
                                                ->where('a.ebac_app_year', '=', now()->year)
                                                ->where('a.ebac_status', '=', 0)
                                                ->count();
                $data['top5BarangayEnvClearance'] =DB::table('enro_bplo_app_clearances as a')
                                                ->select('d.brgy_name as barangay', DB::raw('COUNT(a.id) as total'))
                                                ->join('bplo_business as b', function ($join) {
                                                    $join->on('a.busn_id', '=', 'b.id')
                                                        ->where('b.is_active', '=', 1);
                                                })
                                                ->join('bplo_business_endorsement as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.busn_id')
                                                        ->on('a.bend_id', '=', 'c.id')
                                                        ->where('c.bend_status', '<>', 3);
                                                })
                                                ->join('barangays as d', 'b.busn_office_barangay_id', '=', 'd.id')
                                                ->where('a.ebac_app_year', '=', now()->year)
                                                ->groupBy('d.id')
                                                ->orderByDesc(DB::raw('COUNT(a.id)'))
                                                ->limit(5)
                                                ->get();
                $data['inspStatusRptCancelled']=DB::table('enro_bplo_inspection_report as a')
                                                ->select(DB::raw('COUNT(a.id) as Cancelled Inspection Report'))
                                                ->join('bplo_business as b', function ($join) {
                                                    $join->on('a.busn_id', '=', 'b.id')
                                                        ->where('b.is_active', '=', 1);
                                                })
                                                ->join('bplo_business_endorsement as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.busn_id')
                                                        ->on('a.bend_id', '=', 'c.id')
                                                        ->where('c.bend_status', '<>', 3);
                                                })
                                                ->where('a.ebir_year', '=', now()->year)
                                                ->where('a.ebir_status', '=', 0)
                                                ->count();   
                $data['inspStatusRptActive']= DB::table('enro_bplo_inspection_report as a')
                                                ->select(DB::raw('COUNT(a.id) as Active Inspection Report'))
                                                ->join('bplo_business as b', function ($join) {
                                                    $join->on('a.busn_id', '=', 'b.id')
                                                        ->where('b.is_active', '=', 1);
                                                })
                                                ->join('bplo_business_endorsement as c', function ($join) {
                                                    $join->on('a.busn_id', '=', 'c.busn_id')
                                                        ->on('a.bend_id', '=', 'c.id')
                                                        ->where('c.bend_status', '<>', 3);
                                                })
                                                ->where('a.ebir_year', '=', now()->year)
                                                ->where('a.ebir_status', '=', 1)
                                                ->count();                             

                return view('dashboard.environmentalDashboard',$data);
            case 'general-services':      
                return view('dashboard.gsoDashboard',$data);
            case 'accounting':      
                return view('dashboard.accountingDashboard',$data);
            case 'human-resource':      
                return view('dashboard.humanResourceDashboard',$data);
            case 'economic-investment':      
                return view('dashboard.economicInvestmentDashboard',$data);
            default:
                $data['isCustomerPermitted'] = $this->is_dash_permitted('cpdo-customers');
                $data['isVendorPermitted'] = $this->is_dash_permitted('cpdo-vendors');
                $data['isInvoicePermitted'] = $this->is_dash_permitted('cpdo-invoices');
                $data['isBillsPermitted'] = $this->is_dash_permitted('cpdo-bills');
                return view('dashboard.dashboardNew',$data);
        }
    }
    public function project_dashboard_index()
    {
        $user = Auth::user();     
            $this->is_permitted($this->slugs, 'read');
            if($user->type == 'admin')
            {
                return view('admin.dashboard');
            }
            else
            {
                $home_data = [];

                $user_projects   = $user->projects()->pluck('project_id')->toArray();
                $project_tasks   = ProjectTask::whereIn('project_id', $user_projects)->get();
                $project_expense = Expense::whereIn('project_id', $user_projects)->get();
                $seven_days      = Utility::getLastSevenDays();

                // Total Projects
                $complete_project           = $user->projects()->where('status', 'LIKE', 'complete')->count();
                $home_data['total_project'] = [
                    'total' => count($user_projects),
                    'percentage' => Utility::getPercentage($complete_project, count($user_projects)),
                ];

                // Total Tasks
                $complete_task           = ProjectTask::where('is_complete', '=', 1)->whereRaw("find_in_set('" . $user->id . "',assign_to)")->whereIn('project_id', $user_projects)->count();
                $home_data['total_task'] = [
                    'total' => $project_tasks->count(),
                    'percentage' => Utility::getPercentage($complete_task, $project_tasks->count()),
                ];

                // Total Expense
                $total_expense        = 0;
                $total_project_amount = 0;
                foreach($user->projects as $pr)
                {
                    $total_project_amount += $pr->budget;
                }
                foreach($project_expense as $expense)
                {
                    $total_expense += $expense->amount;
                }
                $home_data['total_expense'] = [
                    'total' => $project_expense->count(),
                    'percentage' => Utility::getPercentage($total_expense, $total_project_amount),
                ];

                // Total Users
                $home_data['total_user'] = Auth::user()->contacts->count();

                // Tasks Overview Chart & Timesheet Log Chart
                $task_overview    = [];
                $timesheet_logged = [];
                foreach($seven_days as $date => $day)
                {
                    // Task
                    $task_overview[$day] = ProjectTask::where('is_complete', '=', 1)->where('marked_at', 'LIKE', $date)->whereIn('project_id', $user_projects)->count();

                    // Timesheet
                    $time                   = Timesheet::whereIn('project_id', $user_projects)->where('date', 'LIKE', $date)->pluck('time')->toArray();
                    $timesheet_logged[$day] = str_replace(':', '.', Utility::calculateTimesheetHours($time));
                }

                $home_data['task_overview']    = $task_overview;
                $home_data['timesheet_logged'] = $timesheet_logged;

                // Project Status
                $total_project  = count($user_projects);
                $project_status = [];
                foreach(Project::$project_status as $k => $v)
                {
                    $project_status[$k]['total']      = $user->projects->where('status', 'LIKE', $k)->count();
                    $project_status[$k]['percentage'] = Utility::getPercentage($project_status[$k]['total'], $total_project);
                }
                $home_data['project_status'] = $project_status;

                // Top Due Project
                $home_data['due_project'] = $user->projects()->orderBy('end_date', 'DESC')->limit(5)->get();

                // Top Due Tasks
                $home_data['due_tasks'] = ProjectTask::where('is_complete', '=', 0)->whereIn('project_id', $user_projects)->orderBy('end_date', 'DESC')->limit(5)->get();

                $home_data['last_tasks'] = ProjectTask::whereIn('project_id', $user_projects)->orderBy('end_date', 'DESC')->limit(5)->get();

                return view('dashboard.project-dashboard', compact('home_data'));
            }
    }

    public function hrm_dashboard_index()
    {
        if(Auth::check())
        {   
            
            $this->is_permitted($this->slugs, 'read');
           
                $user = Auth::user();
                if($user->type != 'client' && $user->type != 'company')
                {
                    $emp = Employee::where('user_id', '=', $user->id)->first();

                    $announcements = Announcement::orderBy('announcements.id', 'desc')->take(5)->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')->where('announcement_employees.employee_id', '=', $emp->id)->orWhere(
                        function ($q){
                            $q->where('announcements.department_id', '["0"]')->where('announcements.employee_id', '["0"]');
                        }
                    )->get();

                    $employees = Employee::get();
                    $meetings  = Meeting::orderBy('meetings.id', 'desc')->take(5)->leftjoin('meeting_employees', 'meetings.id', '=', 'meeting_employees.meeting_id')->where('meeting_employees.employee_id', '=', $emp->id)->orWhere(
                        function ($q){
                            $q->where('meetings.department_id', '["0"]')->where('meetings.employee_id', '["0"]');
                        }
                    )->get();
                    $events    = Event::leftjoin('event_employees', 'events.id', '=', 'event_employees.event_id')->where('event_employees.employee_id', '=', $emp->id)->orWhere(
                        function ($q){
                            $q->where('events.department_id', '["0"]')->where('events.employee_id', '["0"]');
                        }
                    )->get();

                    $arrEvents = [];
                    foreach($events as $event)
                    {

                        $arr['id']              = $event['id'];
                        $arr['title']           = $event['title'];
                        $arr['start']           = $event['start_date'];
                        $arr['end']             = $event['end_date'];
                        $arr['backgroundColor'] = $event['color'];
                        $arr['borderColor']     = "#fff";
                        $arr['textColor']       = "white";
                        $arrEvents[]            = $arr;
                    }

                    $date               = date("Y-m-d");
                    $time               = date("H:i:s");
                    $employeeAttendance = AttendanceEmployee::orderBy('id', 'desc')->where('employee_id', '=', !empty(\Auth::user()->employee) ? \Auth::user()->employee->id : 0)->where('date', '=', $date)->first();

                    $officeTime['startTime'] = Utility::getValByName('company_start_time');
                    $officeTime['endTime']   = Utility::getValByName('company_end_time');

                    return view('dashboard.dashboard', compact('arrEvents', 'announcements', 'employees', 'meetings', 'employeeAttendance', 'officeTime'));
                }

                else
                {
                    $events    = Event::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $arrEvents = [];

                    foreach($events as $event)
                    {
                        $arr['id']    = $event['id'];
                        $arr['title'] = $event['title'];
                        $arr['start'] = $event['start_date'];
                        $arr['end']   = $event['end_date'];

                        $arr['backgroundColor'] = $event['color'];
                        $arr['borderColor']     = "#fff";
                        $arr['textColor']       = "white";
                        $arr['url']             = route('event.edit', $event['id']);

                        $arrEvents[] = $arr;
                    }


                    $announcements = Announcement::orderBy('announcements.id', 'desc')->take(5)->where('created_by', '=', \Auth::user()->creatorId())->get();


                    $emp           = User::where('type', '!=', 'client')->where('type', '!=', 'company')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $countEmployee = count($emp);

                    $user      = User::where('type', '!=', 'client')->where('type', '!=', 'company')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $countUser = count($user);


                    $countTrainer    = Trainer::where('created_by', '=', \Auth::user()->creatorId())->count();
                    $onGoingTraining = Training::where('status', '=', 1)->where('created_by', '=', \Auth::user()->creatorId())->count();
                    $doneTraining    = Training::where('status', '=', 2)->where('created_by', '=', \Auth::user()->creatorId())->count();

                    $currentDate = date('Y-m-d');

                    $employees   = User::where('type', '=', 'client')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $countClient = count($employees);
                    $notClockIn  = AttendanceEmployee::where('date', '=', $currentDate)->get()->pluck('employee_id');

                    $notClockIns = Employee::where('created_by', '=', \Auth::user()->creatorId())->whereNotIn('id', $notClockIn)->get();
                    $activeJob   = Job::where('status', 'active')->where('created_by', '=', \Auth::user()->creatorId())->count();
                    $inActiveJOb = Job::where('status', 'in_active')->where('created_by', '=', \Auth::user()->creatorId())->count();


                    $meetings = Meeting::where('created_by', '=', \Auth::user()->creatorId())->limit(5)->get();

                    return view('dashboard.dashboard', compact('arrEvents', 'onGoingTraining', 'activeJob', 'inActiveJOb', 'doneTraining', 'announcements', 'employees', 'meetings', 'countTrainer', 'countClient', 'countUser', 'notClockIns', 'countEmployee'));
                }
            
        }
        else
        {
            if(!file_exists(storage_path() . "/installed"))
            {
                header('location:install');
                die;
            }
            else
            {
                $settings = Utility::settings();
                if($settings['display_landing_page'] == 'on')
                {


                    return view('layouts.landing');
                }
                else
                {
                    return redirect('login');
                }

            }
        }
    }

    // Load Dashboard user's using ajax
    public function filterView(Request $request)
    {
        $usr   = Auth::user();
        $users = User::where('id', '!=', $usr->id);

        if($request->ajax())
        {
            if(!empty($request->keyword))
            {
                $users->where('name', 'LIKE', $request->keyword . '%')->orWhereRaw('FIND_IN_SET("' . $request->keyword . '",skills)');
            }

            $users      = $users->get();
            $returnHTML = view('dashboard.view', compact('users'))->render();

            return response()->json(
                [
                    'success' => true,
                    'html' => $returnHTML,
                ]
            );
        }
    }

    public function clientView()
    {

        if(Auth::check())
        {   
            
            $this->is_permitted($this->slugs, 'read');
            if(Auth::user()->type == 'client')
            {
                $transdate = date('Y-m-d', time());
                $currentYear  = date('Y');

                $calenderTasks = [];
                $chartData     = [];
                $arrCount      = [];
                $arrErr        = [];
                $m             = date("m");
                $de            = date("d");
                $y             = date("Y");
                $format        = 'Y-m-d';
                $user          = \Auth::user();
                
                    $company_setting = Utility::settings();
               
                $arrTemp = [];
                for($i = 0; $i <= 7 - 1; $i++)
                {
                    $date                 = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
                    $arrTemp['date'][]    = __(date('D', strtotime($date)));
                    $arrTemp['invoice'][] = 10;
                    $arrTemp['payment'][] = 20;
                }

                $chartData = $arrTemp;

                foreach($user->clientDeals as $deal)
                {
                    foreach($deal->tasks as $task)
                    {
                        $calenderTasks[] = [
                            'title' => $task->name,
                            'start' => $task->date,
                            'url' => route(
                                'deals.tasks.show', [
                                                      $deal->id,
                                                      $task->id,
                                                  ]
                            ),
                            'className' => ($task->status) ? 'bg-success border-success' : 'bg-warning border-warning',
                        ];
                    }

                    $calenderTasks[] = [
                        'title' => $deal->name,
                        'start' => $deal->created_at->format('Y-m-d'),
                        'url' => route('deals.show', [$deal->id]),
                        'className' => 'deal bg-primary border-primary',
                    ];
                }
                $client_deal = $user->clientDeals->pluck('id');

                $arrCount['deal'] = $user->clientDeals->count();
                if(!empty($client_deal->first()))
                {
                    $arrCount['task'] = DealTask::whereIn('deal_id', [$client_deal])->count();
                }
                else
                {
                    $arrCount['task'] = 0;
                }


                $project['projects']             = Project::where('client_id', '=', Auth::user()->id)->where('created_by', \Auth::user()->creatorId())->where('end_date', '>', date('Y-m-d'))->limit(5)->orderBy('end_date')->get();
                $project['projects_count']       = count($project['projects']);
                $user_projects                   = Project::where('client_id', \Auth::user()->id)->pluck('id', 'id')->toArray();
                $tasks                           = ProjectTask::whereIn('project_id', $user_projects)->where('created_by', \Auth::user()->creatorId())->get();
                $project['projects_tasks_count'] = count($tasks);
                $project['project_budget']       = Project::where('client_id', Auth::user()->id)->sum('budget');

                $project_last_stages      = Auth::user()->last_projectstage();
                $project_last_stage       = (!empty($project_last_stages) ? $project_last_stages->id : 0);
                $project['total_project'] = Auth::user()->user_project();
                $total_project_task       = Auth::user()->created_total_project_task();
                $allProject               = Project::where('client_id', \Auth::user()->id)->where('created_by', \Auth::user()->creatorId())->get();
                $allProjectCount          = count($allProject);

                $bugs                               = Bug::whereIn('project_id', $user_projects)->where('created_by', \Auth::user()->creatorId())->get();
                $project['projects_bugs_count']     = count($bugs);
                $bug_last_stage                     = BugStatus::orderBy('order', 'DESC')->first();
                $completed_bugs                     = Bug::whereIn('project_id', $user_projects)->where('status', $bug_last_stage->id)->where('created_by', \Auth::user()->creatorId())->get();
                $allBugCount                        = count($bugs);
                $completedBugCount                  = count($completed_bugs);
                $project['project_bug_percentage']  = ($allBugCount != 0) ? intval(($completedBugCount / $allBugCount) * 100) : 0;
                $complete_task                      = Auth::user()->project_complete_task($project_last_stage);
                $completed_project                  = Project::where('client_id', \Auth::user()->id)->where('status', 'complete')->where('created_by', \Auth::user()->creatorId())->get();
                $completed_project_count            = count($completed_project);
                $project['project_percentage']      = ($allProjectCount != 0) ? intval(($completed_project_count / $allProjectCount) * 100) : 0;
                $project['project_task_percentage'] = ($total_project_task != 0) ? intval(($complete_task / $total_project_task) * 100) : 0;
                $invoice                            = [];
                $top_due_invoice                    = [];
                $invoice['total_invoice']           = 5;
                $complete_invoice                   = 0;
                $total_due_amount                   = 0;
                $top_due_invoice                    = array();
                $pay_amount                         = 0;

                if(Auth::user()->type == 'client')
                {
                    if(!empty($project['project_budget']))
                    {
                        $project['client_project_budget_due_per'] = intval(($pay_amount / $project['project_budget']) * 100);
                    }
                    else
                    {
                        $project['client_project_budget_due_per'] = 0;
                    }

                }

                $top_tasks       = Auth::user()->created_top_due_task();
                $users['staff']  = User::where('created_by', '=', Auth::user()->creatorId())->count();
                $users['user']   = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '!=', 'client')->count();
                $users['client'] = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '=', 'client')->count();
                $project_status  = array_values(Project::$project_status);
                $projectData     = \App\Models\Project::getProjectStatus();

                $taskData        = \App\Models\TaskStage::getChartData();

                return view('dashboard.clientView', compact('calenderTasks', 'arrErr', 'arrCount', 'chartData', 'project', 'invoice', 'top_tasks', 'top_due_invoice', 'users', 'project_status', 'projectData', 'taskData','transdate','currentYear'));
            }
        }
    }

    public function getOrderChart($arrParam)
    {
        $arrDuration = [];
        if($arrParam['duration'])
        {
            if($arrParam['duration'] == 'week')
            {
                $previous_week = strtotime("-2 week +1 day");
                for($i = 0; $i < 14; $i++)
                {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }

        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];
        foreach($arrDuration as $date => $label)
        {

            $data               = Order::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][]  = $data->total;
        }

        return $arrTask;
    }

    public function stopTracker(Request $request)
    {
        if(Auth::user()->isClient())
        {
            return Utility::error_res(__('Permission denied.'));
        }
        $validatorArray = [
            'name' => 'required|max:120',
            'project_id' => 'required|integer',
        ];
        $validator      = Validator::make(
            $request->all(), $validatorArray
        );
        if($validator->fails())
        {
            return Utility::error_res($validator->errors()->first());
        }
        $tracker = TimeTracker::where('created_by', '=', Auth::user()->id)->where('is_active', '=', 1)->first();
        if($tracker)
        {
            $tracker->end_time   = $request->has('end_time') ? $request->input('end_time') : date("Y-m-d H:i:s");
            $tracker->is_active  = 0;
            $tracker->total_time = Utility::diffance_to_time($tracker->start_time, $tracker->end_time);
            $tracker->save();

            return Utility::success_res(__('Add Time successfully.'));
        }

        return Utility::error_res('Tracker not found.');
    }

}
