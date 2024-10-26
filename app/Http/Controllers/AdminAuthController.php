<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MedicalCenter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function loginAction(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
//            'captcha' => 'required|captcha'
        ]);

        if (Auth::guard('admin')->attempt(['username' => $validated['username'], 'password' => $validated['password']])) {
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid username or password');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function applicationList()
    {
        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $applicationList = Application::whereBetween('created_at', [$start_date, $end_date])->get();
        }
        else if(request()->has('passport_search'))
        {
            if (request('passport_search') == null) {
                return back()->with('error', 'Please enter passport number.');
            }

            $applicationList = Application::where('passport_number', trim(request('passport_search')))->get();
        }
        else {
            $applicationList = Application::whereDate('created_at', Carbon::today())->latest()->get();
        }

        return view('admin.application-list', ['applicationList' => $applicationList]);
    }

    public function applicationUpdateResult(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'ems_number' => 'nullable',
            'health_status' => 'nullable',
            'health_condition' => 'nullable',
        ]);

        $application = Application::find($validated['id']);
        $application->ems_number = $validated['ems_number'];
        $application->health_status = $validated['health_status'];
        $application->health_status_details = $validated['health_condition'];
        $application->save();

        return response()->json([
            'status' => true,
            'success' => 'Application updated successfully.'
        ]);
    }

    public function applicationEdit($id)
    {
        $application = Application::find($id);
        return view('admin.application-edit', compact('application'));
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
            'religion' => 'required',
            'pp_issue_place' => 'nullable',
            'profession' => 'required',
            'nationality' => 'required',
            'date_of_birth' => 'nullable',
            'nid_no' => 'nullable|numeric',
            'passport_issue_date' => 'nullable',
            'passport_expiry_date' => 'nullable',
            'ref_no' => 'required',
        ]);

        $application = Application::find($id);
        $application->update($validated);

        return back()->with('success', 'Application updated successfully.');
    }

    public function applicationDelete()
    {
        $validated = request()->validate([
            'id' => 'required',
        ]);

        Application::findOrFail($validated['id'])->delete();

        return response()->json([
            'status' => true,
            'success' => 'Application deleted successfully.'
        ]);
    }

    public function newUser()
    {
        return view('admin.new-user');
    }

    public function newUserCreate(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'User created successfully.');
    }

    public function userList()
    {
        $userList = User::latest()->get();
        return view('admin.user-list', compact('userList'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        session()->flush();

        return redirect()->route('admin.login');
    }

    public function allocatedMedicalCenterList()
    {
        $medicalCenterList = MedicalCenter::withCount('allocatedMedicalCenter')->latest()->get();
        return view('admin.allocation-list', compact('medicalCenterList'));
    }

    public function allocatedMedicalCenterDetails($id)
    {
        $medical_center_details = MedicalCenter::with('allocatedMedicalCenter')->findOrFail($id);
        $applications = Application::where('center_name', $medical_center_details->username)->latest()->get();

        return view('admin.allocation-medical-center-details', compact('medical_center_details', 'applications'));
    }

    public function allocatedMedicalCenterApprove($id)
    {
        $medical_center = Application::findOrFail($id);
        $medical_center->allocatedMedicalCenter->status = true;
        $medical_center->allocatedMedicalCenter->save();

        return back()->with('success', 'Medical Center approved successfully.');
    }

    public function allocatedMedicalCenterDisapprove($id)
    {
        $medical_center = Application::findOrFail($id);
        $medical_center->allocatedMedicalCenter->status = false;
        $medical_center->allocatedMedicalCenter->save();

        return back()->with('success', 'Medical Center disapproved successfully.');
    }

    public function changePassword()
    {
        return view('admin.change-password');
    }

    public function changePasswordAction(Request $request)
    {
        $validated = $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($validated['old_password'], $admin->password)) {
            return back()->with('error', 'Old password does not match.');
        }

        $admin->password = Hash::make($validated['password']);
        $admin->save();

        return back()->with('success', 'Password changed successfully.');
    }
}
