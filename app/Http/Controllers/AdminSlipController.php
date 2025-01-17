<?php

namespace App\Http\Controllers;

use App\Models\PaymentLog;
use App\Models\Slip;
use App\Models\SlipPayment;
use App\Models\SlipStatusLink;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminSlipController extends Controller
{
    public function slipList()
    {
        abort_if(! auth()->user()->role('super-admin'), 403);

        $start_date = null;
        $end_date = null;

        $slipList = Slip::with(['slipPayment']);

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $slipList = $slipList->whereBetween('created_at', [$start_date, $end_date])->paginate(20)->withQueryString();
        }
        else if(request()->has('passport_search'))
        {
            if (request('passport_search') == null) {
                return back()->with('error', 'Please enter passport number.');
            }

            $slipList = $slipList->where('passport_number', trim(request('passport_search')))->paginate(20);
        }
        else {
            $slipList = $slipList->whereDate('created_at', Carbon::today())->latest()->paginate(20);
        }

        return view('admin.slip.slip-list', [
            'slipList' => $slipList,
            'start_date' => $start_date ? Carbon::parse($start_date)->format('d-M-Y') : null,
            'end_date' => $end_date ? Carbon::parse($end_date)->format('d-M-Y') : null,
        ]);
    }

    public function slipUpdate(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:slips,id',
            'slip_status' => 'required|in:processing,processed-link,cancelled,we-cant-not-expired,cancelled-for-time-out,completed',
            'link' => 'required_if:slip_status,completed',
        ]);

        SlipStatusLink::updateOrCreate(
            [
                'slip_id' => $validated['id']
            ],
            [
                'slip_id' => $validated['id'],
                'slip_status' => $validated['slip_status'],
                'link' => $validated['link']
            ]
        );

        try {
            if (in_array($validated['slip_status'], ['cancelled', 'we-cant-not-expired', 'cancelled-for-time-out']))
            {
                $slip_payment = SlipPayment::where('slip_id', $validated['id'])->first();

                if ($slip_payment && $slip_payment->payment_status === 'paid') {
                    $slip_payment_amount = $slip_payment->slip_rate;

                    $slip = Slip::where('id', $validated['id'])->first();
                    $slip->user()->increment('slip_balance', $slip_payment_amount);

                    PaymentLog::create([
                        'user_id' => $slip->user->id,
                        'amount' => $slip_payment_amount,
                        'payment_type' => 'deposit',
                        'payment_method' => 'system',
                        'score_type' => 'slip',
                        'reference_no' => 'system',
                        'deposit_date' => Carbon::now(),
                        'remarks' => 'Score refunded by admin.',
                        'status' => 'approved'
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please contact with developer.'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Slip status updated successfully.'
        ]);
    }

    public function slipListSingle($id)
    {
        $slipList = Slip::with(['slipPayment', 'notification'])->where('id', $id)->get();
        $the_application = $slipList->first();

        if ($the_application->notification) {
            if ($the_application->notification->read_at === null)
            {
                $the_application->notification->update(['read_at' => now()]);
            }
        }

        $username = $the_application->center_slug;

        return view('admin.slip.slip-list-single', compact('slipList', 'username'));
    }

    public function slipDelete()
    {
        $validated = request()->validate([
            'id' => 'required|exists:slips,id',
        ]);

        $slip = Slip::findOrFail($validated['id']);

        try {
            $slip->slipPayment()->delete();
            $slip->delete();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Slip deleted successfully.'
        ]);
    }
}
