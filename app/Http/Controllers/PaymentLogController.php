<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Notification;
use App\Models\PaymentLog;
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
        abort_if(empty($id) && auth()->user()->id !== $id, 403);
        $user = User::findOrFail($id);

        $oldLog = PaymentLog::where('user_id', $user->id)
            ->where('payment_type', 'score_request')
            ->first();

        if ($oldLog) {
            return back()->with('error', 'আপনি ইতিমধ্যে স্কোর রিকোয়েস্ট করেছেন. দয়া করে অপেক্ষা করুন.');
        }

        PaymentLog::create([
            'user_id' => auth()->id(),
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
}
