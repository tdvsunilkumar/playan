<?php

namespace App\Http\Controllers;
use App\Models\Faq;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentFAQInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class FAQController extends Controller
{
    private ComponentFAQInterface $componentFAQRepository;
    private $carbon;
    private $slugs;

    public function __construct(ComponentFAQInterface $componentFAQRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentFAQRepository = $componentFAQRepository;
        $this->carbon = $carbon;
        $this->slugs = 'faq';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $faqs = $this->componentFAQRepository->lists();
        $groups = $this->componentFAQRepository->allGroupMenus();
        return view('faq.index')->with(compact('faqs', 'groups'));
    }

    public function lists(Request $request): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->componentFAQRepository->lists($request->get('keywords'), $request->get('group'))
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->componentFAQRepository->find($id),
            'details' => $this->componentFAQRepository->find_details($id)
        ]);
    }
}
