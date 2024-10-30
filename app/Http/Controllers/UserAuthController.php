<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\StaticOption;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class UserAuthController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }

        $adText = StaticOption::getOption('ad_text') ?? '';
        return view('welcome', ['adText' => $adText]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|exists:users,username',
            'password' => 'required',
//            'captcha' => 'required|captcha'
        ], [
            'username.exists' => 'User dose not exists.',
//            'captcha.captcha' => 'Invalid captcha code.'
        ]);

        $user = User::where('username', $validated['username'])->first();
        if ($user)
        {
            if ($user->banned)
            {
                return back()->with('error', 'Your account has been banned. Contact with admin for details.');
            }
        }

        if (Auth::guard('web')->attempt(['username' => $validated['username'], 'password' => $validated['password']])) {
            return redirect()->route('dashboard');
        } else {
            return back()->with('error', 'Invalid username or password.');
        }
    }

    public function dashboard()
    {
        $adText = StaticOption::getOption('ad_text');
        $showNotice = (bool) StaticOption::getOption('show_notice');
        $noticeText = StaticOption::getOption('notice_text') ?? '';

        return view('user.dashboard', [
            'adText' => $adText,
            'showNotice' => $showNotice,
            'noticeText' => fixNoticeText($noticeText),
        ]);
    }

    public function userPanel()
    {
        $user_id = Auth::guard('web')->id();

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $applicationList = Application::with(['applicationPayment'])->where('user_id', $user_id)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->latest()
                ->get();
        }
        else if(request()->has('passport_search'))
        {
            if (request('passport_search') == null) {
                return back()->with('error', 'Please enter passport number.');
            }

            $applicationList = Application::with(['applicationPayment'])->where('user_id', $user_id)
                ->where('passport_number', trim(request('passport_search')))
                ->latest()
                ->get();
        }
        else {
            $applicationList = Application::with(['applicationPayment'])->where('user_id', $user_id)
                ->whereDate('created_at', Carbon::today())
                ->latest()->get();
        }

        return view('user.user-panel', compact('applicationList'));
    }

    public function userRegistration()
    {
        $showNotice = (bool) StaticOption::getOption('show_notice');
        $noticeText = StaticOption::getOption('notice_text') ?? '';

        return view('user.user-registration', [
            'showNotice' => $showNotice,
            'noticeText' => fixNoticeText($noticeText),
        ]);
    }

    public function storeUserRegistration(Request $request)
    {
        $validated = $request->validate([
            'medical_type' => 'required',
            'passport_number' => 'required',
            'gender' => 'required',
            'traveling_to' => 'required',
            'marital_status' => 'nullable',
            'center_name' => 'required',
            'surname' => 'required',
            'given_name' => 'required',
            'father_name' => 'nullable',
            'mother_name' => 'nullable',
            'religion' => 'nullable',
            'pp_issue_place' => 'nullable',
            'profession' => 'required',
            'nationality' => 'required',
            'date_of_birth' => 'nullable',
            'nid_no' => 'nullable|numeric',
            'passport_issue_date' => 'nullable',
            'passport_expiry_date' => 'nullable',
            'ref_no' => 'required',
            'problem' => 'nullable'
        ]);

        $validated['user_id'] = Auth::guard('web')->id();
        $validated['serial_number'] = now()->format('Ym').'-'.rand(1, 999999);
        $validated['pdf_code'] = generatePdfCode($validated['center_name']);
        $validated['contact_no'] = 0000;
        $validated['religion'] = 'none';
//        $validated['problem'] = json_encode($validated['problem']);

        Application::create($validated);

        return back()->with('success', 'Application submitted successfully.')->with('url', route('user.application.list'));
    }

    public function generatePdf($id)
    {
        $application = Application::findOrFail($id);

        $data = [
            'id' => $application->id,
            'pdf_code' => $application->pdf_code,
            'passport_no' => $application->passport_number,
            'passenger' => $application->given_name,
            'country' => travelingToName($application->traveling_to),
            'center_name' => centerName($application->center_name),
            'created_at' => $application->created_at,
            'delivery_date' => $application->created_at->addDays(3),
        ];

        $pdf = PDF::loadView('user.render.report-pdf', $data)->setPaper([0, 0, 650, 450]);

        return $pdf->download('application.pdf');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
