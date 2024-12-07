<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MedicalCenter;
use App\Models\Notification;
use App\Models\PaymentLog;
use App\Models\User;
use App\Services\MedicalPDFManager;
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
        $start_date = null;
        $end_date = null;

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $applicationList = Application::with(['applicationPayment', 'applicationCustomComment'])->whereBetween('created_at', [$start_date, $end_date])->paginate(20)->withQueryString();
        }
        else if(request()->has('passport_search'))
        {
            if (request('passport_search') == null) {
                return back()->with('error', 'Please enter passport number.');
            }

            $applicationList = Application::with(['applicationPayment', 'applicationCustomComment'])->where('passport_number', trim(request('passport_search')))->paginate(20);
        }
        else {
            $applicationList = Application::with(['applicationPayment', 'applicationCustomComment'])->whereDate('created_at', Carbon::today())->latest()->paginate(20);
        }

        return view('admin.application-list', [
            'applicationList' => $applicationList,
            'start_date' => $start_date ? Carbon::parse($start_date)->format('d-M-Y') : null,
            'end_date' => $end_date ? Carbon::parse($end_date)->format('d-M-Y') : null,
        ]);
    }

    public function applicationUpdateResult(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'health_condition' => 'nullable',
            'application_payment' => 'nullable|numeric',
            'application_admin_discount' => 'nullable|numeric',
        ], [
            'application_payment.numeric' => 'The admin score must be a number.'
        ]);

        $application = Application::find($validated['id']);

        if ($application)
        {
            $application->applicationCustomComment()->updateOrCreate(
                [
                    'application_id' => $application->id,
                ],
                [
                    'application_id' => $application->id,
                    'health_condition' => $validated['health_condition'] ?? $application->health_status_details,
                ]
            );

            $application->applicationPayment()->updateOrCreate(
                [
                    'application_id' => $application->id,
                ],
                [
                    'application_id' => $application->id,
                    'admin_amount' => (double) $validated['application_payment'],
                    'discount_amount' => (double) $validated['application_admin_discount'],
                    'center_amount' => 0
                ]
            );
        }

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

