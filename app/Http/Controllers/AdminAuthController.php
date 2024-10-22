<?php

namespace App\Http\Controllers;

use App\Models\Application;
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
            $applicationList = Application::latest()->get();
        }

        return view('admin.application-list', ['applicationList' => $applicationList]);
    }

    public function applicationUpdateResult(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'ems_number' => 'required',
            'health_status' => 'required',
            'health_condition' => 'required',
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
            'nid_no' => 'required|numeric',
            'passport_issue_date' => 'required',
            'passport_expiry_date' => 'required',
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
}
