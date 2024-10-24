<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\StaticOption;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }

        $adText = StaticOption::getOption('ad_text');
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

        if (Auth::guard('web')->attempt(['username' => $validated['username'], 'password' => $validated['password']])) {
            return redirect()->route('dashboard');
        } else {
            return back()->with('error', 'Invalid username or password.');
        }
    }

    public function dashboard()
    {
        $adText = StaticOption::getOption('ad_text');
        return view('user.dashboard', ['adText' => $adText]);
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

            $applicationList = Application::where('user_id', $user_id)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->latest()
                ->get();
        }
        else if(request()->has('passport_search'))
        {
            if (request('passport_search') == null) {
                return back()->with('error', 'Please enter passport number.');
            }

            $applicationList = Application::where('user_id', $user_id)
                ->where('passport_number', trim(request('passport_search')))
                ->latest()
                ->get();
        }
        else {
            $applicationList = Application::where('user_id', $user_id)
                ->whereDate('created_at', Carbon::today())
                ->latest()->get();
        }

        return view('user.user-panel', compact('applicationList'));
    }

    public function userRegistration()
    {
        return view('user.user-registration');
    }

    public function storeUserRegistration(Request $request)
    {
        $validated = $request->validate([
            'medical_type' => 'required',
            'passport_number' => 'required',
            'gender' => 'required',
            'traveling_to' => 'required',
            'marital_status' => 'required',
            'center_name' => 'required',
            'surname' => 'required',
            'given_name' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'religion' => 'required',
            'pp_issue_place' => 'required',
            'profession' => 'required',
            'nationality' => 'required',
            'date_of_birth' => 'required',
//            'contact_no' => 'required',
            'nid_no' => 'required|numeric',
            'passport_issue_date' => 'required',
            'passport_expiry_date' => 'required',
            'ref_no' => 'required',
        ]);

        $validated['user_id'] = Auth::guard('web')->id();
        $validated['serial_number'] = now()->format('Ym').'-'.rand(1, 999999);
        $validated['contact_no'] = 0000;

        Application::create($validated);

        return back()->with('success', 'Application submitted successfully.')->with('url', route('user.application.list'));
    }

    public function generatePdf($id)
    {
        $application = Application::findOrFail($id);

        $data = [
            'agent' => 'Raju',
            'passport_no' => $application->passport_number,
            'nid_no' => $application->nid_no,
            'passenger' => $application->given_name,
            'country' => travelingToName($application->traveling_to),
            'created_by' => $application->user->username,
            'for' => $application->user->username,
            'center_name' => centerName($application->center_name),
            'date_of_birth' => $application->date_of_birth,
            'passport_expiry_date' => $application->passport_expiry_date,
        ];

        $pdf = PDF::loadView('user.render.report-pdf', $data)->setPaper([0, 0, 650, 380]);

        return $pdf->download('application.pdf');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('home');
    }
}
