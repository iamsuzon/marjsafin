<?php

namespace App\Http\Controllers;

use App\Models\AllocateMedicalCenter;
use App\Models\Application;
use App\Models\MedicalCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnionMedicalManageController extends Controller
{
    public function dashboard()
    {
        return view('medical-center.dashboard');
    }

    public function medicalList()
    {
        $medical_list = auth()->user()?->unionMedicalCenterList ?? [];

        foreach ($medical_list as $index => $medical) {
            $medical_center = MedicalCenter::with(['applications'])->find($medical->medical_center_id);
            $application_count = Application::where('center_name', $medical_center->username)->whereDate('created_at', Carbon::today())->count();

            $medical_center->application_count = $application_count;
            $medical_list[$index] = $medical_center;
        }

        return view('union-account.medical-list', compact('medical_list'));
    }

    public function applicationList()
    {
        $username = \request()->get('center');
        abort_if(empty($username), 404);
        MedicalCenter::where('username', $username)->firstOrFail();

        $applicationList = Application::with(['applicationPayment'])->where('center_name', $username);

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                $applicationList = $applicationList->whereDate('created_at', Carbon::today());
            } else {
                $start_date = Carbon::parse(request('start_date'));
                $end_date = Carbon::parse(request('end_date'));

                $applicationList = $applicationList->whereBetween('created_at', [$start_date, $end_date]);
            }
        }
        else if(\request()->has('passport_search')){
            $applicationList = $applicationList->where('passport_number', \request('passport_search'));
        }
        else {
            $applicationList = $applicationList->whereDate('created_at', Carbon::today());
        }

        $applicationList = $applicationList->latest()->get();

        return view('union-account.application-list', compact('applicationList', 'username'));
    }

    public function applicationUpdateResult(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'health_status' => 'nullable|in:fit,cfit,unfit,held-up',
            'health_condition' => 'nullable',
            'allocated_medical_center' => 'nullable',
            'application_payment' => 'nullable|numeric',
            'center' => 'required|exists:medical_centers,username',
        ],[
            'application_payment.numeric' => 'The status must be a number.'
        ]);


        $application = Application::find($validated['id']);
        $application->health_status = $validated['health_status'];
        $application->health_status_details = $validated['health_condition'];

        if ($application->save()) {
            $application->applicationCustomComment()->updateOrCreate(
                [
                    'application_id' => $application->id,
                ],
                [
                    'application_id' => $application->id,
                    'health_condition' => $validated['health_condition'],
                ]
            );

            $medical_center_id = MedicalCenter::where('username', $validated['center'])->first()?->id;

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

            if ($validated['application_payment'] ?? false) {
                $payment = (double) $validated['application_payment'] ?? 0;

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

    public function logout()
    {
        \Auth::logout();
        session()->forget('union_account');
        return redirect()->route('medical.login');
    }
}
