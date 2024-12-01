<?php

namespace App\Http\Controllers;

use App\Models\AllocateMedicalCenter;
use App\Models\Application;
use App\Models\MedicalCenter;
use App\Models\Notification;
use App\Services\MedicalPDFManager;
use Carbon\Carbon;
use Dompdf\Dompdf;
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
        abort_if(auth()->user()?->account_type !== 'medical_center', 404);

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
        abort_if(auth()->user()?->account_type !== 'medical_center', 404);

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

    public function applicationListPdf()
    {
        abort_if(auth()->user()?->account_type !== 'medical_center', 404);

        $username = \request()->get('center');
        abort_if(empty($username), 404);
        $center = MedicalCenter::where('username', $username)->firstOrFail();
        $center_name = $center->name;

        $applicationList = Application::select([
            'id',
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
        ])->with(['allocatedMedicalCenter'])->where('center_name', $username);

        $date_string = '';
        if (\request()->has('start_date') && \request()->has('end_date')) {
            $start_date = \request('start_date');
            $end_date = \request('end_date');

            if ($start_date != null && $end_date != null)
            {
                $start_date = Carbon::parse($start_date);
                $end_date = Carbon::parse($end_date);

                $date_string = $start_date->format('d-m-Y').'_'.$end_date->format('d-m-Y');
                $applicationList = $applicationList->whereBetween('created_at', [$start_date, $end_date]);
            } else {
                return back()->with('error', 'Please select both start and end date.');
            }
        } else {
            $date_string = Carbon::today()->format('d-m-Y');
            $applicationList = $applicationList->whereDate('created_at', Carbon::today());
        }

        if ($applicationList->count() === 0) {
            return back()->with('error', 'No application found.');
        }

        $iteration = 1;
        $applicationList->chunk(100, function ($applications) use ($center_name, $username, &$iteration) {
            MedicalPDFManager::generateEachPDF($applications, $center_name, $username, $iteration);
            $iteration += 100;

//            $pdf = \PDF::loadView('union-account.render.application-list-pdf', compact('applications', 'center_name'));
//            $pdf->setPaper('A4', 'landscape');
//            $pdf->save(storage_path('app/public/application-list.pdf'));
        });

        MedicalPDFManager::combinePDF(
            target_directory: "app\public\medical\\".$username,
            center_name: $username,
            date: $date_string
        );

//        $pdf = \PDF::loadView('union-account.render.application-list-pdf', compact('applicationList', 'center_name'));
//        $pdf->setPaper('A4', 'landscape');
//        return $pdf->download('application-list.pdf');
    }

    public function medicalApplicationListPdf()
    {
        abort_if(auth()->user()?->account_type !== 'medical_center', 404);

        $medical_list = auth()->user()?->unionMedicalCenterList ?? [];
        if (empty($medical_list)) {
            return back()->with('error', 'No medical center found.');
        }

        $medical_list_id = $medical_list->pluck('medical_center_id')->toArray();
        $center = MedicalCenter::whereIn('id', $medical_list_id)->pluck('username')->toArray();

        $applicationList = Application::with(['applicationPayment'])->whereIn('center_name', $center);

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                $applicationList = $applicationList->whereDate('created_at', Carbon::today());
            } else {
                $start_date = Carbon::parse(request('start_date'));
                $end_date = Carbon::parse(request('end_date'));

                $applicationList = $applicationList->whereBetween('created_at', [$start_date, $end_date]);
            }
        }
        else {
            $applicationList = $applicationList->whereDate('created_at', Carbon::today());
        }

        $applicationList = $applicationList->latest()->get();
        if ($applicationList->isEmpty()) {
            return back()->with('error', 'No application found.');
        }

        $center_name = 'All Medical Center';

        $pdf = \PDF::loadView('union-account.render.application-list-pdf', compact('applicationList', 'center_name'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('application-list.pdf');
    }

    public function applicationUpdateResult(Request $request)
    {
        abort_if(auth()->user()?->account_type !== 'medical_center', 404);

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
        abort_if(auth()->user()?->account_type !== 'medical_center', 404);

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

    public function applicationListSingle($id)
    {
        abort_if(auth()->user()?->account_type !== 'medical_center', 404);

        $applicationList = Application::with(['applicationPayment', 'applicationCustomComment', 'notification'])->where('id', $id)->get();
        $the_application = $applicationList->first();

        if ($the_application->notification) {
            if ($the_application->notification->read_at === null)
            {
                $the_application->notification->update(['read_at' => now()]);
            }
        }

        $username = $the_application->center_name;

        return view('union-account.application-list-single', compact('applicationList', 'username'));
    }

    public function allNotification()
    {
        abort_if(auth()->user()?->account_type !== 'medical_center', 404);

        if (request()->ajax())
        {
            $notificationsMarkup = view('union-account.render.notification-list')->render();
            return response()->json([
                'status' => true,
                'markup' => $notificationsMarkup,
            ]);
        }

        $notifications = Notification::whereDate('created_at', '>=', now()->subDays(7))->latest()->get();
        return view('union-account.all-notifications', compact('notifications'));
    }

    public function logout()
    {
        \Auth::logout();
        session()->forget('union_account');
        return redirect()->route('medical.login');
    }
}
