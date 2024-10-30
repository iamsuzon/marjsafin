<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\PaymentLog;
use Carbon\Carbon;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use function Symfony\Component\String\b;

class PaymentLogController extends Controller
{
    public function index()
    {
        return view('user.deposit');
    }

    public function deposit(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:'. implode(',', array_keys(paymentMethods())),
            'reference' => 'required',
            'deposit_date' => 'required|date',
            'remarks' => 'required',
            'deposit_slip' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120'
        ]);

        if (Cache::get('deposit_requested') == auth()->id()) {
            return back()->with('error', 'আপনি ইতিমধ্যে ডিপোজিটের জন্য অনুরোধ করেছেন. দয়া করে এক মিনিট অপেক্ষা করুন.');
        }

        $path = public_path('assets\uploads\deposit');
        if (! is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $deposit_slip = $request->file('deposit_slip');
        $extension = $deposit_slip->getClientOriginalExtension();
        $file_name = 'deposit_slip_'. time() . '_' . auth()->id() . '.' . $extension;
        $deposit_slip->move($path, $file_name);

        PaymentLog::create([
            'user_id' => auth()->id(),
            'amount' => $validated['amount'],
            'payment_type' => 'deposit',
            'payment_method' => $validated['payment_method'],
            'reference_no' => $validated['reference'],
            'deposit_date' => Carbon::parse($validated['deposit_date']),
            'remarks' => $validated['remarks'],
            'deposit_slip' => $file_name
        ]);

        Cache::put('deposit_requested', auth()->id(), now()->addMinutes(1));

        return back()->with('success', 'আপনার ডিপোজিটের অনুরোধ গ্রহণ করা হয়েছে. অ্যাডমিন অনুমোদনের জন্য অপেক্ষা করুন.');
    }

    public function payBill()
    {
        $validated = \request()->validate([
            'application_id' => 'required|exists:applications,id',
        ]);

        $application = Application::where('user_id', auth('web')->id)
            ->where('id', $validated['application_id'])
            ->first();

        abort_if($application->user_id != auth()->id(), 403);

        if ($application->paymentLog) {
            return back()->with('error', 'আপনি ইতিমধ্যে এই আবেদনের জন্য পেমেন্ট করেছেন.');
        }

        $decrease_amount = $application->applicationPayment?->admin_amount;

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

            $application->user()->decrement('balance', $decrease_amount);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'পেমেন্ট সম্পন্ন হয়নি. আবার চেষ্টা করুন.');
        }

        return back()->with('success', 'পেমেন্ট সম্পন্ন হয়েছে. ধন্যবাদ!');
    }
}
