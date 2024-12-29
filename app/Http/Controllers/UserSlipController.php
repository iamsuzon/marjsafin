<?php

namespace App\Http\Controllers;

use App\Models\Slip;
use App\Models\SlipMedicalCenterRate;
use App\Models\SlipPayment;
use App\Models\StaticOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSlipController extends Controller
{
    public function slipRegistration()
    {
        if (!hasSlipPermission()) {
            if (hasMedicalPermission()) {
                return redirect()->route('user.registration');
            }

            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }

        $showNotice = (bool) StaticOption::getOption('show_notice');
        $noticeText = StaticOption::getOption('notice_text') ?? '';

        return view('user.slip.slip-registration', [
            'showNotice' => $showNotice,
            'noticeText' => fixNoticeText($noticeText),
        ]);
    }

    public function storeSlip(Request $request)
    {
        $validated = $request->validate([
            'slip_type' => 'required',
            'passport_number' => 'required|unique:slips,passport_number',
            'gender' => 'required',
            'city_id' => 'required|integer',
            'center_slug' => 'required',
            'marital_status' => 'nullable',
            'surname' => 'required',
            'given_name' => 'required',
            'father_name' => 'nullable',
            'mother_name' => 'nullable',
            'religion' => 'nullable',
            'pp_issue_place' => 'nullable',
            'profession' => 'required',
            'nationality' => 'required',
            'date_of_birth' => 'required',
            'nid_no' => 'nullable|numeric',
            'passport_issue_date' => 'required',
            'passport_expiry_date' => 'required',
            'ref_no' => 'required',
        ]);

        $validated['user_id'] = Auth::guard('web')->id();
        $validated['pdf_code'] = now()->format('Ym').'-'.rand(1, 999999);

        $slip_rate = SlipMedicalCenterRate::where('medical_center_slug', $validated['center_slug'])->first();
        if (empty($slip_rate)) {
            return back()->with('error', 'Medical center rate not found.');
        }


        try {
            $slip = Slip::create($validated);

            SlipPayment::create([
                'slip_id' => $slip->id,
                'slip_rate' => $slip_rate->rate,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong. Please try again.');
        }

        return back()->with('success', 'Slip submitted successfully.')->with('url', route('user.slip.list'));
    }

    public function slipList()
    {
        if (!hasSlipPermission()) {
            if (hasMedicalPermission()) {
                return redirect()->route('user.application.list');
            }

            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }

        $user_id = Auth::guard('web')->id();

        $slipList = Slip::query();

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $slipList = $slipList->where('user_id', $user_id)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->latest()
                ->paginate(20)
                ->withQueryString();
        }
        else if(request()->has('passport_search'))
        {
            if (request('passport_search') == null) {
                return back()->with('error', 'Please enter passport number.');
            }

            $slipList = $slipList->where('user_id', $user_id)
                ->where('passport_number', trim(request('passport_search')))
                ->latest()
                ->paginate(20);
        }
        else {
            $slipList = $slipList->where('user_id', $user_id)
                ->whereDate('created_at', Carbon::today())
                ->latest()
                ->paginate(20)
                ->withQueryString();
        }

        return view('user.slip.slip-list', compact('slipList'));
    }

    public function medicalCenterRates()
    {
        $cityList = slipCenterListWithRate();
        return view('user.slip.medical-center-rates', compact('cityList'));
    }
}
