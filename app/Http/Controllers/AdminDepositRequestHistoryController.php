<?php

namespace App\Http\Controllers;

use App\Models\PaymentLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDepositRequestHistoryController extends Controller
{
    public function depositRequestHistory()
    {
        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $deposit_history = PaymentLog::where('payment_type', 'deposit')->whereBetween('created_at', [$start_date, $end_date])->latest()->get();
        }
        else {
            $deposit_history = PaymentLog::where('payment_type', 'deposit')->whereDate('created_at', Carbon::today())->latest()->get();
        }

        return view('admin.deposit-request-history', [
            'deposit_history' => $deposit_history
        ]);
    }

    public function changePaymentStatus()
    {
        $validated = request()->validate([
            'id' => 'required|integer',
            'status' => 'required|in:approved,declined'
        ]);

        $payment = PaymentLog::findOrFail($validated['id']);

        if ($payment->approved === 'approved') {
            return back()->with('error', 'Payment has already been approved.');
        }

        $payment->update([
            'status' => $validated['status']
        ]);

        if ($payment->status === 'approved') {
            $payment->user()->increment('balance', $payment->amount);
            return back()->with('success', 'Payment has been approved.');
        }

        return back()->with('error', 'Payment has been declined.');
    }

    public function transactionHistory()
    {
        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $transactionHistory = PaymentLog::whereBetween('created_at', [$start_date, $end_date])->latest()->get();
        }
        else {
            $transactionHistory = PaymentLog::whereDate('created_at', Carbon::today())->latest()->get();
        }

        return view('admin.transaction-history', [
            'transactionHistory' => $transactionHistory
        ]);
    }
}
