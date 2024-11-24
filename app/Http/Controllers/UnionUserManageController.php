<?php

namespace App\Http\Controllers;

use App\Models\AllocateMedicalCenter;
use App\Models\Application;
use App\Models\MedicalCenter;
use App\Models\Notification;
use App\Models\PaymentLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UnionUserManageController extends Controller
{
    public function dashboard()
    {
        return view('medical-center.dashboard');
    }

    public function userList()
    {
        abort_if(auth()->user()?->account_type !== 'user', 404);

        $user_list = auth()->user()?->unionUserList ?? [];

        foreach ($user_list as $index => $user) {
            $medical_center = User::with(['applications'])->find($user->user_id);
            $application_count = Application::where('user_id', $user->user_id)->whereDate('created_at', Carbon::today())->count();

            $medical_center->application_count = $application_count;
            $user_list[$index] = $medical_center;
        }

        return view('union-account.user-list', compact('user_list'));
    }

    public function applicationList()
    {
        abort_if(auth()->user()?->account_type !== 'user', 404);

        $username = \request()->get('username');
        abort_if(empty($username), 404);
        $user = User::where('username', $username)->firstOrFail();
        auth()->user()->unionUserList->where('user_id', $user->id)->firstOrFail();

        $applicationList = Application::with(['applicationPayment'])->where('user_id', $user->id);

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                $applicationList = $applicationList->whereDate('created_at', Carbon::today());
            } else {
                $start_date = Carbon::parse(request('start_date'));
                $end_date = Carbon::parse(request('end_date'));

                $applicationList = $applicationList->whereBetween('created_at', [$start_date, $end_date]);
            }
        } else if (\request()->has('passport_search')) {
            $applicationList = $applicationList->where('passport_number', \request('passport_search'));
        } else {
            $applicationList = $applicationList->whereDate('created_at', Carbon::today());
        }

        $applicationList = $applicationList->latest()->get();

        return view('union-account.application-user-list', compact('applicationList', 'username'));
    }

    public function applicationUpdate(Request $request)
    {
        abort_if(auth()->user()?->account_type !== 'user', 404);

        $validated = $request->validate([
            'id' => 'required',
            'registration_number' => 'nullable|numeric|unique:applications,serial_number,' . $request->id,
            'medical_date' => 'nullable|date',
        ]);

        if ($validated['registration_number'] == null && $validated['medical_date'] == null) {
            return response()->json([
                'status' => false,
                'message' => 'Nothing to update.'
            ]);
        }

        $application = Application::findOrFail($validated['id']);
        $application->serial_number = $validated['registration_number'];
        $application->medical_date = Carbon::parse($validated['medical_date']);
        $application->save();

        return response()->json([
            'status' => true,
            'message' => 'Application updated successfully.'
        ]);
    }

    public function applicationListPdf()
    {
        abort_if(auth()->user()?->account_type !== 'user', 404);

        $username = \request()->get('center');
        abort_if(empty($username), 404);
        $center = MedicalCenter::where('username', $username)->firstOrFail();
        $center_name = $center->name;

        $applicationList = Application::with(['applicationPayment'])->where('center_name', $username)
            ->whereDate('created_at', Carbon::today())
            ->get();

        $pdf = \PDF::loadView('union-account.render.application-list-pdf', compact('applicationList', 'center_name'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('application-list.pdf');
    }

    public function applicationUpdateResult(Request $request)
    {
        abort_if(auth()->user()?->account_type !== 'user', 404);

        $validated = $request->validate([
            'id' => 'required',
            'health_status' => 'nullable|in:fit,cfit,unfit,held-up',
            'health_condition' => 'nullable',
            'allocated_medical_center' => 'nullable',
            'application_payment' => 'nullable|numeric',
            'center' => 'required|exists:medical_centers,username',
        ], [
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
                $payment = (double)$validated['application_payment'] ?? 0;

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

    public function scoreRequest($id)
    {
        abort_if(auth()->user()?->account_type !== 'user', 404);

        abort_if(empty($id), 403);
        $user = User::findOrFail($id);

        $oldLog = PaymentLog::where('user_id', $user->id)
            ->where('payment_type', 'score_request')
            ->first();

        if ($oldLog) {
            return back()->with('error', 'আপনি ইতিমধ্যে স্কোর রিকোয়েস্ট করেছেন. দয়া করে অপেক্ষা করুন.');
        }

        PaymentLog::create([
            'user_id' => $user->id,
            'amount' => 0,
            'payment_type' => 'score_request',
            'payment_method' => 'score_request',
            'reference_no' => 'score_request',
            'deposit_date' => now(),
            'remarks' => 'Score request for user ID: '. $user->id,
            'deposit_slip' => 'score_request'
        ]);

        return back()->with('success', 'আপনার স্কোর রিকোয়েস্ট গ্রহণ করা হয়েছে. অ্যাডমিন অনুমোদনের জন্য অপেক্ষা করুন.');
    }

    public function payBill()
    {
        abort_if(auth()->user()?->account_type !== 'user', 404);

        $validated = \request()->validate([
            'application_id' => 'required|exists:applications,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $application = Application::where('user_id', $validated['user_id'])
            ->where('id', $validated['application_id'])
            ->first();

        abort_if($application->user_id != $validated['user_id'], 403);

        if ($application->paymentLog) {
            return back()->with('error', 'আপনি ইতিমধ্যে এই আবেদনের জন্য পেমেন্ট করেছেন.');
        }

        $decrease_amount = $application->applicationPayment?->admin_amount;

        if ($decrease_amount > $application->user?->balance) {
            return back()->with('error', 'আপনার ব্যালেন্স পর্যাপ্ত নয়.')->with('url', route('union.user.score.request', $validated['user_id']));
        }

        \DB::beginTransaction();
        try {
            PaymentLog::create([
                'user_id' => $validated['user_id'],
                'application_id' => $application->id,
                'amount' => $decrease_amount,
                'payment_type' => 'payment',
                'deposit_date' => now(),
                'remarks' => 'Payment for application ID: '. $application->id,
                'status' => 'approved'
            ]);

            $application->update([
                'medical_status' => 'new'
            ]);

            $application->user()->decrement('balance', $decrease_amount);

            Notification::create([
                'user_id' => $application->user_id,
                'application_id' => $application->id,
                'message' => 'Application processing request for passport no : '. $application->passport_number ?? '',
                'link' => $application->id
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'পেমেন্ট সম্পন্ন হয়নি. আবার চেষ্টা করুন.');
        }

        return back()->with('success', 'পেমেন্ট সম্পন্ন হয়েছে. ধন্যবাদ!');
    }

    public function updateMedicalStatus(Request $request)
    {
        abort_if(auth()->user()?->account_type !== 'user', 404);

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
        abort_if(auth()->user()?->account_type !== 'user', 404);

        $applicationList = Application::with(['applicationPayment', 'applicationCustomComment', 'notification'])->where('id', $id)->get();
        $the_application = $applicationList->first();

        if ($the_application->notification) {
            if ($the_application->notification->read_at === null) {
                $the_application->notification->update(['read_at' => now()]);
            }
        }

        $username = $the_application->center_name;

        return view('union-account.application-list-single', compact('applicationList', 'username'));
    }

    public function allNotification()
    {
        abort_if(auth()->user()?->account_type !== 'user', 404);

        if (request()->ajax()) {
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
        $auth_type = auth()->user()?->account_type;

        \Auth::logout();
        session()->forget('union_account');

        if ($auth_type === 'user') {
            return redirect()->route('login');
        }
        return redirect()->route('medical.login');
    }
}
