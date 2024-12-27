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
        $deposit_history = PaymentLog::where('payment_type', 'score_request')
                ->latest()
                ->get();

        return view('admin.deposit-request-history', [
            'deposit_history' => $deposit_history
        ]);
    }

    public function addScore(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:payment_logs,id',
            'score_amount' => 'required|integer|min:1',
            'remarks' => 'nullable|string|max:255'
        ]);

        try {
            $payment_log = PaymentLog::find($validated['request_id']);

            if ($payment_log->score_type === 'slip')
            {
                $payment_log->user()->increment('slip_balance', $validated['score_amount']);
            } else {
                $payment_log->user()->increment('balance', $validated['score_amount']);
            }

            PaymentLog::create([
                'user_id' => $payment_log->user_id,
                'amount' => $validated['score_amount'],
                'payment_type' => 'deposit',
                'score_type' => $payment_log->score_type,
                'payment_method' => 'admin',
                'reference_no' => 'admin',
                'deposit_date' => Carbon::now(),
                'remarks' => $validated['remarks'] ?? 'Score added by admin.',
                'status' => 'approved'
            ]);

            $payment_log->delete();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Score added successfully.'
        ]);
    }

    public function transactionHistory()
    {
        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $transactionHistory = PaymentLog::with(['user', 'application:id,passport_number'])->whereBetween('created_at', [$start_date, $end_date])->latest()->get();
        }
        else {
            $transactionHistory = PaymentLog::with(['user', 'application:id,passport_number'])->whereDate('created_at', Carbon::today())->latest()->get();
        }

        return view('admin.transaction-history', [
            'transactionHistory' => $transactionHistory
        ]);
    }
}
