<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }
        return view('admin.login');
    }

    public function loginAction(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ], ['captcha.captcha' => 'Invalid captcha code.']);

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
        $applicationList = Application::latest()->get();
        return view('admin.application-list', ['applicationList' => $applicationList]);
    }

    public function applicationUpdate(Request $request)
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
}