//        $validated['problem'] = json_encode($validated['problem']);

        $application = Application::find($id);
        $application->update($validated);

        return back()->with('success', 'Application updated successfully.');
    }

    public function applicationRegDateUpdate(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'registration_number' => 'nullable',
            'medical_date' => 'nullable|date',
        ]);

        $application = Application::findOrFail($validated['id']);
        $application->serial_number = $validated['registration_number'];
        $application->medical_date = Carbon::parse($validated['medical_date']);
        $application->save();

        return response()->json([
            'status' => true,
            'message' => 'Application updated successfully.'
        ]);
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
            'refer' => 'nullable',
            'balance' => 'nullable|numeric',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'refer_by' => $validated['refer'],
            'balance' => $validated['balance'],
        ]);

        if (($validated['balance'] ?? 0) > 0) {
            PaymentLog::create([
                'user_id' => $user->id,
                'amount' => $validated['balance'],
                'payment_type' => 'deposit',
                'payment_method' => 'admin',
                'reference_no' => 'admin',
                'deposit_date' => Carbon::now(),
                'remarks' => 'Starting score added by admin.',
                'status' => 'approved'
            ]);
        }

        return back()->with('success', 'User created successfully.');
    }

    public function userList()
    {
        $userList = User::latest()->get();
        return view('admin.user-list', compact('userList'));
    }

    public function updateBalance(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:users',
            'balance' => 'required|numeric',
        ]);

        $user = User::findOrFail($validated['id']);
        $user->balance = $validated['balance'];
        $user->save();

        return response()->json([
            'status' => true,
            'success' => 'Balance updated successfully.'
        ]);
    }

    public function generatePdf()
    {
        $validated = request()->validate([
            'id' => 'required',
        ]);

        $start_date = request('start_date');
        $end_date = request('end_date');

        $applicationList = Application::select([
            'id',
            'user_id',
            'pdf_code',
            'created_at',
            'updated_at',
            'serial_number',
            'ems_number',
            'passport_number',
            'given_name',
            'nid_no',
            'gender',
            'traveling_to',
            'center_name',
            'health_status',
            'health_status_details',
            'medical_status',
        ])->with(['user','applicationPayment', 'applicationCustomComment'])->where('user_id', $validated['id']);

        $date_string = 'all';

        if ($start_date && $end_date) {
            $start_date = Carbon::parse($start_date)->format('Y-m-d');
            $end_date = Carbon::parse($end_date)->format('Y-m-d');

            $applicationList = $applicationList->whereBetween('created_at', [$start_date, $end_date]);
            $date_string = Carbon::parse($start_date)->format('d-M-Y') . ' to ' . Carbon::parse($end_date)->format('d-M-Y');
        }
        elseif ($start_date) {
            $start_date = Carbon::parse($start_date)->format('Y-m-d');
            $applicationList = $applicationList->whereDate('created_at', $start_date);
            $date_string = Carbon::parse($start_date)->format('d-M-Y');
        }

        if ($applicationList->count() === 0) {
            return back()->with('error', 'No application found.');
        }

        $single_application_user = $applicationList->first()->user;
        $name = $single_application_user->name;
        $username = $single_application_user->username;

        $iteration = 1;
        $applicationList->chunk(100, function ($applications) use (&$iteration, $name) {
            MedicalPDFManager::generateEachPDF(
                applications: $applications,
                center_name: $name,
                username: '../admin',
                iteration: $iteration,
                pdf_view: 'admin.render.user-application-list-pdf'
            );

            $iteration += 100;
        });


        MedicalPDFManager::combinePDF(
            target_directory: "app\public\admin\\",
            center_name: $username,
            date: $date_string
        );
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
        $applications = Application::whereHas('allocatedMedicalCenter')->where('center_name', $medical_center_details->username)->latest()->paginate(10);

        $unapproved_applications = Application::whereHas('allocatedMedicalCenter')->where('center_name', $medical_center_details->username)->whereHas('allocatedMedicalCenter', function($query) {
            $query->where('status', false);
        })->latest()->count();

        return view('admin.allocation-medical-center-details', compact('medical_center_details', 'applications', 'unapproved_applications'));
    }

    public function allocatedMedicalCenterApprove($id)
    {
        $medical_center = Application::with(['allocatedMedicalCenter'])->findOrFail($id);
        $medical_center->allocatedMedicalCenter->status = true;
        $medical_center->allocatedMedicalCenter->save();

        return response()->json([
            'status' => true,
            'message' => 'Medical Center approved successfully.',
            'medical_center' => $medical_center->allocatedMedicalCenter
        ]);
    }

    public function allocatedMedicalCenterDisapprove($id)
    {
        $medical_center = Application::findOrFail($id);
        $medical_center->allocatedMedicalCenter->status = false;
        $medical_center->allocatedMedicalCenter->save();

        return response()->json([
            'status' => true,
            'message' => 'Medical Center disapproved successfully.',
            'medical_center' => $medical_center->allocatedMedicalCenter
        ]);
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

    public function applicationListSingle($id)
    {
        $applicationList = Application::with(['applicationPayment', 'applicationCustomComment', 'notification'])->where('id', $id)->get();
        $the_application = $applicationList->first();

        if ($the_application->notification) {
            if ($the_application->notification->read_at === null)
            {
                $the_application->notification->update(['read_at' => now()]);
            }
        }

        $username = $the_application->center_name;

        return view('admin.application-list-single', compact('applicationList', 'username'));
    }

    public function allNotification()
    {
        if (request()->ajax())
        {
            $notificationsMarkup = view('admin.render.notification-list')->render();
            return response()->json([
                'status' => true,
                'markup' => $notificationsMarkup,
            ]);
        }

        $notifications = Notification::whereDate('created_at', '>=', now()->subDays(7))->latest()->get();
        return view('admin.all-notifications', compact('notifications'));
    }

    public function updateMedicalStatus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'medical_status' => 'required|in:' . implode(',', array_keys(medicalStatus())),
        ]);

        $application = Application::find($validated['id']);
        $application->medical_status = $validated['medical_status'];
        $application->save();

        return response()->json([
            'status' => true,
            'success' => 'Application updated successfully.'
        ]);
    }
}
