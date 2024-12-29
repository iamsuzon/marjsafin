<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Notification;
use App\Models\PaymentLog;
use App\Models\Slip;
use App\Models\SlipMedicalCenterRate;
use App\Models\User;
use Carbon\Carbon;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use function Symfony\Component\String\b;

class PaymentLogController extends Controller
{
//    public function index()
//    {
//        return view('user.deposit');
//    }

//    public function deposit(Request $request)
//    {
//        $validated = $request->validate([
//            'amount' => 'required|numeric|min:1',
//            'payment_method' => 'required|in:'. implode(',', array_keys(paymentMethods())),
//            'reference' => 'required',
//            'deposit_date' => 'required|date',
//            'remarks' => 'required',
//            'deposit_slip' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120'
//        ]);
//
//        if (Cache::get('deposit_requested') == auth()->id()) {
//            return back()->with('error', 'আপনি ইতিমধ্যে ডিপোজিটের জন্য অনুরোধ করেছেন. দয়া করে এক মিনিট অপেক্ষা করুন.');
//        }
//
//        $path = public_path('assets/uploads/deposit');
//        if (! is_dir($path)) {
//            mkdir($path, 0777, true);
//        }
//
//        $deposit_slip = $request->file('deposit_slip');
//        $extension = $deposit_slip->getClientOriginalExtension();
//        $file_name = 'deposit_slip_'. time() . '_' . auth()->id() . '.' . $extension;
//        $deposit_slip->move($path, $file_name);
//
//        PaymentLog::create([
//            'user_id' => auth()->id(),
//            'amount' => $validated['amount'],
//            'payment_type' => 'deposit',
//            'payment_method' => $validated['payment_method'],
//            'reference_no' => $validated['reference'],
//            'deposit_date' => Carbon::parse($validated['deposit_date']),
//            'remarks' => $validated['remarks'],
//            'deposit_slip' => $file_name
//        ]);
//
//        Cache::put('deposit_requested', auth()->id(), now()->addMinutes(1));
//
//        return back()->with('success', 'আপনার ডিপোজিটের অনুরোধ গ্রহণ করা হয়েছে. অ্যাডমিন অনুমোদনের জন্য অপেক্ষা করুন.');
//    }

