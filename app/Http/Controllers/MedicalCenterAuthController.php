<?php

namespace App\Http\Controllers;

use App\Models\AllocateMedicalCenter;
use App\Models\Application;
use App\Models\MedicalCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MedicalCenterAuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('medical_center')->check()) {
            return redirect()->route('medical.dashboard');
        }

        return view('medical-center.login');
    }

    public function loginAction(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('medical_center')->attempt(['username' => $validated['username'], 'password' => $validated['password']])) {
            return redirect()->route('medical.dashboard');
        }

        return back()->with('error', 'Invalid username or password');
    }

    public function dashboard()
    {
        return view('medical-center.dashboard');
    }

    public function logout()
    {
        Auth::logout();
        session()->forget('medical_center');
        return redirect()->route('medical.login');
    }

    public function applicationList()
    {
        $username = Auth::guard('medical_center')->user()->username;
        $applicationList = Application::with(['applicationPayment'])->where('center_name', $username);

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $applicationList = $applicationList->whereBetween('created_at', [$start_date, $end_date])->latest()->get();
        }
        else if(request()->has('passport_search'))
        {
            if (request('passport_search') == null) {
                return back()->with('error', 'Please enter passport number.');
            }

            $applicationList = $applicationList->where('passport_number', trim(request('passport_search')))->latest()->get();
        }
        else {
            $applicationList = $applicationList->whereDate('created_at', Carbon::today())->latest()->get();
        }

        return view('medical-center.application-list', compact('applicationList'));
    }

    public function applicationUpdateResult(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'ems_number' => 'nullable',
            'health_status' => 'nullable',
            'health_condition' => 'nullable',
            'allocated_medical_center' => 'nullable',
            'application_payment' => 'nullable|numeric',
        ],[
            'application_payment.numeric' => 'The status must be a number.'
        ]);


        $application = Application::find($validated['id']);
        $application->ems_number = $validated['ems_number'];
        $application->health_status = $validated['health_status'];
        $application->health_status_details = $validated['health_condition'];

        if ($application->save()) {
            $medical_center_id = Auth::guard('medical_center')->id();

            AllocateMedicalCenter::updateOrCreate(
                [
                    'application_id' => $application->id,
                    'medical_center_id' => $medical_center_id,
                ],
                [
                    'application_id' => $application->id,
                    'medical_center_id' => $medical_center_id,
                    'allocated_medical_center' => $validated['allocated_medical_center'],
                ]
            );

            if ($validated['application_payment']) {
                $payment = (double) $validated['application_payment'];

                $application->applicationPayment()->updateOrCreate(
                    [
                        'application_id' => $application->id,
                    ],
                    [
                        'application_id' => $application->id,
                        'center_amount' => $payment,
                    ]
                );
            }
        }

        return response()->json([
            'status' => true,
            'success' => 'Application updated successfully.'
        ]);
    }

    public function changePassword()
    {
        return view('medical-center.change-password');
    }

    public function changePasswordAction(Request $request)
    {
        $validated = $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        $medical_center = Auth::guard('medical_center')->user();

        if (!Hash::check($validated['old_password'], $medical_center->password)) {
            return back()->with('error', 'Old password does not match.');
        }

        $medical_center->password = Hash::make($validated['password']);
        $medical_center->save();

        return back()->with('success', 'Password changed successfully.');
    }

    public function applicationEdit($id)
    {
        $application = Application::findOrFail($id);
        return view('medical-center.application-edit', compact('application'));
    }

    public function applicationUpdate(Request $request, $id)
    {
        $validated = $request->validate([
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
        ]);

        $application = Application::find($id);
        $application->update($validated);

        return back()->with('success', 'Application updated successfully.');
    }
}