    public function scoreRequest($id)
    {
        $validated = request()->validate([
            'for' => 'nullable|in:slip'
        ]);

        abort_if(empty($id) && auth()->user()->id !== $id, 403);
        $user = User::findOrFail($id);

        $oldLog = PaymentLog::where('user_id', $user->id)
            ->where('payment_type', 'score_request');

        if (isset($validated['for'])) {
            $oldLog->where('score_type', 'slip');
        }
        $oldLog = $oldLog->first();

        if ($oldLog) {
            return back()->with('error', 'আপনি ইতিমধ্যে স্কোর রিকোয়েস্ট করেছেন. দয়া করে অপেক্ষা করুন.');
        }

        PaymentLog::create([
            'user_id' => auth()->id(),
            'amount' => 0,
            'payment_type' => 'score_request',
            'score_type' => $validated['for'] ? 'slip' : 'medical',
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
        $validated = \request()->validate([
            'application_id' => 'required|exists:applications,id',
        ]);

        $user_id = auth('web')->user()->id;

        $application = Application::where('user_id', $user_id)
            ->where('id', $validated['application_id'])
            ->first();

        abort_if($application->user_id != $user_id, 403);

        if ($application->paymentLog) {
            return back()->with('error', 'আপনি ইতিমধ্যে এই আবেদনের জন্য পেমেন্ট করেছেন.');
        }

        $decrease_amount = $application->applicationPayment?->admin_amount;

        if ($decrease_amount > $application->user?->balance) {
            return back()->with('error', 'আপনার ব্যালেন্স পর্যাপ্ত নয়.')->with('url', route('user.score.request', $user_id));
        }

        \DB::beginTransaction();
        try {
            PaymentLog::create([
                'user_id' => auth()->id(),
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

    public function paySlipBill()
    {
        $validated = request()->validate([
            'slip_id' => 'required|exists:slips,id',
        ]);

        $user_id = auth('web')->user()->id;

        $slip = Slip::where('user_id', $user_id)
            ->where('id', $validated['slip_id'])
            ->first();

        if ($slip->paymentLog) {
            return back()->with('error', 'আপনি ইতিমধ্যে এই আবেদনের জন্য পেমেন্ট করেছেন.');
        }

        $slip_rate = $slip->slipPayment?->slip_rate;
        $slip_discount = $slip->slipPayment?->discount ?? 0;
        $decrease_amount = $slip_rate - $slip_discount;

        if ($decrease_amount > $slip->user?->slip_balance) {
            return back()->with('error', 'আপনার ব্যালেন্স পর্যাপ্ত নয়.')->with('url', route('user.score.request', $user_id)."?for=slip");
        }

        \DB::beginTransaction();
        try {
            PaymentLog::create([
                'user_id' => auth()->id(),
                'application_id' => $slip->id,
                'amount' => $decrease_amount,
                'payment_type' => 'payment',
                'score_type' => 'slip',
                'deposit_date' => now(),
                'remarks' => 'Payment for application ID: '. $slip->id,
                'status' => 'approved'
            ]);

            $slip->user()->decrement('slip_balance', $decrease_amount);
            $slip->slipPayment()->update([
                'payment_status' => 'paid'
            ]);

            Notification::create([
                'user_id' => $slip->user_id,
                'application_id' => $slip->id,
                'message' => 'Payment has been made for slip passport number : '. $slip->passport_number ?? '',
                'link' => $slip->id,
                'extra' => json_encode([
                    'type' => 'slip'
                ])
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'পেমেন্ট সম্পন্ন হয়নি. আবার চেষ্টা করুন.');
        }

        return back()->with('success', 'পেমেন্ট সম্পন্ন হয়েছে. ধন্যবাদ!');
    }

    public function depositHistory()
    {
        $user_id = auth('web')->user()->id;

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $depositHistory = PaymentLog::where('user_id', $user_id)
                ->where('payment_type', 'deposit')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->latest()
                ->get();
        }
        else {
            $depositHistory = PaymentLog::where('user_id', $user_id)
                ->where('payment_type', 'deposit')
                ->whereDate('created_at', Carbon::today())
                ->latest()
                ->get();
        }

        return view('user.deposit-history', [
            'depositHistory' => $depositHistory
        ]);
    }

    public function transactionHistory()
    {
        $user_id = auth('web')->user()->id;

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $transactionHistory = PaymentLog::with(['application'])->where('user_id', $user_id)
                ->whereNot('payment_type', 'score_request')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->latest()
                ->get();
        }
        else {
            $transactionHistory = PaymentLog::with(['application'])->where('user_id', $user_id)
                ->whereNot('payment_type', 'score_request')
                ->whereDate('created_at', Carbon::today())
                ->latest()
                ->get();
        }

        return view('user.transaction-history', [
            'transactionHistory' => $transactionHistory
        ]);
    }

    public function slipMedicalCenter()
    {
        abort_if(auth('admin')->user()->role !== 'super-admin', 403);

        $slipMedicalCenters = slipCenterListWithRate();

        return view('admin.slip-medical-center', compact('slipMedicalCenters'));
    }

    public function storeSlipRate(Request $request)
    {
        $validated = $request->validate([
            'center_slug' => 'required|array',
            'rate' => 'required|array',
            'rate.*' => 'nullable|numeric|min:0',
            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric|min:0'
        ]);

        foreach ($validated['center_slug'] as $key => $center_slug) {
            $rate = $validated['rate'][$key] ?? 0;
            $discount = $validated['discount'][$key] ?? 0;

            $medicalCenter = SlipMedicalCenterRate::where('medical_center_slug', $center_slug)->first();

            if ($medicalCenter) {
                $medicalCenter->update([
                    'rate' => $rate,
                    'discount' => $discount
                ]);
            }
            else {
                SlipMedicalCenterRate::create([
                    'medical_center_slug' => $center_slug,
                    'rate' => $rate,
                    'discount' => $discount
                ]);
            }
        }

        return back()->with('success', 'Slip rate updated successfully.');
    }
}
